<?php

namespace WZ\ChildFree\Actions\Referrals;

use Wz\Childfree\Actions\Hook;

class AddReferralToSession extends Hook
{
    public static array $hooks = array( 'init' );

    public function __invoke() {
        if ( ! isset( $_REQUEST['ref'] ) ) {
            return;
        }

        if ( 'product' !== get_post_type( $_REQUEST['ref'] ) ) {
            return;
        }

        setcookie( 'candidate_referrer', $_REQUEST['ref'], time() + DAY_IN_SECONDS );
    }
}
