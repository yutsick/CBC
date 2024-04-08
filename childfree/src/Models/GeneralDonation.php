<?php

namespace WZ\ChildFree\Models;

class GeneralDonation extends \WC_Product
{
    public const PRODUCT_ID = 23603;

    /**
     * Get product name
     *
     * @param string $context
     * @return string
     */
    public function get_name($context = 'view') {
        return get_the_title(self::PRODUCT_ID);
    }

    /**
     * Get product type
     *
     * @return string
     */
    public function get_type() {
        return 'general-donation';
    }

    /**
     * Returns false if the product cannot be bought.
     *
     * @return bool
     */
    public function is_purchasable() {
        return true;
    }

    /**
     * Check if a product is sold individually (no quantities).
     *
     * @return bool
     */
    public function is_sold_individually() {
        return true;
    }

    /**
     * Get add to cart text
     *
     * @return string
     */
    public function single_add_to_cart_text() {
        return __('General Donate');
    }
}
