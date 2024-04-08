<?php

namespace WZ\ChildFree\Actions\Admin;

use WZ\ChildFree\Actions\Hook;

class AddCandidateDataTab extends Hook
{
    public static array $hooks = array('woocommerce_product_data_tabs');

    public function __invoke( $tabs ) {
        global $product_object;

        if ( 'candidate' !== $product_object->get_type() ) {
            return $tabs;
        }

        return array(
                'candidate' => array(
                    'label' => __('Candidate'),
                    'target' => 'candidate_product_data',
                    'priority' => 1
                ),
                'form_entry' => array(
                    'label' => __('Form Entry'),
                    'target' => 'candidate_form_entry',
                    'priority' => 2
                ),
                'referrals' => array(
                    'label' => __('Referrals'),
                    'target' => 'candidate_referrals',
                    'priority' => 3
                )
            ) + $tabs;
    }
}
