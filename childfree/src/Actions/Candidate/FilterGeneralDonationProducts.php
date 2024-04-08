<?php

namespace WZ\ChildFree\Actions\Candidate;

use WZ\ChildFree\Actions\Hook;

class FilterGeneralDonationProducts extends Hook
{
    public static array $hooks = array( 'pre_get_posts' );

    public static int $priority = 5;

    public function __invoke( $query ) {
        if (
            !is_admin() &&
            !is_singular( 'product' ) &&
            $query->get( 'post_type' ) === 'product'
        ) {
            $tax_query = (array) $query->get( 'tax_query' );

            $tax_query[] = array(
                'taxonomy'	=> 'product_type',
                'field'		=> 'slug',
                'terms'		=> array( 'candidate' ),
            );

            $query->set( 'tax_query', $tax_query );
        }

        return $query;
    }
}

