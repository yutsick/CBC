<?php

namespace WZ\ChildFree\Actions\Admin;

use Wz\Childfree\Actions\Hook;
use WZ\ChildFree\Services\EmailVerification;

class AddCandidateExportData extends Hook
{
    public static array $hooks = array(
        "woocommerce_product_export_product_column_sex",
        "woocommerce_product_export_product_column_age",
        "woocommerce_product_export_product_column_honorarium",
        "woocommerce_product_export_product_column_goal",
        "woocommerce_product_export_product_column_amount_raised",
        "woocommerce_product_export_product_column_email",
        "woocommerce_product_export_product_column_ip_address",
        "woocommerce_product_export_product_column_email_verified",
        "woocommerce_product_export_product_column_referred_page_url",
        "woocommerce_product_export_product_column_referred_person",
        "woocommerce_product_export_product_column_user_id",
        "woocommerce_product_export_product_column_zip_code",
    );

    public static int $arguments = 3;

    public function __invoke( $value, $product, $column_id ) {
        if ( $product->get_type() !== 'candidate' ) {
            return $value;
        }

        if ( 'first_name' === $column_id ) {
            return $product->get_registration_entry()->metas[7];
        } else if ( 'last_name' === $column_id ) {
            return $product->get_registration_entry()->metas[8];
        } else if ( 'sex' === $column_id ) {
            return $product->get_sex();
        } else if ( 'age' === $column_id ) {
            return $product->get_age();
        } else if ( 'honorarium' === $column_id ) {
            return $product->get_honorarium();
        } else if ( 'goal' === $column_id ) {
            return $product->get_goal();
        } else if ( 'amount_raised' === $column_id ) {
            return $product->get_amount_raised();
        } else if ( 'email' === $column_id ) {
            return $product->get_registration_entry()->metas[9];
        } else if ( 'ip_address' === $column_id ) {
            return $product->get_registration_entry()->ip;
        } else if ( 'email_verified' === $column_id ) {
            return wc_bool_to_string( EmailVerification::is_verified( $product->get_user_id() ) );
        } else if ( 'referred_page_url' === $column_id ) {
            return $product->get_referred_page_url();
        } else if ( 'referred_person' === $column_id ) {
            return $product->get_referred_person();
        } else if ( 'user_id' === $column_id ) {
            return $product->get_person_user_id();
        } else if ( 'zip_code' === $column_id ) {
            return $product->get_location();
        }


        return $value;
    }
}