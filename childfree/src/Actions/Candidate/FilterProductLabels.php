<?php

namespace WZ\ChildFree\Actions\Candidate;

use WZ\ChildFree\Actions\Hook;

class FilterProductLabels extends Hook
{
    public static array $hooks = ['woocommerce_register_post_type_product'];

    public function __invoke( $args ) {
        $args['labels'] = [
            'name'                  => __( 'Candidates', 'woocommerce' ),
            'singular_name'         => __( 'Candidate', 'woocommerce' ),
            'all_items'             => __( 'All Candidates', 'woocommerce' ),
            'menu_name'             => _x( 'Candidates', 'Admin menu name', 'woocommerce' ),
            'add_new'               => __( 'Add New', 'woocommerce' ),
            'add_new_item'          => __( 'Add new product', 'woocommerce' ),
            'edit'                  => __( 'Edit', 'woocommerce' ),
            'edit_item'             => __( 'Edit candidate', 'woocommerce' ),
            'new_item'              => __( 'New candidate', 'woocommerce' ),
            'view_item'             => __( 'View candidate', 'woocommerce' ),
            'view_items'            => __( 'View candidates', 'woocommerce' ),
            'search_items'          => __( 'Search candidates', 'woocommerce' ),
            'not_found'             => __( 'No candidates found', 'woocommerce' ),
            'not_found_in_trash'    => __( 'No candidates found in trash', 'woocommerce' ),
            'parent'                => __( 'Parent candidate', 'woocommerce' ),
            'featured_image'        => __( 'Product image', 'woocommerce' ),
            'set_featured_image'    => __( 'Set candidate image', 'woocommerce' ),
            'remove_featured_image' => __( 'Remove candidate image', 'woocommerce' ),
            'use_featured_image'    => __( 'Use as candidate image', 'woocommerce' ),
            'insert_into_item'      => __( 'Insert into candidate', 'woocommerce' ),
            'uploaded_to_this_item' => __( 'Uploaded to this candidate', 'woocommerce' ),
            'filter_items_list'     => __( 'Filter candidates', 'woocommerce' ),
            'items_list_navigation' => __( 'Candidates navigation', 'woocommerce' ),
            'items_list'            => __( 'Candidates list', 'woocommerce' ),
            'item_link'             => __( 'Candidate Link', 'woocommerce' ),
            'item_link_description' => __( 'A link to a candidate.', 'woocommerce' ),
        ];

        $args['supports'] = ['title', 'thumbnail'];

        return $args;
    }
}
