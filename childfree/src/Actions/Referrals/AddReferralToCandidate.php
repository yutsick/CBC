<?php

namespace WZ\ChildFree\Actions\Referrals;

use WZ\ChildFree\Models\Candidate;
use Wz\Childfree\Actions\Hook;

class AddReferralToCandidate extends Hook
{
    public static array $hooks = array(
        'frm_after_create_entry',
        'frm_after_update_entry'
    );

    public static int $arguments = 2;
    public static int $priority = 30;

    public function __invoke( $entry_id, $form_id ) {
        if ( 3 !== (int) $form_id ) {
            return;
        }

        $referral_id = $_COOKIE['candidate_referrer'];

        if ( $referral_id ) {
            $candidate = new Candidate( $referral_id );
            $candidate->add_referral( $entry_id );

            do_action( 'cbc_candidate_referral', $referral_id, $entry_id );
        }
    }
}