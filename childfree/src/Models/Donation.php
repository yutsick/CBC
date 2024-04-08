<?php

namespace WZ\ChildFree\Models;

class Donation
{

    /**
     * Order ID
     *
     * @var int
     */
    public int $order_id;

    /**
     * Candidate ID
     *
     * @var int
     */
    public int $candidate_id;

    /**
     * Candidate Name
     *
     * @var string
     */
    public string $name;

    /**
     * Donation amount
     *
     * @var int
     */
    public int $amount;

    /**
     * Donation date
     *
     * @var \WC_DateTime|NULL
     */
    public \WC_DateTime $date;

    /**
     * Construct new donation
     */
    public function __construct( int $order_id, int $candidate_id ) {
        $donation = wc_get_order( $order_id );

        $this->order_id = $order_id;
        $this->candidate_id = $candidate_id;
        $this->name = self::get_name( $donation );
        $this->amount = 0;

        foreach ( $donation->get_items() as $item ) {
            if ( $item->get_product_id() === $candidate_id ) {
                $this->amount += $item->get_total();
            }
        }

        $this->date = $donation->get_date_created();
    }

    /**
     * Get the amount donated for candidate
     *
     * @param int $candidate_id
     * @return string
     */
    public static function get_candidate_total( int $candidate_id ) {
        global $wpdb;

        return $wpdb->get_var(
            $wpdb->prepare(
                "
				SELECT SUM(m.meta_value)
				FROM {$wpdb->prefix}woocommerce_order_itemmeta m
				LEFT JOIN {$wpdb->prefix}woocommerce_order_itemmeta p
				ON m.order_item_id = p.order_item_id
				WHERE m.meta_key = '_line_total'
					AND p.meta_key = '_product_id'
					AND p.meta_value = %d
				",
                $candidate_id
            )
        );
    }


    /**
     * Get all recent donations for candidate
     *
     * return array
     */
    public static function get( int $candidate_id ) {
        global $wpdb;

        $order_status = array( 'wc-completed' );

        $order_ids = $wpdb->get_col("
			SELECT DISTINCT order_items.order_id
			FROM {$wpdb->prefix}woocommerce_order_items AS order_items
			LEFT JOIN {$wpdb->prefix}woocommerce_order_itemmeta AS product_id ON order_items.order_item_id = product_id.order_item_id
			LEFT JOIN {$wpdb->posts} AS posts ON order_items.order_id = posts.ID
			WHERE posts.post_type = 'shop_order'
			AND posts.post_status IN ( '" . implode( "','", $order_status ) . "' )
			AND order_items.order_item_type = 'line_item'
			AND product_id.meta_key = '_product_id'
			AND product_id.meta_value = '$candidate_id'
			ORDER BY posts.post_date DESC
		");

        return array_map( fn( $order_id ) => new self( $order_id, $candidate_id ), $order_ids );
    }

    /**
     * Get top recent donations for candidate
     *
     * @param int $candidate_id
     * @param $limit
     * @return array
     */
    public static function get_top( int $candidate_id, $limit = 10 ) {
        global $wpdb;

        $order_statuses = array( 'wc-completed' );

        $order_ids = $wpdb->get_col("
			SELECT DISTINCT order_items.order_id
			FROM {$wpdb->prefix}woocommerce_order_items AS order_items
			LEFT JOIN {$wpdb->prefix}woocommerce_order_itemmeta AS product_id ON order_items.order_item_id = product_id.order_item_id
			LEFT JOIN {$wpdb->prefix}woocommerce_order_itemmeta AS amount ON order_items.order_item_id = amount.order_item_id
			LEFT JOIN {$wpdb->posts} AS posts ON order_items.order_id = posts.ID
			WHERE posts.post_type = 'shop_order'
			AND posts.post_status IN ( '" . implode( "','", $order_statuses ) . "' )
			AND order_items.order_item_type = 'line_item'
			AND product_id.meta_key = '_product_id'
			AND product_id.meta_value = '$candidate_id'
			AND amount.meta_key = '_line_subtotal'
			ORDER BY CAST(amount.meta_value as unsigned) DESC
			LIMIT {$limit}
		");

        return array_map( fn( $order_id ) => new self( $order_id, $candidate_id ), $order_ids );
    }


    /**
     * Get name for order
     * if the order was placed anonymously, return that instead
     *
     * @param $order
     * @return string
     */
    public static function get_name( $order ) {
        if ( 'yes' === $order->get_meta( '_donate_anonymously' ) ) {
            return __( 'Anonymous' );
        }

        return "{$order->get_billing_first_name()} {$order->get_billing_last_name()}";
    }

}
