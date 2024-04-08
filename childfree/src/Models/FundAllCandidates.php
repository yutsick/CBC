<?php

namespace WZ\ChildFree\Models;

class FundAllCandidates extends \WC_Product
{
    public const PRODUCT_ID = 59599;

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
        return 'fund-all-candidates';
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
        return __('Fund All Candidates Donate');
    }
}
