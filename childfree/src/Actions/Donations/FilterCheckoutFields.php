<?php

namespace WZ\ChildFree\Actions\Donations;

use WZ\ChildFree\Actions\Hook;

class FilterCheckoutFields extends Hook
{

    public static array $hooks = array(
        'woocommerce_checkout_fields',
    );

    public static int $priority = 20;

    public function __invoke($fields) {
        // Change the default value of donate_anonymously hidden field
        $fields['billing']['donate_anonymously'] = array(
            'type' => 'hidden',
            'class' => array('donate-anonymously'),
            'default' => 'empty',
        );
        return $fields;
    }
}
