<?php

namespace WZ\ChildFree\Actions\Notifications;

use WZ\ChildFree\Actions\Hook;

class SendEmailNotification extends Hook
{
    public static array $hooks = ['cbc_notification_created'];

    public function __invoke( $notification ) {
        $user = new \WP_User( $notification->get_user_id() );

        $disable = get_user_meta( $user->ID, 'disable_email_notifications', true );

        if ( true === wc_string_to_bool( $disable ) ) {
            return;
        }

        WC()->mailer()->send(
            $user->user_email,
            'You have new notifications on ChildFree By Choice',
            WC()->mailer()->wrap_message(
                'New Notifications',
                'You have new notifications on ChildFree By Choice. Click <a href="'
                . wc_get_account_endpoint_url('notifications') . '">here</a> to read them.'
            )
        );
    }
}
