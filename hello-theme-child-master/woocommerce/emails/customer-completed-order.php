<?php
/**
 * Customer completed order email
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/emails/customer-completed-order.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates\Emails
 * @version 3.7.0
 */
// DONOR EMAIL TEMPLATE

if (!defined('ABSPATH')) {
    exit;
}

/*
 * @hooked WC_Emails::email_header() Output the email header
 */
do_action( 'woocommerce_email_header', $email_heading, $email ); ?>

<?php /* translators: %s: Customer first name */ ?>

        <!--<div style="padding-top:50px;"><img src="https://childfreebc.com/wp-content/uploads/2022/01/childfree-logo.png" width="200"></div>!-->

        <!--<p><br></p>!-->

    <div style="line-height: 155%;padding-bottom: 50px;">

        <p><?php printf(esc_html__('Dear %s,', 'woocommerce'), esc_html($order->get_billing_first_name())); ?></p>

        <?php
        $donate_again_url = get_site_url() . '/checkout/';
        $order_total = $order->get_total();
        $order_total_formatted = wc_price($order_total);

        $site_title = get_bloginfo('name');
        $site_url = home_url();
        $items = $order->get_items();
        $candidate_name = '';
        $product_id = '';

        foreach ($items as $item) {
            $product_id = $item->get_product_id();
            $product_url = get_permalink($product_id);
            $candidate_name = $item->get_name();
            $candidate_meta_data = get_post_meta($product_id);
            $candidate_gender = get_post_meta($product_id, '_sex', true);
            $candidate_procedure_type = match ($candidate_gender) {
                'male' => 'vasectomy',
                'female' => 'tubal ligation',
                default => 'procedure',
            };

            break;
        }
        ?>

        <p>We wanted to drop you a quick note to say a massive thank you for your awesome donation of <?php echo $order_total_formatted; ?>. Your support seriously means the world to us here at Childfree By Choice. Knowing that you're backing our mission to help folks with reproductive procedures (vasectomies and tubal ligations) fills us with gratitude. Thanks to you, we're on track to make a positive impact on society by cutting down those unnecessary costs linked to unintended pregnancies and childbirth.</p>

        <p>Here's the scoop: we don't get a cent from the government or any swanky foundations. Nope, it's all thanks to fantastic folks like you who believe in making things better.</p>

        <p>In the words of Charles Dickens, "It was the best of times, it was the worst of times." Right now, our society is dealing with the challenges that come with unintended pregnancies and childbirths, but with the support coming from generous people like you, we're turning things around and making a real difference.</p>

        <p>Your donation isn't just about the money; it's a vote of confidence that fuels our determination. We want to assure you that we're committed to living up to your trust!</p>

        <p>Thanks a bunch for being our rock. Your support is the secret sauce helping us build a future without the extra costs of unintended pregnancies and childbirth.</p>

        <p>Please consider donating again or sharing our cause with others.</p>

        <p>Thanks.</p>

        <p><br></p><a href="<?php echo $donate_again_url; ?>" style="text-decoration:none;color:black;text-align: center; vertical-align: middle; padding: 10px 20px; border: 0; border-radius: 12px;
    background: #ffbd12;font-weight:bold;font-size:18px;">Donate Again now</a>

    </div>

<?php

/*
 * @hooked WC_Emails::order_details() Shows the order details table.
 * @hooked WC_Structured_Data::generate_order_data() Generates structured data.
 * @hooked WC_Structured_Data::output_structured_data() Outputs structured data.
 * @since 2.5.0
 */
//do_action( 'woocommerce_email_order_details', $order, $sent_to_admin, $plain_text, $email );

/*
 * @hooked WC_Emails::order_meta() Shows order meta data.
 */
//do_action( 'woocommerce_email_order_meta', $order, $sent_to_admin, $plain_text, $email );

/*
 * @hooked WC_Emails::customer_details() Shows customer details
 * @hooked WC_Emails::email_address() Shows email address
 */
//do_action( 'woocommerce_email_customer_details', $order, $sent_to_admin, $plain_text, $email );

/**
 * Show user-defined additional content - this is set in each email's settings.
 */
//if ( $additional_content ) {
//    echo wp_kses_post( wpautop( wptexturize( $additional_content ) ) );
//}

/*
 * @hooked WC_Emails::email_footer() Output the email footer
 */
do_action( 'woocommerce_email_footer', $email );
