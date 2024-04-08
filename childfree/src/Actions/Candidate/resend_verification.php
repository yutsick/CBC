<?php
add_action('wp_ajax_resend_verification', 'resend_verification_handler');
add_action('wp_ajax_nopriv_resend_verification', 'resend_verification_handler');

function resend_verification_handler() {
    $email = isset($_POST['email']) ? $_POST['email'] : null;

    if ($email) {
        $user = get_user_by('email', $email);
        $user_id = $user ? $user->ID : null;

        if ($user_id) {
            $verification_key = wp_generate_password(20, false);
            update_user_meta($user_id, 'email_verification_key', $verification_key);

            $subject = 'Verification Email';
            $message = 'To confirm your registration, please follow the following link: ' . home_url( '/verify-email' ) . '?key=' . $verification_key;
            $headers = array('Content-Type: text/html; charset=UTF-8');
            $sent = wp_mail($email, $subject, $message, $headers);
        } else {
            echo "error";
        }
    } else {
        echo "error";
    }
    wp_die();
}
?>
