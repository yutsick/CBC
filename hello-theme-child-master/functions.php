<?php
/**
 * Theme functions and definitions.
 *
 * For additional information on potential customization options,
 * read the developers' documentation:
 *
 * https://developers.elementor.com/docs/hello-elementor-theme/
 *
 * @package HelloElementorChild
 */
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

define('HELLO_ELEMENTOR_CHILD_VERSION', '2.0.0');

/**
 * Load child theme scripts & styles.
 *
 * @return void
 */
function hello_elementor_child_scripts_styles()
{

    wp_enqueue_style(
        'hello-elementor-child-style',
        get_stylesheet_directory_uri() . '/style.css',
        [
            'hello-elementor-theme-style',
        ],
        HELLO_ELEMENTOR_CHILD_VERSION
    );

}

add_action('wp_enqueue_scripts', 'hello_elementor_child_scripts_styles', 20);


/**
 * ENQUEUE SCRIPTS -> Toggle Filters on Candidates Page
 */
function enqueue_toggle_filters_script()
{
    if (is_page('candidates')) {
        wp_enqueue_script('toggle-filters', get_stylesheet_directory_uri() . '/assets/js/toggleFilters.js', array('jquery'), '1.0', true);
    }
    if (is_page('home')) {
        wp_enqueue_script('front-js', get_stylesheet_directory_uri() . '/assets/js/frontPage.js', array('jquery'), '1.0', true);
    }
    if (is_page('checkout')) {
        wp_enqueue_script('checkout-js', get_stylesheet_directory_uri() . '/assets/js/checkoutIndicator.js', array('jquery'), '1.0', true);
        wp_enqueue_script('estimateCalc-js', get_stylesheet_directory_uri() . '/assets/js/locationSpecificDonCalc.js', array('jquery'), '1.0', true);
    }
    if (is_page('donor-info')) {
        wp_enqueue_script('donor-js', get_stylesheet_directory_uri() . '/assets/js/donorPage.js', array('jquery'), '1.0', true);
    }
    if (is_page('faq')) {
        wp_enqueue_script('faq-js', get_stylesheet_directory_uri() . '/assets/js/faq.js', array('jquery'), '1.0', true);
    }
    if (is_page('advocate-3')) {
        wp_enqueue_script('adv-js', get_stylesheet_directory_uri() . '/assets/js/adv.js', array('jquery'), '1.0', true);
    }
    if (is_page('pricing')) {
        wp_enqueue_script('pricing-js', get_stylesheet_directory_uri() . '/assets/js/pricing.js', array('jquery'), '1.0', true);
    }
    if (is_page('referral-program')) {
        wp_enqueue_script('ref-js', get_stylesheet_directory_uri() . '/assets/js/ref.js', array('jquery'), '1.0', true);
    }
    if (is_page('expansion-fund')) {
        wp_enqueue_script('expansion-js', get_stylesheet_directory_uri() . '/assets/js/expansion-fund.js', array('jquery'), '1.0', true);
    }

    $regPages = array('register-as-candidates', 'register-as-advocate', 'register-as-donor', 'register-as-physicians', 'login');
    foreach ($regPages as $page) {
        if (is_page($page)) {
            wp_enqueue_script('userRegistration-js', get_stylesheet_directory_uri() . '/assets/js/userRegistration.js', array('jquery'), '1.0', true);
        }
    };


    // Cart message

    wp_enqueue_script('cartMessage-js', get_stylesheet_directory_uri() . '/assets/js/cartMessage.js', array('jquery'), '1.0', true);

    //Dashboards JS

    if (is_page('dashboard-physician') || is_page('dashboard-candidate') || is_page('dashboard-advocate') || is_page('dashboard-donor')) {
        wp_enqueue_script('copyLink-js', get_stylesheet_directory_uri() . '/assets/js/dashboardsJS/copyAccLink.js', array('jquery'), '1.0', true);
        wp_enqueue_script('menu-js', get_stylesheet_directory_uri() . '/assets/js/dashboardsJS/menuDropdown.js', array('jquery'), '1.0', true);
     //   wp_enqueue_script('shareAcc-js', get_stylesheet_directory_uri() . '/assets/js/dashboardsJS/shareAcc.js', array('jquery'), '1.0', true);
         wp_enqueue_script('switchAcc-js', get_stylesheet_directory_uri() . '/assets/js/dashboardsJS/switchAcc.js', array('jquery'), '1.0', true);
        wp_enqueue_script('tabs-js', get_stylesheet_directory_uri() . '/assets/js/dashboardsJS/tabs.js', array('jquery'), '1.0', true);
        wp_enqueue_script('accImage-js', get_stylesheet_directory_uri() . '/assets/js/dashboardsJS/accImage.js', array('jquery'), '1.0', true);

        add_action('wp_enqueue_scripts', 'register_resend_verification_script');
        function register_resend_verification_script() {
            wp_register_script('resend-verification-script', get_stylesheet_directory_uri() . '/assets/js/dashboardsJS/resendVerificationEmail.js', array('jquery'), '1.0', true);
            wp_localize_script('resend-verification-script', 'resend_verification_object', array(
                'ajax_url' => admin_url('admin-ajax.php'),
            ));
            wp_enqueue_script('resend-verification-script');
        }

        if (!is_page('dashboard-physician') ){
            wp_enqueue_script('filters-js', get_stylesheet_directory_uri() . '/assets/js/dashboardsJS/filters.js', array('jquery'), '1.0', true);
            wp_localize_script( 'filters-js', 'filterData', array(
                'ajaxUrl' => admin_url( 'admin-ajax.php' ),
            ) );
            
        } else {
            wp_enqueue_script('physician-js', get_stylesheet_directory_uri() . '/assets/js/dashboardsJS/physician.js', array('jquery'), '1.0', true);
            wp_localize_script( 'physician-js', 'filterData', array(
                'ajaxUrl' => admin_url( 'admin-ajax.php' ),
            ) );
        }

        wp_enqueue_script('gender-js', get_stylesheet_directory_uri() . '/assets/js/dashboardsJS/gender.js', array('jquery'), '1.0', true);
        wp_enqueue_script('qr-js', get_stylesheet_directory_uri() . '/assets/js/dashboardsJS/QRCodeGenerator.js', array('jquery'), '1.0', true);
    }

    if (is_product()) {
       // wp_enqueue_script('shareAcc-js', get_stylesheet_directory_uri() . '/assets/js/dashboardsJS/shareAcc.js', array('jquery'), '1.0', true);
    }


}

