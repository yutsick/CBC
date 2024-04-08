<?php

namespace WZ\ChildFree\Actions\Candidate;

use WZ\ChildFree\Actions\Hook;

class HandleAddToCart extends Hook
{
    public static array $hooks = array(
        'wp_footer',
        'woocommerce_add_to_cart_handler_candidate',
        'woocommerce_add_to_cart_handler_general-donation',
        'woocommerce_add_to_cart_handler_expansion-donation',
        'woocommerce_add_to_cart_handler_location-donation',
    );

    public function __invoke() {
        // Hide product quantity column on cart page
        if ( is_page( 'cart' ) ) {
            ?>
            <script type="text/javascript">
                jQuery(document).ready(function($) {
                    $('.product-quantity').hide();
                });
            </script>
            <?php
        }

        if(isset($_REQUEST['product_id'])) {
            $product_id = absint( wp_unslash( $_REQUEST['product_id'] ) );
        }
        else {
//            echo __( 'Product ID ['.$_REQUEST['product_id'].'] is missing!' );
            return;
        }
        if ( ! $product_id ) {
            return;
        }

        $product = wc_get_product( $product_id );
        if ($product && $product->is_purchasable()) {
            $donation_amount = absint( wp_unslash( $_REQUEST['amount'] ) );

            if ( 'other' === $_REQUEST['amount'] ) {
                $donation_amount = absint( wp_unslash( $_REQUEST['other_amount'] ) );
            }

            $cartItemData = array( 'donation' => $donation_amount );

            if ( false !== wc()->cart->add_to_cart( $product_id, 1, 0, [], $cartItemData ) ) {
                wc_add_to_cart_message( $product_id );
            }
        }
        else {
            wc_add_notice( __( 'ProductID:['.$product_id.'] is not purchasable. This product cannot be added to your cart.' ), 'error' );
        }
    }
}
