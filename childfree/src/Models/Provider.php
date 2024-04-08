<?php

namespace WZ\ChildFree\Models;

use WZ\ChildFree\Integrations\Stripe;

class Provider extends \WC_Data
{
    protected $object_type = 'provider';
    protected $cache_group = 'providers';

    protected $data = array(
        'business_name' 		=> '',
        'specialty' 			=> '',
        'state_license_number' 	=> '',
        'address_1' 			=> '',
        'address_2'				=> '',
        'address_city'			=> '',
        'address_state'			=> '',
        'address_postcode'		=> '',
        'website'				=> '',
        'contact_first_name'	=> '',
        'contact_last_name'		=> '',
        'phone'					=> '',
        'email'					=> '',
        'user_id'				=> 0,
        'est_distance'			=> 0,
        'candidates'			=> array(),
    );

    /**
     * Construct
     *
     * @param int $id
     */
    public function __construct( $id = 0 )
    {
        parent::__construct( $id );

        if ( is_numeric( $id ) && $id > 0 ) {
            $this->set_id( $id );
            $this->hydrate();
        } else {
            $this->set_object_read( true );
        }
    }

    /**
     * Get data from form entry
     */
    public function hydrate( $entry = false ) {
        if ( false === $entry && 0 !== $this->get_id() ) {
            $entry = \FrmEntry::getOne( $this->get_id(), true );

            if ( false === $entry ) {
                return;
            }
        }

        $this->set_props( array(
            'id'					=> absint( $entry->id ),
            'user_id'				=> absint( $entry->user_id ),
            'business_name'			=> $entry->metas[107],
            'specialty'				=> $entry->metas[110],
            'state_license_number'	=> $entry->metas[113],
            'address_1'				=> $entry->metas[109]['line1'],
            'address_2'				=> $entry->metas[109]['line2'],
            'address_city' 			=> $entry->metas[109]['city'],
            'address_state' 		=> $entry->metas[109]['state'],
            'address_postcode'		=> $entry->metas[109]['zip'],
            'website'				=> $entry->metas[130],
            'contact_first_name'	=> $entry->metas[99],
            'contact_last_name' 	=> $entry->metas[100],
            'phone'					=> $entry->metas[103],
            'email'					=> $entry->metas[102],
            'candidates'			=> (array) get_user_meta( $entry->user_id, 'candidates', true )
        ) );

        $this->set_object_read( true );
    }

    /**
     * Format address for display
     *
     * @return string
     */
    public function get_formatted_address() {
        return WC()->countries->get_formatted_address( array(
            'address_1' => $this->get_address_1(),
            'address_2' => $this->get_address_2(),
            'city'		=> $this->get_address_city(),
            'state'		=> $this->get_address_state(),
            'postcode' 	=> $this->get_address_postcode(),
        ) );
    }

    /**
     * Get the admin url for this provider
     *
     * @return string
     */
    public function get_admin_url() {
        return add_query_arg(
            array(
                'page' 		 => 'formidable-entries',
                'frm_action' => 'show',
                'id'		 => $this->get_id(),
            ),
            admin_url( 'admin.php' )
        );
    }

    /**
     * Get provider info by user_id
     *
     * @param int $user_id
     * @return self|null
     */
    public static function get_by_user_id( $user_id ) {

        // make sure the user exists
        if ( false === get_userdata( $user_id ) ) {
            return null;
        }

        $entries = \FrmEntry::getAll(
            array(
                'it.form_id' => 13,
                'it.user_id' => $user_id,
            ),
            '',
            1,
            true
        );

        $entry = current( $entries );

        $provider = new self();
        $provider->hydrate( $entry );

        return $provider;
    }

