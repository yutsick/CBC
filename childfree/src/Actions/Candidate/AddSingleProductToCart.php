<?php

namespace WZ\ChildFree\Actions\Candidate;

use Exception;
use WZ\ChildFree\Actions\Hook;
class AddSingleProductToCart extends Hook
{
    public function __construct() {
        add_action('wp_ajax_childfree_add_to_cart', array($this, 'childfree_add_to_cart_action'));
        add_action('wp_ajax_nopriv_childfree_add_to_cart', array($this, 'childfree_add_to_cart_action'));
    }

    /**
     * @throws Exception
     */
    public function childfree_add_to_cart_action() {
        try {
            // Verify nonce
            $nonce = $_POST['nonce'];
            if ( ! wp_verify_nonce( $nonce, 'browse_candidates_nonce' ) ) {
                wp_send_json_error('Nonce [' . $_POST['nonce'] . '] is invalid!');
            }

            $response = array();  // Initialize a response array

            // Check if the product ID is provided
            if ( isset( $_POST['product_id'] ) && isset( $_POST['amount'] ) && $_POST['amount'] > 0 ) {
                $product_id = absint( $_POST['product_id'] );
                $donation_amount = absint( wp_unslash( $_REQUEST['amount'] ) );

                if ( '0' === $_REQUEST['amount'] || '' === $_REQUEST['amount'] ) {
                    $donation_amount = absint( wp_unslash( $_REQUEST['other_amount'] ) );
                }

                // Check if product exists in cart, if yes, then add the donation amount to the existing amount of same product
                $cart = WC()->cart->get_cart();
                foreach( $cart as $cart_item_key => $cart_item ) {
                    if ( $cart_item['product_id'] === $product_id ) {
                        $donation_amount += (int)$cart_item['donation'];
                        // Remove the existing product from cart and later I'll add it again with updated donation amount
                        WC()->cart->remove_cart_item( $cart_item_key );
                    }
                }

                $cartItemData = array( 'donation' => $donation_amount );

                // Before adding the product to the cart along with its updated donation amount, check if the $cartItemData exceeds the remaining amount of the product
                $remaining_amount = do_shortcode('[candidate_remaining_amount id="' . $product_id . '"]');
                if ( $donation_amount > $remaining_amount ) {
                    // In this case, set the donation amount to the remaining amount of the product
                    $cartItemData = array( 'donation' => $remaining_amount );
                }

                // Adding the product to the cart (along with updated donation amount ~if applicable~)
                $result = wc()->cart->add_to_cart( $product_id, 1, 0, [], $cartItemData );

                // Check if adding to cart was successful
                if ( $result instanceof \WP_Error ) {
                    // Adding to cart failed, provide the error message
                    $response['success'] = false;
                    $response['message'] = $result->get_error_message();
                } else {
                    $response['success'] = true;
                    $response['remaining_amount'] = $remaining_amount;
                    $response['message'] = 'Product with amount:[$' . $donation_amount . '] added to cart successfully.';
                }
            } else {
                $response['success'] = false;
                $response['message'] = 'Please check Product ID: ' . $_POST['product_id'] . ' or Amount: ' . $_POST['amount'] . ' is valid.';
            }

            // Send JSON response and exit
            wp_send_json( $response );
            wp_die();  // Ensure proper termination
        } catch (Exception $e) {
            wp_send_json_error( $e->getMessage() );
            wp_die();
        }
    }
}

// Instantiate the class, otherwise you'll get 400 Bad Request error while calling ajax action
new AddSingleProductToCart();