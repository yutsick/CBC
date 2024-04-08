<?php

namespace WZ\ChildFree\Models;

use WZ\ChildFree\Integrations\Stripe;

class Candidate extends \WC_Product
{
    /**
     * Extra data meta keys
     *
     * @var string[]
     */
    protected $extra_data = [
        'goal'					=> 0,
        'amount_raised' 		=> 0,
        'progress'				=> 0,
        'honorarium'			=> 0,
        'sex'					=> 'male',
        'date_of_birth' 		=> '',
        'age' 					=> 18,
        'location' 				=> '',
        'latitude' 				=> '',
        'longitude' 			=> '',
        'interested_providers'	=> array(),
        'saved_providers' 		=> array(),
        'provider' 				=> 0,
        'user_id'				=> 0,
        'referrals'				=> array(),
        'referred_page_url'		=> '',
        'referred_person'		=> '',
        'person_user_id'		=> '',
    ];

    /**
     * Form entry
     *
     * @var object
     */
    protected $entry;

    /**
     * Registration form entry
     *
     * @var object
     */
    protected $registration_entry;

    /**
     * Get product type
     *
     * @return string
     */
    public function get_type() {
        return 'candidate';
    }

    /**
     * Returns false if the product cannot be bought.
     *
     * @return bool
     */
    public function is_purchasable() {
        return ! $this->is_funded();
    }

    /**
     * Check if a product is sold individually (no quantities).
     *
     * @return bool
     */
    public function is_sold_individually() {
        return true;
    }

    /**
     * Get add to cart text
     *
     * @return string
     */
    public function single_add_to_cart_text() {
        return __( 'Donate' );
    }

    /**
     * Getters
     */
    public function get_goal( $context = 'view' ) {
        return (int) $this->get_prop( 'goal', $context );
    }

    public function get_amount_raised( $context = 'view' ) {
        return (int) $this->get_prop( 'amount_raised', $context );
    }

    public function get_progress( $context = 'view' ) {
        return (float) $this->get_prop( 'progress', $context );
    }

    public function get_honorarium( $context = 'view' ) {
        return (int) $this->get_prop( 'honorarium', $context );
    }

    public function get_sex( $context = 'view' ) {
        return $this->get_prop( 'sex', $context );
    }

    public function get_date_of_birth( $context = 'view' ) {
        return $this->get_prop( 'date_of_birth', $context );
    }

    public function get_age( $context = 'view' ) {
        return $this->get_prop( 'age', $context );
    }

    public function get_location( $context = 'view' ) {
        return $this->get_prop( 'location', $context );
    }

    public function get_latitude( $context = 'view' ) {
        return $this->get_prop( 'latitude', $context );
    }

    public function get_longitude( $context = 'view' ) {
        return $this->get_prop( 'longitude', $context );
    }

    public function get_interested_providers( $context = 'view' ) {
        return $this->get_prop( 'interested_providers', $context );
    }

    public function get_saved_providers( $context = 'view' ) {
        return $this->get_prop( 'saved_providers', $context );
    }

    public function get_provider( $context = 'view' ) {
        return $this->get_prop( 'provider', $context );
    }

    public function get_user_id( $context = 'view' ) {
        return $this->get_prop( 'user_id', $context );
    }

    public function get_referrals( $context = 'view' ) {
        return $this->get_prop( 'referrals', $context );
    }

    public function get_referred_page_url( $context = 'view' ) {
        return $this->get_prop( 'referred_page_url', $context );
    }

    public function get_referred_person( $context = 'view' ) {
        return $this->get_prop( 'referred_person', $context );
    }

    public function get_person_user_id( $context = 'view' ) {
        return $this->get_prop( 'person_user_id', $context );
    }
    public function get_user_email( $context = 'view' ) {
        return $this->get_prop( 'email', $context );
    }



    /**
     * Setters
     */
    public function set_user_email( string $email ) {
        $this->set_prop( 'email', $email );
    }
    public function set_person_user_id( string $person ) {
        $this->set_prop( 'person_user_id', $person );
    }

