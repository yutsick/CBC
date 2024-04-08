<?php

namespace WZ\ChildFree\Actions\Donations;

use JsonException;
use WC_Coupon;
use Wz\Childfree\Actions\Hook;
use WZ\ChildFree\Models\ExpansionDonation;
use WZ\ChildFree\Models\GeneralDonation;
use WZ\ChildFree\Models\LocationDonation;

class HandleSpecificTabOnCheckoutPage extends Hook
{

    public static array $hooks = array(
        'wp_footer',
        'wp_ajax_specific_checkout_action',
        'wp_ajax_nopriv_specific_checkout_action',
    );

    public static int $priority = 15;

    /**
     * @throws JsonException
     */
    public function __invoke(): void
    {
        $raw_cart_total = 0;
        $user_role = '';
        $user_display_name = '';
        if (is_user_logged_in()) {
            $user = wp_get_current_user();
            if (isset($user->roles[0])) {
                $user_role = $user->roles[0];
                $user_display_name = $user->display_name;
            }
        }

        // HIDE PAYMENT METHODS when the Voucher is applied and the cart total is $0
        add_filter('woocommerce_cart_needs_payment', function () {
            if (WC()->cart->get_cart_contents_total() <= 0) {
                wp_die('You have a voucher applied and your cart total is $0. Please proceed to checkout.');
                return false;
            }
            return true;
        }, 99);

        if (is_page('checkout') || (isset($_GET['funds']) && $_GET['funds'] === 'specific' && !isset($_POST['action']))) {
            $cart_total = 0;
            $voucher_amount = 0;
            $discount_amount = 0;
            $voucher_code = '';
            $custom_cart_contents = array();
            if (isset($_COOKIE['ckVoucherCode'])) {
                $voucher_code = $_COOKIE['ckVoucherCode'];
            }
            // check if any voucher is applied to the cart and if so, display the voucher code
            if (WC()->cart->has_discount()) {
                $applied_coupons = WC()->cart->get_applied_coupons();
                $voucher_code = $applied_coupons[0];
                $voucher = new WC_Coupon($voucher_code);
                $voucher_amount = $voucher->get_amount();
            }
            // Else check if the voucher code is set in the cookie and if so, display the voucher code
            else if ($voucher_code) {
                $voucher = new WC_Coupon($voucher_code);
                $voucher_amount = $voucher->get_amount();
            }

            if (!WC()->cart->is_empty()) {


                $cart_contents = WC()->cart->get_cart_contents();
                $raw_cart_total = WC()->cart->get_cart_total();
                $raw_cart_total = html_entity_decode(strip_tags($raw_cart_total));
                $raw_cart_total = str_replace('$', '', $raw_cart_total);

                // get subtotal from cart
                $cart_subtotal = WC()->cart->get_cart_subtotal();
                $cart_subtotal = html_entity_decode(strip_tags($cart_subtotal));

                if ($voucher_code) {
                    $discount_amount = WC()->cart->get_discount_total();

                    if ($raw_cart_total > 0) {
                        $cart_total = $raw_cart_total;
                    }
                } else {
                    $cart_total = '$' . $raw_cart_total;
                }

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

                // Get Cart Items Other than General and Expansion etc Donation Products
                $cart = WC()->cart;
                // Initialize total amount
                $total_specific_cart_Amount = 0;
                // Loop through each item in the cart
                foreach ($cart->get_cart() as $cart_item_key => $cart_item) {
                    // Get product ID
                    $product_id = $cart_item['product_id'];

                    // Check if the product ID is not donation product ID
                    if ($product_id !== GeneralDonation::PRODUCT_ID
                        && $product_id !== ExpansionDonation::PRODUCT_ID
                        && $product_id !== LocationDonation::PRODUCT_ID) {
                        // Add the item total to the total amount
                        $total_specific_cart_Amount += $cart_item['line_subtotal'];
                    }
                }
            } else {
                $cart_total = 0;
                $cart_subtotal = 0;
            }

            // set cart total cookie value if it is already not set
            setcookie('ckSpecificDonationTotalAmount', $total_specific_cart_Amount, time() + (86400 * 30), "/");
            setcookie('ckVoucherCode', $voucher_code, time() + (86400 * 30), "/");
            setcookie('ckVoucherAmount', $voucher_amount, time() + (86400 * 30), "/");

            wp_enqueue_style('childfree-checkout',
                WZ_CHILDFREE_URL . 'assets/css/checkout.css?h='.uniqid('', true),
                array(), WZ_CHILDFREE_VERSION, 'all');

            wp_enqueue_script('childfree-checkout',
                WZ_CHILDFREE_URL . 'assets/js/checkout-specific.js?h=' . microtime(),
                array('jquery'), WZ_CHILDFREE_VERSION, true);

            wp_localize_script('childfree-checkout', 'obj_candidates', array(
                'nonce' => wp_create_nonce('woocommerce-cart'),
                'ajaxurl' => admin_url('admin-ajax.php'),
                'is_specific_funds_tab_selected' => true,
                'user_role' => $user_role,
                'user_display_name' => $user_display_name,
                'voucher_code' => $voucher_code,
                'voucher_amount' => $voucher_amount,
                'discount_amount' => $discount_amount,
                'cart_contents' => $custom_cart_contents,
                'cart_total' => $raw_cart_total,
                'cart_subtotal' => $cart_subtotal,
                'total_specific_cart_Amount' => $total_specific_cart_Amount,
            ));
        }

        // SPECIFIC CHECKOUT ACTION HANDLER
        if (isset($_POST['action']) && $_POST['action'] === 'specific_checkout_action') {
            // check if nonce is set and if so, check if it is valid
            if (isset($_POST['security']) && wp_verify_nonce($_POST['security'], 'woocommerce-cart')) {

                // PAGE-1
                if (!empty($_POST['specific_form_page_1_data'])) {
                    $page1_data = json_decode(stripslashes($_POST['specific_form_page_1_data']), true, 512, JSON_THROW_ON_ERROR);
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
                        $location_product_result =
                            wc()->cart->add_to_cart($location_product_id, 1, 0, [], $location_cart_data);

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
                    $specific_cart_total = str_replace('$', '', $cart_total);

                    // minus the values of donation and expansion products for sending only the specific donation amount to the ajax callback
                    if ($generalDonationAmount) {
                        $specific_cart_total -= $generalDonationAmount;
                    }
                    if ($expansionDonationAmount) {
                        $specific_cart_total -= $expansionDonationAmount;
                    }
                    if ($locationDonationAmount) {
                        $specific_cart_total -= $locationDonationAmount;
                    }

                    // set cart total cookie value
                    setcookie('ckSpecificDonationTotalAmount', $specific_cart_total, time() + (86400 * 30), "/");

                    // get subtotal from cart
                    $cart_subtotal = WC()->cart->get_cart_subtotal();
                    $cart_subtotal = html_entity_decode(strip_tags($cart_subtotal));
                    // update minicart with new values
                    ob_start();
                    woocommerce_mini_cart();
                    $woocommerce_mini_cart = ob_get_clean();

                    wp_send_json_success(array(
                        'status' => 'success',
                        'message' => 'Operation completed successfully.',
                        'voucher_amount' => $voucher_amount,
                        'specific_cart_total' => $specific_cart_total,
                        'cart_total' => $cart_total,
                        'cart_subtotal' => $cart_subtotal,
                        'mini_cart' => $woocommerce_mini_cart,
                    ));
                    wp_die();
                }

                // PAGE 2
                if (!empty($_POST['specific_form_page_2_data'])) {
                    $page2_data = json_decode(stripslashes($_POST['specific_form_page_2_data']), true, 512, JSON_THROW_ON_ERROR);
                    // Store values in session
                    $createDonorAccountYesNo = $page2_data['createDonorAccountYesNo'];
                    $specificReferralYesNo = $page2_data['specificReferralYesNo'];
                    $specificReferrerDetail = $page2_data['specificReferrerDetail'];
                        $response = array(
                            'status' => 'success2',
                            'message' => 'Operation successful',
                            'cart_total' => $cart_total,
                            'createDonorAccountYesNo' => $createDonorAccountYesNo,
                            'specificReferralYesNo' => $specificReferralYesNo,
                            'specificReferrerDetail' => $specificReferrerDetail,
                        );
                        wp_send_json_success($response);
                }

                // PAGE 3
                if (!empty($_POST['specific_form_page_3_data'])) {
                    $page3_data = json_decode(stripslashes($_POST['specific_form_page_3_data']), true, 512, JSON_THROW_ON_ERROR);

                    if (isset($page3_data['donate_anonymously'])) {
                        $donate_anonymously = sanitize_text_field($page3_data['donate_anonymously']);

                        if (!empty($donate_anonymously)) {
                            wp_send_json_success(array(
                                'message' => 'Donate Anonymously: [' . $donate_anonymously . ']',
                                'cart_total' => $cart_total,
                            ));
                        } else {
                            wp_send_json_error(array(
                                'message' => 'Donate Anonymously is empty',
                            ));
                        }
                    } else {
                        wp_send_json_error(array(
                            'message' => 'Donate Anonymously is not set',
                        ));
                    }
                    wp_die();
                }
            }
        }
    }
}