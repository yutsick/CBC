<?php

namespace WZ\ChildFree\Actions\Admin;

use WZ\ChildFree\Actions\Hook;
use WZ\ChildFree\Models\Funding;
use WZ\ChildFree\Models\Provider;
use WZ\ChildFree\Template;

class AddCandidateDataPanel extends Hook
{
    public static array $hooks = array( 'woocommerce_product_data_panels' );

    public function __invoke() {
        global $product_object;

        if ( 'candidate' !== $product_object->get_type() ) {
            return;
        }

        $options = array( '' => '--' );
        $physicians = Provider::all();
        $funding = $product_object->get_funding();

        foreach ($physicians as $physician) {
            $options[ $physician->get_id() ] = "{$physician->get_business_name()} ({$physician->get_address_city()}, {$physician->get_address_state()})";
        }

        Template::render( 'admin/candidate-tab', array(
            'product_object' => $product_object,
            'candidate_entry' => $product_object->get_entry(),
            'registration_entry' => $product_object->get_registration_entry(),
            'funding' => $funding,
            'physicians' => $options,
            'affiliate' => $product_object->get_referred_by('user'),
            'can_submit_procedure_completed' => ( $product_object->is_funded() && $product_object->has_provider() )
        ) );

        Template::render( 'admin/candidate-form-entry', array(
            'candidate_entry' => $product_object->get_entry(),
            'registration_entry' => $product_object->get_registration_entry(),
        ) );
    }
}
