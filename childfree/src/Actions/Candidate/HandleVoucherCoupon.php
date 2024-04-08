<?php

namespace WZ\ChildFree\Actions\Candidate;

use WC_Coupon;
use WZ\ChildFree\Actions\Hook;
use WZ\ChildFree\Models\ExpansionDonation;
use WZ\ChildFree\Models\GeneralDonation;
use WZ\ChildFree\Models\LocationDonation;

class HandleVoucherCoupon extends Hook
{

    public static array $hooks = array(
        'wp_footer',
        'wp_ajax_apply_voucher',
        'wp_ajax_nopriv_apply_voucher',
        'wp_ajax_remove_voucher',
        'wp_ajax_nopriv_remove_voucher',
    );

    public function __invoke()
    {
        add_action('wp_ajax_apply_voucher', array($this, 'apply_voucher'));
        add_action('wp_ajax_nopriv_apply_voucher', array($this, 'apply_voucher'));

        add_action('wp_ajax_remove_voucher', array($this, 'remove_voucher'));
        add_action('wp_ajax_nopriv_remove_voucher', array($this, 'remove_voucher'));

        // If voucher code is applied to cart, display the voucher code on the page
        if (is_product()) { // single product page id: 22547 (if needed)
            // check if any voucher is applied to the cart and if so, display the voucher code
            if (WC()->cart->has_discount()) {
                $applied_coupons = WC()->cart->get_applied_coupons();
                $voucher_code = $applied_coupons[0];
                $voucher = new WC_Coupon($voucher_code);
                ?>
                <script>
                    jQuery('.woocommerce-message').hide();
                    // jQuery('#form-field-acceptance').prop('checked', true);
                    jQuery('#form-field-acceptance').next('label').html(
                        'Applied voucher code: <span id="coupon_code_value"><strong style="color: #3cb371;"><?= $voucher_code ?></strong></span>' +
                        '<button id="remove_coupon_code_value" data-voucher_code="<?= $voucher_code ?>">' +
                            '<i aria-hidden="true" class="far fa-trash-alt"></i>' +
                        '</button>'
                    );
                    jQuery('#form-field-acceptance').remove();
                </script>
                <?php
            }
            else {
                ?>
                <script>
                    jQuery('.woocommerce-message').hide();
                    jQuery('#form-field-acceptance').prop('checked', false);
                </script>
                <?php
            }
            $this->add_voucher_js();
        }
    }

