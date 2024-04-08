<?php

namespace WZ\ChildFree\Actions\Donations;

use WZ\ChildFree\Actions\Hook;

class FilterBillingFields extends Hook
{
    public static array $hooks = array(
        'woocommerce_billing_fields',
    );

    public static int $priority = 1;

    public function __invoke( $fields ) {
        $fields['billing_phone']['required'] = false;

        unset( $fields['billing_company'] );

        # if cart total is 0 then unset billing form, there is no need to fill it
        if ( WC()->cart->total == 0 ) {
            unset( $fields['billing_first_name'] );
            unset( $fields['billing_last_name'] );
            unset( $fields['billing_address_1'] );
            unset( $fields['billing_address_2'] );
            unset( $fields['billing_city'] );
            unset( $fields['billing_postcode'] );
            unset( $fields['billing_country'] );
            unset( $fields['billing_state'] );
            unset( $fields['billing_phone'] );
            unset( $fields['billing_email'] );

            echo "<script>jQuery('#frm_field_179_container > h3').hide();</script>"; // specific tab
            echo "<script>jQuery('#frm_field_213_container > h3').hide();</script>"; // general tab
            echo "<script>jQuery('#frm_field_434_container > h3').hide();</script>"; // location tab
            echo "<script>jQuery('#frm_field_325_container > h3').hide();</script>"; // expansion tab

            echo "<style>.woocommerce-account-fields {margin-top:unset !important;}</style>"; // all tabs
        }

        return $fields;
    }
}
