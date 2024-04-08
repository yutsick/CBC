<?php

namespace WZ\ChildFree\Actions\Notifications;

use WZ\ChildFree\Actions\Hook;

class RegisterPostType extends Hook
{
    public static array $hooks = array( 'init' );

    public function __invoke() {
        $labels = array(
            'name'                  => _x( 'Notifications', 'Post Type General Name', 'text_domain' ),
            'singular_name'         => _x( 'Notification', 'Post Type Singular Name', 'text_domain' ),
            'menu_name'             => __( 'Notifications', 'text_domain' ),
            'name_admin_bar'        => __( 'Notification', 'text_domain' ),
            'archives'              => __( 'Notification Archives', 'text_domain' ),
            'attributes'            => __( 'Notification Attributes', 'text_domain' ),
            'parent_item_colon'     => __( 'Parent Notification:', 'text_domain' ),
            'all_items'             => __( 'All Notifications', 'text_domain' ),
            'add_new_item'          => __( 'Add New Notification', 'text_domain' ),
            'add_new'               => __( 'Add New', 'text_domain' ),
            'new_item'              => __( 'New Notification', 'text_domain' ),
            'edit_item'             => __( 'Edit Notification', 'text_domain' ),
            'update_item'           => __( 'Update Notification', 'text_domain' ),
            'view_item'             => __( 'View Notification', 'text_domain' ),
            'view_items'            => __( 'View Notifications', 'text_domain' ),
            'search_items'          => __( 'Search Notification', 'text_domain' ),
            'not_found'             => __( 'Not found', 'text_domain' ),
            'not_found_in_trash'    => __( 'Not found in Trash', 'text_domain' ),
            'featured_image'        => __( 'Featured Image', 'text_domain' ),
            'set_featured_image'    => __( 'Set featured image', 'text_domain' ),
            'remove_featured_image' => __( 'Remove featured image', 'text_domain' ),
            'use_featured_image'    => __( 'Use as featured image', 'text_domain' ),
            'insert_into_item'      => __( 'Insert into notification', 'text_domain' ),
            'uploaded_to_this_item' => __( 'Uploaded to this notification', 'text_domain' ),
            'items_list'            => __( 'Notifications list', 'text_domain' ),
            'items_list_navigation' => __( 'Notifications list navigation', 'text_domain' ),
            'filter_items_list'     => __( 'Filter notifications list', 'text_domain' ),
        );

        $args = array(
            'label'                 => __( 'Notification', 'text_domain' ),
            'description'           => __( 'Notifications for users', 'text_domain' ),
            'labels'                => $labels,
            'supports'              => false,
            'taxonomies'            => array(),
            'hierarchical'          => false,
            'public'                => false,
            'show_ui'               => false,
            'show_in_menu'          => false,
            'menu_position'         => 5,
            'show_in_admin_bar'     => false,
            'show_in_nav_menus'     => false,
            'can_export'            => false,
            'has_archive'           => false,
            'exclude_from_search'   => true,
            'publicly_queryable'    => false,
            'capability_type'       => 'page',
        );

        register_post_type( 'notification', $args );
    }
}
