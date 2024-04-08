<?php

require_once('custom_referral_field.php');
require_once('switch_account.php');
/**
 * Theme functions and definitions
 *
 * @package HelloElementorChild
 */
define('FLUID22_CHILDFREE_THEME_VERSION', '1.1.0');

/**
 * Load child theme css and optional scripts
 *
 * @return void
 */
function hello_elementor_child_enqueue_scripts()
{
    wp_enqueue_style('childfree-theme-style', get_stylesheet_directory_uri() . '/style.css', ['hello-elementor-theme-style'], FLUID22_CHILDFREE_THEME_VERSION);
    wp_enqueue_style('custom-theme-style', get_stylesheet_directory_uri() . '/custom_style.css', ['hello-elementor-theme-style'], FLUID22_CHILDFREE_THEME_VERSION);
    wp_enqueue_script('childfree-theme', get_stylesheet_directory_uri() . '/js/theme.js', ['jquery'], FLUID22_CHILDFREE_THEME_VERSION, true);
    wp_enqueue_script('custom-js', get_stylesheet_directory_uri() . '/js/custom.js', ['jquery'], true);
}

add_action('wp_enqueue_scripts', 'hello_elementor_child_enqueue_scripts', 20);


//Included by Sheraz 
function enqueue_childfree_multi_select_script()
{
    if (is_account_page()) {
        wp_enqueue_script('childfree-multi-select', FLUID22_CHILDFREE_URL . 'assets/js/multi-select.js', array('jquery'), FLUID22_CHILDFREE_VERSION, true);
        wp_localize_script('childfree-multi-select', 'cbc_multiselect_options', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('cbc_sponsor_multiple_candidates')
        ));
    }
}

add_action('wp_enqueue_scripts', 'enqueue_childfree_multi_select_script');


function enqueue_childfree_multi_select_script_two()
{
    if (is_account_page()) { ?>
        <script id="childfree-multi-select-js-extra">

            jQuery(document).ready(function ($) {
                var cbc_multiselect_options = {
                    "ajax_url": "https:\/\/childfreebc.com\/wp-admin\/admin-ajax.php",
                    "nonce": "c42a2293ef"
                };
            });
        </script>
    <?php }
}

add_action('wp_enqueue_scripts', 'enqueue_childfree_multi_select_script');


/**
 * Trigger all embedded form actions
 *
 * @return true
 */
add_filter('frm_use_embedded_form_actions', '__return_true');


/**
 * Redirect to my account
 *
 * @return string
 */
function childfree_login_redirect()
{
    return home_url('/my-account/');
}

add_filter('login_redirect', 'childfree_login_redirect');


/**
 * Add Register Link To Login
 */
function childfree_login_register_link()
{
    return home_url('/register/');
}

add_filter('register_url', 'childfree_login_register_link');


/**
 * Style login page
 */
function childfree_login_css()
{
    ?>
    <style type="text/css">
        body.login div#login h1 a {
            background-image: url(/wp-content/uploads/2022/01/childfree-logo.png);
            background-size: contain;
            width: 300px;
            padding-bottom: 30px;
        }
    </style>
    <?php
}

add_action('login_enqueue_scripts', 'childfree_login_css');


/**
 * Change affiliate wording
 *
 * @param string $text
 * @return string
 */
function childfree_change_affiliate_wording($text)
{
    if ($text === 'Lifetime Customers') {
        return 'Registered Users';
    }

    return $text;
}

add_filter('gettext', 'childfree_change_affiliate_wording');


/**
 *
 */
function childfree_empty_cart_message($text)
{
    return __('Your donation cart is empty. Please add Candidates.');
}

add_filter('wc_empty_cart_message', 'childfree_empty_cart_message');


/**
 * My account change order to donation on orders table
 */
function childfree_account_orders_columns($columns)
{
    $columns['order-number'] = __('Donation');

    return $columns;
}

add_filter('woocommerce_account_orders_columns', 'childfree_account_orders_columns');


/**
 * Determine whether the mini-cart should render
 *
 * @param $bool
 * @param $widget
 * @return bool
 */
function childfree_maybe_hide_minicart($element)
{
    if ($element->get_name() === 'woocommerce-menu-cart' && WC()->cart->is_empty()) {
        $element->add_render_attribute('_wrapper', ['class' => 'hidden']);
    }
}

add_action('elementor/frontend/widget/before_render', 'childfree_maybe_hide_minicart');


