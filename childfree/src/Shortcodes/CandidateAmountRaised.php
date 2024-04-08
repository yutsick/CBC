<?php

namespace WZ\ChildFree\Shortcodes;

class CandidateAmountRaised
{
    public function __invoke($atts = [])
    {
        extract(shortcode_atts(array(
            'user_id' => get_the_ID(), // setting default value to current user id
        ), $atts));

        global $wpdb;
        $candidate_id = $user_id;
        $amount_raised = $wpdb->get_var("SELECT meta_value FROM wp_postmeta WHERE post_id = $candidate_id AND meta_key = '_amount_raised'");
        $order_status = ['wc-completed'];
        $orders_ids = $wpdb->get_col("
            SELECT order_items.order_id
            FROM {$wpdb->prefix}woocommerce_order_items as order_items
            LEFT JOIN {$wpdb->prefix}woocommerce_order_itemmeta as order_item_meta ON order_items.order_item_id = order_item_meta.order_item_id
            LEFT JOIN {$wpdb->posts} AS posts ON order_items.order_id = posts.ID
            WHERE posts.post_type = 'shop_order'
            AND posts.post_status IN ( '" . implode("','", $order_status) . "' )
            AND order_items.order_item_type = 'line_item'
            AND order_item_meta.meta_key = '_product_id'
            AND order_item_meta.meta_value = '" . $candidate_id . "'
            ORDER BY order_items.order_id DESC");

        // Update the amount raised based on the total number of orders received and their total amount
        if (!empty($orders_ids)) {

            $all_orders_total = 0;
            foreach ($orders_ids as $order_id) {
                $order = wc_get_order($order_id);
                $items_amount_total = $order->get_total();

                $discount_total = $order->get_total_discount();

                // add voucher discount to order total, for displaying full amount received by candidate
                if ($discount_total > 0) {
                    $order_total = (int)$discount_total + (int)$items_amount_total;
                } else {
                    $order_total = $items_amount_total;
                }
                $all_orders_total += $order_total;
            }
//            echo wc_price($all_orders_total) . "<br>";
            if ($amount_raised != $all_orders_total) {
                update_post_meta($candidate_id, '_amount_raised', $all_orders_total);
            }
            $amount_raised = get_post_meta($candidate_id, '_amount_raised', true);
        }

        // If the amount raised is more than than the goal, set the amount raised to the goal
        $goal = $wpdb->get_var("SELECT meta_value FROM wp_postmeta WHERE post_id = $candidate_id AND meta_key = '_goal'");
        if ($amount_raised > $goal) {
            $amount_raised = $goal;
        }
        return wc_price( (int) $amount_raised );
//        return wc_price( (int) $all_orders_total );
    }
}