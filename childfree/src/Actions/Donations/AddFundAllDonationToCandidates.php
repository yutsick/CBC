<?php

namespace WZ\ChildFree\Actions\Donations;

use WP_Query;
use Wz\Childfree\Actions\Hook;
use WZ\ChildFree\Models\FundAllCandidates;

class AddFundAllDonationToCandidates extends Hook
{
    public static array $hooks = array(
        'woocommerce_payment_complete',
    );

    public function __invoke($order_id)
    {
        $order = wc_get_order($order_id);
        $items = $order->get_items();
        $fund_all_candidates = new FundAllCandidates();
        $fund_all_candidates_name = $fund_all_candidates->get_name();

        foreach ($items as $item) {
            $product_id = $item->get_product_id();
            if ($product_id === FundAllCandidates::PRODUCT_ID) {
                $value_in_percent = $_COOKIE['ckValueInPercent'];
                $args = array(
                    'post_type' => 'product',
                    'post_status' => 'publish',
                    'posts_per_page' => -1,
                    'tax_query' => array(
                        array(
                            'taxonomy' => 'product_type',
                            'field' => 'slug',
                            'terms' => 'candidate',
                        ),
                    ),
                );

                // get the total number of candidates in the database
                $candidates_count = wc_get_products($args);
                $candidates_count = count($candidates_count);

                // get all candidates with remaining amount > 0
                $loop = new WP_Query($args);
                $candidates = array();

                // iterate over all candidates and add them to the cart if they are purchasable $candidates_count
                while ($loop->have_posts() && count($candidates) < $candidates_count+1) : $loop->the_post();
                    $product = wc_get_product(get_the_ID());
                    $candidate_id = $product->get_id();
                    $previous_amount_remaining = $product->get_amount_remaining();

                    // if the candidate has remaining amount > 0, then add the candidate to the candidates array
                    if ($previous_amount_remaining > 0) {
                        $previous_amount_raised = get_post_meta($product->get_id(), '_amount_raised', true);
                        $percent_amount = round($previous_amount_remaining * ($value_in_percent / 100), 0,PHP_ROUND_HALF_DOWN);
                        $new_amount_raised = (int)$previous_amount_raised + (int)$percent_amount;
                        $candidates[] = array(
                            'id' => $candidate_id,
                            'amount_remaining' => $previous_amount_remaining,
                            'percent_amount' => $percent_amount,
                            'amount_raised_previous' => $previous_amount_raised,
                            'new_amount_raised_value' => $previous_amount_remaining - $percent_amount,
                        );

                         // for testing these are test candidates
//                        $canArr = array(59435, 58836);
//                        if (in_array($candidate_id, $canArr)) {
                            $product_id = $candidate_id; // Replace with your actual product ID
                            $custom_amount = $new_amount_raised; // Replace with your actual custom amount
                            $order = wc_create_order();
                            $order->set_billing_first_name($fund_all_candidates_name);
                            $product = wc_get_product($product_id);
                            $product_price = $custom_amount; // Adjust the price to the custom amount
                            $order->add_product($product, 1, array('subtotal' => $product_price, 'total' => $product_price));
                            $order->calculate_totals();
                            $items = $order->get_items();
                            foreach ($items as $item_id => $itemD) {
                                wc_add_order_item_meta($item_id, 'donation', $custom_amount);
                            }
                            $order->update_status('completed');
                            $order->save();
//                        }
                    }
                endwhile;
                wp_reset_query();
            }
        }
    }
}
