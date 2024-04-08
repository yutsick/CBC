<?php
/**
 * Thankyou page
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/thankyou.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.7.0
 */

defined( 'ABSPATH' ) || exit;
// unset cookies
if (isset($_SERVER['HTTP_COOKIE'])) {
    $cookies = explode(';', $_SERVER['HTTP_COOKIE']);
    foreach($cookies as $cookie) {
        $parts = explode('=', $cookie);
        $name = trim($parts[0]);
        if (substr($name, 0, 2) === 'ck') {
            setcookie($name, '', time()-1000);
            setcookie($name, '', time()-1000, '/');
        }
    }
}
?>

<div class="woocommerce-order">

	<?php
	if ( $order ) :

		do_action( 'woocommerce_before_thankyou', $order->get_id() );
		?>

		<?php if ( $order->has_status( 'failed' ) ) : ?>

			<p class="woocommerce-notice woocommerce-notice--error woocommerce-thankyou-order-failed"><?php esc_html_e( 'Unfortunately your order cannot be processed as the originating bank/merchant has declined your transaction. Please attempt your purchase again.', 'woocommerce' ); ?></p>

			<p class="woocommerce-notice woocommerce-notice--error woocommerce-thankyou-order-failed-actions">
				<a href="<?php echo esc_url( $order->get_checkout_payment_url() ); ?>" class="button pay"><?php esc_html_e( 'Pay', 'woocommerce' ); ?></a>
				<?php if ( is_user_logged_in() ) : ?>
					<a href="<?php echo esc_url( wc_get_page_permalink( 'myaccount' ) ); ?>" class="button pay"><?php esc_html_e( 'My account', 'woocommerce' ); ?></a>
				<?php endif; ?>
			</p>

		<?php else : ?>

			<p style="text-align:center;" class="woocommerce-notice woocommerce-notice--success woocommerce-thankyou-order-received">
                <?php echo apply_filters( 'woocommerce_thankyou_order_received_text',
                    esc_html__( 'Thank you. Your order has been received.', 'woocommerce' ), $order );
                // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
            </p>
			<ul class="woocommerce-order-overview woocommerce-thankyou-order-details order_details">

				<li class="woocommerce-order-overview__order order">
					<?php esc_html_e( 'Order number:', 'woocommerce' ); ?>
					<strong><?php echo $order->get_order_number(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></strong>
				</li>

<?php die(); ?>
				<li class="woocommerce-order-overview__date date">
					<?php esc_html_e( 'Date:', 'woocommerce' ); ?>
					<strong><?php echo wc_format_datetime( $order->get_date_created() ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></strong>
				</li>

<!--				--><?php //if ( is_user_logged_in() && $order->get_user_id() === get_current_user_id() && $order->get_billing_email() ) : ?>
<!--					<li class="woocommerce-order-overview__email email">-->
<!--						--><?php //esc_html_e( 'Email:', 'woocommerce' ); ?>
<!--						<strong>--><?php //echo $order->get_billing_email(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?><!--</strong>-->
<!--					</li>-->
<!--				--><?php //endif; ?>

				<li class="woocommerce-order-overview__total total">
					<?php esc_html_e( 'Total:', 'woocommerce' ); ?>
					<strong><?php echo $order->get_formatted_order_total(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></strong>
				</li>

				<?php if ( $order->get_payment_method_title() ) : ?>
					<li class="woocommerce-order-overview__payment-method method">
						<?php esc_html_e( 'Payment method:', 'woocommerce' ); ?>
						<strong><?php echo wp_kses_post( $order->get_payment_method_title() ); ?></strong>
					</li>
				<?php endif; ?>

			</ul>

		<?php endif; ?>

		<?php do_action( 'woocommerce_thankyou_' . $order->get_payment_method(), $order->get_id() ); ?>
        <?php do_action( 'woocommerce_thankyou', $order->get_id() ); ?>

	<?php else : ?>

		<p class="woocommerce-notice woocommerce-notice--success woocommerce-thankyou-order-received"><?php echo apply_filters( 'woocommerce_thankyou_order_received_text', esc_html__( 'Thank you. Your order has been received.', 'woocommerce' ), null ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></p>

	<?php endif; ?>
        <!-- <table class="woocommerce-table woocommerce-table--order-details shop_table order_details">
				<thead>
					<tr>
						<th style="color:#91C8C8"><?php //esc_html_e( 'Referral User Name', 'woocommerce' ); ?></th>
						<th style="color:#91C8C8"><?php //esc_html_e( 'Referral Amount', 'woocommerce' ); ?></th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td style="color:#003366"><?php //echo get_post_meta( $order->get_id(), '_refering_name', true ); ?></td>
						<td style="color:#003366"><?php //echo wc_price( get_post_meta( $order->get_id(), '_refering_amount', true ) ); ?></td>
					</tr>
				</tbody>
			</table> -->
</div>
                            
<?php if ( $order && $order->get_item_count() > 0 ) : ?>
    <div class="woocommerce-order-products">
        <h2><?php esc_html_e( 'Your Donated People', 'woocommerce' ); ?></h2>
        <ul class="product-list" style="padding-left: 20px;">
            <?php
            foreach ( $order->get_items() as $item ) {
                $product = $item->get_product();
                if ( $product && $product->exists() ) {
                    $product_link = $product->get_permalink();
                    $product_name = $product->get_name();
                    echo '<li style="list-style:auto;">
                            <a class="donated-people-anchor" style="color:#143A62;font-size:18px;font-weight: 600;padding: 10px 16px;" href="' . esc_url( $product_link ) . '">' . esc_html( $product_name ) . '</a>
                          </li>';
                }
            }
            ?>
        </ul>
    </div>
<?php endif;
//wp_die("Thank you for your donation.");

?>