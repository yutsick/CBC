<?php

namespace WZ\ChildFree\Actions\Donations;

use Exception;
use JsonException;
use WC_Coupon;
use Wz\Childfree\Actions\Hook;
use WZ\ChildFree\Models\ExpansionDonation;
use WZ\ChildFree\Models\GeneralDonation;
use WZ\ChildFree\Models\LocationDonation;

class HandleExpansionTabOnCheckoutPage  extends Hook
{
    public static array $hooks = array(
        'wp_loaded',
        'wp_ajax_expansion_checkout_action',
        'wp_ajax_nopriv_expansion_checkout_action',
    );

    public static int $priority = 15;

    /**
     * @throws JsonException
     * @throws Exception
     */
    public function __invoke(): void
    {
        $user_role = '';
        $user_display_name = '';
        if (is_user_logged_in()) {
            $user = wp_get_current_user();
            if (isset($user->roles[0])) {
                $user_role = $user->roles[0];
                $user_display_name = $user->display_name;
            }
        }

        if (isset($_GET['funds']) && $_GET['funds'] === 'expansion' && !isset($_POST['action'])) {

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
                WZ_CHILDFREE_URL . 'assets/css/checkout.css?h='.uniqid('', true),
                array(), WZ_CHILDFREE_VERSION, 'all');

            wp_enqueue_script('childfree-checkout',
                WZ_CHILDFREE_URL . 'assets/js/checkout-expansion.js?h=' . microtime(),
                array('jquery'), WZ_CHILDFREE_VERSION, true);

            wp_localize_script('childfree-checkout', 'obj_expansion', array(
                'nonce' => wp_create_nonce('woocommerce-cart'),
                'ajaxurl' => admin_url('admin-ajax.php'),
                'is_expansion_funds_tab_selected' => true,
                'user_role' => $user_role,
                'user_display_name' => $user_display_name,
                'cart_contents' => $custom_cart_contents,
                'cart_total' => $cart_total,
                'cart_subtotal' => $cart_subtotal,
            ));
        }

