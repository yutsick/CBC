<?php

namespace WZ\ChildFree\Integrations;

use WZ\ChildFree\Models\Candidate;

class AffiliateWP extends \Affiliate_WP_Base
{
    public $context = 'childfree';

    /**
     * Init the integration class
     */
    public function init() {
        add_filter( 'affwp_payout_methods', array( $this, 'register_payout_method' ) );
        add_filter( 'affwp_is_payout_method_enabled', array( $this, 'enable_payout_method' ), 10, 2 );

        add_action( 'affwp_insert_payout', array( $this, 'handle_payout' ) );

        add_filter( 'affwp_referral_reference_column', array( $this, 'reference_link' ), 10, 2 );

        add_action( 'cbc_candidate_procedure_done', array( $this, 'add_pending_candidate_referral' ), 5 );
        add_action( 'cbc_candidate_procedure_done', array( $this, 'add_pending_physician_referral' ), 5 );
    }

    /**
     * Add our custom payout method
     *
     * @param array $methods
     * @return array
     */
    public function register_payout_method( $methods ) {
        $methods[ $this->context ] = __( 'Stripe' );

        return $methods;
    }

    /**
     * Force enable our payout method
     *
     * @param bool $enabled
     * @param string $method
     * @return bool
     */
    public function enable_payout_method( $enabled, $method ) {
        if ( $method === $this->context ) {
            return true;
        }

        return $enabled;
    }

    /**
     * Send the payout transfer to Stripe
     *
     * @param $add
     * @throws \WC_Stripe_Exception
     */
    public function handle_payout( $add ) {
        $stripe = new Stripe();

        $user_id = affwp_get_affiliate_user_id( $add['affiliate_id'] );
        $amount = $add['amount'];

        $response = $stripe->transfer( $user_id, $amount );

        if ( property_exists( $response, 'error' ) ) {
            throw new \Exception( $response->error->message );
        }
    }

    /**
     * Add pending referral for candidate
     *
     * @param $candidate_id
     */
    public function add_pending_candidate_referral( $candidate_id ) {
        $candidate = new Candidate( $candidate_id );
        $customer = affwp_get_customer_by( 'user_id', $candidate->get_user_id() );
        $funding = $candidate->get_funding();

        if ( false === $customer || is_wp_error( $customer ) ) {
            return;
        }

        $linked_affiliate = affiliate_wp_lifetime_commissions()->lifetime_customers->get_by( 'affwp_customer_id', $customer->customer_id );

        $this->insert_pending_referral(
            $funding->candidate_referral,
            $candidate_id,
            'Candidate Referral',
            array(),
            array(
                'affiliate_id' => $linked_affiliate->affiliate_id,
                'customer_id' => $customer->customer_id,
            )
        );
    }

    /**
     * Add pending referral for physician
     *
     * @param $candidate_id
     */
    public function add_pending_physician_referral( $candidate_id ) {
        $candidate = new Candidate( $candidate_id );
        $customer = affwp_get_customer_by( 'user_id', $candidate->get_provider() );
        $funding = $candidate->get_funding();

        if ( false === $customer || is_wp_error( $customer ) ) {
            return;
        }

        $linked_affiliate = affiliate_wp_lifetime_commissions()->lifetime_customers->get_by( 'affwp_customer_id', $customer->customer_id );

        $this->insert_pending_referral(
            $funding->physician_referral,
            $candidate_id,
            'Physician Referral',
            array(),
            array(
                'affiliate_id' => $linked_affiliate->affiliate_id,
                'customer_id' => $customer->customer_id,
            )
        );
    }

    /**
     * Filter the reference link in the admin
     *
     * @param $reference
     * @param $referral
     * @return string
     */
    public function reference_link( $reference, $referral ) {
        if ( empty( $referral->context ) || $this->context != $referral->context ) {
            return $reference;
        }

        $url       = get_edit_post_link( $reference );
        $reference = $this->parse_reference( $reference );

        return '<a href="' . esc_url( $url ) . '">' . $reference . '</a>';
    }

    /**
     * Get customer by candidate ID
     *
     * @param int $candidate_id
     * @return array
     */
    public function get_customer( $candidate_id = 0 ) {
        $candidate = wc_get_product( $candidate_id );
        $user = new \WP_User( $candidate->get_user_id() );

        return array(
            'first_name' => $user->user_firstname,
            'last_name' => $user->user_lastname,
            'email' => $user->user_email,
            'ip' =>  affiliate_wp()->tracking->get_ip()
        );
    }
}
