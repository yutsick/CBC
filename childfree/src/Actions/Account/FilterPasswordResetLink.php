<?php

namespace WZ\ChildFree\Actions\Account;

use Wz\Childfree\Actions\Hook;

class FilterPasswordResetLink extends Hook
{
    public static array $hooks = array(
        'lostpassword_url'
    );

    public function __invoke( $redirect ) {
        return home_url(
            '/reset-password/'
//            . '?redirect_to=' . $redirect
        );
    }
}
