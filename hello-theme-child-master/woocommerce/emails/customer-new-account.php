<?php
/**
 * Customer new account email
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/emails/customer-new-account.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://woo.com/document/template-structure/
 * @package WooCommerce\Templates\Emails
 * @version 6.0.0
 */

defined( 'ABSPATH' ) || exit;

//do_action( 'woocommerce_email_header', $email_heading, $email ); ?>



<?php /* translators: %s: Customer first name */ ?>

    <div style="padding-top:50px;"><img src="https://childfreebc.com/wp-content/uploads/2022/01/childfree-logo.png" width="200"></div>

    <p><br></p>

    <div style="font-size:18px;font-family:verdana;padding-bottom: 50px;">

        <p><?php printf(esc_html__('Hi %s,', 'woocommerce'), esc_html($order->get_billing_first_name())); ?></p>

        <?php
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
            $candidate_pronoun = match ($candidate_gender) {
                'male' => 'his',
                'female' => 'her',
                default => 'their',
            };
            $candidate_procedure_type = match ($candidate_gender) {
                'male' => 'vasectomy',
                'female' => 'tubal ligation',
                default => 'procedure',
            };

            break;
        }
        ?>

        <div><p>
                It's the ChildFree By Choice Team.  Thanks for registering as a Donor!
            </p></div>


        <div><p>
                You can view all CBC Candidates in need of financial help to live a lifestyle of their choice by <a href="https://childfreebc.com/candidates/">clicking here</a>.
            </p></div>


        <div><p>
                You can also easily make a <a href="https://childfreebc.com/checkout/?funds=general">general donation</a> to CBC’s mission, and we’ll allocate them to individual Candidates in need.
            </p></div>


        <div><p><strong>
                    Tips:
                </strong></p></div>
        <div><ul>
                <li><a href="https://childfreebc.com/login/">Login</a> to quickly navigate to your Donor Dashboard Account to see all your donations, status of Candidates you have donated to, and more.</li>
                <li>Consider creating a recurring subscription donation for an ongoing impact.</li>
                <li>Share donations you have made on social media or with friends and family to help maximize CBC’s reach and your contributions to help our global efforts!</li>
                <li>Please consider helping us grow our mission by sharing our <a href="https://childfreebc.com/register-as-donor/">Donor Registration Link</a>.</li>
                <li>View <a href="https://childfreebc.com/donor-info/">CBC Donor Page</a>, <a href="https://youtu.be/-MgDeslaTs0">Donor YouTube Video</a>, & <a href="https://childfreebc.com/resources/">CBC Resources Page</a></li>
            </ul></div>

        <div><p><strong>
                    Questions:
                </strong></p></div>
        <div><ul>
                <li>Email us at <a href="mailto:registrations@childfreebc.com">registrations@childfreebc.com</a> (best)</li>
                <li><a href="https://calendly.com/justincbc/15min">Calendly</a>: Schedule a call with someone from our team</li>
                <li>Call or text <a href="tel:+19377017442">937-701-7442</a> between 8a-5p ET</li>
            </ul></div>

    </div>






<?php ///* translators: %s: Customer username */ ?>
<!--<p>--><?php //printf( esc_html__( 'Hi %s,', 'woocommerce' ), esc_html( $user_login ) ); ?><!--</p>-->
<?php ///* translators: %1$s: Site title, %2$s: Username, %3$s: My account link */ ?>
<!--<p>--><?php //printf(
//        esc_html__(
//        'Thanks for creating an account on %1$s. Your username is %2$s. You can access your account area to view orders, change your password, and more at: %3$s',
//        'woocommerce' ),
//        esc_html( $blogname ),
//        '<strong>' . esc_html( $user_login ) . '</strong>',
//        make_clickable( esc_url( wc_get_page_permalink( 'myaccount' ) ) )
//    ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?><!--</p>-->
<?php //if ( 'yes' === get_option( 'woocommerce_registration_generate_password' ) && $password_generated && $set_password_url ) : ?>
<!--	--><?php //// If the password has not been set by the user during the sign up process, send them a link to set a new password ?>
<!--	<p><a href="--><?php //echo esc_attr( $set_password_url ); ?><!--">--><?php //printf( esc_html__( 'Click here to set your new password.', 'woocommerce' ) ); ?><!--</a></p>-->
<?php //endif; ?>
<!---->
<?php
///**
// * Show user-defined additional content - this is set in each email's settings.
// */
//if ( $additional_content ) {
//	echo wp_kses_post( wpautop( wptexturize( $additional_content ) ) );
//}
//
//do_action( 'woocommerce_email_footer', $email );
