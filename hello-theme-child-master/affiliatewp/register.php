<?php
global $affwp_register_redirect;

affiliate_wp()->register->print_errors();

$errors = affiliate_wp()->register->get_errors();

$required_registration_fields = affiliate_wp()->settings->get( 'required_registration_fields' );

if ( ! is_user_logged_in() && ! empty( $errors ) ) {

	if ( ! array_key_exists( 'empty_name', $errors ) ) {
		$user_name = sanitize_text_field( $_POST['affwp_user_name'] );
	}

	if ( ! array_key_exists( 'empty_username', $errors ) && ! array_key_exists( 'username_unavailable', $errors ) && ! array_key_exists( 'username_invalid', $errors ) ) {
		$user_login = sanitize_text_field( $_POST['affwp_user_login'] );
	}

	if ( ! array_key_exists( 'email_unavailable', $errors ) && ! array_key_exists( 'email_invalid', $errors ) ) {
		$user_email = sanitize_text_field( $_POST['affwp_user_email'] );
	}

	if ( ! array_key_exists( 'payment_email_invalid', $errors ) ) {
		$payment_email = sanitize_text_field( $_POST['affwp_payment_email'] );
	}

	$url    = esc_url( $_POST['affwp_user_url'] );
	$method = sanitize_text_field( $_POST['affwp_promotion_method'] );

}

if ( is_user_logged_in() ) {
	$current_user = wp_get_current_user();
	$first_name   = $current_user->user_firstname;
	$last_name    = $current_user->user_lastname;
	$user_login   = $current_user->user_login;
	$user_email   = $current_user->user_email;
	$url          = $current_user->user_url;

	$readonly = ' readonly="readonly"';
} else {
	$readonly = '';
}

?>

