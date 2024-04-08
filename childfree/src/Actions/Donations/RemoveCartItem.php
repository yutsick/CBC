<?php

namespace WZ\ChildFree\Actions\Donations;

use Wz\Childfree\Actions\Hook;
use WC_Cart;
use WZ\ChildFree\Models\ExpansionDonation;
use WZ\ChildFree\Models\FundAllCandidates;
use WZ\ChildFree\Models\GeneralDonation;
use WZ\ChildFree\Models\LocationDonation;

// Add this import to access the WooCommerce cart

class RemoveCartItem extends Hook
{
    public static array $hooks = array(
        'wp_ajax_remove_custom_cart_item',
        'wp_ajax_nopriv_remove_custom_cart_item',
    );

    public function __invoke(): void
    {
        $this->remove_custom_cart_item();
    }

    public function remove_custom_cart_item()
    {
        // Check for a valid nonce to ensure the request is secure
        check_ajax_referer('woocommerce-cart', 'security');

        // Get the product key or ID you want to remove
        $product_key = sanitize_text_field($_POST['product_key']);

        // Get the WooCommerce cart instance
        $cart = WC()->cart;

        // Remove the product from the cart by its key
        $cart->remove_cart_item($product_key);

        // Calculate and update the cart totals
        $cart->calculate_totals();

        // Initialize total amount
        $total_specific_cart_Amount = 0;
        // Loop through each item in the cart
        foreach ($cart->get_cart() as $cart_item_key => $cart_item) {
            // Get product ID
            $product_id = $cart_item['product_id'];
            $product = wc_get_product($product_id);
            // Check if the product ID is not '1' or '2'
            if ($product_id !== GeneralDonation::PRODUCT_ID
                && $product_id !== ExpansionDonation::PRODUCT_ID
                && $product_id !== LocationDonation::PRODUCT_ID
                && $product_id !== FundAllCandidates::PRODUCT_ID) {
                // Add the item total to the total amount
                $total_specific_cart_Amount += $cart_item['line_total'];
                $custom_cart_contents[] = array(
                    'product_id' => $product_id,
                    'product_thumbnail' => $product->get_image(),
                    'product_url' => $product->get_permalink(),
                    'product_name' => $product->get_name(),
                    'product_price' => $cart_item['donation'],
                    'product_key' => $cart_item['key'],
                );

            }
        }

        // set cart total cookie value if it is already not set
        setcookie('ckSpecificDonationTotalAmount', $total_specific_cart_Amount, time() + (86400 * 30), "/");

        // get cart total
        $cart_total = $cart->get_cart_total();
        $cart_total = html_entity_decode(strip_tags($cart_total));
        // if cart_total is 0 or empty then check if cart has any discount applied, if yes, then remove the discount voucher
//        if ($cart_total === '$0') {
//            $applied_coupons = $cart->get_applied_coupons();
//            if (!empty($applied_coupons)) {
//                foreach ($applied_coupons as $coupon) {
//                    $cart->remove_coupon($coupon);
//                }
//                $applied_coupons = $cart->get_applied_coupons();
//            }
//            // Remove voucher from session
//            do_action('remove_voucher');
//            // update cookie value ckVoucherCode ckVoucherAmount
//            setcookie('ckVoucherCode', '', time() + (86400 * 30), "/");
//            setcookie('ckVoucherAmount', 0, time() + (86400 * 30), "/");
//        }

        // get woocommerce_mini_cart content and send in json success response
        ob_start();
        woocommerce_mini_cart();
        $woocommerce_mini_cart = ob_get_clean();

        // do action 'remove_voucher' to remove voucher from session

        // Return a response
        wp_send_json_success(array(
            'message' => "ProductKey [$product_key] removed, new cartTotal [$cart_total], new miniCart [$woocommerce_mini_cart]",
            'cartTotal' => $cart_total,
//            'voucherCode' => $applied_coupons,
//            'voucherAmount' => $cart->get_discount_total(),
            'cart_contents' => $custom_cart_contents,
            'mini_cart' => $woocommerce_mini_cart,
        ));
        wp_die();
    }
}