add_action('wp_enqueue_scripts', 'enqueue_toggle_filters_script');

/*
 * HEADER SCRIPTS
 * */
function custom_header_code()
{
    if (is_page(array('dashboard-candidate', 'dashboard-advocate', 'dashboard-donor', 'dashboard-physician'))) {
        ?>
        <link href="https://cdn.jsdelivr.net/npm/daisyui@3.9.4/dist/full.css" rel="stylesheet" type="text/css"/>
        <script src="https://cdn.tailwindcss.com"></script>
        <script>
            tailwind.config = {
                theme: {
                    extend: {
                        colors: {
                            clifford: '#DA373D',
                            mainBg: '#F5F5F5',
                            primary: '#143A62',
                            blueMain: '#0B46A0',
                            accent: '#8802A9',
                            accentBg: '#F3E6F6',
                            textColor: '#333',
                            borderColor: '#EBEBEB',
                            textValue: '#76787A',
                            info: '#8497AB',
                            card1: '#FEF4EA',
                            card2: '#E4F0F0',
                            card3: '#F2E5FF',
                            success: '#02A95C',
                            warning: '#FF2919',
                            alert: '#FFD45A',
                            badgeM: '#EDF3FE',
                            badgeM: '#F3E6F6',
                        },
                        boxShadow: {
                            shadowItem: '0px 0px 12px 0px rgba(52, 64, 77, 0.11)',
                        },
                        maxHeight: {
                            '490': '490px',
                        },
                    },
                }
            }
        </script>
        <?php
    }
}

add_action('wp_head', 'custom_header_code');

