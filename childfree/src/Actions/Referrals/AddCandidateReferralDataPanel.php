<?php

namespace WZ\ChildFree\Actions\Referrals;

use WZ\ChildFree\Template;

class AddCandidateReferralDataPanel extends \WZ\ChildFree\Actions\Hook
{
    public static array $hooks = array( 'woocommerce_product_data_panels' );

    public function __invoke() {
        global $product_object;

        if ( 'candidate' !== $product_object->get_type() ) {
            return;
        }

        $referrals = $product_object->get_referrals();

        Template::render( 'admin/candidate-referrals', array(
            'referrals' => $referrals,
            'candidate' => $product_object
        ) );
    }
}