function itx_admin_menu_rename()
{
    global $menu; // Global to get menu array

    global $submenu; // Global to get submenu array
    $menu[58][0] = 'Digital Vouchers';
    $submenu['woocommerce-marketing'][1][0] = 'Add Digital Copuon Codes';
}

add_action('admin_menu', 'itx_admin_menu_rename');


/** Subhan function*/


/**
 * Wrapper function to deal with backwards compatibility.
 */
if (!function_exists('hello_elementor_body_open')) {
    function hello_elementor_body_open()
    {
        if (function_exists('wp_body_open')) {
            wp_body_open();
        } else {
            do_action('wp_body_open');
        }
    }
}


//post per page
function my_custom_posts_per_page_shortcode()
{
    ob_start();
    ?>
    <form id="my-custom-posts-per-page-form">
        <label for="my-custom-posts-per-page-select">Posts per page:</label>
        <select name="my_custom_posts_per_page" id="my-custom-posts-per-page-select">
            <option value="10" <?php selected(get_query_var('my_custom_posts_per_page'), 10); ?>>10</option>
            <option value="20" <?php selected(get_query_var('my_custom_posts_per_page'), 20); ?>>20</option>
            <option value="50" <?php selected(get_query_var('my_custom_posts_per_page'), 50); ?>>50</option>
            <option value="100" <?php selected(get_query_var('my_custom_posts_per_page'), 100); ?>>100</option>
            <option value="-1" <?php selected(get_query_var('my_custom_posts_per_page'), -1); ?>>View All</option>
        </select>
    </form>
    <?php
    return ob_get_clean();
}

add_shortcode('my_custom_posts_per_page', 'my_custom_posts_per_page_shortcode');


function my_custom_posts_per_page_ajax_handler()
{
    $per_page = isset($_POST['per_page']) ? intval($_POST['per_page']) : get_option('posts_per_page');
    $args = array(
        'post_type' => 'candidate',
        'posts_per_page' => $per_page,
    );
    $query = new WP_Query($args);
    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();
            // Display your custom post type content here
        }
        wp_reset_postdata();
    } else {
        // Display a message if there are no custom posts to display
        echo 'No custom posts found';
    }
    die();
}

add_action('wp_ajax_my_custom_posts_per_page', 'my_custom_posts_per_page_ajax_handler');
add_action('wp_ajax_nopriv_my_custom_posts_per_page', 'my_custom_posts_per_page_ajax_handler');


add_action('wp_head', 'my_js_variables');
function my_js_variables()
{
    // for specific page templates
    $current_template = get_page_template();

    // return if there is no page template, or if the page template is other than template-x1.php or template-x2.php
    if (!isset($current_template) || ($current_template != 'template-x1.php' && $current_template != 'template-x2.php')) {
        return;
    } ?>
    <script type="text/javascript">
        var ajaxurl = <?php echo json_encode(admin_url("admin-ajax.php")); ?>;
        var ajaxnonce = <?php echo json_encode(wp_create_nonce("itr_ajax_nonce")); ?>;
        var myarray = <?php echo json_encode(array(
            'foo' => 'bar',
            'available' => TRUE,
            'ship' => array(1, 2, 3,),
        )); ?>
    </script>
    <?php
}


/** Unset checkout validation **/


add_action('woocommerce_checkout_process', 'custom_checkout_field_process', 10);
function custom_checkout_field_process()
{
    if (isset($_POST['coupon_code'])) {
        add_action('woocommerce_after_checkout_validation', 'misha_validate_fname_lname', 10, 2);
    }
    return true;
}


function misha_validate_fname_lname($fields, $errors)
{
    if (!empty($errors->get_error_codes())) {
        // remove all of them
        foreach ($errors->get_error_codes() as $code) {
            $errors->remove($code);
        }
    }
}


add_action('woocommerce_checkout_create_order', 'bbloomer_alter_checkout_fields_after_order');

function bbloomer_alter_checkout_fields_after_order($order)
{


    $discount_total = $order->get_discount_total();
    $total = $order->get_total();
    $new_total = $discount_total + $total;
    $order->set_total($new_total);

}

add_action('woocommerce_before_single_product', 'get_product_orders', 10);

