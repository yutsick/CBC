<?php

namespace WZ\ChildFree\Actions\Donations;

use Wz\Childfree\Actions\Hook;

class HandleThankyouPage extends Hook
{
    public static array $hooks = array(
//        'init',
        'wp_ajax_thankyou_page_action',
        'wp_ajax_nopriv_thankyou_page_action',
    );

    public function __invoke()
    {
        if ($_POST['action'] === 'thankyou_page_action') {
            $this->thankyou_page_action($_POST['order_id']);
        }
    }

    public function thankyou_page_action($order_id): void
    {

        $order = wc_get_order($order_id);
        $order_data = $order->get_data();

        foreach ($order_data['line_items'] as $line_item) {
            // Access the product_id for each line item
            $product_id = $line_item['product_id'];
            // Update the candidate amount raised value in wp_postmeta -> '_amount_raised'
            do_shortcode("[candidate_amount_raised user_id='$product_id']");
        }
        //echo '<pre>';
        //print_r($order_data);
        //echo '</pre>';
        //die();

        $response = array();

        if (empty($order_data)) {
            wp_send_json_error('Order not found');
        }
        $response['order_id'] = $order_id;
        $response['order_key'] = $order_data['order_key'];
        $response['order_date'] = $order_data['date_created']->date('Y-m-d H:i:s');
        $response['order_discount_total'] = $order_data['discount_total'];
        $response['order_total'] = $order_data['total'];
        $response['order_currency'] = $order_data['currency'];
        $response['order_status'] = $order_data['status'];
        $response['order_payment_method'] = $order_data['payment_method'];
        $response['order_payment_method_title'] = $order_data['payment_method_title'];
        $response['order_billing_first_name'] = $order_data['billing']['first_name'];
        $response['order_billing_last_name'] = $order_data['billing']['last_name'];
        $response['order_billing_company'] = $order_data['billing']['company'];
        $response['order_billing_address_1'] = $order_data['billing']['address_1'];
        $response['order_billing_address_2'] = $order_data['billing']['address_2'];
        $response['order_billing_city'] = $order_data['billing']['city'];
        $response['order_billing_state'] = $order_data['billing']['state'];
        $response['order_billing_postcode'] = $order_data['billing']['postcode'];
        $response['order_billing_country'] = $order_data['billing']['country'];
        $response['order_billing_email'] = $order_data['billing']['email'];
        $response['order_billing_phone'] = $order_data['billing']['phone'];

        // fetch product name and subtotal from order
        $items = $order->get_items();
        $response['order_items'] = array();
        foreach ($items as $item) {
            $product = $item->get_product();
            $response['order_items'][] = array(
                'product_id' => $product->get_id(),
                'product_name' => $product->get_name(),
                'product_price' => $product->get_price(),
                'product_subtotal' => $item->get_subtotal(),
                // get prouct image url
                'product_image_url' => wp_get_attachment_url($product->get_image_id()),
            );
        }

        global $wpdb;

        // fetch referrer's affiliate id, name and amount from affiliate_wp_referrals table
        $table_name = $wpdb->prefix . 'affiliate_wp_referrals';
        $referral_details = $wpdb->get_results(" SELECT affiliate_id, amount FROM $table_name WHERE reference = $order_id ");

        // fetch referrer's user id from affiliate_wp_affiliates table
        $affiliate_id = (string)$referral_details[0]->affiliate_id;
        $table_name = $wpdb->prefix . 'affiliate_wp_affiliates';
        $referrer_user_id = $wpdb->get_results(" SELECT user_id FROM $table_name WHERE affiliate_id = $affiliate_id ");

        // fetch referrer's display name from wp_users table
        $user_id = (string)$referrer_user_id[0]->user_id;
        $table_name = $wpdb->prefix . 'users';
        $referrer_name = $wpdb->get_results(" SELECT display_name FROM $table_name WHERE ID = $user_id ");

        $response['referral_details'][] = array(
            'affiliate_id' => $affiliate_id,
            'referrer_amount' => $referral_details[0]->amount,
            'referrer_name' => $referrer_name[0]->display_name,
        );

        wp_send_json_success($response);

    }
}