<form id="affwp-register-form" class="affwp-form" action="" method="post">
	<?php
	/**
	 * Fires at the top of the affiliate registration templates' form (inside the form element).
	 *
	 * @since 1.0
	 */
	do_action( 'affwp_affiliate_register_form_top' );
	?>

	<fieldset>
		<legend><?php _e( 'Register a new affiliate account', 'affiliate-wp' ); ?></legend>

		<?php
		/**
		 * Fires just before the affiliate registration templates' form fields.
		 *
		 * @since 1.0
		 */
		do_action( 'affwp_register_fields_before' );
		?>

		<p>
			<label for="affwp-first-name"><?php _e( 'First Name', 'affiliate-wp' ); ?></label>
			<input id="affwp-first-name" required="required" type="text" name="affwp_first_name" value="<?php if( ! empty( $first_name ) ) { echo $first_name; } ?>" title="<?php esc_attr_e( 'First Name', 'affiliate-wp' ); ?>"  />
		</p>
		
		<p>
			<label for="affwp-last-name"><?php _e( 'Last Name', 'affiliate-wp' ); ?></label>
			<input id="affwp-last-name" required="required" type="text" name="affwp_last_name" value="<?php if( ! empty( $last_name ) ) { echo $last_name; } ?>" title="<?php esc_attr_e( 'Last Name', 'affiliate-wp' ); ?>" />
		</p>

		<p>
			<label for="affwp-user-email"><?php _e( 'Account Email', 'affiliate-wp' ); ?></label>
			<input id="affwp-user-email" required="required" type="email" name="affwp_user_email" value="<?php if( ! empty( $user_email ) ) { echo $user_email; } ?>" title="<?php esc_attr_e( 'Email Address', 'affiliate-wp' ); ?>" />
		</p>

		<p>
			<label for="affwp-user-url"><?php _e( 'Website URL', 'affiliate-wp' ); ?> (optional)</label>
			<input id="affwp-user-url" type="text" name="affwp_user_url" value="<?php if( ! empty( $url ) ) { echo $url; } ?>" title="<?php esc_attr_e( 'Website URL', 'affiliate-wp' ); ?>" <?php echo affwp_required_field_attr( 'website_url' ); ?> />
		</p>

		<p>
			<label for="affwp-promotion-method"><?php _e( 'How will you promote us?', 'affiliate-wp' ); ?> <span class="tooltip" title="Please in as many words as you desire explain how you plan to advocate for the CBC, this will enable the CBC team to assist with your advocacy. The advocacy opportunities are endless. Thank you in advance for help spreading the movement."><i class="fa fa-question-circle"></i></span></label>
			<textarea id="affwp-promotion-method" name="affwp_promotion_method" rows="5" cols="30"<?php echo affwp_required_field_attr( 'promotion_method' ); ?>><?php if( ! empty( $method ) ) { echo esc_textarea( $method ); } ?></textarea>
		</p>

		<?php if ( ! is_user_logged_in() && isset( $required_registration_fields['password'] ) ) : ?>

			<p>
				<label for="affwp-user-pass"><?php _e( 'Password', 'affiliate-wp' ); ?></label>
				<input id="affwp-user-pass" required="required" class="password" type="password" name="affwp_user_pass" />
				<small>Enter Password</small>
			</p>

			<p>
				<input id="affwp-user-pass2" required="required" class="password" type="password" name="affwp_user_pass2" />
				<small>Confirm Password</small>
			</p>

		<?php endif; ?>
		
		<p>
			<label>Have you previously registered with CBC as a:</label><br>
			
			<label for="affwp-registered-roles-Candidate">
				<input id="affwp-registered-roles-Candidate" type="checkbox" name="affwp_registered_roles" value="Candidate" />
				Candidate
			</label><br>
			<label for="affwp-registered-roles-Donor">
				<input id="affwp-registered-roles-Donor" type="checkbox" name="affwp_registered_roles" value="Donor" />
				Donor
			</label><br>
			<label for="affwp-registered-roles-Physician">
				<input id="affwp-registered-roles-Physician" type="checkbox" name="affwp_registered_roles" value="Physician" />
				Physician
			</label><br>
			<label for="affwp-registered-roles-Advocate">
				<input id="affwp-registered-roles-Advocate" type="checkbox" name="affwp_registered_roles" value="Advocate" />
				Advocate
			</label><br>
			<label for="affwp-registered-roles-None">
				<input id="affwp-registered-roles-None" type="checkbox" name="affwp_registered_roles" value="None of the above" />
				None of the above
			</label>
		</p>
		
		<p>
			<hr>
		</p>

		<?php
		/**
		 * Fires just before the terms of service field within the affiliate registration form template.
		 *
		 * @since 1.0
		 */
		do_action( 'affwp_register_fields_before_tos' );
		?>

		<?php $terms_of_use = affiliate_wp()->settings->get( 'terms_of_use' ); ?>
		<?php if ( ! empty( $terms_of_use ) ) : ?>
			<p>
				<label class="affwp-tos" for="affwp-tos">
					<input id="affwp-tos" required="required" type="checkbox" name="affwp_tos" />
					<?php /* <a href="<?php echo esc_url( get_permalink( affiliate_wp()->settings->get( 'terms_of_use' ) ) ); ?>" target="_blank"> */ ?>
					<?php /* echo affiliate_wp()->settings->get( 'terms_of_use_label', __( 'Agree to our Terms of Use and Privacy Policy', 'affiliate-wp' ) ); */ ?>
					<?php /* </a> */ ?>
					
					I agree to and consent to the <a href="/terms-conditions/" target="_blank">Terms and Conditions</a> as well as the <a href="/privacy-policy/" target="_blank">Privacy Policy</a>.
				</label>
			</p>
		<?php endif; ?>

		<?php if ( affwp_is_recaptcha_enabled() ) :
			affwp_enqueue_script( 'affwp-recaptcha' ); ?>

			<div class="g-recaptcha" data-sitekey="<?php echo esc_attr( affiliate_wp()->settings->get( 'recaptcha_site_key' ) ); ?>"></div>

			<p>
				<input type="hidden" name="g-recaptcha-remoteip" value="<?php echo esc_attr( affiliate_wp()->tracking->get_ip() ); ?>" />
			</p>
		<?php endif; ?>
		
		

		<?php
		/**
		 * Fires inside of the affiliate registration form template (inside the form element, prior to the submit button).
		 *
		 * @since 1.0
		 */
		do_action( 'affwp_register_fields_before_submit' );
		?>

		<p>
			<input type="hidden" name="affwp_honeypot" value="" />
			<input type="hidden" name="affwp_redirect" value="<?php echo esc_url( $affwp_register_redirect ); ?>"/>
			<input type="hidden" name="affwp_register_nonce" value="<?php echo wp_create_nonce( 'affwp-register-nonce' ); ?>" />
			<input type="hidden" name="affwp_action" value="affiliate_register" />
			<input class="button" type="submit" value="<?php esc_attr_e( 'Register', 'affiliate-wp' ); ?>" />
		</p>

		<?php
		/**
		 * Fires inside of the affiliate registration form template (inside the form element, after the submit button).
		 *
		 * @since 1.0
		 */
		do_action( 'affwp_register_fields_after' );
		?>
	</fieldset>

	<?php
	/**
	 * Fires at the bottom of the affiliate registration form template (inside the form element).
	 *
	 * @since 1.0
	 */
	do_action( 'affwp_affiliate_register_form_bottom' );
	?>
</form>
