<?php

namespace WZ\ChildFree\Models;

class Notification extends \WC_Data
{
    /**
     * Object type key
     *
     * @var string
     */
    protected $object_type = 'notification';

    /**
     * Post type key
     *
     * @var string
     */
    protected $post_type = 'notification';

    /**
     * Cache group key
     *
     * @var string
     */
    protected $cache_group = 'notifications';

    /**
     * Data keys and defaults
     *
     * @var array
     */
    protected $data = array(
        'name'			=> '',
        'date_created' 	=> null,
        'date_modified' => null,
        'description' 	=> '',
        'user_id' 		=> 0,
        'read'			=> false,
    );

    /**
     * Construct
     *
     * @param int $notification
     * @throws \Exception
     */
    public function __construct( $notification = 0 )
    {
        parent::__construct( $notification );

        if ( is_numeric( $notification ) && $notification > 0 ) {
            $this->set_id( $notification );
        } elseif ( $notification instanceof self ) {
            $this->set_id( absint( $notification->get_id() ) );
        } else {
            $this->set_object_read( true );
        }

        $this->data_store = \WC_Data_Store::load( 'notification' );

        if ( $this->get_id() > 0 ) {
            $this->data_store->read( $this );
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Getters
    |--------------------------------------------------------------------------
    */

    public function get_name( $context = 'view' ) {
        return $this->get_prop( 'name', $context );
    }

    public function get_date_created( $context = 'view' ) {
        return $this->get_prop( 'date_created', $context );
    }

    public function get_date_modified( $context = 'view' ) {
        return $this->get_prop( 'date_modified', $context );
    }

    public function get_description( $context = 'view' ) {
        return $this->get_prop( 'description', $context );
    }

    public function get_user_id( $context = 'view' ) {
        return $this->get_prop( 'user_id', $context );
    }

    public function get_read( $context = 'view' ) {
        return $this->get_prop( 'read', $context );
    }

    /*
    |--------------------------------------------------------------------------
    | Setters
    |--------------------------------------------------------------------------
    */

    public function set_name( $name ) {
        $this->set_prop( 'name', $name );
    }

    public function set_date_created( $date = null ) {
        $this->set_date_prop( 'date_created', $date );
    }

    public function set_date_modified( $date = null ) {
        $this->set_date_prop( 'date_modified', $date );
    }

    public function set_description( $description ) {
        $this->set_prop( 'description', $description );
    }

    public function set_user_id( $user_id ) {
        $this->set_prop( 'user_id', absint( $user_id ) );
    }

    public function set_read( $read ) {
        $this->set_prop( 'read', wc_string_to_bool( $read ) );
    }

    /*
    |--------------------------------------------------------------------------
    | Additional methods
    |--------------------------------------------------------------------------
    */

    public function get_view_url() {
        return wc_get_endpoint_url( 'notifications', $this->get_id(), wc_get_page_permalink( 'myaccount' ) );
    }

    /**
     * Create a new notification
     *
     * @param $user_id
     * @param $name
     * @param $description
     * @return Notification
     */
    public static function create( $user_id, $name, $description ) {
        $notification = new self();

        $notification->set_props( array(
            'user_id'     => $user_id,
            'name'	      => $name,
            'description' => $description,
        ) );

        $notification->save();

        self::clear_unread_count( $user_id );

        do_action( 'cbc_notification_created', $notification );

        return $notification;
    }

    /**
     * Get unread notifications count
     *
     * @param int $user_id
     * @return int
     */
    public static function get_unread_count( int $user_id ) {
        $transient_key = "unread_count_{$user_id}";

        if ( false === ( $count = get_transient( $transient_key ) ) ) {
            $notification_ids = get_posts( array(
                'fields'		=> 'ids',
                'numberposts' 	=> -1,
                'post_type' 	=> 'notification',
                'meta_query'	=> array(
                    array(
                        'key' => '_user_id',
                        'value' => $user_id
                    ),
                    array(
                        'key' => '_read',
                        'value' => 'no'
                    )
                )
            ) );

            $count = count( $notification_ids );

            set_transient( $transient_key, $count, HOUR_IN_SECONDS );
        }

        return $count;
    }

    /**
     * Clear unread count transient
     *
     * @param int $user_id
     */
    public static function clear_unread_count( int $user_id ) {
        delete_transient( "unread_count_{$user_id}" );
    }
}
