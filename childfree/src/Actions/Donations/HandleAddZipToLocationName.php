<?php

namespace WZ\ChildFree\Actions\Donations;

use Wz\Childfree\Actions\Hook;
use WZ\ChildFree\Models\LocationDonation;

class HandleAddZipToLocationName extends Hook
{
    public static array $hooks = array(
//        'woocommerce_before_calculate_totals',
    );

    public static int $arguments = 1;
    public static int $priority = 15;

    public function __invoke($cart): void
    {
        if (is_admin() && !defined('DOING_AJAX')) return;
        if (did_action('woocommerce_before_calculate_totals') >= 2) return;

        // Specify the product ID you want to modify
        $target_product_id = LocationDonation::PRODUCT_ID;

        // Loop through each cart item
        foreach ($cart->get_cart() as $cart_item) {
            echo 'product id: ' . $cart_item['product_id'] . '<br>';
            // Continue if the current cart item is not the target product
            if ($cart_item['product_id'] !== $target_product_id) {
                continue;
            }

            // Get an instance of the WC_Product Object
            $product = $cart_item['data'];
            // Get the product name (Added compatibility with WooCommerce 3+)
            $product_name = method_exists($product, 'get_name') ? $product->get_name() : $product->post->post_title;

            // The new string composite name
            $product_name .= ' (' . $cart_item['_zip_code'] . ')';

            // Set the new composite name (WooCommerce versions 2.5.x to 3+)
            if (method_exists($product, 'set_name')) {
                $product->set_name($product_name);
            } else {
                $product->post->post_title = $product_name;
            }
            echo 'product name: ' . $product_name . '<br>';
        }
        die();
    }
}