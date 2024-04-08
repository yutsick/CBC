<?php

namespace WZ\ChildFree\Actions\Admin;

class AddCandidateExportColumns extends \WZ\ChildFree\Actions\Hook
{
    public static array $hooks = array( 'woocommerce_product_export_product_default_columns' );

    public function __invoke( $columns ) {
        $columns['first_name']		= 'First Name';
        $columns['last_name']		= 'Last Name';
        $columns['sex'] 			= 'Sex';
        $columns['age'] 			= 'Age';
        $columns['honorarium'] 		= 'Honorarium';
        $columns['goal'] 			= 'Goal';
        $columns['amount_raised'] 	= 'Amount Raised';
        $columns['email'] 			= 'Email';
        $columns['ip_address']		= 'IP Address';
        $columns['email_verified']  = 'Email Verified';
        $columns['referred_page_url']  ='Referred Page Url';
        $columns['referred_person']  = 'Referred Person';
        $columns['user_id']  = 'User ID';
        $columns['zip_code']  = 'Zip Code';

        return $columns;
    }
}