    public function set_referred_page_url( string $page_url ) {
        $this->set_prop( 'referred_page_url', $page_url );
    }

    public function set_referred_person( string $person ) {
        $this->set_prop( 'referred_person', $person );
    }

    public function set_goal( $goal ) {
        $this->set_prop( 'goal', absint( $goal ) );
    }

    public function set_amount_raised( $amount ) {
        if ($amount == 0){
            $amount = Donation::get_candidate_total( $this->get_id() );
            update_post_meta($this->get_id(), '_amount_raised', $amount);
        }
        $this->set_prop( 'amount_raised', $amount);
    }

    public function set_progress( $progress ) {
        $this->set_prop( 'progress', absint( $progress ) );
    }

    public function set_honorarium( $honorarium ) {
        $this->set_prop( 'honorarium', absint( $honorarium ) );
    }

    public function set_sex( string $sex ) {
        $this->set_prop( 'sex', $sex );
    }

    public function set_date_of_birth( $dob ) {
        $this->set_prop( 'date_of_birth', $dob );
    }

    public function set_age( $age ) {
        $this->set_prop( 'age', absint( $age ) );
    }

    public function set_location( string $location ) {
        $this->set_prop('location', $location);
    }

    public function set_latitude( $latitude ) {
        $this->set_prop( 'latitude', $latitude );
    }

    public function set_longitude( $longitude ) {
        $this->set_prop( 'longitude', $longitude );
    }

    public function set_interested_providers( $providers ) {
        $this->set_prop( 'interested_providers', $providers );
    }

    public function set_saved_providers( $providers ) {
        $this->set_prop( 'saved_providers', $providers );
    }

    public function set_provider( $provider ) {
        $this->set_prop( 'provider', absint( $provider ) );
    }

    public function set_user_id( $user_id ) {
        $this->set_prop( 'user_id', absint( $user_id ) );
    }

    public function set_referrals( $referrals ) {
        $this->set_prop( 'referrals', $referrals );
    }

    /**
     * Get the remaining amount
     *
     * @return float
     */
    public function get_amount_remaining() {
        $amount = $this->get_goal() - $this->get_amount_raised();
        if($amount <= 0){
            return 0;
        }
        else{
            return $this->get_goal() - $this->get_amount_raised();
        }
    }

    /**
     * Check if candidate is fully funded
     *
     * @return bool
     */
    public function is_funded() {
        return ( $this->get_amount_raised() >= $this->get_goal() );
    }

    /**
     * Check if candidate has a selected provider
     *
     * @return bool
     */
    public function has_provider() {
        return absint( $this->get_provider() ) > 0;
    }

    /**
     * Check if candidate has been completed
     *
     * @return bool
     */
    public function procedcure_has_been_completed() {
        return ( $this->get_catalog_visibility() === 'hidden' && $this->is_funded() );
    }

    /**
     * Get the donor's total amount given to candidate
     *
     * @param int $donor_id
     * @return int
     */
    public function get_donor_amount( $donor_id ) {
        return Donation::get_donor_amount_given_to_candidate( $donor_id, $this->get_id() );
    }

    /**
     * Check if user is in interested providers
     *
     * @param $user_id
     * @return bool
     */
    public function has_interested_provider( $user_id ) {
        return in_array( $user_id, $this->get_interested_providers( 'edit' ) );
    }

    /**
     * Add interested provider
     *
     * @param int $provider_id
     */
    public function add_interested_provider( int $provider_id ) {
        $candidate_providers = (array) $this->get_interested_providers( 'edit' );

        if ( ! in_array( $provider_id, $candidate_providers ) ) {
            $candidate_providers[] = $provider_id;
            $this->set_interested_providers( array_filter( $candidate_providers ) );

            $this->save();
        }
    }

    /**
     * Remove interested provider
     *
     * @param int $provider_id
     */
    public function remove_interested_provider( int $provider_id ) {
        $candidate_providers = (array) $this->get_interested_providers( 'edit' );

        $candidate_providers = array_filter( $candidate_providers, function ( $id ) use ( $provider_id ) {
            return is_numeric( $id ) && $id !== $provider_id;
        } );

        $this->set_interested_providers( $candidate_providers );
        $this->save();
    }

