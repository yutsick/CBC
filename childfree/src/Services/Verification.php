<?php

namespace WZ\ChildFree\Services;

abstract class Verification
{
    const KEY_META = '';
    const VERIFIED_META = '';

    /**
     * Generate and store key for verification
     *
     * @param $user_id
     * @return string
     */
    abstract public static function generate_key( $user_id );

    /**
     * Send verification code
     *
     * @param $user_id
     */
    abstract public static function send( $user_id );

    /**
     * Delete key
     *
     * @param $user_id
     */
    public static function expire_key( $user_id ) {
        delete_user_meta( $user_id, static::KEY_META );
    }

    /**
     * Check if user is verified
     *
     * @param $user_id
     * @return bool
     */
    public static function is_verified( $user_id ) {
        return wc_string_to_bool( get_user_meta( $user_id, static::VERIFIED_META, true ) );
    }

    /**
     * Check if given key matches the user's key
     *
     * @param $user_id
     * @param $key
     * @return bool
     */
    public static function check_key( $user_id, $key ) {
        $user_key = get_user_meta( $user_id, static::KEY_META, true );

        return ( $key === $user_key );
    }

    /**
     * Check and store verification
     *
     * @param $user_id
     * @param $key
     */
    public static function verify( $user_id, $key ) {
        if ( ! static::check_key( $user_id, $key ) ) {
            return false;
        }

        update_user_meta( $user_id, static::VERIFIED_META, wc_bool_to_string( true ) );

        return true;
    }
}
