<?php
/**
 * My Account Dashboard
 *
 * Shows the first intro screen on the account dashboard.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/dashboard.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 4.4.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

$allowed_html = array(
	'a' => array(
		'href' => array(),
	),
);
?>
<div>
<p>
	<?php
	printf(
		/* translators: 1: user display name 2: logout url */
		wp_kses( __( 'Hello %1$s (not %1$s? <a href="%2$s">Log out</a>)', 'woocommerce' ), $allowed_html ),
		'<strong>' . esc_html( $current_user->display_name ) . '</strong>',
		esc_url( wc_logout_url() )
	);
	?>
</p>

<p>
	<?php
	/* translators: 1: Orders URL 2: Address URL 3: Account URL. */
	if ( current_user_can( 'medical_provider' ) ) {
		printf(
			wp_kses( 'From your account dashboard, you can view your <a href="%1$s">available Candidates</a>, manage your <a href="%2$s">selected Candidates</a>, and <a href="%3$s">edit your password and account details</a>.', $allowed_html ),
			esc_url( wc_get_endpoint_url( 'search-candidates' ) ),
			esc_url( wc_get_endpoint_url( 'my-candidates' ) ),
			esc_url( wc_get_endpoint_url( 'edit-account' ) )
		);
	} else {
		printf(
			wp_kses( 'From your account dashboard, you can view your <a href="%1$s">recent donations</a> and <a href="%2$s">edit your password and account details</a>.', $allowed_html ),
			esc_url( wc_get_endpoint_url( 'orders' ) ),
			esc_url( wc_get_endpoint_url( 'edit-account' ) )
		);
	}

	
	?>
</p>

<p>
	<?php
	printf(
		wp_kses( 'If you would like your account to be completed removed from our website and database, please <a href="%1$s">contact us</a>.', $allowed_html ),
		esc_url( home_url( 'contact-us' ) )
	);
	?>
</p>
</div class="woocommerce-MyAccount-content">
<?php
	/**
	 * My Account dashboard.
	 *
	 * @since 2.6.0
	 */
	do_action( 'woocommerce_account_dashboard' );

	/**
	 * Deprecated woocommerce_before_my_account action.
	 *
	 * @deprecated 2.6.0
	 */
	do_action( 'woocommerce_before_my_account' );

	/**
	 * Deprecated woocommerce_after_my_account action.
	 *
	 * @deprecated 2.6.0
	 */
	do_action( 'woocommerce_after_my_account' );

/* Omit closing PHP tag at the end of PHP files to avoid "headers already sent" issues. */