        if (isset($_POST['action']) && $_POST['action'] === 'expansion_checkout_action') {
            if (isset($_POST['security']) && wp_verify_nonce($_POST['security'], 'woocommerce-cart')) {
                // PAGE: 1
                if (!empty($_POST['expansion_form_page_1_data'])) {
                    $page1_data = json_decode(stripslashes($_POST['expansion_form_page_1_data']), true, 512, JSON_THROW_ON_ERROR);
                    $generalDonationAmount = $page1_data['generalDonationAmount'];
                    $expansionDonationAmount = $page1_data['expansionDonationAmount'];
                    $locationDonationAmount = $page1_data['locationDonationAmount'];
                    $locationZipCodeNumber = $page1_data['locationZipCodeNumber'];

                    // check if any voucher is applied to the cart and if so, display the voucher code
                    if (WC()->cart->has_discount()) {
                        $applied_coupons = WC()->cart->get_applied_coupons();
                        $voucher_code = $applied_coupons[0];
                        $voucher = new WC_Coupon($voucher_code);
                        $voucher_amount = $voucher->get_amount();
                    }

                    // if $generalDonationAmount has a value, then add the general donation product to the cart with the given amount
                    if ($generalDonationAmount > 0) {
                        // check if the general donation product is already in the cart
                        $general_donation_product_id = GeneralDonation::PRODUCT_ID;
                        foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item) {
                            if ( $cart_item['product_id'] === $general_donation_product_id ) {
                                if ($generalDonationAmount > $cart_item['donation'] || $generalDonationAmount < $cart_item['donation']) {
                                    // Add the new donation amount to the existing donation amount
//                                    $general_donation_amount += (int)$cart_item['donation'];
                                    // Overwrite the existing donation amount with the new donation amount
                                    $cart_item['donation'] = $generalDonationAmount;
                                }
                                else {
                                    $generalDonationAmount = (int)$cart_item['donation'];
                                }
                                // Remove the existing product from cart and later I'll add it again with updated donation amount
                                WC()->cart->remove_cart_item( $cart_item_key );
                            }
                        }
                        // add the general donation product to the cart
                        $cartItemData = array('donation' => $generalDonationAmount);
                        WC()->cart->add_to_cart($general_donation_product_id, 1, 0, [], $cartItemData);
                        setcookie('ckGeneralDonationAmount', $generalDonationAmount, time() + (86400 * 30), "/");
                    }
                    else {
                        // remove the general donation product from the cart
                        $general_donation_product_id = GeneralDonation::PRODUCT_ID;
                        foreach (WC()->cart->get_cart() as $cart_item_key => $values) {
                            $_product = $values['data'];
                            if ($_product->get_id() === $general_donation_product_id) {
                                $general_donation_product_in_cart = true;
                                break;
                            }
                        }
                        if ($general_donation_product_in_cart) {
                            // remove the general donation product from the cart
                            WC()->cart->remove_cart_item($cart_item_key);
                        }
                    }

                    // if $expansionDonationAmount has a value, then add the expansion donation product to the cart with the given amount
                    if ($expansionDonationAmount > 0) {
                        // check if the expansion donation product is already in the cart
                        $expansion_donation_product_id = ExpansionDonation::PRODUCT_ID;
                        foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item) {
                            if ( $cart_item['product_id'] === $expansion_donation_product_id ) {
                                if ($expansionDonationAmount > $cart_item['donation'] || $expansionDonationAmount < $cart_item['donation']) {
//                                    $expansionDonationAmount += (int)$cart_item['donation'];
                                    // Overwrite the existing donation amount with the new donation amount
                                    $cart_item['donation'] = $expansionDonationAmount;
                                }
                                else {
                                    $expansionDonationAmount = (int)$cart_item['donation'];
                                }
                                // Remove the existing product from cart and later I'll add it again with updated donation amount
                                WC()->cart->remove_cart_item( $cart_item_key );
                            }
                        }
                        // add the expansion donation product to the cart
                        $cartItemData = array('donation' => $expansionDonationAmount);
                        WC()->cart->add_to_cart($expansion_donation_product_id, 1, 0, [], $cartItemData);
                        setcookie('ckExpansionDonationAmount', $expansionDonationAmount, time() + (86400 * 30), "/");
                    }
                    else {
                        // remove the expansion donation product from the cart
                        $expansion_donation_product_id = ExpansionDonation::PRODUCT_ID;
                        $expansion_donation_product_in_cart = false;
                        foreach (WC()->cart->get_cart() as $cart_item_key => $values) {
                            $_product = $values['data'];
                            if ($_product->get_id() === $expansion_donation_product_id) {
                                $expansion_donation_product_in_cart = true;
                                break;
                            }
                        }
                        if ($expansion_donation_product_in_cart) {
                            // remove the expansion donation product from the cart
                            WC()->cart->remove_cart_item($cart_item_key);
                        }
                    }

                    // Check if the location product ID is provided then add it to cart
                    if ($locationDonationAmount > 0 && !empty($locationZipCodeNumber)) {
                        $location_product_id = LocationDonation::PRODUCT_ID;
                        foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item) {
                            if ( $cart_item['product_id'] === $location_product_id ) {
                                if ($locationDonationAmount > $cart_item['donation'] || $locationDonationAmount < $cart_item['donation']) {
//                                    $locationDonationAmount += (int)$cart_item['donation'];
                                    // Overwrite the existing donation amount with the new donation amount
                                    $cart_item['donation'] = $locationDonationAmount;
                                }
                                else {
                                    $locationDonationAmount = (int)$cart_item['donation'];
                                }
                                // Remove the existing product from cart and later I'll add it again with updated donation amount
                                WC()->cart->remove_cart_item( $cart_item_key );
                            }
                        }

                        $location_cart_data = array(
                            '_zip_code' => $locationZipCodeNumber,
                            'donation' => $locationDonationAmount
                        );
                        $location_product_result = wc()->cart->add_to_cart($location_product_id, 1, 0, [], $location_cart_data);

                        if ($location_product_result instanceof \WP_Error) {
                            $response = array(
                                'status' => 'error',
                                'message' => $location_product_result->get_error_message(),
                            );
                            wp_send_json_error($response);
                        }
                        setcookie('ckLocationDonationAmount', $locationDonationAmount, time() + (86400 * 30), "/");
                    }
                    else {
                        // remove the location donation product from the cart
                        $location_donation_product_id = LocationDonation::PRODUCT_ID;
                        $location_donation_product_in_cart = false;
                        foreach (WC()->cart->get_cart() as $cart_item_key => $values) {
                            $_product = $values['data'];
                            if ($_product->get_id() === $location_donation_product_id) {
                                $location_donation_product_in_cart = true;
                                break;
                            }
                        }
                        if ($location_donation_product_in_cart) {
                            // remove the location donation product from the cart
                            WC()->cart->remove_cart_item($cart_item_key);
                        }
                    }


                    // get cart total without html tags
                    $cart_total = WC()->cart->get_cart_total();
                    $cart_total = html_entity_decode(strip_tags($cart_total));
                    // get subtotal from cart
                    $cart_subtotal = WC()->cart->get_cart_subtotal();
                    $cart_subtotal = html_entity_decode(strip_tags($cart_subtotal));
                    ob_start();
                    woocommerce_mini_cart();
                    $woocommerce_mini_cart = ob_get_clean();


                    wp_send_json_success(array(
                        'status' => 'success',
                        'message' => 'Operation completed successfully.',
                        'voucher_amount' => $voucher_amount,
                        'cart_total' => $cart_total,
                        'cart_subtotal' => $cart_subtotal,
                        'mini_cart' => $woocommerce_mini_cart,
                    ));
                    wp_die();
                }

                // PAGE: 2
                if (!empty($_POST['expansion_form_page_2_data'])) {
                    $page2_data = json_decode(stripslashes($_POST['expansion_form_page_2_data']), true, 512, JSON_THROW_ON_ERROR);

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