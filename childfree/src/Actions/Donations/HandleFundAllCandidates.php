<?php

namespace WZ\ChildFree\Actions\Donations;

use Exception;
use Wz\Childfree\Actions\Hook;
use WZ\ChildFree\Models\Candidate;
use WP_Query;
use WZ\ChildFree\Models\ExpansionDonation;
use WZ\ChildFree\Models\FundAllCandidates;
use WZ\ChildFree\Models\GeneralDonation;
use WZ\ChildFree\Models\LocationDonation;

class HandleFundAllCandidates extends Hook
{
    public static array $hooks = array(
        'wp_ajax_fund_all_candidates',
        'wp_ajax_nopriv_fund_all_candidates',
    );

    public function start_session(): void
    { if (!session_id()) { session_start(); } }

    /**
     * @throws Exception
     */
    public function __invoke(): void
    {
        // Start or resume the session
//        add_action('init', 'start_session');

        // FUND_ALL_CANDIDATES ACTION HANDLER
        if (isset($_POST['action']) && $_POST['action'] === 'fund_all_candidates') {
            // Verify nonce
            $nonce = $_POST['nonce'];
            if ( ! wp_verify_nonce( $nonce, 'browse_candidates_nonce' ) ) {
                wp_send_json_error('Nonce [' . $_POST['nonce'] . '] is invalid!');
            }

            $value_in_percent = $_POST['value_in_percent'];
            $value_in_amount = str_replace(array('$', ','), '', $_POST['value_in_amount']);
            if (!empty($value_in_percent && $value_in_amount)) {
                WC()->cart->empty_cart();

                // add the product FundAllCandidates to the cart with the 'donation' meta data as value_in_amount
                $cart_data = array('donation' => $value_in_amount);
                // before adding the product to the cart, check if the product is already in the cart
                $cart_item_key = WC()->cart->find_product_in_cart(FundAllCandidates::PRODUCT_ID);
                if ($cart_item_key) {
                    WC()->cart->remove_cart_item($cart_item_key);
                }
                WC()->cart->add_to_cart(
                    FundAllCandidates::PRODUCT_ID,
                    1,
                    0,
                    array(),
                    $cart_data,
                );
                // update cart total
                WC()->cart->calculate_totals();

                $cart_contents = WC()->cart->get_cart_contents();
                $custom_cart_contents = array();
                if ($cart_contents) {
                    foreach ($cart_contents as $key => $value) {

                        if (isset($value['product_id'])) {
                            $product_id = $value['product_id'];
                            $product = wc_get_product($product_id);

                            // Create an array with the desired keys and values
                            $custom_cart_contents[] = array(
                                'product_id' => $product_id,
                                'product_thumbnail' => $product->get_image(),
                                'product_url' => $product->get_permalink(),
                                'product_name' => $product->get_name(),
                                'product_price' => $value['donation'],
                                'product_key' => $value['key'],
                            );
                        }
                    }
                }

                $cart_total = WC()->cart->get_cart_total();
                $cart_total = html_entity_decode(strip_tags($cart_total));
                $raw_cart_total = str_replace(array('$', ','), '', $cart_total);

                // Get Cart Items Other than General and Expansion etc Donation Products
                $cart = WC()->cart;
                // Initialize total amount
                $total_specific_cart_Amount = 0;
                // Loop through each item in the cart
                foreach ($cart->get_cart() as $cart_item_key => $cart_item) {
                    // Get product ID
                    $product_id = $cart_item['product_id'];

                    // Check if the product ID is not donation product ID
                    if ($product_id !== GeneralDonation::PRODUCT_ID
                        && $product_id !== ExpansionDonation::PRODUCT_ID
                        && $product_id !== LocationDonation::PRODUCT_ID) {
                        // Add the item total to the total amount
                        $total_specific_cart_Amount += $cart_item['line_subtotal'];
                    }
                }
                setcookie('ckSpecificDonationTotalAmount', $total_specific_cart_Amount, time() + (86400 * 30), "/");
                setcookie('ckValueInPercent', $value_in_percent, time() + (86400 * 30), "/");

                $total_remaining_amount = Candidate::get_all_remaining_total();
                $percent_remaining_amount = $total_remaining_amount * ($value_in_percent / 100);
                $percent_remaining_amount = round($percent_remaining_amount);
                $args = array(
                    'post_type' => 'product',
                    'post_status' => 'publish',
                    'posts_per_page' => -1,
                    'tax_query' => array(
                        array(
                            'taxonomy' => 'product_type',
                            'field' => 'slug',
                            'terms' => 'candidate',
                        ),
                    ),
                );
//                // get the total number of candidates in the database
//                $candidates_count = wc_get_products($args);
//                $candidates_count = count($candidates_count);
//                //echo $candidates_count;
//                // get all candidates
//                $loop = new WP_Query($args);
//                $candidates = array();
////                while ($loop->have_posts()) : $loop->the_post();
//                // iterate over all candidates and add them to the cart if they are purchasable $candidates_count
//                while ($loop->have_posts() && count($candidates) < $candidates_count+1) : $loop->the_post();
//                    $product = wc_get_product(get_the_ID());
////                    if ($product->get_amount_remaining() > 0 && count($candidates) < 850) {
//                    if ($product->get_amount_remaining() > 0) {
//                        $candidates[] = array(
//                            'id' => $product->get_id(),
//                            'percent_amount' => round($product->get_amount_remaining() * ($value_in_percent / 100),
//                                0,PHP_ROUND_HALF_DOWN),
//                            'after_adding_to_goal' => $product->get_amount_remaining() - round($product->get_amount_remaining() * ($value_in_percent / 100),
//                                    0,PHP_ROUND_HALF_DOWN),
//                        );
//                    }
//                endwhile;
//                wp_reset_query();

                wp_send_json_success(array(
                    'status' => 'Success',
                    'value_in_percent' => $value_in_percent,
                    'value_in_amount' => $value_in_amount,
                    'cart_contents' => $custom_cart_contents,
                    'total_specific_cart_Amount' => $total_specific_cart_Amount,
                    'cart_total' => $raw_cart_total,
//                    'candidates_count' => count($candidates),
//                    'candidates' => $candidates,
                    'percent_remaining_amount' => $percent_remaining_amount,
                ));
            } else {
                wp_send_json_error(array(
                    'status' => 'Error',
                    'value_in_percent' => $value_in_percent,
                ));
            }
        }
    }

}