    /**
     * Search for providers
     *
     * @param array $args
     * @return array
     */
    public static function search( $args = array() ) {
        global $wpdb;
// 		ini_set('display_errors', 1);
// 		ini_set('display_startup_errors', 1);
// 		error_reporting(E_ALL);
        //print_r($args);
// 		$sql = $wpdb->prepare(
// 			"
// 				SELECT entry.id,
// 				( 3959 * acos(
// 					cos( radians( %d ) )
// 					* cos( radians( latitude.meta_value ) )
// 					* cos( radians( longitude.meta_value ) - radians( %d ) )
// 					+ sin( radians( %d ) )
// 					* sin( radians( latitude.meta_value ) )
// 				) )
// 				AS distance
// 				FROM {$wpdb->prefix}frm_items entry
// 				INNER JOIN {$wpdb->prefix}frm_item_metas latitude ON entry.id = latitude.item_id
// 				INNER JOIN {$wpdb->prefix}frm_item_metas longitude ON entry.id = longitude.item_id
// 				INNER JOIN {$wpdb->prefix}frm_item_metas name ON entry.id = name.item_id
// 				WHERE 1=1
// 				  AND latitude.field_id = 128
// 				  AND longitude.field_id = 129
// 				  AND name.field_id = 107
// 				  AND name.meta_value LIKE %s
// 				",
// 			$args['latitude'],
// 			$args['longitude'],
// 			$args['latitude'],
// 			'%' . $wpdb->esc_like( $args['search'] ) . '%'
// 		);
// 		if ( $args['radius'] > 0 ) {
// 			$sql .= " HAVING distance <= {$args['radius']} ";
// 		}
//
// 		$sql = $wpdb->prepare(
// 			"
// 				SELECT entry.id,
// 				( 3959 * acos(
// 					cos( radians( %s ) )
// 					* cos( radians( latitude.meta_value ) )
// 					* cos( radians( longitude.meta_value ) - radians( %s ) )
// 					+ sin( radians( %s ) )
// 					* sin( radians( latitude.meta_value ) )
// 				) )
// 				AS distance
// 				FROM {$wpdb->prefix}frm_items entry
// 				INNER JOIN {$wpdb->prefix}frm_item_metas latitude ON entry.id = latitude.item_id
// 				INNER JOIN {$wpdb->prefix}frm_item_metas longitude ON entry.id = longitude.item_id
// 				INNER JOIN {$wpdb->prefix}frm_item_metas name ON entry.id = name.item_id
// 				WHERE 1=1
// 					AND latitude.field_id = 128
// 					AND longitude.field_id = 129
// 					AND name.field_id = 107
// 					AND name.meta_value LIKE %s
// 				HAVING distance <= %f
// 			",
// 			$args['latitude'],
// 			$args['longitude'],
// 			$args['latitude'],
// 			'%' . $wpdb->esc_like( $args['search'] ) . '%',
// 			$args['radius'] / 3959
// 		);
// 		$sql = $wpdb->prepare(
//     "
//     SELECT entry.id,
//     ( 3959 * acos(
//         cos( radians( %s ) )
//         * cos( radians( latitude.meta_value ) )
//         * cos( radians( longitude.meta_value ) - radians( %s ) )
//         + sin( radians( %s ) )
//         * sin( radians( latitude.meta_value ) )
//     ) )
//     AS distance
//     FROM {$wpdb->prefix}frm_items entry
//     INNER JOIN {$wpdb->prefix}frm_item_metas latitude ON entry.id = latitude.item_id
//     INNER JOIN {$wpdb->prefix}frm_item_metas longitude ON entry.id = longitude.item_id
//     INNER JOIN {$wpdb->prefix}frm_item_metas name ON entry.id = name.item_id
//     WHERE 1=1
//         AND latitude.field_id = 128
//         AND longitude.field_id = 129
//         AND name.field_id = 107
//         AND name.meta_value LIKE %s
//     HAVING distance <= %f
//     ",
//     $args['latitude'],
//     $args['longitude'],
//     $args['latitude'],
//     '%' . $wpdb->esc_like( $args['search'] ) . '%',
//     $args['radius'] / 3959
// );
//
        $sql = $wpdb->prepare(
            "
    SELECT entry.id, latitude.meta_value AS latitude, longitude.meta_value AS longitude
    FROM {$wpdb->prefix}frm_items entry
    INNER JOIN {$wpdb->prefix}frm_item_metas latitude ON entry.id = latitude.item_id
    INNER JOIN {$wpdb->prefix}frm_item_metas longitude ON entry.id = longitude.item_id
    INNER JOIN {$wpdb->prefix}frm_item_metas name ON entry.id = name.item_id
    WHERE 1=1
        AND latitude.field_id = 128
        AND longitude.field_id = 129
        AND name.field_id = 107
        AND name.meta_value LIKE %s
    ",
            '%' . $wpdb->esc_like( $args['search'] ) . '%'
        );


        //var_dump($sql);
        $results = $wpdb->get_results( $sql );
// 		var_dump($results);
// 		echo '<pre>';
// 		print_r($args['radius']);die;



        $zip_latitude = $args['latitude'];
        $zip_longitude = $args['longitude'];
        $searchRadius = $args['radius'];
        $results = array_filter($results, function($result) {
            return !empty($result->latitude) && !empty($result->longitude);
        });
        $newLocation = array();
        if($searchRadius ==1) {
            $newLocation = $results;
        }else {

            foreach($results as $result) {
                if(!empty($result->latitude) && !empty($result->latitude)) {
                    $location_lat = floatval($result->latitude);
                    $location_long = floatval($result->longitude);
                    $radius = 3960;
                    $lat_diff = deg2rad($location_lat - $zip_latitude);
                    $lon_diff = deg2rad($location_long - $zip_longitude);
                    $a = sin($lat_diff / 2) * sin($lat_diff / 2) + cos(deg2rad($zip_latitude)) * cos(deg2rad($location_lat)) * sin($lon_diff / 2) * sin($lon_diff / 2);
                    $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
                    $distance = $radius * $c;
                    $result->distance = $distance; // Add distance index to the object

                    if ($distance <= $searchRadius) {
                        $dataArray = (object)[
                            "id" => $result->id,
                            "distance " => $result->distance,
                        ];
                        array_push($newLocation, $dataArray);
                    }

                    unset($result->latitude); // Remove latitude index
                    unset($result->longitude); // Remove longitude index
                    // 			print_r($result);
                    // 			echo '<br>';
                }
            }
        }


// 		echo '<pre>';
// 		print_r($newLocation);die;
//  		echo '<pre>';die;
        return array_map( array( self::class, 'handle_result_row' ), $newLocation );
    }