    /**
     * Check if user is in interested providers
     *
     * @param $user_id
     * @return bool
     */
    public function has_saved_provider( $provider_id ) {
        return in_array( $provider_id, $this->get_saved_providers( 'edit' ) );
    }

    /**
     * Add interested provider
     *
     * @param int $provider_id
     */
    public function add_saved_provider( int $provider_id ) {
        $candidate_providers = (array) $this->get_saved_providers( 'edit' );

        if ( ! in_array( $provider_id, $candidate_providers ) ) {
            $candidate_providers[] = $provider_id;
            $this->set_saved_providers( array_filter( $candidate_providers ) );
            $this->save();
        }
    }

    /**
     * Remove interested provider
     *
     * @param int $provider_id
     */
    public function remove_saved_provider( int $provider_id ) {
        $candidate_providers = (array) $this->get_saved_providers( 'edit' );

        $candidate_providers = array_filter( $candidate_providers, function ( $id ) use ( $provider_id ) {
            return is_numeric( $id ) && $id !== $provider_id;
        } );

        $this->set_saved_providers( $candidate_providers );
        $this->save();
    }

    /**
     * Get add saved provider link
     */
    public function get_add_saved_provider_link( $provider_id ) {
        return add_query_arg(
            array(
                'action' => 'cbc_add_saved_provider',
                'candidate' => $this->get_id(),
                'provider' => $provider_id
            ),
            admin_url( 'admin-post.php' )
        );
    }

    /**
     * Get remove saved provider link
     *
     * @param $provider_id
     * @return string
     */
    public function get_remove_saved_provider_link( $provider_id ) {
        return add_query_arg(
            array(
                'action' => 'cbc_remove_saved_provider',
                'candidate' => $this->get_id(),
                'provider' => $provider_id
            ),
            admin_url( 'admin-post.php' )
        );
    }

    /**
     * Add referral to candidate
     *
     * @param $referral
     */
    public function add_referral( $referral_id ) {
        $referrals = array_filter( (array) $this->get_referrals( 'edit' ) );

        if ( empty( $referrals ) || ! in_array( $referral_id, wp_list_pluck( $referrals, 'id' ) ) ) {
            $referrals[] = [
                'id' => $referral_id,
                'date_paid' => null,
                'payout_type' => null,
            ];;
            $this->set_referrals( $referrals );
            $this->save();
        }
    }

    /**
     * @param $referral_id
     */
    public function remove_referral( $referral_id ) {
        $referrals = (array) $this->get_referrals( 'edit' );

        $referrals = array_filter( $referrals, function ( $referral ) use ( $referral_id ) {
            return $referral['id'] !== $referral_id;
        } );

        $this->set_referrals( $referrals );
        $this->save();
    }

    /**
     * Mark referral paid
     *
     * @param $referral_id
     * @param $payout_type
     * @return void
     */
    public function mark_referral_paid( $referral_id, $payout_type ) {
        $referrals = (array) $this->get_referrals( 'edit' );

        foreach ($referrals as $i => $referral) {
            if ($referral['id'] === $referral_id) {
                $referrals[$i]['date_paid'] = date('Y-m-d H:i:s');
                $referrals[$i]['payout_type'] = $payout_type;
            }
        }

        $this->set_referrals( $referrals );
        $this->save();
    }

    /**
     * Get Stripe Account
     */
    public function get_stripe_account() {
        return (new Stripe())->get_account( $this->get_user_id() );
    }

    /**
     * Get funding info for this candidate
     *
     * @return \stdClass|null
     */
    public function get_funding() {
        return Funding::get( $this->get_age(), $this->get_sex() );
    }

    /**
     * Get candidate AffiliateWP customer
     */
    public function get_affwp_customer() {
        return affwp_get_customer_by( 'user_id', $this->get_user_id() );
    }