function get_product_orders()
{

    $product_id = get_the_ID();
    $orders_ids = get_orders_ids_by_product_id($product_id);
    $raised_ammount = get_post_meta($product_id, '_amount_raised', true);


    if (!empty($orders_ids)) {

        $all_orders_total = 0;
        foreach ($orders_ids as $order_id) {
            $order = wc_get_order($order_id);
            $total = $order->get_total();
            $all_orders_total += $total;
        }
        if ($raised_ammount != $all_orders_total) {

            update_post_meta($product_id, '_amount_raised', $all_orders_total);

        }
    }


}


function get_orders_ids_by_product_id($product_id)
{

    global $wpdb;
    $order_status = ['wc-completed'];

    $results = $wpdb->get_col("
        SELECT order_items.order_id
        FROM {$wpdb->prefix}woocommerce_order_items as order_items
        LEFT JOIN {$wpdb->prefix}woocommerce_order_itemmeta as order_item_meta ON order_items.order_item_id = order_item_meta.order_item_id
        LEFT JOIN {$wpdb->posts} AS posts ON order_items.order_id = posts.ID
        WHERE posts.post_type = 'shop_order'
        AND posts.post_status IN ( '" . implode("','", $order_status) . "' )
        AND order_items.order_item_type = 'line_item'
        AND order_item_meta.meta_key = '_product_id'
        AND order_item_meta.meta_value = '" . $product_id . "'
        ORDER BY order_items.order_id DESC");

    return $results;
}


add_filter('woocommerce_checkout_coupon_message', 'bbloomer_have_coupon_message');

function bbloomer_have_coupon_message()
{
    return '<i class="fa fa-ticket" aria-hidden="true"></i>Do you have a Donation Voucher? <a href="#" class="showcoupon">Click Here to Enter Voucher Code</a>';
}


add_filter('gettext', 'woocommerce_change_coupon_field_instruction_text');

function woocommerce_change_coupon_field_instruction_text($translated)
{
    $translated = str_ireplace('If you have a coupon code, please apply it below.', 'If you have a voucher code, please apply it below.', $translated);
    return $translated;
}

function my_text_strings($translated_text, $text, $domain)
{
    switch ($translated_text) {
        case 'Coupon code' :
            $translated_text = __('Voucher Code', 'woocommerce');
            break;
        case 'Voucher code' :
            $translated_text = __('Voucher Code', 'woocommerce');
            break;
    }
    return $translated_text;
}

add_filter('gettext', 'my_text_strings', 20, 3);


/**
 * Filter "Return To Shop" text.
 *
 * @param string $default_text Default text.
 * @since 4.6.0
 */
function filter_woocommerce_return_to_shop_text($default_text)
{
    // Add new text
    $default_text = __('View all Candidates', 'woocommerce');

    return $default_text;
}

add_filter('woocommerce_return_to_shop_text', 'filter_woocommerce_return_to_shop_text', 99, 1);


//Logout without Confirmation Code 
add_action('template_redirect', 'logout_confirmation');

function logout_confirmation()
{

    global $wp;

    if (isset($wp->query_vars['customer-logout'])) {

        wp_redirect(str_replace('&amp;', '&', wp_logout_url(wc_get_page_permalink('myaccount'))));

        exit;

    }

}

//***********Logout without Confirmation Code****


function show_advocate_text_shortcode()
{
    if (isset($_GET['membership']) && !empty($_GET['membership'])) {
        echo '<h6 class="welcome-message">Congratulations! You have successfully created your Advocate account.</h6>';
    }
    if (isset($_GET['candidate']) && !empty($_GET['candidate'])) {
        echo '<h6 class="welcome-message">Congratulations! You have successfully created your Candidate account.</h6>';
    }
    if (isset($_GET['donor']) && !empty($_GET['donor'])) {
        echo '<h6 class="welcome-message">Congratulations! You have successfully created your Donor account.</h6>';
    }
    if (isset($_GET['physician']) && !empty($_GET['physician'])) {
        echo '<h6 class="welcome-message">Congratulations! You have successfully created your Physician account.</h6>';
    }
}

add_shortcode('show_advocate_text', 'show_advocate_text_shortcode');


add_filter('woocommerce_return_to_shop_redirect', 'bbloomer_change_return_shop_url');

function bbloomer_change_return_shop_url()
{
    return get_the_permalink(24534);
}

add_filter('woocommerce_checkout_fields', 'wc_add_confirm_password_checkout', 10, 1);
function wc_add_confirm_password_checkout($checkout_fields)
{
    if (get_option('woocommerce_registration_generate_password') == 'no') {
        $checkout_fields['account']['account_password2'] = array(
            'type' => 'password',
            'label' => __('Confirm password', 'woocommerce'),
            'required' => true,
            'placeholder' => _x('Confirm Password', 'placeholder', 'woocommerce')
        );
    }

    return $checkout_fields;
}

add_action('woocommerce_after_checkout_validation', 'wc_check_confirm_password_matches_checkout', 10, 2);
function wc_check_confirm_password_matches_checkout($posted)
{
    $checkout = WC()->checkout;
    if (!is_user_logged_in() && ($checkout->must_create_account || !empty($posted['createaccount']))) {
        if (strcmp($posted['account_password'], $posted['account_password2']) !== 0) {
            wc_add_notice(__('Passwords do not match.', 'woocommerce'), 'error');
        }
    }
}


add_filter('woocommerce_checkout_get_value', '__return_empty_string', 10);

/**Included referral functionality */

add_shortcode('my_account_title', 'dynamic_myaccount_title');
function dynamic_myaccount_title()
{
    if (is_user_logged_in()) {

        $user_id = get_current_user_id();

        $user_meta = get_userdata($user_id);
        $user_roles = $user_meta->roles;
        if (in_array("candidate", $user_roles)) {

            $my_account_title = "My Candidate Account";
        } else if (in_array("subscriber", $user_roles)) {

            $my_account_title = "My Donor Account";
        } else if (in_array("customer", $user_roles)) {

            $my_account_title = "My Advocate Account";
        } else if (in_array("medical_provider", $user_roles)) {
            $my_account_title = "My Physician Account";
        } else {
            $my_account_title = "My Account";
        }

        return $my_account_title;
    }

}

//June 15 2023 (Sheraz)
//Removing restriction of second email registeration for Candidate
add_filter('frm_validate_field_entry', 'remove_email_error_for_candidate', 25, 3);
function remove_email_error_for_candidate($errors, $field, $value)
{
    $email_field_id = 10;
    if ($field->id == $email_field_id && isset($errors['field' . $field->id]) && $errors['field' . $field->id] == 'This email address is already registered.') {
        //Unset the Erroe
        unset($errors['field' . $field->id]);
        //Get the email from the Form
        $email_entered = $_POST['item_meta'][10];
        //Find the user in the database from the form
        $user = get_user_by('email', $email_entered);
        if ($user) {
            // The user was found - do something with $user
            $user->add_role('candidate');
            wp_update_user($user);
            $form = FrmForm::getOne(3);
            //SKIPPING THE FORM
            add_filter('frm_skip_form_action', 'form_action_conditions', 10, 2);
            function form_action_conditions($skip_this_action, $args)
            {
                if ($args['action']->ID == 422) {//replace ID value with your action ID
                    $skip_this_action = true;
                }
                return $skip_this_action;
            }
            //=========
        }
    }
    return $errors;
}

add_filter('frm_validate_field_entry', 'remove_username_error_for_candidate', 25, 3);
function remove_username_error_for_candidate($errors, $field, $value)
{
    $email_field_id = 233;
    if ($field->id == $email_field_id && isset($errors['field' . $field->id]) && $errors['field' . $field->id] == 'This username is already registered.') {
        unset($errors['field' . $field->id]);
    }
    return $errors;
}


//Removing restriction of second email registeration for Advocate (OK)
add_filter('frm_validate_field_entry', 'remove_email_error_for_advocate', 25, 3);
function remove_email_error_for_advocate($errors, $field, $value)
{
    $email_field_id = 160;
    if ($field->id == $email_field_id && isset($errors['field' . $field->id]) && $errors['field' . $field->id] == 'This email address is already registered.') {
        //Unset the Error
        unset($errors['field' . $field->id]);
        //Get the email from the Form
        $email_entered = $_POST['item_meta'][160];
        //Find the user in the database from the form
        $user = get_user_by('email', $email_entered);
        if ($user) {
            // The user was found - do something with $user
            $user->add_role('customer');
            wp_update_user($user);
            $form = FrmForm::getOne(15);
            //SKIPPING THE FORM
            add_filter('frm_skip_form_action', 'form_action_conditions', 10, 2);
            function form_action_conditions($skip_this_action, $args)
            {
                if ($args['action']->ID == 7620) {//replace 115 with your action ID
                    $skip_this_action = true;
                }
                return $skip_this_action;
            }
            //=========
        }
    }
    return $errors;
}

add_filter('frm_validate_field_entry', 'remove_username_error_for_advocate', 25, 3);
function remove_username_error_for_advocate($errors, $field, $value)
{
    $email_field_id = 234;
    if ($field->id == $email_field_id && isset($errors['field' . $field->id]) && $errors['field' . $field->id] == 'This username is already registered.') {
        unset($errors['field' . $field->id]);
    }
    return $errors;
}


//Removing restriction of second email registeration for Physician (OK)
add_filter('frm_validate_field_entry', 'remove_email_error_for_physician', 25, 3);
function remove_email_error_for_physician($errors, $field, $value)
{
    $email_field_id = 102;
    if ($field->id == $email_field_id && isset($errors['field' . $field->id]) && $errors['field' . $field->id] == 'This email address is already registered.') {
        //Unset the Erroe
        unset($errors['field' . $field->id]);
        //Get the email from the Form
        $email_entered = $_POST['item_meta'][102];
        //Find the user in the database from the form
        $user = get_user_by('email', $email_entered);
        if ($user) {
            // The user was found - do something with $user
            $user->add_role('medical_provider');
            wp_update_user($user);
            $form = FrmForm::getOne(13);
            //SKIPPING THE FORM
            add_filter('frm_skip_form_action', 'form_action_conditions', 10, 2);
            function form_action_conditions($skip_this_action, $args)
            {
                if ($args['action']->ID == 792) {//replace 115 with your action ID
                    $skip_this_action = true;
                }
                return $skip_this_action;
            }
            //=========
        }
    }
    return $errors;
}

add_filter('frm_validate_field_entry', 'remove_username_error_for_physician', 25, 3);
function remove_username_error_for_physician($errors, $field, $value)
{
    $email_field_id = 234;
    if ($field->id == $email_field_id && isset($errors['field' . $field->id]) && $errors['field' . $field->id] == 'This username is already registered.') {
        unset($errors['field' . $field->id]);
    }
    return $errors;
}

//Removing restriction of second email registeration for Donor (OK)
add_filter('frm_validate_field_entry', 'remove_email_error_for_donor', 25, 3);
function remove_email_error_for_donor($errors, $field, $value)
{
    $email_field_id = 35;
    if ($field->id == $email_field_id && isset($errors['field' . $field->id]) && $errors['field' . $field->id] == 'This email address is already registered.') {
        //Unset the Erroe
        unset($errors['field' . $field->id]);
        //Get the email from the Form
        $email_entered = $_POST['item_meta'][35];
        //Find the user in the database from the form
        $user = get_user_by('email', $email_entered);
        if ($user) {
            // The user was found - do something with $user
            $user->add_role('subscriber');
            wp_update_user($user);
            $form = FrmForm::getOne(7);
            //SKIPPING THE FORM
            add_filter('frm_skip_form_action', 'form_action_conditions', 10, 2);
            function form_action_conditions($skip_this_action, $args)
            {
                if ($args['action']->ID == 481) {//replace 115 with your action ID
                    $skip_this_action = true;
                }
                return $skip_this_action;
            }
            //=========
        }
    }
    return $errors;
}


add_filter('frm_validate_field_entry', 'remove_username_error_for_donor', 25, 3);
function remove_username_error_for_donor($errors, $field, $value)
{
    $email_field_id = 232;
    if ($field->id == $email_field_id && isset($errors['field' . $field->id]) && $errors['field' . $field->id] == 'This username is already registered.') {
        unset($errors['field' . $field->id]);
    }
    return $errors;
}


add_filter('woocommerce_new_customer_data', 'woocommerce_new_customer_data_set_role');

/**
 * Function for woocommerce_new_customer_data filter hook.
 *
 * @param array $customer_data An array of customer data.
 *
 * @return array
 */
function woocommerce_new_customer_data_set_role($customer_data)
{
    $customer_data['role'] = 'subscriber';
    return $customer_data;
}


// $user_longitude = -95.35671; // Replace with the user's longitude.
// $user_latitude = 29.69495; // Replace with the user's latitude.
// $radius_in_miles = 10; // The radius within which products are to be found (in miles).

// $xx = find_products_within_radius($user_longitude,$user_latitude,$radius_in_miles);
// var_dump($xx);


add_action('woocommerce_before_checkout_billing_form', function () {
    echo "Test";
});