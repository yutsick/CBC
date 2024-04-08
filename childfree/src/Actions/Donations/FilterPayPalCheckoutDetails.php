<?php

namespace WZ\ChildFree\Actions\Donations;

use WZ\ChildFree\Actions\Hook;

class FilterPayPalCheckoutDetails extends Hook
{
    /**
     * @var string
     */
    public static array $hooks = array(
        'woocommerce_gateway_title'
    );

    public static int $arguments = 2;
    public static int $priority = 25;

    // use __invoke method
    public function __invoke($title, $gateway_id)
    {
        // check if gateway is stripe
        if ($gateway_id !== 'ppcp-gateway') {
            return $title;
        }

        return 'PayPal/Venmo';
    }
}
