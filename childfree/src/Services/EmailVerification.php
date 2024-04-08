<?php

namespace WZ\ChildFree\Services;

class EmailVerification extends Verification
{
    const KEY_META = 'cbc_email_key';
    const VERIFIED_META = 'cbc_email_verified';

    /**
     * Generate and store key for verification
     *
     * @param $user_id
     * @return string
     */
    public static function generate_key( $user_id ) {
        $now = time();
        $key = base64_encode( "cbc-{$user_id}-{$now}" );

        update_user_meta( $user_id, self::KEY_META, $key );

        return $key;
    }

    /**
     * Send verification email to user
     *
     * @param $user_id
     */
    public static function send( $user_id ) {
        $user = get_userdata( $user_id );
        $key = self::generate_key( $user_id );
        $url = add_query_arg(
            array(
                'key' => urlencode( $key ),
                '' => ''
            ),
            wc_get_account_endpoint_url( 'verify-info' )
        );

        WC()->mailer()->send(
            $user->user_email,
            'Verify Your Email',
            WC()->mailer()->wrap_message(
                'Verify Your Email',
                "Please <a href='{$url}'>click here</a> to verify your email address. This link will expire in 24 hours, after which time you will need to request another."
            )
        );

        as_schedule_single_action( time() + DAY_IN_SECONDS, 'cbc_expire_verify_key', array( 'user_id' => $user_id ) );
    }
}
