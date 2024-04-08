<?php

namespace WZ\ChildFree\Actions\Candidate;

use Wz\Childfree\Actions\Hook;
use WZ\ChildFree\Models\ExpansionDonation;
use WZ\ChildFree\Models\FundAllCandidates;
use WZ\ChildFree\Models\GeneralDonation;
use WZ\ChildFree\Models\LocationDonation;

class HandleMiniCartItems extends Hook
{
    public static array $hooks = array(
        'woocommerce_before_calculate_totals',
        'woocommerce_cart_item_permalink',
//        'woocommerce_cart_item_name',
//        'woocommerce_mini_cart_contents',
    );

    public function __invoke()
    {
        add_filter('woocommerce_cart_item_permalink', '__return_false');
        add_filter('woocommerce_cart_item_name', array($this, 'add_back_product_link'), 10, 2);
    }

    function add_back_product_link( $cart_item_name,  $cart_item ): string
    {
        $excluded_product_ids = array(
            GeneralDonation::PRODUCT_ID,
            LocationDonation::PRODUCT_ID,
            ExpansionDonation::PRODUCT_ID,
            FundAllCandidates::PRODUCT_ID,
        );

        if (in_array($cart_item['product_id'], $excluded_product_ids, true)) {
            if ($cart_item['product_id'] === LocationDonation::PRODUCT_ID) {
                $cart_item_name = $cart_item['data']->get_name();
                $cart_item_name .= ' (' . $cart_item['_zip_code'] . ')';
            }
            else {
                $cart_item_name = $cart_item['data']->get_name();
            }
        }
        else {
            $product_permalink = $cart_item['data']->get_permalink();
            $cart_item_name = "<a href='{$product_permalink}'>{$cart_item['data']->get_name()}</a>";
        }

        return $cart_item_name;
    }

}