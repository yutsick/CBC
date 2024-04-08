<?php

namespace WZ\ChildFree\Actions\Verification;

use WZ\ChildFree\Actions\Hook;
use WZ\ChildFree\Services\EmailVerification;
//use WZ\ChildFree\Services\SMSVerification;

class HandleUserRegistered extends Hook
{
	public static array $hooks = array(
        'user_register'
    );


	public function __invoke( $user_id ) {
        // send woocommerce email verification on user registration
//		EmailVerification::send( $user_id );
//		SMSVerification::send( $user_id );
	}
}
