<?php

namespace WZ\ChildFree\Actions\Donations;

use Wz\Childfree\Actions\Hook;
use WC_Cart; // Add this import to access the WooCommerce cart

class ClearCartItems extends Hook
{
    public static array $hooks = array(
        'init',
        'wp_ajax_clear_cart_items',
        'wp_ajax_nopriv_clear_cart_items',
    );

    public function __invoke(): void
    {
        $this->clear_cart_action();
    }

    function clear_cart_action() {
        $clear_cart = 'clear-cart';
        if (isset($_GET[$clear_cart])) {

            setcookie('ckGeneralDonationAmount', '', time() - 3600, '/');
            setcookie('ckGeneralDonationAmount', '', time() + (86400 * 30), "/");
            setcookie('ckExpansionDonationAmount', '', time() - 3600, '/');
            setcookie('ckLocationDonationAmount', '', time() - 3600, '/');
            setcookie('ckLocationZipCodeNumber', '', time() - 3600, '/');
            setcookie('ckSpecificDonationTotalAmount', '', time() - 3600, '/');

            WC()->cart->empty_cart();

            $current_page_url = add_query_arg(array(), $_SERVER['REQUEST_URI']);
            $current_page_url = remove_query_arg($clear_cart, $current_page_url);
            wp_safe_redirect($current_page_url);
            exit;
        }
    }
}