    private function add_voucher_js()
    {
        ?>

        <script>
            $ = jQuery;

            jQuery(document).ready(function($) {
                // Whenever single product page is reloaded, voucher checkbox has to be unchecked.
                setTimeout(function() {
                    if ($('#form-field-acceptance').is(':checked')) {
                        $('#form-field-acceptance').prop('checked', false);
                    }
                }, 1500);
            });

            function apply_voucher_code() {
                console.log('apply_voucher_code');
                let voucher_code = $('#voucher_code').val();
                let wc_voucher_notification = $('#woocommerce_voucher_notification');
                let voucherDiv = $('.acceptance-modal');
                console.log('voucher_code:: ', voucher_code);
                // Apply the voucher via WooCommerce AJAX
                $.ajax({
                    type: 'POST',
                    url: '<?= admin_url("admin-ajax.php") ?>',
                    data: {
                        action: 'apply_voucher',
                        voucher_code,
                    },
                    beforeSend: function () {
                        jQuery('#apply_voucher_button').html('<img src="/wp-content/uploads/2023/04/candidates-loader.gif" width="30px">');
                    },
                    success: function (response) {
                        console.log('response:: ', JSON.stringify(response));
                        if (response.success) {
                            wc_voucher_notification.hide();
                            voucherDiv.hide();
                            jQuery('.woocommerce-message').hide();
                            // jQuery('#form-field-acceptance').prop('checked', true);
                            jQuery('#form-field-acceptance').next('label').html(
                                'Applied voucher code: <span id="coupon_code_value"><strong style="color: #3cb371;">'
                                + response.data.voucher_code + '</strong></span>' +
                                '<button id="remove_coupon_code_value" data-voucher_code="' + response.data.voucher_code + '">'
                                +'<i aria-hidden="true" class="far fa-trash-alt"></i>'
                                +'</button>'
                            );
                            jQuery('#form-field-acceptance').remove();
                            $('#remove_coupon_code_value').on('click', function () {
                                remove_voucher_code();
                            });
                            $(document.body).trigger('wc_fragment_refresh');
                        } else {
                            wc_voucher_notification.prop('class', 'woocommerce-error').html(response.data.message).show();
                        }
                        jQuery('#apply_voucher_button').text('Apply');
                    },
                    error: function (response) {
                        jQuery('#apply_voucher_button').text('Apply');
                        wc_voucher_notification.prop('class', 'woocommerce-error').html(response.data.message).show();
                    },
                    complete: function () {
                        jQuery('#apply_voucher_button').text('Apply');
                        // Trigger the 'update_checkout' event to refresh the cart total
                        $(document.body).trigger('update_checkout');
                    }
                });
            }

            // Listen for clicks on the "Apply Voucher" button
            jQuery('#apply_voucher_button').on('click', function () {
                apply_voucher_code();
            });


            function remove_voucher_code(myObj) {
                $ = jQuery;
                console.log('remove_coupon_code');
                let removeButton = $('#remove_coupon_code_value');

                let voucher_code = removeButton.attr('data-voucher_code');
                let wc_voucher_notification = $('#woocommerce_voucher_notification');

                $.ajax({
                    type: 'POST',
                    url: '<?= admin_url("admin-ajax.php") ?>',
                    data: {
                        action: 'remove_voucher',
                        voucher_code,
                    },
                    beforeSend: function () {
                        jQuery('#remove_coupon_code_value').html('<img src="/wp-content/uploads/2023/04/candidates-loader.gif" width="15px">');
                    },
                    success: function (response) {
                        // delete cookie if it exists
                        if (document.cookie.indexOf('ckVoucherCode') > -1) {
                            document.cookie = 'ckVoucherCode=; path=/;';
                            document.cookie = 'ckVoucherAmount=; path=/;';
                        }
                        wc_voucher_notification.hide();
                        // wc_voucher_notification.prop('class','woocommerce-message').html(response.data.message).show();
                        $('.applied-voucher-code-message').hide();
                        $('#coupon_code_value').html('').hide();
                        $('#voucher_code').val('');
                        removeButton.hide();
                        $('#form-field-acceptance').prop('checked', false);
                        // Voucher DOM element
                        $('.elementor-element-1d550e85').replaceWith(
                            '<div class="elementor-element elementor-element-1d550e85 elementor-widget elementor-widget-html" data-id="1d550e85" data-element_type="widget" data-widget_type="html.default">'
                            + '<div class="elementor-widget-container">'
                            + '<div class="elementor-field-subgroup">'
                            + '<span class="elementor-field-option">'
                            + '<input type="checkbox" name="form_fields[acceptance]" id="form-field-acceptance" class="elementor-field elementor-size-sm  elementor-acceptance-field">'
                            + '<label for="form-field-acceptance">I have a Donation Voucher</label>'
                            + '</span>'
                            + '</div>'
                            + '<div id="woocommerce_voucher_notification" class="woocommerce-message" style="display: none;">Invalid voucher</div>'
                            + '<div class="applied-voucher-code-message" style="display:none;">'
                            + '<p style="color:black;">Applied Coupon Code:'
                            + '<span id="coupon_code_value"></span>'
                            + '<button id="remove_coupon_code_value" data-voucher_code="" style="cursor: pointer;font-weight: 700;">'
                            + '<i aria-hidden="true" class="far fa-trash-alt"></i></button>'
                            + '</p></div>'
                            + '<div class="acceptance-modal">'
                            + '<div class="code-status">'
                            + '<input type="text" class="code-input" id="voucher_code" placeholder="Input Donation Voucher Here">'
                            + '<button class="voucher-btn" id="apply_voucher_button">Apply</button></div></div></div></div>');

                        // jQuery('#remove_coupon_code_value').text('');
                        jQuery('#remove_coupon_code_value').text('').append('<i aria-hidden="true" class="far fa-trash-alt"></i>');


                        $(document.body).trigger('wc_fragment_refresh');
                        // location.reload();
                    },
                    error: function (response) {
                        jQuery('#remove_coupon_code_value').text('');
                        wc_voucher_notification.prop('class', 'woocommerce-error').html(response.data.message).show();
                    },
                    complete: function () {
                        jQuery('#remove_coupon_code_value').text('');
                        $(document.body).trigger('update_checkout');
                    }
                });

                $(document).on('change', '#form-field-acceptance', function () {
                    console.log('change');
                    if ($(this).is(':checked')) {
                        $('.acceptance-modal').show();
                    } else {
                        $('.acceptance-modal').hide();
                    }
                });
                $(document).on('click', '#apply_voucher_button', function () {
                    console.log('apply_voucher_button clicked');
                    apply_voucher_code();
                });
            }

            $('#remove_coupon_code_value').on('click', function () {
                remove_voucher_code($(this));
            });

        </script>

        <?php
    }