    /**
     * Get referred by affiliate
     *
     * @param 'affiliate'|'user' $return
     * @return \AffWP\Affiliate|\WP_User|false
     */
    public function get_referred_by( $return = 'affiliate' ) {
        $customer = $this->get_affwp_customer();

        if ( is_wp_error( $customer ) ) {
            return false;
        }

        $linked_affiliate = affiliate_wp_lifetime_commissions()->lifetime_customers->get_by(
            'affwp_customer_id',
            $customer->customer_id
        );

        if ( null === $linked_affiliate ) {
            return false;
        }

        $affiliate = affwp_get_affiliate( $linked_affiliate->affiliate_id );

        if ( 'user' === $return && false !== $affiliate ) {
            return get_userdata( $affiliate->user_id );
        }

        return $affiliate;
    }

    /**
     * Generate all lookup data
     */
    public function generate_lookup_data() {
        $this->calculate_age();
        $this->calculate_lat_long();
    }

    /**
     * Get goal amount from goal table
     */
    public function generate_funding_data() {
        $funding = $this->get_funding();

        if ( ! $funding ) {
            return;
        }

        $this->set_goal( $funding->amount );
        $this->set_honorarium( $funding->honorarium );

        $rate = round( ( $funding->donor_referral / $funding->amount ) * 100, 2 );

        update_post_meta( $this->get_id(), '_affwp_woocommerce_product_rate', $rate );
        update_post_meta( $this->get_id(), '_affwp_woocommerce_product_rate_type', 'percentage' );
    }

    /**
     * Generate funding look-up data
     */
    public function generate_progress_data() {
        $this->calculate_amount_raised();
        $this->calculate_progress();
    }


    public function generate_progress_data_only() {
        //$this->calculate_amount_raised();
        $this->calculate_progress();
    }

    /**
     * Calc age based on DOB
     *
     * @throws \Exception
     */
    private function calculate_age() {
        $date = new \DateTime( $this->get_date_of_birth() );
        $now = new \DateTime();

        $interval = $now->diff( $date );

        $this->set_age( $interval->y );
    }

    /**
     * Get LAT/LNG from zipcode table
     */
    private function calculate_lat_long() {
        $zipcode = ZipCode::find( $this->get_location() );

        if ( null === $zipcode ) {
            return;
        }

        $this->set_latitude( $zipcode->latitude );
        $this->set_longitude( $zipcode->longitude );
    }

    /**
     * Get amount raised from orders
     */
    private function calculate_amount_raised() {
//		$this->set_amount_raised(
//			Donation::get_candidate_total( $this->get_user_id() )
//		);
        $amount = Donation::get_candidate_total( $this->get_id() );
        $this->set_amount_raised($amount);
    }

    public function calculate_amount_raised_for_account($user_id){
        $this->set_amount_raised(
            Donation::get_candidate_total($user_id)
        );
    }

    /**
     * Calculate progress from goal vs amount raised
     */
    private function calculate_progress() {
        $amount_raised = $this->get_amount_raised();
        $goal = $this->get_goal();
        if ( 0 === $amount_raised || 0 === $goal ) {
            $this->set_progress( 'progress', 0 );
        } else {
            $this->set_progress( floor( ( $amount_raised / $goal ) * 100 ) );
        }
    }


    public function calculate_progress_for_account() {
        $amount_raised = $this->get_amount_raised();

        $goal = $this->get_goal();
        if ( 0 === $amount_raised || 0 === $goal ) {
            $this->set_progress( 'progress', 0 );
        } else {
            $this->set_progress( floor( ( $amount_raised / $goal ) * 100 ) );
        }
    }
    /**
     * Get the parent form entry
     *
     * return array|null
     */
    public function get_entry() {
        if ( $this->entry === null ) {
            $entries = \FrmEntry::getAll(array(
                'it.post_id' => $this->get_id(),
                'it.form_id' => 6,
            ), '', 1, true );

            if ( empty( $entries ) ) {
                return null;
            }

            $this->entry = current( $entries );
        }

        return $this->entry;
    }

