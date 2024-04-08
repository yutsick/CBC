<?php

namespace WZ\ChildFree\Models;

class Referral extends \WC_Data
{
    protected $data = array(
        'user_id' => 0,
        'name' => '',
        'date_created' => null,
        'date_paid' => null,
        'payout_type' => null,
    );

    public function __construct( $id = 0 ) {
        parent::__construct( $id );

        if ( is_numeric( $id ) && $id > 0 ) {
            $this->set_id( $id );
        } else {
            $this->set_object_read( true );
        }

        if ( ! $this->get_object_read() ) {
            $this->hydrate();
        }
    }

    public function hydrate() {
        if ( 0 !== $this->get_id() ) {
            $entry = \FrmEntry::getOne( $this->get_id(), true );

            if ( false === $entry ) {
                return;
            }
        }

        $user = get_userdata($entry->user_id);

        $this->set_props( array(
            'user_id' => $entry->user_id,
            'date_created' => $entry->created_at,
            'name' => $user->display_name,
        ) );
    }

    /*
    |--------------------------------------------------------------------------
    | Getters
    |--------------------------------------------------------------------------
    */

    public function get_user_id( $context = 'view' ) {
        return $this->get_prop( 'user_id', $context );
    }

    public function get_name( $context = 'view' ) {
        return $this->get_prop( 'name', $context );
    }

    public function get_date_created( $context = 'view' ) {
        return $this->get_prop( 'date_created', $context );
    }

    public function get_date_paid( $context = 'view' ) {
        return $this->get_prop( 'date_paid', $context );
    }

    public function get_payout_type( $context = 'view' ) {
        return $this->get_prop( 'payout_type', $context );
    }

    /*
    |--------------------------------------------------------------------------
    | Setters
    |--------------------------------------------------------------------------
    */

    public function set_user_id( $user_id ) {
        $this->set_prop( 'user_id', absint( $user_id ) );
    }

    public function set_name( $name ) {
        $this->set_prop( 'name', (string) $name );
    }

    public function set_date_created( $date ) {
        $this->set_prop( 'date_created', $date );
    }

    public function set_date_paid( $date ) {
        $this->set_prop( 'date_paid', $date );
    }

    public function set_payout_type( $date ) {
        $this->set_prop( 'payout_type', $date );
    }

    /*
    |--------------------------------------------------------------------------
    | Business Logic
    |--------------------------------------------------------------------------
    */

    public function get_action_link( $action, $candidate_id ) {
        return add_query_arg(
            array(
                'action' => 'cbc_payout_referral',
                'candidate' => $candidate_id,
                'referral' => $this->get_id(),
                'payout_type' => $action
            ),
            admin_url( 'admin-post.php' )
        );
    }
}