    function apply_voucher(): void
    {
        $response = array();
        $cookie_expiry_time = time() + (86400 * 30);

        if (!empty($_POST['voucher_code'])) {
            $voucherCode = sanitize_text_field($_POST['voucher_code']);
            // apply coupon to cart if it's not already applied
            if (!WC()->cart->has_discount($voucherCode)) {
                // check if the coupon code exists in WooCommerce
                $voucher = new WC_Coupon($voucherCode);
                if ($voucher->exists) {

                    // Check if candidate product is not in the cart then do not apply the voucher
//                    $cart_items = WC()->cart->get_cart();
//                    $product_ids = array_column($cart_items, 'product_id');
//                    if (!in_array(GeneralDonation::PRODUCT_ID, $product_ids)
//                        || !in_array(LocationDonation::PRODUCT_ID, $product_ids)
//                        || !in_array(ExpansionDonation::PRODUCT_ID, $product_ids)) {
//                        $response['message'] = __('Please add a candidate product to the cart first.', 'woocommerce');
//                    }

                    $result = WC()->cart->add_discount($voucherCode);

                    if ($result === true) {
                        // get voucher amount
                        $voucher_amount = $voucher->get_amount();
                        $response['message'] = __("$$voucher_amount Voucher applied successfully.", 'woocommerce');
                        $response['voucher_code'] = $voucherCode;
                        $response['voucher_amount'] = $voucher_amount;
                        // create cookie and save voucher code and voucher amount in it
                        setcookie('ckVoucherCode', $voucherCode, $cookie_expiry_time, "/");
                        setcookie('ckVoucherAmount', $voucher_amount, $cookie_expiry_time, "/");
                        wp_send_json_success($response);
                        wp_die();
                    } else {
                        $response['message'] = __('Invalid voucher code. Please try again.', 'woocommerce');
                        wp_send_json_error($response);
                    }
                } else {
                    $response['message'] = __('Voucher code does not exist.', 'woocommerce');
                    wp_send_json_error($response);
                }

            } else {
                $response['message'] = __('Voucher already applied.', 'woocommerce');
                wp_send_json_error($response);
            }

        }
    }

    function remove_voucher()
    {
        $response = array();
        $cookie_expiry_time = time() + (86400 * 30);

        if (isset($_POST['voucher_code']) && !empty($_POST['voucher_code'])) {
            $voucherCode = sanitize_text_field($_POST['voucher_code']);

            // Attempt to apply the voucher via WooCommerce
            $result = WC()->cart->remove_coupon($voucherCode);

            if ($result === true) {

                // Delete cookie if it exists
                if (isset($_COOKIE['ckVoucherCode'])) {
                    setcookie('ckVoucherCode', '', -1, '/');
                    unset($_COOKIE['ckVoucherCode']);
                }
                if (isset($_COOKIE['ckVoucherAmount'])) {
                    setcookie('ckVoucherAmount', '', -1, '/');
                    unset($_COOKIE['ckVoucherAmount']);
                }
                $response['message'] = __('Voucher removed successfully.', 'woocommerce');
                wp_send_json_success($response);
            } else {
                $response['message'] = __('Invalid voucher code. Please try again.', 'woocommerce');
                wp_send_json_error($response);
            }
        } else {
            $response['message'] = __('Voucher code is missing.', 'woocommerce');
            wp_send_json_error($response);
        }
    }
}

// Instantiate the class
$custom_instance = new HandleVoucherCoupon();
$custom_instance();