// write a function hook to redirect the user to dashboard after login
add_action('wp_login', 'redirect_after_login', 50, 2);
function redirect_after_login($user_login, $user)
{
    if ($user) {
        $roles = $user->roles; // Get the roles array
        if (isset($roles[0])) {
            $firstRole = $roles[0];
        } else {
            $firstRole = reset($roles);
        }

        if ($firstRole === 'administrator') {
            wp_redirect(home_url('/wp-admin/'));
            exit();
        }
        if ($firstRole === 'candidate' && str_contains(wp_get_referer(), 'login')) {
            wp_redirect(home_url('/dashboard-candidate/'));
            exit();
        }
        if ($firstRole === 'customer' && str_contains(wp_get_referer(), 'login')) {
            wp_redirect(home_url('/dashboard-advocate/'));
            exit();
        }
        if ($firstRole === 'subscriber' && str_contains(wp_get_referer(), 'login')) {
            wp_redirect(home_url('/dashboard-donor/'));
            exit();
        }
        if ($firstRole === 'medical_provider' && str_contains(wp_get_referer(), 'login')) {
            wp_redirect(home_url('/dashboard-physician/'));
            exit();
        }
    } else {
        echo "User not found.";
    }
}

/*
 * REDIRECT LOGOUT TO LOGIN
 * */
add_action('template_redirect', 'login_logout_manager');
function login_logout_manager()
{

    if (strpos($_SERVER['REQUEST_URI'], '/login/') !== false) {
        if (is_user_logged_in()) {
            // get $user object
            $user = wp_get_current_user();
            $user_login = $user->user_login;
            redirect_after_login($user_login, $user);
        }
    }

    if (strpos($_SERVER['REQUEST_URI'], '/logout/') !== false) {
        wp_logout();
        wp_redirect(home_url('/login/')); // Replace with your login page URL
        exit;
    }
}

/**
 * REDIRECT TO RIGHT DASHBOARD
 */

add_shortcode('get_user_dahboard_url', 'getUsertDashboardUrl');

function getUsertDashboardUrl()
{
    $userRole = reset(wp_get_current_user()->roles);
    switch ($userRole) {
        case 'administrator':
            return '/wp-admin/';
            break;
        case 'candidate':
            return '/dashboard-candidate/';
            break;
        case 'customer':
            return '/dashboard-advocate/';
            break;
        case 'subscriber':
            return '/dashboard-donor/';
            break;
        case 'medical_provider':
            return '/dashboard-physician/';
            break;
        default:
            return '/wp-admin/';
    }
}


function custom_woocommerce_cart_is_empty_message($message)
{
    return 'Your Donation Cart is Empty.  <a href="/candidates/">Click here</a> to Donate.';
}

add_filter('woocommerce_cart_is_empty', 'custom_woocommerce_cart_is_empty_message');

/*
 * UPLOAD PRODUCT IMAGE IN DASHBOARDS
 * */

add_action('wp_ajax_upload_product_image', 'upload_product_image');
add_action('wp_ajax_nopriv_upload_product_image', 'upload_product_image');

function upload_product_image()
{
    $product_id = 53188;
    $response = array('status' => 'error');

    if (isset($_FILES['file'])) {
        $file = $_FILES['file'];
        $attachment_id = media_handle_upload('file', $product_id);

        if (!is_wp_error($attachment_id)) {
            update_post_meta($product_id, '_thumbnail_id', $attachment_id);
            $imageHTML = get_the_post_thumbnail($product_id);
            $response = array('status' => 'success', 'imageHTML' => $imageHTML);
        }
    }

    echo json_encode($response);
    wp_die();
}

/*
* DASHBOARDS DELETE ACCOUNT IMAGE
* */

add_action('wp_ajax_delete_product_image', 'delete_product_image');
add_action('wp_ajax_nopriv_delete_product_image', 'delete_product_image');

function delete_product_image()
{
    if (isset($_POST['product_id'])) {
        $product_id = sanitize_text_field($_POST['product_id']);

        delete_post_thumbnail($product_id);

        echo json_encode(array('success' => true));
    }
    wp_die();
}

/*
 * DASHBOARDS DELETE ACCOUNT
 * */

// if (isset($_POST['delete_account'])) {
//     $user_id = get_current_user_id();

//     if (wp_delete_user($user_id)) {
//         wp_redirect(wp_logout_url());
//         exit();
//     } else {
//         echo 'Error delete account';
//     }
// }

