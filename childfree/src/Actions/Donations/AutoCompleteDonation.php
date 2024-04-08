<?php

namespace WZ\ChildFree\Actions\Donations;

use WZ\ChildFree\Actions\Hook;

class AutoCompleteDonation extends Hook
{
    public static array $hooks = array(
        'init',
        'woocommerce_order_status_pending_to_processing',
        'woocommerce_thankyou',
    );

    public function __invoke( $order_id ) {
        // if new order is placed, then mark it as processing
        add_action('woocommerce_order_status_pending_to_processing', function($order_id) {
            // Get the order object
            $order = wc_get_order($order_id);

            // Set the order status from pending to completed
            if ( $order->get_status() === 'processing' ) {
                $order->update_status( 'completed' );
            }
        });

        add_action('template_redirect', function() {
            // Check if on the checkout page, order-received endpoint, and has the order key
            if ( is_page('checkout') && is_wc_endpoint_url( 'order-received' ) && !empty( $_GET['key'] ) ) {
                // Get order ID
                $order_id = wc_get_order_id_by_order_key( sanitize_text_field( $_GET['key'] ) );
                // change order status to completed
                $order = wc_get_order($order_id);
                $order->update_status( 'completed' );

                // If order ID is valid, perform the redirect
                if ( $order_id ) {
                    wp_safe_redirect( home_url( '/thank-you_page?id=' . $order_id ) );
                    exit;
                }
            }
        });

    }
}
