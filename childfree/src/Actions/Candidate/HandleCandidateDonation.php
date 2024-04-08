<?php

namespace WZ\ChildFree\Actions\Candidate;

use WZ\ChildFree\Actions\Hook;
use WZ\ChildFree\Models\Candidate;
use WZ\ChildFree\Models\ExpansionDonation;
use WZ\ChildFree\Models\FundAllCandidates;
use WZ\ChildFree\Models\GeneralDonation;
use WZ\ChildFree\Models\LocationDonation;

class HandleCandidateDonation extends Hook
{
    public static array $hooks = array(
//        'woocommerce_payment_complete'
    );

    public function __invoke( $order_id ) {
        $order = wc_get_order( $order_id );

        // Clear the candidate total cache
        delete_transient( 'all_candidates_total' );

        foreach ( $order->get_items() as $item ) {
            $candidate_id = $item->get_product_id();

            if ( GeneralDonation::PRODUCT_ID === $candidate_id ) {
                continue; // skip general donation
            }
            if ( ExpansionDonation::PRODUCT_ID === $candidate_id ) {
                continue; // skip expansion donation
            }
            if ( LocationDonation::PRODUCT_ID === $candidate_id ) {
                continue; // skip location donation
            }
            if ( FundAllCandidates::PRODUCT_ID === $candidate_id ) {
                continue; // skip fund all candidates
            }

            $candidate = new Candidate( $item->get_product_id() );
            $candidate->generate_progress_data();

            if ( $candidate->is_funded() ) {
                // $candidate->set_catalog_visibility( 'hidden' );

                // If the candidate was fully funded, loop through the related subscriptions and cancel them
                foreach ( wcs_get_subscriptions_for_product( $candidate_id ) as $subscription ) {
                    $subscription->update_status( 'expired' );
                }

                do_action( 'cbc_candidate_fully_funded', $candidate_id, $candidate );

                as_schedule_single_action( strtotime('+60 days'), 'cbc_check_candidate_honorarium', array( 'candidate_id' => $candidate_id ) );
            }

            $candidate->save();
        }
    }
}
