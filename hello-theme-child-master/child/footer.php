<?php
/**
 * The template for displaying the footer.
 *
 * Contains the body & html closing tags.
 *
 * @package HelloElementor
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

if (!function_exists('elementor_theme_do_location') || !elementor_theme_do_location('footer')) {
    if (did_action('elementor/loaded') && hello_header_footer_experiment_active()) {
        get_template_part('template-parts/dynamic-footer');
    } else {
        get_template_part('template-parts/footer');
    }
}
?>

<?php wp_footer(); ?>



<?php
if (is_user_logged_in()) {
    global $post;
    $page_id = $post->ID;

    if ($page_id != "3335") {
        $user_id = get_current_user_id();
        $user_meta = get_userdata($user_id);
        $user_roles = $user_meta->roles;
        $avialable_user_roles = array('candidate', 'customer', 'subscriber', 'medical_provider');
        $find_current_role = array_intersect($avialable_user_roles, $user_roles);
        $find_current_role = array_values($find_current_role);
        if (in_array("candidate", $find_current_role)) {
            $current_role = 'candidate';
        } else {

            $current_role = $find_current_role[0];
        }
        $switch_to = $_GET['switchto'];
    }
    if (is_page(787) and $switch_to = "medical_provider" and !empty($user_id)) {

        ?>
        <script>
            var new_user_role = "<?php echo $switch_to;?>";
            var current_user_role = "<?php echo $current_role; ?>";
            var cuser_id = "<?php echo $user_id;?>";

            var ajaxurl = "<?php echo admin_url('admin-ajax.php'); ?>";
            jQuery("body").on('click', '.frm_final_submit', function (e) {
                jQuery.ajax({
                    type: "post",
                    dataType: "json",
                    url: ajaxurl,
                    data: {
                        action: "switch_to_account",
                        current_role: current_user_role,
                        new_role: new_user_role,
                        user_id: cuser_id
                    },
                    success: function (response) {
                        if (response.type == "success") {
                            alert("Your account has switched successfully");
                            jQuery("#form_physician").submit();
                            window.location.replace("/my-account/");
                        } else {
                            alert("Your account unable to swtich try again.");
                        }
                    }
                });

            });

        </script>

    <?php }
}

?>


<script type="text/javascript">


    var minLength = 5;
    var maxLength = 5;
    jQuery(document).ready(function () {
        jQuery('#zipi .jet-search-filter__input').on('keydown keyup change', function () {
            var char = jQuery(this).val();
            var charLength = jQuery(this).val().length;
            if (charLength < minLength) {

                jQuery('#warning-message').text('Length is short, minimum ' + minLength + ' required.');
            } else if (charLength > maxLength) {
                jQuery('#warning-message').text('Length is not valid, maximum ' + maxLength + ' allowed.');
                jQuery(this).val(char.substring(0, maxLength));
            } else {
                jQuery('#warning-message').text('');
            }
        });
    });


    jQuery(function () {

        jQuery('#zipi .jet-search-filter__input').keyup(function (e) {
            if (this.value != '-')
                while (isNaN(this.value))
                    this.value = this.value.split('').reverse().join('').replace(/[\D]/i, '')
                        .split('').reverse().join('');
        })
            .on("cut copy paste", function (e) {
                e.preventDefault();
            });

    });
    jQuery('#coupon_code').attr('placeholder',
        'Voucher code');

    jQuery('.checkout_coupon .wp-element-button').text('Apply Voucher');


    jQuery('.e-coupon-anchor-description').text(jQuery('.e-coupon-anchor-description').text().replace(/coupon/g, 'voucher'));

    //jQuery('.checkout_coupon').text(jQuery('.checkout_coupon').text().replace(/coupon/g, 'voucher'));

    document.querySelector("#account_username_field > label").innerHTML = "Account Username";
    document.querySelector("#account_password_field > label").innerHTML = "Create Account Password";


</script>

<script>
    (function ($) {

        //$('.woocommerce-form-coupon-toggle').remove();
        $(document).on("click", 'button[name="apply_coupon"]', function (event) {

            event.preventDefault();

            $form = $('form[name="checkout"]');
            $form.block({message: ''});

            var data = {
                security: wc_checkout_params.apply_coupon_nonce,
                coupon_code: $('input[name="coupon_code"]').last().val()
            };

            $.ajax({
                type: 'POST',
                url: wc_checkout_params.wc_ajax_url.toString().replace('%%endpoint%%', 'apply_coupon'),
                data: data,
                success: function (code) {

                    $('.woocommerce-error, .woocommerce-message').remove();
                    $form.removeClass('processing').unblock();

                    if (code) {

                        $('.e-woocommerce-coupon-nudge').before(code);
                        $(document.body).trigger('update_checkout', {update_shipping_method: false});
                    }

                },
                dataType: 'html'
            });

        });

    })(jQuery);
</script>

</body>
</html>