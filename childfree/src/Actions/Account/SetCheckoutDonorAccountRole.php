<?php

namespace WZ\ChildFree\Actions\Account;

use Wz\Childfree\Actions\Hook;

class SetCheckoutDonorAccountRole extends Hook
{

    public static array $hooks = array(
        'woocommerce_new_customer_data',
    );

    public function __invoke($customer_data): array
    {
        // Set donor role at checkout page if created donor account is set to yes.
        if(is_checkout()) {
            $customer_data['role'] = 'subscriber';
        }
        return $customer_data;
    }

}