<?php

namespace WZ\ChildFree\Actions\Verification;

use WZ\ChildFree\Actions\Hook;

class HandleNewsletterConfirmation extends Hook
{
    public static array $hooks = ['wp_ajax_elementor_pro_forms_send_form', 'wp_ajax_nopriv_elementor_pro_forms_send_form', 'elementor_pro/forms/new_record'];

    public function __invoke(): void
    {
        add_action('wp_ajax_elementor_pro_forms_send_form', array($this, 'handle_form_submission'), 10, 2);
        add_action('wp_ajax_nopriv_elementor_pro_forms_send_form', array($this, 'handle_form_submission'), 10, 2);
        add_action('elementor_pro/forms/new_record', array($this, 'handle_new_record'), 10, 2);
    }

    public function handle_new_record($record, $handler): void
    {
        if (isset($_POST['form_id']) && $_POST['form_id'] === '2806986') {
            $subscriber_email = $record->get_formatted_data()['No Label email'];

            if (!is_email($subscriber_email)) {
                return;
            }

            $email_template_path = get_stylesheet_directory() . '/custom-verification-email-template.php';
            $email_template_content = file_get_contents($email_template_path);

            $email_subject = 'Please Verify Your Email Address';
            $email_body = str_replace('{email}', $subscriber_email, $email_template_content);

            $headers[] = 'Content-Type: text/html; charset=UTF-8';

            wp_mail( $subscriber_email, $email_subject, $email_body, $headers );

            $response = [
                "success" => true,
                "data" => [
                    "message" => "Your submission was successful.",
                    "data" => [
                        "popup" => [
                            "action" => "open",
                            "id" => "58226"
                        ]
                    ]
                ]
            ];
            echo json_encode($response, JSON_THROW_ON_ERROR);
            die();
        }
    }
}

