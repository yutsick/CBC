<?php

namespace WZ\ChildFree\Actions\Donations;

use Exception;
use JsonException;
use WC_Coupon;
use WZ\ChildFree\Actions\Hook;

class HandleGeneralTabOnCheckoutPage extends Hook
{
    public static array $hooks = array(
        'wp_loaded',
        'wp_ajax_general_checkout_action',
        'wp_ajax_nopriv_general_checkout_action',
    );

    public static int $priority = 15;

    /**
     * @throws JsonException
     * @throws Exception
     */
    public function __invoke(): void
    {
        // Start or resume the session
        if (!session_id()) {
            session_start();
        }

        $user_role = '';
        $user_display_name = '';
        if (is_user_logged_in()) {
            $user = wp_get_current_user();
            if (isset($user->roles[0])) {
                $user_role = $user->roles[0];
                $user_display_name = $user->display_name;
            }
        }

        if (isset($_GET['funds']) && $_GET['funds'] === 'general') {

            $custom_cart_contents = array();
            // Check if cart is empty if not then get cart total
            if (WC()->cart->get_cart_contents_count() > 0) {
                $cart_contents = WC()->cart->get_cart_contents();
                if ($cart_contents) {
                    foreach ($cart_contents as $key => $value) {

                        if (isset($value['product_id'])) {
                            $product_id = $value['product_id'];
                            $product = wc_get_product($product_id);

                            // Create an array with the desired keys and values
                            $custom_cart_contents[] = array(
                                'product_id' => $product_id,
                                'product_thumbnail' => $product->get_image(),
                                'product_url' => $product->get_permalink(),
                                'product_name' => $product->get_name(),
                                'product_price' => $value['donation'],
                                'product_key' => $value['key'],
                            );
                        }
                    }
                }
                $cart_total = WC()->cart->get_cart_total();
                $cart_total = html_entity_decode(strip_tags($cart_total));

                // get subtotal from cart
                $cart_subtotal = WC()->cart->get_cart_subtotal();
                $cart_subtotal = html_entity_decode(strip_tags($cart_subtotal));
            } else {
                $cart_total = 0;
                $cart_subtotal = 0;
            }

            wp_enqueue_style('childfree-checkout',
                WZ_CHILDFREE_URL . 'assets/css/checkout.css?h=' . uniqid('', true),
                array(), WZ_CHILDFREE_VERSION, 'all');

            wp_enqueue_script('childfree-checkout',
                WZ_CHILDFREE_URL . 'assets/js/checkout-general.js?h=' . microtime(),
                array('jquery'), WZ_CHILDFREE_VERSION, true);

            wp_localize_script('childfree-checkout', 'obj_general', array(
                'nonce' => wp_create_nonce('woocommerce-cart'),
                'ajaxurl' => admin_url('admin-ajax.php'),
                'is_general_funds_tab_selected' => true,
                'user_role' => $user_role,
                'voucher_amount' => $voucher_amount,
                'user_display_name' => $user_display_name,
                'cart_contents' => $custom_cart_contents,
                'cart_total' => $cart_total,
                'cart_subtotal' => $cart_subtotal,
            ));
        }

        // GENERAL_CHECKOUT_ACTION FUNCTIONALITY
        if (isset($_POST['action']) && $_POST['action'] === 'general_checkout_action') {
            if (isset($_POST['security']) && wp_verify_nonce($_POST['security'], 'woocommerce-cart')) {
                // PAGE: 1
                if (!empty($_POST['general_form_page_1_data'])) {
                    $page1_data = json_decode(stripslashes($_POST['general_form_page_1_data']), true, 512, JSON_THROW_ON_ERROR);

                    $general_product_id = $page1_data['generalProductID'];
                    $general_donation_amount = $page1_data['generalDonationAmount'];

                    $expansion_product_id = $page1_data['expansionProductID'];
                    $expansion_donation_amount = $page1_data['expansionDonationAmount'];

                    $location_product_id = $page1_data['locationProductID'];
                    $location_donation_amount = $page1_data['locationDonationAmount'];

                    $total_donation_amount = $page1_data['totalDonationAmount'];

                    // Check if the general product ID is provided then add it to cart
                    if (!empty($general_product_id) && $general_donation_amount > 0) {
                        foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item) {
                            if ( $cart_item['product_id'] === $general_product_id ) {
                                if ($general_donation_amount > $cart_item['donation'] || $general_donation_amount < $cart_item['donation']) {
                                    // Add the new donation amount to the existing donation amount
//                                    $general_donation_amount += (int)$cart_item['donation'];
                                    // Overwrite the existing donation amount with the new donation amount
                                    $cart_item['donation'] = $general_donation_amount;
                                }
                                else {
                                    $general_donation_amount = (int)$cart_item['donation'];
                                }
                                // Remove the existing product from cart and later I'll add it again with updated donation amount
                                WC()->cart->remove_cart_item( $cart_item_key );
                            }
                        }
                        // add the general donation product to the cart
                        $general_cart_data = array('donation' => $general_donation_amount);
                        $general_product_result = WC()->cart->add_to_cart($general_product_id, 1, 0, [], $general_cart_data);

                        if ($general_product_result instanceof \WP_Error) {
                            $response = array(
                                'status' => 'error',
                                'message' => $general_product_result->get_error_message(),
                            );
                            wp_send_json_error($response);
                        }
                        setcookie('ckGeneralDonationAmount', $general_donation_amount, time() + (86400 * 30), "/");
                    }
                    else {
                        // If general product is not selected then remove it from cart
                        $cart_contents = WC()->cart->get_cart_contents();
                        if ($cart_contents) {
                            foreach ($cart_contents as $key => $value) {
                                if (isset($value['product_id']) && $value['product_id'] === $general_product_id) {
                                    WC()->cart->remove_cart_item($key);
                                }
                            }
                        }
                    }

                    // Check if the expansion product ID is provided then add it to cart
                    if (!empty($expansion_product_id) && $expansion_donation_amount > 0) {
                        foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item) {
                            if ( $cart_item['product_id'] === $expansion_product_id ) {
                                if ($expansion_donation_amount > $cart_item['donation'] || $expansion_donation_amount < $cart_item['donation']) {
//                                    $expansion_donation_amount += (int)$cart_item['donation'];
                                    // Overwrite the existing donation amount with the new donation amount
                                    $cart_item['donation'] = $expansion_donation_amount;
                                }
                                else {
                                    $expansion_donation_amount = (int)$cart_item['donation'];
                                }
                                // Remove the existing product from cart and later I'll add it again with updated donation amount
                                WC()->cart->remove_cart_item( $cart_item_key );
                            }
                        }
                        // add the general donation product to the cart
                        $expansion_cart_data = array('donation' => $expansion_donation_amount);
                        $expansion_product_result = WC()->cart->add_to_cart($expansion_product_id, 1, 0, [], $expansion_cart_data);

                        if ($expansion_product_result instanceof \WP_Error) {
                            $response = array(
                                'status' => 'error',
                                'message' => $expansion_product_result->get_error_message(),
                            );
                            wp_send_json_error($response);
                        }
                        setcookie('ckExpansionDonationAmount', $expansion_donation_amount, time() + (86400 * 30), "/");
                    }
                    else {
                        // If expansion product is not selected then remove it from cart
                        $cart_contents = WC()->cart->get_cart_contents();
                        if ($cart_contents) {
                            foreach ($cart_contents as $key => $value) {
                                if (isset($value['product_id']) && $value['product_id'] === $expansion_product_id) {
                                    WC()->cart->remove_cart_item($key);
                                }
                            }
                        }
                    }

                    // Check if the location product ID is provided then add it to cart
                    if (!empty($location_product_id) && $location_donation_amount > 0) {
                        foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item) {
                            if ( $cart_item['product_id'] === $location_product_id ) {
                                if ($location_donation_amount > $cart_item['donation'] || $location_donation_amount < $cart_item['donation']) {
//                                    $location_donation_amount += (int)$cart_item['donation'];
                                    // Overwrite the existing donation amount with the new donation amount
                                    $cart_item['donation'] = $location_donation_amount;
                                }
                                else {
                                    $location_donation_amount = (int)$cart_item['donation'];
                                }
                                // Remove the existing product from cart and later I'll add it again with updated donation amount
                                WC()->cart->remove_cart_item( $cart_item_key );
                            }
                        }
                        // add the general donation product to the cart
                        $location_cart_data = array(
                            '_zip_code' => $page1_data['locationZipCodeNumber'],
                            'donation' => $location_donation_amount
                        );
                        $location_product_result = WC()->cart->add_to_cart($location_product_id, 1, 0, [], $location_cart_data);

                        if ($location_product_result instanceof \WP_Error) {
                            $response = array(
                                'status' => 'error',
                                'message' => $location_product_result->get_error_message(),
                            );
                            wp_send_json_error($response);
                        }
                        setcookie('ckLocationDonationAmount', $location_donation_amount, time() + (86400 * 30), "/");
                    }
                    else {
                        // If location product is not selected then remove it from cart
                        $cart_contents = WC()->cart->get_cart_contents();
                        if ($cart_contents) {
                            foreach ($cart_contents as $key => $value) {
                                if (isset($value['product_id']) && $value['product_id'] === $location_product_id) {
                                    WC()->cart->remove_cart_item($key);
                                }
                            }
                        }
                    }

                    if (WC()->cart->get_cart_contents_count() > 0) {
                        $cart_total = WC()->cart->get_cart_total();
                        // get subtotal from cart
                        $cart_subtotal = WC()->cart->get_cart_subtotal();
                        $cart_subtotal = html_entity_decode(strip_tags($cart_subtotal));
                        ob_start();
                        woocommerce_mini_cart();
                        $woocommerce_mini_cart = ob_get_clean();

                        $response = array(
                            'status' => 'success',
                            'message' => 'ADDED'
                                . '\n\t-> General_Amount:[$' . $general_donation_amount . ']'
                                . '\n\t-> Expansion_Amount:[$' . $expansion_donation_amount . ']'
                                . '\n\t-> location_Amount:[$' . $location_donation_amount . ']'
                                . '\n\t-> location_ZipCode:[$' . $page1_data['locationZipCodeNumber'] . ']'
                                . '\n\tCartTotal:['.$cart_total.'] '
                                .'\n\tCookie:[' . $_COOKIE['ckTotalDonations'].']',
                            'generalProductID' => $general_product_id,
                            'expansionProductID' => $expansion_product_id,
                            'generalDonationAmount' => $general_donation_amount,
                            'expansionDonationAmount' => $expansion_donation_amount,
                            'Cart_Total' => str_replace('&#36;', '$', strip_tags($cart_total)),
                            'cart_subtotal' => $cart_subtotal,
                            'mini_cart' => $woocommerce_mini_cart,
                        );
                        wp_send_json_success($response);
                    }

                    wp_die();
                }

                // PAGE: 2
                if (!empty($_POST['general_form_page_2_data'])) {
                    $page2_data = json_decode(stripslashes($_POST['general_form_page_2_data']), true, 512, JSON_THROW_ON_ERROR);

                    // Store values in session
                    $selectedReferralYesNo = $page2_data['selectedReferralYesNo'];
                    $donationReferrerDetail = $page2_data['donationReferrerDetail'];
                        $response = array(
                            'status' => 'success',
                            'message' => 'Operation successful',
                            'selectedReferralYesNo' => 'No',
                            'donationReferrerDetail' => $donationReferrerDetail,
                        );
                        wp_send_json_success($response);
                }

            }
        }
    }
}