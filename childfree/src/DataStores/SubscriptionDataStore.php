<?php

namespace WZ\ChildFree\DataStores;

use WZ\ChildFree\Models\Subscription;

class SubscriptionDataStore extends \WC_Data_Store_WP
{
    /**
     * Data stored in meta keys, but not considered "meta".
     *
     * @since 3.0.0
     * @var array
     */
    protected $internal_meta_keys = array(
        '_customer_user',
        '_date_next_payment',
        '_date_cancelled',
        '_order_renewals',
    );

    /**
     * Method to create a new subscription in the database
     *
     * @param Subscription $subscription
     */
    public function create( &$subscription ) {
        if ( ! $subscription->get_date_created( 'edit' ) ) {
            $subscription->set_date_created( time() );
        }

        $id = wp_insert_post(
            array(
                'post_date'     => gmdate( 'Y-m-d H:i:s', $subscription->get_date_created( 'edit' )->getOffsetTimestamp() ),
                'post_date_gmt' => gmdate( 'Y-m-d H:i:s', $subscription->get_date_created( 'edit' )->getTimestamp() ),
                'post_type'     => 'cbc_subscription',
                'post_status'   => $this->get_post_status( $subscription ),
                'ping_status'   => 'closed',
                'post_author'   => 1,
                'post_title'    => $this->get_post_title(),
                'post_password' => $this->get_order_key( $subscription ),
                'post_parent'   => $subscription->get_parent_id( 'edit' ),
                'post_excerpt'  => $this->get_post_excerpt( $subscription ),
            ),
            true
        );

        if ( $id && ! is_wp_error( $id ) ) {
            $subscription->set_id( $id );
            $this->update_post_meta( $subscription );
            $subscription->save_meta_data();
            $subscription->apply_changes();
            $this->clear_caches( $subscription );
        }
    }

    /**
     * Method to read a subscription from the database.
     *
     * @param Subscription $subscription Order object.
     * @throws Exception If passed order is invalid.
     */
    public function read( &$subscription ) {
        $subscription->set_defaults();
        $post_object = get_post( $subscription->get_id() );
        if ( ! $subscription->get_id() || ! $post_object ) {
            throw new Exception( __( 'Invalid order.', 'woocommerce' ) );
        }

        $subscription->set_props(
            array(
                'parent_id'     => $post_object->post_parent,
                'date_created'  => $this->string_to_timestamp( $post_object->post_date_gmt ),
                'date_modified' => $this->string_to_timestamp( $post_object->post_modified_gmt ),
                'status'        => $post_object->post_status,
            )
        );

        $this->read_order_data( $subscription, $post_object );
        $subscription->read_meta_data();
        $subscription->set_object_read( true );
    }

    /**
     * Method to update a subscription in the database.
     *
     * @param Subscription $subscription
     */
    public function update( &$subscription ) {
        $subscription->save_meta_data();

        if ( null === $subscription->get_date_created( 'edit' ) ) {
            $subscription->set_date_created( time() );
        }

        $changes = $subscription->get_changes();

        // Only update the post when the post data changes.
        if ( array_intersect( array( 'date_created', 'date_modified', 'status', 'parent_id', 'post_excerpt' ), array_keys( $changes ) ) ) {
            $post_data = array(
                'post_date'         => gmdate( 'Y-m-d H:i:s', $subscription->get_date_created( 'edit' )->getOffsetTimestamp() ),
                'post_date_gmt'     => gmdate( 'Y-m-d H:i:s', $subscription->get_date_created( 'edit' )->getTimestamp() ),
                'post_status'       => $this->get_post_status( $subscription ),
                'post_parent'       => $subscription->get_parent_id(),
                'post_excerpt'      => $this->get_post_excerpt( $subscription ),
                'post_modified'     => isset( $changes['date_modified'] ) ? gmdate( 'Y-m-d H:i:s', $subscription->get_date_modified( 'edit' )->getOffsetTimestamp() ) : current_time( 'mysql' ),
                'post_modified_gmt' => isset( $changes['date_modified'] ) ? gmdate( 'Y-m-d H:i:s', $subscription->get_date_modified( 'edit' )->getTimestamp() ) : current_time( 'mysql', 1 ),
            );

            /**
             * When updating this object, to prevent infinite loops, use $wpdb
             * to update data, since wp_update_post spawns more calls to the
             * save_post action.
             *
             * This ensures hooks are fired by either WP itself (admin screen save),
             * or an update purely from CRUD.
             */
            if ( doing_action( 'save_post' ) ) {
                $GLOBALS['wpdb']->update(
                    $GLOBALS['wpdb']->posts,
                    $post_data,
                    array( 'ID' => $subscription->get_id() )
                );

                clean_post_cache( $subscription->get_id() );
            } else {
                wp_update_post( array_merge( array( 'ID' => $subscription->get_id() ), $post_data ) );
            }

            $subscription->read_meta_data( true ); // Refresh internal meta data, in case things were hooked into `save_post` or another WP hook.
        }

        $this->update_post_meta( $subscription );
        $subscription->apply_changes();
        $this->clear_caches( $subscription );
    }

    /**
     * Method to delete an subscription from the database.
     *
     * @param Subscription $subscription
     * @param array $args
     * @return void
     */
    public function delete( &$subscription, $args = array() ) {
        $id = $subscription->get_id();
        $args = wp_parse_args( $args, array( 'force_delete' => false ) );

        if ( ! $id ) {
            return;
        }

        if ( $args['force_delete'] ) {
            wp_delete_post( $id );
            $subscription->set_id( 0 );
        } else {
            wp_trash_post( $id );
            $subscription->set_status( 'trash' );
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Additional Methods
    |--------------------------------------------------------------------------
    */

    /**
     * Get the status to save to the post object.
     *
     * Plugins extending the order classes can override this to change the stored status/add prefixes etc.
     *
     * @since 3.6.0
     * @param  Subscription $subscription Order object.
     * @return string
     */
    protected function get_post_status( $subscription ) {
        $status = $subscription->get_status( 'edit' );

        if ( ! $status ) {
            $status = apply_filters( 'woocommerce_default_order_status', 'pending' );
        }

        $post_status    = $status;
        $valid_statuses = get_post_stati();

        // Add a wc- prefix to the status, but exclude some core statuses which should not be prefixed.
        // @todo In the future this should only happen based on `wc_is_order_status`, but in order to
        // preserve back-compatibility this happens to all statuses except a select few. A doing_it_wrong
        // Notice will be needed here, followed by future removal.
        if ( ! in_array( $post_status, array( 'auto-draft', 'draft', 'trash' ), true ) && in_array( 'wc-' . $post_status, $valid_statuses, true ) ) {
            $post_status = 'wc-' . $post_status;
        }

        return $post_status;
    }

    /**
     * Excerpt for post.
     *
     * @return string
     */
    protected function get_post_excerpt() {
        return '';
    }

    /**
     * Get a title for the new post type.
     *
     * @return string
     */
    protected function get_post_title() {
        return sprintf( __( 'Order &ndash; %s', 'woocommerce' ), strftime( _x( '%b %d, %Y @ %I:%M %p', 'Order date parsed by strftime', 'woocommerce' ) ) );
    }

    /**
     * Clear any caches.
     *
     * @param Subscription $subscription
     */
    protected function clear_caches( &$subscription ) {
        clean_post_cache( $subscription->get_id() );
    }
}