//function custom_shop_page_redirect() {
//    if( is_shop() ){
//        wp_redirect( home_url( '/product-category/coffee/' ) );
//        exit();
//    }
//}
//add_action( 'template_redirect', 'custom_shop_page_redirect' );


function my_custom_form_shortcode()
{
    // Your Formidable form code here
    echo FrmFormsController::get_form_shortcode(array('id' => 1, 'title' => false, 'description' => false));
}
add_shortcode('custom_form_shortcode', 'my_custom_form_shortcode');



/**
 * TESTER
 */












/**
 * DEBUG FUNCTIONS ON INIT
 */
add_action('wp', 'debug');
function debug()
{
    if (!defined('ABSPATH')) {
        echo "ABSPATH not defined<br>";
        exit; // Exit if accessed directly.
    }

    // check for WordPress security nonce
    if (isset($_GET['debugger'])) {

        // get product info from db for 59435 and fetch a candidate object and print_r it
        $product = wc_get_product(59435);
        $candidate = $product->get_meta('candidate');


        echo "<pre>";
        print_r($product);
        echo "</pre>";

        wp_die('Bye!');
    }
}

/**
 * FOOTER SUBSCRIPTION VALIDATION
 */
add_action('elementor_pro/forms/validation/email', function ($field, $record, $ajax_handler) {

    global $wpdb;
    $subscriptions = $wpdb->get_results(
        $wpdb->prepare(
            "
            SELECT value
            FROM wp_e_submissions_values
            "
        )
    );

    $emails = array_column($subscriptions, 'value');

    if (in_array($field['value'], $emails)) {
        $ajax_handler->add_error($field['id'], 'This email is already subscribed.');
    }

}, 10, 3);


function wpse_298888_posts_where($where, $query)
{
    global $wpdb;

    $starts_with = esc_sql($query->get('starts_with'));

    if ($starts_with) {
        $where .= " AND $wpdb->posts.post_title LIKE '$starts_with%'";
    }

    return $where;
}
add_filter('posts_where', 'wpse_298888_posts_where', 10, 2);

// Add a new column to the "All Candidates" page in WP Admin Dashboard
function add_custom_column_to_products_table($columns) {
    $columns['referred_person'] = __('Referred Person', 'text-domain'); // 'Referral' is the column header name
    return $columns;
}
add_filter('manage_product_posts_columns', 'add_custom_column_to_products_table');

function display_custom_column_content($column, $post_id) {
    if ($column == 'referred_person') {
        $referral = get_post_meta($post_id, '_referred_person', true);
        echo $referral ? esc_html($referral) : '-';
    }
}
add_action('manage_product_posts_custom_column', 'display_custom_column_content', 10, 2);


/////////////// Add custom validation for first and last names///////////
add_action('woocommerce_after_checkout_validation', 'custom_checkout_field_validation', 10, 2);
function custom_checkout_field_validation($data, $errors) {
    $first_name = isset($data['billing_first_name']) ? $data['billing_first_name'] : '';
    $last_name = isset($data['billing_last_name']) ? $data['billing_last_name'] : '';

    // Validation pattern
    $pattern = '/^[a-zA-Z\s\']+$/';

    if ( WC()->cart->total > 0 ) {
        // Validate first name
        if (!preg_match($pattern, $first_name)) {
            $errors->add('validation', __('Invalid first name. Only letters, spaces, and apostrophes are allowed.', 'woocommerce'));
        }

        // Validate last name
        if (!preg_match($pattern, $last_name)) {
            $errors->add('validation', __('Invalid last name. Only letters, spaces, and apostrophes are allowed.', 'woocommerce'));
        }
    }
}


//add_action( 'init', 'woocommerce_clear_cart_url' );
function woocommerce_clear_cart_url() {
  global $woocommerce;

    if ( isset( $_GET['empty-cart'] ) ) {
        $woocommerce->cart->empty_cart(); 
    }
}

