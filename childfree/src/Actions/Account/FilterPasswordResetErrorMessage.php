<?php

namespace WZ\ChildFree\Actions\Account;

use Wz\Childfree\Actions\Hook;

class FilterPasswordResetErrorMessage extends Hook
{
    public static array $hooks = array(
        'wp'
    );

    public function __invoke( ) {

        if (is_page('reset-password')) {
            wp_enqueue_script('childfree-passwod-reset-error-message',
                WZ_CHILDFREE_URL . 'assets/js/password-reset-error-message.js?h='.uniqid('', true),
                array('jquery'), WZ_CHILDFREE_VERSION, true);
            wp_localize_script('childfree-passwod-reset-error-message', 'ajax_object', array(
                'ajaxurl' => admin_url('admin-ajax.php'), // DO NOT change this
                'ajax_nonce' => wp_create_nonce( 'browse_candidates_nonce' ), // Create nonce for security
            ));
        }
    }
}