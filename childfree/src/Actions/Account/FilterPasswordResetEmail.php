<?php

namespace WZ\ChildFree\Actions\Account;

use Wz\Childfree\Actions\Hook;

class FilterPasswordResetEmail extends Hook
{
    public static array $hooks = array(
        'init',
//        'retrieve_password_message'
    );

    public function __invoke( $message ) {

        if (isset($_POST['frm_action']) && $_POST['frm_action'] == 'reset_password') {
            // Get user based on reset key and username
            $reset_key = sanitize_text_field($_GET['key']);
            $username = sanitize_text_field($_GET['user']);

            $user = get_user_by('login', $username);

//            echo 'resetkey: ' . $reset_key . '<br>';
//            echo 'username: ' . $username . '<br>';
//            echo 'user: ' . $user->ID . '<br>';
//            wp_die($message);

            // Check if the reset key matches the stored key
            if ($user && get_user_meta($user->ID, 'password_reset_key', true) == $reset_key) {
                // Reset password
                $new_password = wp_generate_password(12, true);
                wp_set_password($new_password, $user->ID);

                // Remove the reset key from user meta
                delete_user_meta($user->ID, 'password_reset_key');

                // Redirect after successful password reset
                wp_redirect('/password-reset-success/');
                exit();
            }
        }

//        add_filter( 'wp_mail_content_type', fn() => 'text/html');
//
//        return WC()->mailer()->wrap_message(
//            __( 'Password Reset' ),
//            $message
//        );
    }
}