<?php
/**
 * Edit account form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/form-edit-account.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 7.5.0
 */

defined( 'ABSPATH' ) || exit;

do_action( 'woocommerce_before_edit_account_form' ); ?>

<form class="woocommerce-EditAccountForm edit-account" action="" method="post" <?php do_action( 'woocommerce_edit_account_form_tag' ); ?> >

	<?php do_action( 'woocommerce_edit_account_form_start' ); ?>

	<p class="woocommerce-form-row woocommerce-form-row--first form-row form-row-first">
		<label for="account_first_name"><?php esc_html_e( 'First name', 'woocommerce' ); ?>&nbsp;<span class="required">*</span></label>
		<input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="account_first_name" id="account_first_name" autocomplete="given-name" value="<?php echo esc_attr( $user->first_name ); ?>" />
	</p>
	<p class="woocommerce-form-row woocommerce-form-row--last form-row form-row-last">
		<label for="account_last_name"><?php esc_html_e( 'Last name', 'woocommerce' ); ?>&nbsp;<span class="required">*</span></label>
		<input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="account_last_name" id="account_last_name" autocomplete="family-name" value="<?php echo esc_attr( $user->last_name ); ?>" />
	</p>
	<div class="clear"></div>

	<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
		<label for="account_email"><?php esc_html_e( 'Email address', 'woocommerce' ); ?>&nbsp;<span class="required">*</span></label>
		<input type="email" class="woocommerce-Input woocommerce-Input--email input-text" name="account_email" id="account_email" autocomplete="email" value="<?php echo esc_attr( $user->user_email ); ?>" />
	</p>
    <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
		<label for="account_phone"><?php esc_html_e( 'Phone', 'woocommerce' ); ?>&nbsp;<span class="required">*</span></label>
		<input type="phone" class="woocommerce-Input woocommerce-Input--phone_number input-text" name="billing_phone" id="billing_phone" autocomplete="email" value="<?php echo esc_attr( $user->billing_phone ); ?>" />
	</p>

	<fieldset>
		<legend><?php esc_html_e( 'Notifications', 'woocommerce' ); ?></legend>
		
		<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
			<label>Disable Email Notifications</label>
			<label>
				<input type="radio" name="disable_email_notifications" value="yes" <?php echo ($user->disable_email_notifications === 'yes') ? 'checked' : '' ?> />
				Yes
			</label>
			<label>
				<input type="radio" name="disable_email_notifications"  value="no" <?php echo ($user->disable_email_notifications === 'no') ? 'checked' : '' ?> />
				No
			</label>
		</p>
		
		<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
			<label>Disable SMS Notifications</label>
			
			<label>
				<input type="radio" name="disable_sms_notifications" value="yes" <?php echo ($user->disable_sms_notifications === 'yes') ? 'checked' : '' ?> />
				Yes
			</label>
			<label>
				<input type="radio" name="disable_sms_notifications"  value="no" <?php echo ($user->disable_sms_notifications === 'no') ? 'checked' : '' ?> />
				No
			</label>
		</p>
	</fieldset>
	<div class="clear"></div>

	<fieldset>
		<legend><label><input type="checkbox" name="change_password" class="Change_password"></label><?php esc_html_e( 'Change Password ?', 'woocommerce' ); ?></legend>
      <div class="Change_password_options" style="display:none">
		<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
			<label for="password_current"><?php esc_html_e( 'Current password (leave blank to leave unchanged)', 'woocommerce' ); ?></label>
			<input type="password" class="woocommerce-Input woocommerce-Input--password input-text" name="password_current" id="password_current" autocomplete="off" />
		</p>
		<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
			<label for="password_1"><?php esc_html_e( 'New password (leave blank to leave unchanged)', 'woocommerce' ); ?></label>
			<input type="password" class="woocommerce-Input woocommerce-Input--password input-text" name="password_1" id="password_1" autocomplete="off" />
		</p>
		<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
			<label for="password_2"><?php esc_html_e( 'Confirm new password (leave blank to leave unchanged)', 'woocommerce' ); ?></label>
			<input type="password" class="woocommerce-Input woocommerce-Input--password input-text" name="password_2" id="password_2" autocomplete="off" />
		</p>
	 </div>	
	</fieldset>
    <?php if ( in_array( 'medical_provider', $user->roles ) ) : ?>
    <fieldset>
        <legend><?php esc_html_e( 'Additional Physician Information', 'woocommerce' ); ?></legend>

        <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
            <label for="business_name"><?php esc_html_e( 'Business Name', 'woocommerce' ); ?></label>
            <input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="business_name" id="business_name" autocomplete="organization" value="<?php echo esc_attr( $user->business_name ); ?>" />
        </p>
    
        <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
            <label for="speciality"><?php esc_html_e( 'Speciality', 'woocommerce' ); ?></label>
            <input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="speciality" id="speciality" autocomplete="organization" value="<?php echo esc_attr( $user->speciality ); ?>" />
        </p>
    
        <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
            <label for="state_license_number"><?php esc_html_e( 'State License Number', 'woocommerce' ); ?></label>
            <input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="state_license_number" id="state_license_number" autocomplete="off" value="<?php echo esc_attr( $user->state_license_number ); ?>" />
        </p>
		<!-- Website details.-->
		<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
            <label for="website"><?php esc_html_e( 'Website', 'woocommerce' ); ?></label>
            <input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="website" id="website" autocomplete="off" value="<?php $user_id = get_current_user_id(); 							$website_url = get_the_author_meta('user_url', $user_id);	echo esc_attr( $website_url ); ?>" />
        </p>
    
    </fieldset>
<?php endif; ?>


	<div class="clear"></div>

	<?php do_action( 'woocommerce_edit_account_form' ); ?>

	<p>
		<?php wp_nonce_field( 'save_account_details', 'save-account-details-nonce' ); ?>
		<button type="submit" class="woocommerce-Button button<?php echo esc_attr( wc_wp_theme_get_element_class_name( 'button' ) ? ' ' . wc_wp_theme_get_element_class_name( 'button' ) : '' ); ?>" name="save_account_details" value="<?php esc_attr_e( 'Save changes', 'woocommerce' ); ?>"><?php esc_html_e( 'Save changes', 'woocommerce' ); ?></button>
		<input type="hidden" name="action" value="save_account_details" />
	</p>

	<?php do_action( 'woocommerce_edit_account_form_end' ); ?>
</form>

<?php do_action( 'woocommerce_after_edit_account_form' ); ?>


<script>
jQuery('.Change_password').on('click',function(){
 if(jQuery(this).is(':checked')){
  
 jQuery(".Change_password_options").show();
	
 }else{
	
  jQuery(".Change_password_options").hide();	
	 
 }
	 
	 
});
</script>