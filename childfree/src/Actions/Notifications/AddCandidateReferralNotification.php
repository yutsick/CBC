<?php

namespace WZ\ChildFree\Actions\Notifications;

use Wz\Childfree\Actions\Hook;
use WZ\ChildFree\Models\Candidate;
use WZ\ChildFree\Models\Notification;

class AddCandidateReferralNotification extends Hook
{
    public static array $hooks = array( 'cbc_candidate_referral' );

    public function __invoke( $candidate_id ) {
        $notifications = get_option( 'notifications' );
        $subject = $notifications['candidate-referral-subject'];
        $content = $notifications['candidate-referral-content'];

        $candidate = new Candidate( $candidate_id );

        Notification::create( $candidate->get_user_id(), $subject, $content );
    }
}