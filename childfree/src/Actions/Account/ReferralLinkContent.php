<?php

namespace WZ\ChildFree\Actions\Account;

use Wz\Childfree\Actions\Hook;
use WZ\ChildFree\Models\Candidate;
use WZ\ChildFree\Template;

class ReferralLinkContent extends Hook
{
    public static array $hooks = array( 'woocommerce_account_referral-link_endpoint' );

    public function __invoke() {
        $user_id = get_current_user_id();

        $candidate = Candidate::get_by_user_id( $user_id );

        if ( $candidate ) {
            $referral_link = add_query_arg(
                array( 'ref' => $candidate->get_id() ),
                home_url('/register')
            );
        } else {
            // If there isn't a candidate, then this is most likely a test account
            // In that case we're using a test candidate id
            $referral_link = add_query_arg(
                array( 'ref' => 8529 ),
                home_url('/register')
            );
        }



        Template::render( 'account/candidate-referral-link', array(
            'referral_link' => $referral_link
        ) );
    }
}