function add_clear_all_link_to_mini_cart($fragments) {
    // Check if the cart is not empty
    if (WC()->cart->get_cart_contents_count() > 0) {
        // Check if the "Clear all" link has already been added
        if (!isset($fragments['div.clear-all-wrapper'])) {
            // Add the "Clear all" link after the main wrapper
            ob_start();
            ?>
          <div class="clear-all-wrapper">
            <a href="javascript:;"><?php _e( 'Clear All', 'woocommerce' ); ?></a>
        </div> 
            <?php
            $fragments['div.clear-all-wrapper'] = ob_get_clean(); 
        }
    } else {
        // Remove the "Clear all" link if the cart is empty
        $fragments['div.clear-all-wrapper'] = '<div class="clear-all-wrapper"></div>';
        //  if (isset($fragments['div.clear-all-wrapper'])) {
        //      unset($fragments['div.clear-all-wrapper']);
        //  }
    }

    return $fragments;
}

add_filter('woocommerce_add_to_cart_fragments', 'add_clear_all_link_to_mini_cart');

// PHP (functions.php or custom plugin)
add_action('wp_ajax_clear_cart', 'clear_cart_callback');
add_action('wp_ajax_nopriv_clear_cart', 'clear_cart_callback');

function clear_cart_callback() {
    WC()->cart->empty_cart();

    wp_send_json_success('Cart cleared successfully');
    wp_die();
}


// HotJar Tracking Code
function custom_header_code_tester()
{
    ?>
    <!-- Hotjar Tracking Code for CBC -->
    <script>
        (function(h,o,t,j,a,r){
            h.hj=h.hj||function(){(h.hj.q=h.hj.q||[]).push(arguments)};
            h._hjSettings={hjid:3857647,hjsv:6};
            a=o.getElementsByTagName('head')[0];
            r=o.createElement('script');r.async=1;
            r.src=t+h._hjSettings.hjid+j+h._hjSettings.hjsv;
            a.appendChild(r);
        })(window,document,'https://static.hotjar.com/c/hotjar-','.js?sv=');
    </script>
    <?php
}
add_action('wp_head', 'custom_header_code_tester');

// Hook into Formidable Forms after entry creation
//add_action('frm_after_create_entry', 'create_post_and_register_user', 50, 2);

function create_post_and_register_user($entry_id, $form_id) {
    global $frmdb, $wpdb;
    // Check if the form ID matches the form where user registration occurs
    if ($form_id == 3) { // Replace YOUR_REGISTRATION_FORM_ID with the actual form ID
        // Get the product ID from the form entry data
        //$product_id = absint($_POST['product_id_field']); // Replace 'product_id_field' with the actual field name for the product ID
        $product_id = $wpdb->get_var($wpdb->prepare("SELECT post_id FROM $frmdb->entries WHERE id=".$entry_id.""));
        // Check if the product ID is valid
        if ($product_id) {
            // Update user meta with the product ID during user registration
            add_action('user_register', function ($user_id) use ($product_id) {
                update_user_meta($user_id, 'product_id', $product_id);
            });
        }
    }
}


//add_action('frm_after_create_entry', 'capture_product_id', 50, 2);
function capture_product_id($entry_id, $form_id) {
    // Check if this is the form where the product is created (replace 'your_form_id' with the actual form ID)
    //if ($form_id == 3) {
        // Assuming you have the product ID in the entry meta under a specific field key (replace 'your_field_key' with the actual field key)
       // $product_id = FrmEntryMeta::get_entry_meta_value($entry_id, 'your_field_key');
        global $frmdb, $wpdb;
        // Save the product ID in a session variable or transient for later use
        // You can also save it in a custom database table if needed
        $product_id = $wpdb->get_var($wpdb->prepare("SELECT post_id FROM $frmdb->entries WHERE id=".$entry_id.""));
        // Set the value in session storage
        
        set_transient('new_product_id', $product_id, HOUR_IN_SECONDS); // Example: save for 1 hour
   // }
}

//add_action('user_register', 'save_product_id_user_meta', 20, 1);

function save_product_id_user_meta($user_id) {
    // Check if the new product ID is available in the transient or session
    //if ($product_id = get_transient('new_product_id')) {
        $product_id = get_transient('new_product_id');
        // Save the product ID as user meta for the newly registered user
        update_user_meta($user_id, 'product_id', $product_id);
        update_user_meta($user_id, '_rest', $product_id);
        update_user_meta($user_id, 'rest', $product_id);
        
        // Optionally, clear the transient or session variable after use
        delete_transient('new_product_id');
   // }
}