    /**
     * Get the parent registration form entry
     *
     * @return array|null
     */
    public function get_registration_entry() {
        if ($this->registration_entry === null) {
            $this->registration_entry = \FrmEntry::getOne( $this->get_entry()->parent_item_id, true );
        }

        return $this->registration_entry;
    }

    /**
     * Get user's candidate by their user id
     *
     * @param int $user_id
     * @return self|false
     */
    public static function get_by_user_id( int $user_id ) {
        $entries = \FrmEntry::getAll(
            array( 'it.form_id' => 3, 'it.user_id' => $user_id ),
            '',
            1,
            false
        );

        if ( empty( $entries ) ) {
            return null;
        }

        $id = current( $entries )->post_id;

        return new self( $id );
    }

    /**
     * Get candidates by donor's user id
     *
     * @param int $user_id
     * @return array
     */
    public static function get_by_donor_id( int $user_id, $status = 'all' ) {
        $candidates = array();
        $orders = wc_get_orders( array(
            'customer_id' => $user_id
        ) );

        foreach ( $orders as $order ) {
            foreach ( $order->get_items() as $item ) {
                $candidate = $item->get_product();

                if ( 'candidate' !== $candidate->get_type() ) {
                    continue;
                }

                if (
                    $status === 'in_progress' &&
                    $candidate->is_funded()
                ) {
                    // If the candidate has been funded, skip
                    continue;
                }

                if (
                    $status === 'fully_funded' &&
                    (
                        ! $candidate->is_funded() ||
                        $candidate->procedcure_has_been_completed()
                    )
                ) {
                    // If the candidate is not funded or their procedure has been completed, skip
                    continue;
                }

                if (
                    $status === 'procedure_completed' &&
                    ! $candidate->procedcure_has_been_completed()
                ) {
                    // if procedure isn't completed, skip
                    continue;
                }

                if ( ! array_key_exists( $candidate->get_id(), $candidates ) ) {
                    $candidates[ $candidate->get_id() ] = $candidate;
                }
            }
        }

        return $candidates;
    }

    /**
     * Get the total remaining goal amount of all candidates
     *
     * @return int
     */
    public static function get_all_remaining_total(): int
    {
        $total = 0;
        $goals = 0;

        $candidates = wc_get_products( array(
            'type' => 'candidate',
            'status' => 'publish',
            'limit' => -1
        ) );

        foreach ( $candidates as $candidate ) {
            if ( $candidate->is_purchasable() ) {
                $total += $candidate->get_amount_remaining();
//                $goals += $candidate->get_goal();
            }
        }

//        return $goals;
        return $total;
    }

    /**
     * Get the total goal amount of all candidates
     *
     * @return int
     */
    public static function get_all_raised_total() {

        $total = 0;
        $count = 0;

        $candidates = wc_get_products( array(
            'type' => 'candidate',
            'status' => 'publish',
            'limit' => -1
        ) );

        foreach ( $candidates as $candidate ) {
            if ( $candidate->is_purchasable() ) {
                $value += $candidate->get_amount_remaining();
                $count++;
            }
        }

        return $value;
    }

    /**
     * Get the total count of all candidates
     *
     * @return int
     */
    public static function get_all_count() {
        if ( false === ( $value = get_transient( 'all_candidates_count' ) ) ) {
            $arr = self::calculate_all_values();
            $value = $arr['count'];
        }

        return $value;
    }

    /**
     * Calculate and cache computed values
     *
     * @return array
     */
    private static function calculate_all_values() {
        $total = 0;
        $count = 0;

        $candidates = wc_get_products( array(
            'type' => 'candidate',
            'status' => 'publish',
            'limit' => -1
        ) );

        foreach ( $candidates as $candidate ) {
            if ( $candidate->is_purchasable() ) {
                $total += $candidate->get_amount_remaining();
                $count++;
            }
        }

        set_transient( 'all_candidates_total', $total, DAY_IN_SECONDS );
        set_transient( 'all_candidates_count', $count, DAY_IN_SECONDS );

        return [
            'total' => $total,
            'count' => $count
        ];
    }
}
