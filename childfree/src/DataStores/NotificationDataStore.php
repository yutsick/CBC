<?php

namespace WZ\ChildFree\DataStores;

use WZ\ChildFree\Models\Notification;

class NotificationDataStore extends \WC_Data_Store_WP
{
    /**
     * Stores internal meta keys
     *
     * @var string[]
     */
    protected $internal_meta_keys = array(
        '_user_id',
        '_read',
    );

    /**
     * Stores updated props.
     *
     * @var array
     */
    protected $updated_props = array();

    /**
     * Creates new notification
     *
     * @param $notification
     */
    public function create( &$notification ) {
        if ( ! $notification->get_date_created( 'edit' ) ) {
            $notification->set_date_created( time() );
        }

        $id = wp_insert_post(
            array(
                'post_type'      => 'notification',
                'post_status'    => 'publish',
                'post_title'     => $notification->get_name() ? $notification->get_name() : __( 'Notification', 'woocommerce' ),
                'post_content'   => $notification->get_description(),
                'comment_status' => 'closed',
                'ping_status'    => 'closed',
                'post_date'      => gmdate( 'Y-m-d H:i:s', $notification->get_date_created( 'edit' )->getOffsetTimestamp() ),
                'post_date_gmt'  => gmdate( 'Y-m-d H:i:s', $notification->get_date_created( 'edit' )->getTimestamp() ),
            ),
            true
        );

        if ( $id && ! is_wp_error( $id ) ) {
            $notification->set_id( $id );

            $this->update_post_meta( $notification, true );

            $notification->save_meta_data();
            $notification->apply_changes();
        }
    }

    /**
     * Reads notification data from db
     *
     * @param Notification $notification
     * @throws \Exception
     */
    public function read( &$notification ) {
        $notification->set_defaults();
        $post_object = get_post( $notification->get_id() );

        if ( ! $notification->get_id() || ! $post_object || 'notification' !== $post_object->post_type ) {
            throw new \Exception( __( 'Invalid notification.', 'woocommerce' ) );
        }

        $notification->set_props(
            array(
                'name'              => $post_object->post_title,
                'date_created'      => $this->string_to_timestamp( $post_object->post_date_gmt ),
                'date_modified'     => $this->string_to_timestamp( $post_object->post_modified_gmt ),
                'description'       => $post_object->post_content,
            )
        );

        $this->read_notification_data( $notification );
        $notification->set_object_read( true );
    }

    /**
     * Updates notification data to db
     *
     * @param Notification $notification
     */
    public function update( &$notification ) {
        $notification->save_meta_data();
        $changes = $notification->get_changes();

        // Only update the post when the post data changes.
        if ( array_intersect( array( 'description', 'name', 'date_created', 'date_modified' ), array_keys( $changes ) ) ) {
            $post_data = array(
                'post_title'     => $notification->get_name( 'edit' ),
                'post_content'   => $notification->get_description( 'edit' ),
                'post_status'    => 'publish',
                'post_type'      => 'notification',
            );

            if ( $notification->get_date_created( 'edit' ) ) {
                $post_data['post_date']     = gmdate( 'Y-m-d H:i:s', $notification->get_date_created( 'edit' )->getOffsetTimestamp() );
                $post_data['post_date_gmt'] = gmdate( 'Y-m-d H:i:s', $notification->get_date_created( 'edit' )->getTimestamp() );
            }

            if ( isset( $changes['date_modified'] ) && $notification->get_date_modified( 'edit' ) ) {
                $post_data['post_modified']     = gmdate( 'Y-m-d H:i:s', $notification->get_date_modified( 'edit' )->getOffsetTimestamp() );
                $post_data['post_modified_gmt'] = gmdate( 'Y-m-d H:i:s', $notification->get_date_modified( 'edit' )->getTimestamp() );
            } else {
                $post_data['post_modified']     = current_time( 'mysql' );
                $post_data['post_modified_gmt'] = current_time( 'mysql', 1 );
            }

            /**
             * When updating this object, to prevent infinite loops, use $wpdb
             * to update data, since wp_update_post spawns more calls to the
             * save_post action.
             *
             * This ensures hooks are fired by either WP itself (admin screen save),
             * or an update purely from CRUD.
             */
            if ( doing_action( 'save_post' ) ) {
                $GLOBALS['wpdb']->update( $GLOBALS['wpdb']->posts, $post_data, array( 'ID' => $notification->get_id() ) );
                clean_post_cache( $notification->get_id() );
            } else {
                wp_update_post( array_merge( array( 'ID' => $notification->get_id() ), $post_data ) );
            }

            $notification->read_meta_data( true ); // Refresh internal meta data, in case things were hooked into `save_post` or another WP hook.

        } else { // Only update post modified time to record this save event.
            $GLOBALS['wpdb']->update(
                $GLOBALS['wpdb']->posts,
                array(
                    'post_modified'     => current_time( 'mysql' ),
                    'post_modified_gmt' => current_time( 'mysql', 1 ),
                ),
                array(
                    'ID' => $notification->get_id(),
                )
            );
            clean_post_cache( $notification->get_id() );
        }

        $this->update_post_meta( $notification );

        $notification->apply_changes();
    }

    /**
     * Delete notification from db
     *
     * @param Notification $notification
     * @param array $args
     */
    public function delete( &$notification, $args = array() ) {
        $id        = $notification->get_id();
        $post_type = 'notification';

        $args = wp_parse_args(
            $args,
            array(
                'force_delete' => false,
            )
        );

        if ( ! $id ) {
            return;
        }

        if ( $args['force_delete'] ) {
            wp_delete_post( $id );
            $notification->set_id( 0 );
        } else {
            wp_trash_post( $id );
        }
    }

    /**
     * Get the meta data for notification
     *
     * @param Notification $notification
     */
    protected function read_notification_data( &$notification ) {
        $id                = $notification->get_id();
        $post_meta_values  = get_post_meta( $id );
        $meta_key_to_props = array(
            '_user_id'	=> 'user_id',
            '_read'		=> 'read',
        );

        $set_props = array();

        foreach ( $meta_key_to_props as $meta_key => $prop ) {
            $meta_value         = isset( $post_meta_values[ $meta_key ][0] ) ? $post_meta_values[ $meta_key ][0] : null;
            $set_props[ $prop ] = maybe_unserialize( $meta_value ); // get_post_meta only unserializes single values.
        }

        $notification->set_props( $set_props );
    }

    /**
     * Update notification meta data
     *
     * @param Notification $notification
     * @param false $force
     */
    protected function update_post_meta( &$notification, $force = false ) {
        $meta_key_to_props = array(
            '_user_id'	=> 'user_id',
            '_read'		=> 'read',
        );

        $props_to_update = $force ? $meta_key_to_props : $this->get_props_to_update( $notification, $meta_key_to_props );

        foreach ( $props_to_update as $meta_key => $prop ) {
            $value = $notification->{"get_$prop"}( 'edit' );
            $value = is_string( $value ) ? wp_slash( $value ) : $value;

            switch ( $prop ) {
                case 'read':
                    $value = wc_bool_to_string( $value );
                    break;
            }

            $updated = $this->update_or_delete_post_meta( $notification, $meta_key, $value );

            if ( $updated ) {
                $this->updated_props[] = $prop;
            }
        }
    }
}
