<?php

namespace WZ\ChildFree\Actions\Donations;

use Wz\ChildFree\Actions\Hook;

class FilterStripeCheckoutDetails extends Hook
{
    /**
     * @var string
     */
    public static array $hooks = array('woocommerce_available_payment_gateways');

    // write invoke method
    public function __invoke($gateways)
    {
        // check if gateway is stripe
        if (!isset($gateways['stripe'])) {
            return $gateways;
        }

        $gateways['stripe']->description =
            'Pay securely with your credit/debit card using Stripe. Stripe’s services are PCI Compliant. They meet the strict standards of the Payment Card Industry, and your cardholder data is used and stored by Stripe only.';

        return $gateways;
    }
}