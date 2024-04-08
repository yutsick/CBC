<?php
/**
 * Checkout Payment Section
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/payment.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 7.5.0
 */

defined( 'ABSPATH' ) || exit;

if ( ! wp_doing_ajax() ) {
	do_action( 'woocommerce_review_order_before_payment' );
}
?>
<div id="payment" class="woocommerce-checkout-payment">
	<?php if ( WC()->cart->needs_payment() ) : ?>
		<ul class="wc_payment_methods payment_methods methods">
			<?php
			if ( ! empty( $available_gateways ) ) {
				foreach ( $available_gateways as $gateway ) {
					wc_get_template( 'checkout/payment-method.php', array( 'gateway' => $gateway ) );
				}
			} else {
				echo '<li class="woocommerce-notice woocommerce-notice--info woocommerce-info">' . apply_filters( 'woocommerce_no_available_payment_methods_message', WC()->customer->get_billing_country() ? esc_html__( 'Sorry, it seems that there are no available payment methods for your state. Please contact us if you require assistance or wish to make alternate arrangements.', 'woocommerce' ) : esc_html__( 'Please fill in your details above to see available payment methods.', 'woocommerce' ) ) . '</li>'; // @codingStandardsIgnoreLine
			}
			?>
            <style>
                /* Styles for the popup container */
                .popup-other-payments {
                    display: none;
                    position: fixed;
                    top: 0;
                    left: 0;
                    width: 100%;
                    height: 100%;
                    background-color: rgba(0, 0, 0, 0.7);
                    z-index: 100;
                    overflow: auto;
                }

                /* Styles for the popup content */
                .popup-other-content {
                    background-color: #fff;
                    margin: 15% auto;
                    padding: 35px;
                    border: 1px solid #888;
                    border-radius: 10px;
                    width: 50%;
                    max-width: 600px;
                    position: relative;
                    font-weight: normal;
                }

                /* Close button style */
                .close {
                    position: absolute;
                    top: 0;
                    right: 0;
                    padding: 10px;
                    cursor: pointer;
                }

                .close:hover {
                    background-color: #f44336;
                    color: white;
                }

                .other-payment-options {
                    margin-top: 10px !important;
                    margin-left: 5px !important;
                    font-weight: 700 !important;
                    border: 1px solid lightgray;
                    border-radius: 5px;
                    padding: 10px;
            </style>
<!--            <li class="wc_payment_method payment_method_ppcp-gateway other-payment-options">-->
<!--                <label>-->
<!--                    *Do you want to donate using another payment method not listed such as Crypto, Stocks, Checks,-->
<!--                    Money Orders, Bonds, Other Securities, or Wire Transfers?-->
<!--                    <a href="#" id="popup-link" style="font-weight: bold;padding-left: 0 !important;font-size: 14px;">-->
<!--                        Click Here-->
<!--                    </a>-->
<!--                </label>-->
<!--                <div class="popup-other-payments" id="popup_other_payments">-->
<!--                    <div class="popup-other-content">-->
<!--                        <span class="close" id="close-popup">&times;</span>-->
<!--                        <p>-->
<!--                            ChildFree by Choice accepts crypto (<a href="/contact-us/">please contact us for wallet information</a>) for a completely anonymous donation option as well as checks, money orders, wire transfers, gifts of stocks, bonds, and other securities. To make this type of donation or a donation larger than your bank or payment processor permits (typically in excess of $10,000), please contact us at <a href="mailto:donation@childfreebc.com">donation@childfreebc.com</a>.-->
<!--                        </p>-->
<!---->
<!--                        <p>-->
<!--                            To mail checks or other documents, please make payable to ChildFree By Choice, LLC and mail to:-->
<!--                        </p>-->
<!---->
<!--                        <p>-->
<!--                            ChildFree By Choice,<br>-->
<!--                            Attention: Donations Department,<br>-->
<!--                            24044 Cinco Village Center Blvd.,<br>-->
<!--                            Ste 100 PMB 33,<br>-->
<!--                            Katy, TX 77494-->
<!--                        </p>-->
<!---->
<!--                        <p>-->
<!--                            You will not be able to donate using our online checkout process but again must contact us directly. Thanks!-->
<!--                        </p>-->
<!--                    </div>-->
<!--                </div>-->
<!--                <script>-->
<!--                    // JavaScript to show and hide the popup-->
<!--                    jQuery(document).ready( function () {-->
<!--                        const popup = document.getElementById('popup_other_payments');-->
<!--                        const popupLink = document.getElementById('popup-link');-->
<!--                        const closePopup = document.getElementById('close-popup');-->
<!---->
<!--                        // Show the popup when the link is clicked-->
<!--                        popupLink.addEventListener('click', function (e) {-->
<!--                            e.preventDefault();-->
<!--                            popup.style.display = 'block';-->
<!--                            console.log('popup-link clicked');-->
<!--                        });-->
<!---->
<!--                        // Close the popup when the close button is clicked-->
<!--                        closePopup.addEventListener('click', function () {-->
<!--                            popup.style.display = 'none';-->
<!--                        });-->
<!---->
<!--                        // Close the popup if the user clicks outside of it-->
<!--                        window.addEventListener('click', function (e) {-->
<!--                            if (e.target === popup) {-->
<!--                                popup.style.display = 'none';-->
<!--                            }-->
<!--                        });-->
<!--                    });-->
<!--                </script>-->
<!--            </li>-->
		</ul>
	<?php endif; ?>
	<div class="form-row place-order">
		<noscript>
			<?php
			/* translators: $1 and $2 opening and closing emphasis tags respectively */
			printf( esc_html__( 'Since your browser does not support JavaScript, or it is disabled, please ensure you click the %1$sUpdate Totals%2$s button before placing your order. You may be charged more than the amount stated above if you fail to do so.', 'woocommerce' ), '<em>', '</em>' );
			?>
			<br/><button type="submit" class="button alt<?php echo esc_attr( wc_wp_theme_get_element_class_name( 'button' ) ? ' ' . wc_wp_theme_get_element_class_name( 'button' ) : '' ); ?>" name="woocommerce_checkout_update_totals" value="<?php esc_attr_e( 'Update totals', 'woocommerce' ); ?>"><?php esc_html_e( 'Update totals', 'woocommerce' ); ?></button>
		</noscript>

		<?php wc_get_template( 'checkout/terms.php' ); ?>

		<?php do_action( 'woocommerce_review_order_before_submit' ); ?>

        <?php //echo apply_filters( 'woocommerce_order_button_html', '<button type="submit" class="button alt' . esc_attr( wc_wp_theme_get_element_class_name( 'button' ) ? ' ' . wc_wp_theme_get_element_class_name( 'button' ) : '' ) . '" name="woocommerce_checkout_place_order" id="place_order" value="' . esc_attr( $order_button_text ) . '" data-value="' . esc_attr( $order_button_text ) . '">' . esc_html( $order_button_text ) . '</button>' ); // @codingStandardsIgnoreLine ?>

        <?php echo apply_filters( 'woocommerce_order_button_html', '<button type="submit" class="button alt1" name="woocommerce_checkout_place_order" id="place_order" value="' . esc_attr( $order_button_text ) . '" data-value="' . esc_attr( $order_button_text ) . '">' . esc_html( $order_button_text ) . '</button>' ); // @codingStandardsIgnoreLine ?>

		<?php do_action( 'woocommerce_review_order_after_submit' ); ?>

		<?php wp_nonce_field( 'woocommerce-process_checkout', 'woocommerce-process-checkout-nonce' ); ?>
	</div>
</div>
<?php
if ( ! wp_doing_ajax() ) {

	do_action( 'woocommerce_review_order_after_payment' );
    ?>

    <div id="other_donation_options" class="frm_form_field  frm_html_container form-field">
        <div class="payment-other">
            <div class="payment-other__row">
                <div class="payment-other__col">
                    <h3 class="frm_pos_top frm_section_spacing">Other Donation Options</h3>
                    <p class="payment-other__text">If you want to use another payment method not listed below such as crypto, stocks, checks, money orders, bonds, other securities, or wire transfers, then please click the information icon here to learn more. <span id="popup-link" class="payment-other__accent" style="vertical-align: middle; cursor: pointer;"><img decoding="async" src="https://childfreebc.com/wp-content/uploads/2023/10/ad-step__icon.svg" width="24px" height="24"></span></p>
                </div>
            </div>
        </div>
    </div>

    <?php
}