    /**
     * Get all providers
     *
     * @return array
     */
    public static function all() {
        $entries = \FrmEntry::getAll( array(
            'it.form_id' => 13
        ) );

        return array_map( array( self::class, 'handle_result_row' ), $entries );
    }

    /**
     * Add candidate to provider's selected candidates
     *
     * @param int $candidate_id
     */
    public function add_candidate( $candidate_id ) {
        $provider_candidates = (array) $this->get_candidates( 'edit' );

        if ( ! in_array( $candidate_id, $provider_candidates ) ) {
            $provider_candidates[] = $candidate_id;

            $provider_candidates = array_filter( $provider_candidates );

            $this->set_candidates( $provider_candidates );
            update_user_meta( $this->get_user_id(), 'candidates', $provider_candidates );
        }
    }

    /**
     * Map entry id to create a new instance
     *
     * @param $row
     * @return Provider
     */
    private static function handle_result_row( $row ) {
        $provider = new static( $row->id );

        if ( isset( $row->distance ) ) {
            $provider->set_est_distance( ceil( $row->distance ) );
        }

        return $provider;
    }

    /**
     * Remove candidate from provider's selected candidates
     *
     * @param $candidate_id
     */
    public function remove_candidate( $candidate_id ) {
        $provider_candidates = (array) $this->get_candidates( 'edit' );

        $provider_candidates = array_filter( $provider_candidates, function ( $id ) use ( $candidate_id ) {
            return $id !== $candidate_id;
        } );

        $this->set_candidates( $provider_candidates );
        update_user_meta( $this->get_user_id(), 'candidates', $provider_candidates );
    }

