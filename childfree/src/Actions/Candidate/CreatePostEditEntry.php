<?php

namespace WZ\ChildFree\Actions\Candidate;

use WZ\ChildFree\Actions\Hook;

class CreatePostEditEntry extends Hook
{
    public static array $hooks = array( 'frm_after_create_entry' );
    public static int $arguments = 2;
    public static int $priority = 40;

    public function __invoke( $entry_id, $form_id ) {
        if ( 3 !== $form_id ) {
            return;
        }

        global $wpdb;

        $id		= 6;
        $entry 	= \FrmEntry::getOne( $entry_id );
        $post	= get_post( $entry->post_id );

        $values = array(
            'description'		=> __( 'Copied from Post' ),
            'form_id'			=> $id,
            'parent_item_id'	=> $entry_id,
            'created_at'		=> $entry->created_at,
            'updated_at'		=> $entry->created_at,
            'ip'				=> $entry->ip,
            'name'				=> $post->post_title,
            'item_key'    		=> \FrmAppHelper::get_unique_key( $post->post_name, $wpdb->prefix . 'frm_items', 'item_key' ),
            'user_id'     		=> $entry->user_id,
            'post_id'     		=> $post->ID,
        );

        $wpdb->insert( $wpdb->prefix . 'frm_items', $values );

        $new_entry_id = $wpdb->insert_id;
        $user_id_field = \FrmField::get_all_types_in_form( $id, 'user_id', 1 );
        if ( $user_id_field ) {
            $new_values = array(
                'meta_value' => $entry->user_id,
                'item_id'    => $new_entry_id,
                'field_id'   => $user_id_field->id,
                'created_at' => current_time( 'mysql', 1 ),
            );

            $wpdb->insert( $wpdb->prefix . 'frm_item_metas', $new_values );
        }

        $display = \FrmProDisplay::get_auto_custom_display( array( 'form_id' => $id, 'entry_id' => $new_entry_id ) );
        if ( $display ) {
            update_post_meta( $post->ID, 'frm_display_id', $display->ID );
        }
    }
}