// Update user progress
function update_product_progress() {
    // Get all products
    $products = get_posts(array(
        'post_type' => 'product',
        'posts_per_page' => -1,
    ));

    // Loop through products
    foreach ($products as $product) {
        $raised = intval(get_post_meta($product->ID, '_amount_raised', true));
        $goal = intval(get_post_meta($product->ID, '_goal', true));
        $progress = ($goal > 0 ? ($raised*100  / $goal): 0);
        update_post_meta($product->ID, '_progress', $progress);
    }
}

add_action('init', 'update_product_progress');

// Add bonus to advocate after candidate registered

function add_bonus_to_affiliate_on_login( $user_login, $user ) {
    if ( ! function_exists( 'affiliate_wp' ) ) {
        error_log( 'AffiliateWP is not active.' );
        return;
    }

    $affiliate_id = affiliate_wp()->tracking->get_affiliate_id();


    if ( $affiliate_id ) {

        $bonus_amount = 5;
        $current_balance = affwp_get_affiliate_unpaid_earnings( $affiliate_id );
        $new_balance = $current_balance + $bonus_amount;

        affwp_increase_affiliate_unpaid_earnings( $affiliate_id, $bonus_amount );

    } else {

        error_log( 'No affiliate ID found for user login.' );
    }
}
add_action( 'wp_login', 'add_bonus_to_affiliate_on_login', 10, 2 );

function add_bonus_to_affiliate_on_registration( $user_id ) {

    if ( ! is_user_logged_in() ) {
        return;
    }


    $user = get_userdata( $user_id );
    $user_login = $user->user_login;

    add_bonus_to_affiliate_on_login( $user_login, $user );
}
add_action( 'user_register', 'add_bonus_to_affiliate_on_registration' );

// Add notes for physician dashboard

function handle_add_procedure_note() {
    // Verify nonce for security
   // check_ajax_referer('acf_form', 'security');

    // Get the product ID from the AJAX request
    $product_id = intval($_POST['candidate_id']);
    $note_title = sanitize_text_field($_POST['note_title']);
    $note_text = sanitize_textarea_field($_POST['note_text']);
    $attachments = $_FILES['attachments'];

    // Current date and time
    $note_created = current_time('mysql');

    // Get the current repeater field values
    $procedure_notes = get_field('procedure_notes', $product_id);

    if (!$procedure_notes) {
        $procedure_notes = [];
    }

    // Handle file uploads
    $uploaded_files = [];
    if (!empty($attachments['name'][0])) {
        require_once(ABSPATH . 'wp-admin/includes/file.php');
        require_once(ABSPATH . 'wp-admin/includes/media.php');
        require_once(ABSPATH . 'wp-admin/includes/image.php');

        for ($i = 0; $i < count($attachments['name']); $i++) {
            if ($attachments['error'][$i] === UPLOAD_ERR_OK) {
                $file = [
                    'name'     => $attachments['name'][$i],
                    'type'     => $attachments['type'][$i],
                    'tmp_name' => $attachments['tmp_name'][$i],
                    'error'    => $attachments['error'][$i],
                    'size'     => $attachments['size'][$i],
                ];

                // Upload the file and get the attachment ID
                $attachment_id = media_handle_sideload($file, 0);
                if (is_wp_error($attachment_id)) {
                    wp_send_json_error(['message' => $attachment_id->get_error_message()]);
                } else {
                    $uploaded_files[] = ['file' => $attachment_id];
                }
            }
        }
    }

    // Prepare the new note data
    $new_note = [
        'note_title' => $note_title,
        'note_text' => $note_text,
        'attachments' => $uploaded_files,
        'note_created' => $note_created,
    ];

    // Append the new note to the repeater field
    $procedure_notes[] = $new_note;
    update_field('procedure_notes', $procedure_notes, $product_id);

    wp_send_json_success(['message' => 'Note added successfully']);
}
add_action('wp_ajax_add_procedure_note', 'handle_add_procedure_note');
add_action('wp_ajax_nopriv_add_procedure_note', 'handle_add_procedure_note');