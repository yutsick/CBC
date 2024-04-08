<?php

namespace WZ\ChildFree\Actions\Donations;

use Wz\Childfree\Actions\Hook;
use WZ\ChildFree\Models\ExpansionDonation;
use WZ\ChildFree\Models\GeneralDonation;
use WZ\ChildFree\Models\LocationDonation;

class UpdateCartItemPrice extends Hook
{

    public static array $hooks = array(
        'wp_ajax_update_cart_item_prices',
        'wp_ajax_nopriv_update_cart_item_prices',
    );

    public function __invoke(): void
    {
        $this->update_cart_item_prices();
    }

    public function update_cart_item_prices(): void
    {
        check_ajax_referer('woocommerce-cart', 'security');

        if (!isset($_POST['modifiedItems']) || !is_array($_POST['modifiedItems'])) {
            wp_send_json_error('Invalid data');
        }

        $modified_items = $_POST['modifiedItems'];

        if (empty($modified_items)) {
            wp_send_json_error('No modified items to update.');
        }

        foreach ($modified_items as $modified_item) {
            $cart_item_key = sanitize_text_field($modified_item['cartItemKey']);
            $new_price = (float) $modified_item['newPrice'];

            if (WC()->cart->cart_contents[$cart_item_key]) {
                WC()->cart->cart_contents[$cart_item_key]['donation'] = $new_price;
            }
        }

        if (!WC()->cart->is_empty()) {

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
        }

        WC()->cart->calculate_totals();

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
        setcookie('ckSpecificDonationTotalAmount', $total_specific_cart_Amount, time() + (86400 * 30), "/");

        // get subtotal from cart
        $cart_subtotal = WC()->cart->get_cart_subtotal();
        $cart_subtotal = html_entity_decode(strip_tags($cart_subtotal));
        ob_start();
        woocommerce_mini_cart();
        $woocommerce_mini_cart = ob_get_clean();

        wp_send_json_success(array(
            'message' => 'Cart updated successfully',
            'cart_contents' => $custom_cart_contents,
            'total_specific_cart_Amount' => $total_specific_cart_Amount,
            'cart_subtotal' => $cart_subtotal,
            'mini_cart' => $woocommerce_mini_cart,
        ));
    }
}