//////////////////////////////////////////////////////////////////////////////////////
//$total_remaining_amount = Candidate::get_all_remaining_total();
//$percent_remaining_amount = $total_remaining_amount * ($value_in_percent / 100);
//$percent_remaining_amount = round($percent_remaining_amount);
//$args = array(
//    'post_type' => 'product',
//    'post_status' => 'publish',
//    'posts_per_page' => -1,
//    'tax_query' => array(
//        array(
//            'taxonomy' => 'product_type',
//            'field' => 'slug',
//            'terms' => 'candidate',
//        ),
//    ),
//);
//WC()->cart->empty_cart();
//// get the total number of candidates in the database
//$candidates_count = wc_get_products($args);
//$candidates_count = count($candidates_count);
////echo $candidates_count;
//// get all candidates
//$loop = new WP_Query($args);
//$candidates = array();
////                while ($loop->have_posts()) : $loop->the_post();
//// iterate over all candidates and add them to the cart if they are purchasable $candidates_count
//while ($loop->have_posts() && count($candidates) < $candidates_count+1) : $loop->the_post();
//    $product = wc_get_product(get_the_ID());
////                    if ($product->get_amount_remaining() > 0 && count($candidates) < 850) {
//    if ($product->get_amount_remaining() > 0) {
//        $candidates[] = array(
//            'id' => $product->get_id(),
//            'percent_amount' => round($product->get_amount_remaining() * ($value_in_percent / 100),
//                0,PHP_ROUND_HALF_DOWN),
//        );
//    }
//endwhile;
//wp_reset_query();
//
//// add all candidates to cart slowly
//foreach ($candidates as $candidate) {
//    $product = wc_get_product($candidate['id']);
//    $percent_amount = $candidate['percent_amount'];
//    if ($percent_amount > 0) {
//        $cart_data = array('donation' => $percent_amount);
//        WC()->cart->add_to_cart(
//            $candidate['id'],
//            1,
//            0,
//            array(),
//            $cart_data,
//        );
//    }
//}



//////////////////////////////////////////////////////////////////////////////////////
/// namespace WZ\ChildFree\Actions\Donations;
//
//use Wz\Childfree\Actions\Hook;
//
//class HandleFundAllCandidates extends Hook
//{
//	public static array $hooks = array(
//		'wp_ajax_fund_all_candidates',
//		'wp_ajax_nopriv_fund_all_candidates',
//	);
//
//	public function start_session(): void
//	{
//		if (!session_id()) {
//			session_start();
//		}
//	}
//
//	/**
//	 * @throws \Exception
//	 */
//	public function __invoke(): void
//	{
//
//		if (isset($_POST['action']) && $_POST['action'] === 'fund_all_candidates') {
//			// Verify nonce
//			$nonce = $_POST['nonce'];
//			if (!wp_verify_nonce($nonce, 'browse_candidates_nonce')) {
//				wp_send_json_error('Nonce [' . $_POST['nonce'] . '] is invalid!');
//			}
//
//			$value_in_percent = $_POST['value_in_percent'];
//
//			WC()->cart->empty_cart();
//
//			// get all products
//			$products = wc_get_products(
//				array('status' => 'publish', 'limit' => -1, 'type' => 'candidate')
//			);
//
//			$products_ids = array_map(function ($product) {
//				if ($product->is_purchasable()) {
//					return $product->get_id();
//				}
//				return null;
//			}, $products);
//
//
//			foreach ($products_ids as $product_id) {
//				$cart_data = array('donation' => 10);
//
//				if ($product_id) {
//					WC()->cart->add_to_cart(
//						$product_id,
//						1,
//						0,
//						array(),
//						$cart_data,
//					);
//				}
//
//			}
//
//			wp_send_json_success($products_ids);
//		}
//	}
//}