    /**
     * Get the candidate select link
     *
     * @return string
     */
    public function get_candidate_select_link( $candidate_id ) {
        return add_query_arg(
            array(
                'action' => 'cbc_add_interested_provider',
                'candidate' => $candidate_id,
                'provider' => $this->get_id()
            ),
            admin_url( 'admin-post.php' )
        );
    }

    /**
     * Get Stripe Account
     */
    public function get_stripe_account() {
        return (new Stripe())->get_account( $this->get_user_id() );
    }

    /*
    |--------------------------------------------------------------------------
    | Getters
    |--------------------------------------------------------------------------
    */

    public function get_business_name( $context = 'view' ) {
        return $this->get_prop( 'business_name', $context );
    }

    public function get_nick_name( $context = 'view' ) {
        return $this->get_prop( 'nickname', $context );
    }

    public function get_specialty( $context = 'view' ) {
        return $this->get_prop( 'specialty', $context );
    }

    public function get_state_license_number( $context = 'view' ) {
        return $this->get_prop( 'state_license_number', $context );
    }

    public function get_address_1( $context = 'view' ) {
        return $this->get_prop( 'address_1', $context );
    }

    public function get_address_2( $context = 'view' ) {
        return $this->get_prop( 'address_2', $context );
    }

    public function get_address_city( $context = 'view' ) {
        return $this->get_prop( 'address_city', $context );
    }

    public function get_address_state( $context = 'view' ) {
        return $this->get_prop( 'address_state', $context );
    }

    public function get_address_postcode( $context = 'view' ) {
        return $this->get_prop( 'address_zip_code', $context );
    }

    public function get_website( $context = 'view' ) {
        return $this->get_prop( 'website', $context );
    }

    public function get_contact_first_name( $context = 'view' ) {
        return $this->get_prop( 'contact_first_name', $context );
    }

    public function get_contact_last_name( $context = 'view' ) {
        return $this->get_prop( 'contact_last_name', $context );
    }

    public function get_phone( $context = 'view' ) {
        return $this->get_prop( 'phone', $context );
    }

    public function get_email( $context = 'view' ) {
        return $this->get_prop( 'email', $context );
    }

    public function get_user_id( $context = 'view' ) {
        return $this->get_prop( 'user_id', $context );
    }

    public function get_est_distance( $context = 'view' ) {
        return $this->get_prop( 'est_distance', $context );
    }

    public function get_candidates( $context = 'view' ) {
        return $this->get_prop( 'candidates', $context );
    }

    /*
    |--------------------------------------------------------------------------
    | Setters
    |--------------------------------------------------------------------------
    */

    public function set_business_name( $value ) {
        $this->set_prop( 'business_name', $value );
    }

    public function set_specialty( $value ) {
        $this->set_prop( 'specialty', $value );
    }

    public function set_state_license_number( $value ) {
        $this->set_prop( 'state_license_number', $value );
    }

    public function set_address_1( $value ) {
        $this->set_prop( 'address_1', $value );
    }

    public function set_address_2( $value ) {
        $this->set_prop( 'address_2', $value );
    }

    public function set_address_city( $value ) {
        $this->set_prop( 'address_city', $value );
    }

    public function set_address_state( $value ) {
        $this->set_prop( 'address_state', $value );
    }

    public function set_address_postcode( $value ) {
        $this->set_prop( 'address_postcode', $value );
    }

    public function set_website( $value ) {
        $this->set_prop( 'website', $value );
    }

    public function set_contact_first_name( $value ) {
        $this->set_prop( 'contact_first_name', $value );
    }

    public function set_contact_last_name( $value ) {
        $this->set_prop( 'contact_last_name', $value );
    }

    public function set_phone( $value ) {
        $this->set_prop( 'phone', $value );
    }

    public function set_email( $value ) {
        $this->set_prop( 'email', $value );
    }

    public function set_user_id( $value ) {
        $this->set_prop( 'user_id', absint( $value ) );
    }

    public function set_est_distance( $value ) {
        $this->set_prop( 'est_distance', $value );
    }

    public function set_candidates( $value ) {
        $this->set_prop( 'candidates', $value );
    }
}
