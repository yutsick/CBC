<?php

namespace WZ\ChildFree\Models;

class LocationDonation extends \WC_Product
{
    public const PRODUCT_ID = 56180;

    /**
     * Get product name
     *
     * @param string $context
     * @return string
     */
    public function get_name($context = 'view'): string
    {
        return __('Specific Location Fund Donation');
    }

    /**
     * Get product type
     *
     * @return string
     */
    public function get_type() {
        return 'location-donation';
    }

    /**
     * Returns false if the product cannot be bought.
     *
     * @return bool
     */
    public function is_purchasable(): bool
    {
        return true;
    }

    /**
     * Check if a product is sold individually (no quantities).
     *
     * @return bool
     */
    public function is_sold_individually(): bool
    {
        return true;
    }

    /**
     * Get add to cart text
     *
     * @return string
     */
    public function single_add_to_cart_text() {
        return __('Location Donate!');
    }
}
