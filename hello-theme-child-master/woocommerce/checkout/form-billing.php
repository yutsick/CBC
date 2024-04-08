<?php
/**
 * Checkout billing information form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/form-billing.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.6.0
 * @global WC_Checkout $checkout
 */

defined('ABSPATH') || exit;
?>
<div class="woocommerce-billing-fields">
    <?php if (wc_ship_to_billing_address_only() && WC()->cart->needs_shipping()) : ?>

        <h3><?php esc_html_e('Billing & Shipping', 'woocommerce'); ?></h3>

    <?php else : ?>

        <h3><?php esc_html_e('Billing details', 'woocommerce'); ?></h3>

    <?php endif; ?>

    <?php do_action('woocommerce_before_checkout_billing_form', $checkout); ?>

    <?php if ( is_page( 'donate' ) ) : ?>
    <style>
        .woocommerce-remove-coupon {
            display: none;
        }
        /* Style for the info icon */
        .info-icon {
            color: #0073aa;
            cursor: pointer;
        }

        /* Style for the description text */
        .info-text {
            display: none;
            margin-top: 5px;
            font-size: 14px;
            background: #fcfcb7;
            padding: 15px;
            border-radius: 10px;
        }

        /* Show description on hover */
        .checkbox:hover .info-text {
            display: block;
        }

    </style>
    <p class="form-row " id="display_name_field" data-priority="110"><span class="woocommerce-input-wrapper">
            <label class="checkbox " aria-describedby="display_name-description">
						<input type="checkbox" class="input-checkbox " name="display_name" id="display_name" value="1">
                Display my name publicly on the candidate donation page.
                <i class="info-icon fa fa-info-circle"></i>
            </label>
            <span class="info-text" id="display_name-description" aria-hidden="true" style="display: none;">To make your name visible on the Candidate Donation page, simply click this checkbox. Alternatively, if you wish to donate anonymously, leave the checkbox unchecked. Donors have the option to reveal their full name on the Candidate Donation page by selecting this box for each donation they make.</span>
        </span>
    </p>
    <?php endif; ?>
    <script>
        // JavaScript to handle hover effect (optional)
        document.addEventListener("DOMContentLoaded", function () {
            const infoIcon = document.querySelector(".info-icon");
            const info_text = document.querySelector(".info-text");

            // if infoIcon is empty, exit
            if (infoIcon) {
                infoIcon.addEventListener("mouseover", function () {
                    info_text.style.display = "block";
                });

                infoIcon.addEventListener("mouseout", function () {
                    info_text.style.display = "none";
                });
            }
        });
    </script>

    <div class="woocommerce-billing-fields__field-wrapper">
        <?php
        $fields = $checkout->get_checkout_fields('billing');

        foreach ($fields as $key => $field) {
            woocommerce_form_field($key, $field, $checkout->get_value($key));
        }
        ?>
    </div>

    <?php do_action('woocommerce_after_checkout_billing_form', $checkout); ?>
</div>

<?php if (!is_user_logged_in() && $checkout->is_registration_enabled()) : ?>
    <div class="woocommerce-account-fields">
        <?php if (!$checkout->is_registration_required()) : ?>

            <p class="form-row form-row-wide create-account">
                <label class="woocommerce-form__label woocommerce-form__label-for-checkbox checkbox">
				<span><?php esc_html_e('Create a Donor Account? ', 'woocommerce'); ?>
				<input class="woocommerce-form__input woocommerce-form__input-checkbox input-checkbox checkout_createaccount"
                       type="checkbox" name="createaccount_no" value="0" checked/><span>No</span>
					<span>				
					<input class="woocommerce-form__input woocommerce-form__input-checkbox input-checkbox "
                           id="createaccount" <?php checked((true === $checkout->get_value('createaccount') || (true === apply_filters('woocommerce_create_account_default_checked', false))), true); ?> type="checkbox"
                           name="createaccount" value="1"/><span>Yes</span>
					</span>
                </label>
            </p>

        <?php endif; ?>

        <?php do_action('woocommerce_before_checkout_registration_form', $checkout); ?>

        <?php if ($checkout->get_checkout_fields('account')) : ?>

            <div class="create-account">
                <?php foreach ($checkout->get_checkout_fields('account') as $key => $field) : ?>
                    <?php woocommerce_form_field($key, $field, $checkout->get_value($key)); ?>
                <?php endforeach; ?>
                <div class="clear"></div>
            </div>

        <?php endif; ?>

        <?php do_action('woocommerce_after_checkout_registration_form', $checkout); ?>
    </div>
<?php endif; ?>

<script>
    jQuery('.checkout_createaccount').on('click', function () {
        if (jQuery(this).is(':checked')) {

            var createaccout = jQuery(this).val();
        } else {
            createaccout = "";
        }
        if (createaccout == 0) {

            jQuery("#createaccount").trigger("click");

        }

    });
    jQuery('#createaccount').on('click', function () {
        if (jQuery(this).is(':checked')) {

            var createaccout = jQuery(this).val();
        } else {
            createaccout = "";
        }
        if (createaccout == 1) {
            jQuery('input.checkout_createaccount').not(this).prop('checked', false);
        } else {
            jQuery('input.checkout_createaccount').not(this).prop('checked', true);
        }
    });
</script>