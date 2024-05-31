<?php get_header() ?>
<?php
    if ( ! defined( 'ABSPATH' ) ) {
        exit; // Exit if accessed directly.
    }

    if (!is_user_logged_in()) {
        wp_redirect(home_url('/login'));
        exit;
    }
?>

<style>

    .navbar {
        padding: 18px 80px;
    }

    .mainBg {
        background: #F5F5F5 !important;
    }

    a.page-link.active {
        background: #143A62;
        color: white;
        border-radius: 100%;
    }

    .dashboard, .bg-white {
        background: white;
    }

    .table :where(th, td) {
        border: 0 !important;
    }

    .rounded-xl {
        border-radius: 0.75rem !important;
    }

    .border-borderColor {
        border-color: #EBEBEB !important;
    }

    .progress::-webkit-progress-value {
        background: #143A62;
    }

    .text-primary {
        color: #143A62;
    }

    .bg-primary {
        background: #143A62 !important;
    }

    .text-info {
        color: #8497AB !important;
    }

    .bg-accentBg {
        background: #F3E6F6;
    }

    .bg-primary.bg-opacity-5 {
        background: rgba(20, 58, 98, 0.03) !important;
    }

    .checkbox-primary:checked {
        border-color: transparent !important
    }

    [type=button]:focus, [type=button]:hover, [type=submit]:focus, [type=submit]:hover, button:focus {
        background-color: unset !important;
        outline: none;
        color: #333;
    }

    .toggle {
        box-shadow: var(--handleoffsetcalculator) 0 0 2px white inset, 0 0 0 2px white  inset, var(--togglehandleborder);
    }

    .toggle-primary:checked {
        background: #143A62;
    }

    #accordionButton.active svg{
        transform:rotate(90deg)
    }

    .accent:after {
        content: '1';
        width: 310px;
        background-size: contain;
        /*background-image: url(https://staging.childfreebc.com/wp-content/uploads/2023/10/title-line__long.svg);*/
        background-image: url(https://childfreebc.com/wp-content/uploads/2023/10/title-line__long.svg);
        position: absolute;
        color: transparent;
        background-repeat: no-repeat;
        right: 10%;
        top: 100%;
    }

    .acc-avatar img {
        object-fit: cover;
        width: 100% !important;
        height: 100%;
        border-radius: 6px !important;
        object-position: right center;
    }

    .shadowCard {
        box-shadow: 0px 0px 12px 0px rgba(52, 64, 77, 0.11);
    }

    .badgeM {
        background: #EDF3FE;
    }

    .badgeF {
        background: #F3E6F6;
    }

    .reqCards-scroll::-webkit-scrollbar {
        width: 6px;  
    }

    .reqCards-scroll::-webkit-scrollbar-thumb {
        background-color: #143A62;  
        border-radius: 6px; 
    }

    .reqCards-scroll::-webkit-scrollbar-track {
        background-color: #eee; 
        border-radius: 6px;
    }

    /* Restyle table */

    .table tr.active, .table tr.active:nth-child(even), .table-zebra tbody tr:nth-child(even) {
        background: white;
    }

    .table :where(thead, tbody) :where(tr:not(:last-child)), .table :where(thead, tbody) :where(tr:first-child:last-child) {
        border: 0px;
    }

    @media screen and (max-width:1024px) {
        .accent:after {
            background-image: unset;
        }
        
        .navbar {
            padding: 18px 16px;
        }
    }

    @media screen and (max-width: 490px) {
        .mobMenuBtn {
            top: 20px;
        }        
    }
</style>

<?php

    $background_image_url = 'https://childfreebc.com/wp-content/uploads/2023/11/foo1.svg';
    $user = wp_get_current_user();
    $user_name = $user->user_nicename;
    $user_id = $user->ID;
//    // get user post meta data
   $user_meta = get_user_meta($user->ID);
//    echo "<pre>";
//    print_r($user_meta);
//    echo "</pre>";
//    die();

    $requests = [];

    $candidate_keys = preg_grep('/^candidates_for_physicians_\d+_candidate$/', array_keys($user_meta));

    foreach ($candidate_keys as $candidate_key) {
        preg_match('/(\d+)/', $candidate_key, $matches);
        $index = $matches[0];
        
        $object = new stdClass();
        $object->{"candidates_for_physicians_{$index}_candidate"} = $user_meta[$candidate_key];

        $status_key = "candidates_for_physicians_{$index}_candidate_status";
        $procedure_key = "candidates_for_physicians_{$index}_procedure_status";
        
        if (isset($user_meta[$status_key])) {
            $object->{$status_key} = $user_meta[$status_key];
        }
        
        if (isset($user_meta[$procedure_key])) {
            $object->{$procedure_key} = $user_meta[$procedure_key];
        }

        $requests[] = $object;
    }

    echo "<script>";
    echo "console.log('User meta:', " . json_encode($user_meta) . ");";
    echo "console.log('Requests from phys cab:', " . json_encode($requests) . ");";
    echo "</script>";

    // REQUESTS from physician
    $user_meta = get_user_meta($user->ID);
    $requestsPhys = [];
    foreach (array_keys($user_meta) as $key) {
        if (preg_match('/^physicians_for_candidate_(\d+)_/', $key, $matches)) {
            $index = $matches[1];

            if (!isset($requestsPhys[$index])) {
                $requestsPhys[$index] = new stdClass();
            }

            if (preg_match('/^physicians_for_candidate_\d+_(\w+)$/', $key, $propertyMatch)) {
                $property = $propertyMatch[1];
                $requestsPhys[$index]->{$property} = $user_meta[$key][0] ?? '';
            }
        }
    }

    echo "<script>";
    echo "console.log('Requests to phys cab:', " . json_encode($requestsPhys) . ");";
    echo "</script>";

    $ids = array_column($requestsPhys, 'physician');

// Remove duplicate physicians
$unique_ids = array_unique($ids);
$unique_phs = array();


foreach ($requestsPhys as $object) {
    if (in_array($object->physician, $unique_ids)) {
        $unique_phs[] = $object;

        $key = array_search($object->physician, $unique_ids);
        unset($unique_ids[$key]);
    }
}




    // Switch account by roles

    $userRoles = $user->roles;
    $roleMask = [
        'subscriber' => 'Donor',
        'candidate' => 'Candidate',
        'customer' => 'Advocate',
        'medical_provider' => 'Physician'
    ];

    $options = '';

    // Extract role from the URL
    $url = rtrim($_SERVER['REQUEST_URI'], '/');
    $parts = explode('-', $url);
    $selectedRole = ucfirst(strtolower(end($parts)));

    foreach ($userRoles as $userRole) {
        if (array_key_exists($userRole, $roleMask)) {
            $roleName = array_key_exists($userRole, $roleMask) ? $roleMask[$userRole] : $userRole;
            $options .= '<option value="' . $userRole . '"';

            if ($roleName === $selectedRole) {
                $options .= ' selected="selected"';
            }

            $options .= '>' . $roleName . '</option>';
        }
    }
?>


 <?php

global $wpdb;
$candidate_id = $wpdb->get_var("
            SELECT ID FROM $wpdb->posts 
            WHERE post_title != 'Product' 
            AND post_type = 'product' 
            AND post_author = $user_id"
        );
//echo "CandidateID: $candidate_id<br>";

//$args = array(
//    'post_type'      => 'product',
//    'posts_per_page' => -1,
//);

//$products = new WP_Query($args);
$catch_item = false;
$amount_raised = 0;
$stripeChecker = do_shortcode('[user_stripe_check]');

//if ($products->have_posts()) {
if ($candidate_id !== null && $candidate_id !== '' && $candidate_id !== 0) {
//    $product_list = array();

//    foreach ($products->posts as $post) {
//        setup_postdata($post);
//        $post_author = $post->post_author;

//        if ($post_author == $user_id) {
            // get product id from user id
//            $product = wc_get_product($product_id);
            $product_id = $candidate_id;
//            echo "ProductID: $product_id<br>";
            $product = wc_get_product($candidate_id);
            $product_slug = $product->get_slug();
//            echo "<pre>";
//            print_r($product);
//            echo "</pre>";
            $catch_item = true;

            if ($product) {
                $product_id = $product->get_id();
//                echo "P:$product_id U:$user_id <br>";
                $goal = (int) get_post_meta($product_id, '_goal', true);
//                $goal = $product->get_goal();
//                $amount_raised = do_shortcode('[candidate_amount_raised]');
//                $amount_raised = get_post_meta($product_id, '_amount_raised', true);
//                $amount_raised = $product->get_amount_raised();
                $amount_raised = (int) get_post_meta($product_id, '_amount_raised', true);
//                echo "Amount Raised: $amount_raised<br>";
                $honorarium = $product->get_honorarium();
                $sex = $product->get_sex();
                $date_of_birth = $product->get_date_of_birth();
                $age = $product->get_age();
                $location = $product->get_location();
                $latitude = $product->get_latitude();
                $longitude = $product->get_longitude();
                $interested_providers = $product->get_interested_providers();
                $saved_providers = $product->get_saved_providers();
                $provider = $product->get_provider();
                $user_id = $product->get_user_id();
                $referred_page_url = $product->get_referred_page_url();
                $referred_person = $product->get_referred_person();
                $person_user_id = $product->get_person_user_id();
                $user_email = $product->get_user_email();
                $user_image = $product->get_image();

                $product_permalink = get_permalink($product_id);

//                $amount_remaining = do_shortcode('[candidate_remaining_amount]');
                $amount_remaining = $product->get_amount_remaining();
                $progress_for_account = $product->calculate_progress_for_account();

                $firstValue = explode(' ', $person_user_id);
                $secondValue = explode(' ', $person_user_id);
                $candidate_progress = do_shortcode('[candidate_progress]');

                $recent_donations = do_shortcode("[dashboard_candidate_recent_donations user_id='$product_id']");
                $recent_donations_view = do_shortcode("[dashboard_candidate_recent_donations_view user_id='$product_id']");

                $honorarium = $product->get_honorarium();
                $product_meta_data = $product->get_meta_data();
                $acc_receive = null;

                foreach ($product_meta_data as $meta) {
                    if ($meta->key === '_affwp_woocommerce_product_rate') {
                        $acc_receive = $meta->value;
                        break;
                    }
                }

                $product_image_id = get_post_thumbnail_id($product_id);
                $product_image_url = wp_get_attachment_url($product_image_id);
                $product_list[] = $product;
            }
//        }
//    }
}
?>


<?php

    $argsRef = array(
        'post_type'      => 'product',
        'posts_per_page' => -1,
    );

    $productsRef = new WP_Query($argsRef);
    $totalRefCount = 0;
    $totalRefSucceed = 0;

    $ref_product_raised = 0;
    $ref_product_goal = 0;
    $ref_user_image = '/wp-content/uploads/2023/10/User-1-1.svg';
    $ref_user_name = 'no-ref';
    $formatted_ref_order_date = 'no-ref';
    $foundRef = false;

        if ($productsRef->have_posts()) {
            while ($productsRef->have_posts()) {
                $productsRef->the_post();
                $product_id = get_the_ID();

                $refproduct = wc_get_product($product_id);
                $referredPerson = $refproduct->get_referred_person();

                if ($referredPerson === $user_name) {
                    $totalRefCount++;
                    $ref_product_raised = $refproduct->amount_raised;
                    $ref_product_goal = $refproduct->goal;
                    $ref_user_image = $refproduct->get_image();
                    $ref_user_name = $refproduct->get_name();
                    $ref_date = $refproduct->get_date_modified();
                    $formatted_ref_order_date = $ref_date->format('j F, g:i A');

                    $honorarium = $refproduct->get_honorarium();
                    $foundRef = true;

                    if ($ref_product_raised >= $ref_product_goal) {
                        $totalRefSucceed++;
                    }
                }
            }
            wp_reset_postdata();
        }
?>

<?php

if(in_array('administrator', $userRoles)){
    $catch_item =  true;
}

if($catch_item === false) {
    ?>
    <div class="my-12 flex justify-center items-center flex-col gap-2">
        <img src="/wp-content/uploads/2023/12/Placeholder.svg" alt="waiting">
        <span class="text-textValue max-w-xl text-center">
            Your profile is not public yet, and being reviewed. You will get notified once it is approved by the Admin.<br>
            If you have any questions - please send us an email to <a href="mailto: info@childfreebc.com" style="text-decoration:underline;">info@childfreebc.com</a>
        </span>
    </div>
    <?php
} else {
    ?>

    <main class="bg-mainBg py-6 px-4 xl:px-20 xl:py-8">
        <div class="screenHeader flex w-full mb-12">
            <h1 class="text-4xl font-normal flex flex-wrap gap-2 lg:gap-3 lg:text-6xl font-normal text-primary relative justify-center md:justify-start" data-id = "<?php echo ($product_id);?>">
                My
                <span>Donations</span>
                and
                <span class="accent">Candidate Account</span>
            </h1>
        </div>

        <div class="dashboard py-6 px-3 lg:px-6 rounded-xl shadow-lg relative">
            <div class="dropdown xl:hidden absolute t-5 mobMenuBtn">
                <label tabindex="0" class="btn btn-ghost btn-circle text-primary">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7" /></svg>
                </label>
                <ul tabindex="0" class="menu menu-sm dropdown-content mt-3 z-[1] p-2 shadow rounded-box w-52 bg-white">
                    <li class="p-2 w-full menuItem cursor-pointer hover:text-primary">Dashboard</li>
                    <li class="p-2 w-full menuItem cursor-pointer hover:text-primary">My Donations</li>
                    <li class="p-2 w-full menuItem cursor-pointer hover:text-primary">My Candidate Profile</li>
                    <li class="p-2 w-full menuItem cursor-pointer hover:text-primary">Physicians</li>
                    <li class="p-2 w-full menuItem cursor-pointer hover:text-primary">Notifications</li>
                    <li class="p-2 w-full menuItem cursor-pointer hover:text-primary">Influencer Referrals</li>
                    <li class="p-2 w-full menuItem cursor-pointer hover:text-primary">Account Details</li>
                    <li class="p-2 w-full menuItem cursor-pointer hover:text-primary">Share Profile</li>
                    <li class="p-2 w-full menuItem cursor-pointer hover:text-primary">Switch Account</li>
                    <button class="pt-4 px-2 text-left border-0 hover:bg-transparent hover:text-primary" onclick="logout.showModal()">Log Out</button>
                </ul>
            </div>

            <div class="flex w-full">
                <div class="w-1/4 pr-8 border-r border-borderColor hidden xl:flex" style="width: 282px;">
                    <ul class="flex flex-col gap-4 text-textColor text-base font-normal w-full">
                        <li><a href="#tab1" class="p-2 w-full flex">Dashboard</a></li>
                        <li><a href="#tab2" class="p-2 w-full flex">My Donations</a></li>
                        <li><a href="#tab3" class="p-2 w-full flex">My Canditate Profile</a></li>
                        <li><a href="#tab4" class="p-2 w-full flex">Physicians</a></li>
                        <li><a href="#tab5" class="p-2 w-full flex">Notifications</a></li>
                        <li class="pb-4" style="border-bottom: 1px solid #EBEBEB;"><a href="#tab6" class="p-2 w-full flex" style="">Influencer Referrals</a></li>
                        <li><a href="#tab7" class="p-2 w-full flex">Account Details</a></li>
                        <li><a href="#tab8" class="p-2 w-full flex">Share Profile</a></li>
                        <li class="pb-4" style="border-bottom: 1px solid #EBEBEB;"><a href="#tab9" class="p-2 w-full flex">Switch Account</a></li>
                        <button class="px-2 text-left hidden xl:flex border-0 hover:bg-transparent hover:text-primary" onclick="logout.showModal()">Log Out</button>
                    </ul>
                </div>
                <div class="w-full xl:pl-8">
                    <div id="tab1" class="hidden tabContent">
                        <div class="w-full h-full flex flex-col gap-8">
                            <h1 class="text-3xl font-medium text-textColor pl-20 xl:p-0">Dashboard</h1>
                            <div class="w-full h-full flex flex-col gap-6">
                                <div class="cardRow w-full flex gap-6 flex-wrap xl:flex-nowrap">

                                    <div class="cardItem px-6 py-5 flex flex-col sm:flex-row gap-6 items-center bg-card1 rounded-xl w-full xl:max-w-3xl shadow-shadowItem">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="80" height="80" viewBox="0 0 80 80" fill="none">
                                            <path d="M40 67C54.9117 67 67 54.9117 67 40C67 25.0883 54.9117 13 40 13C25.0883 13 13 25.0883 13 40C13 54.9117 25.0883 67 40 67Z" fill="#FCDBA6"></path>
                                            <path d="M42.312 72.1403C41.6356 72.141 40.9634 72.0371 40.3214 71.8326C36.1285 70.5922 32.0406 69.045 28.0937 67.2044C27.9677 67.1448 27.8473 67.0748 27.7339 66.9952L20.8554 62.2993C20.4073 61.9757 20.086 61.5163 19.9433 60.9955C19.7947 60.5863 19.7849 60.1424 19.9154 59.7275C20.0458 59.3126 20.3098 58.9482 20.6693 58.6865L21.9496 60.2194C21.9207 60.2408 21.8939 60.2648 21.8697 60.2911C21.878 60.2943 21.885 60.3608 21.9246 60.5073C21.9504 60.5965 22.0067 60.6747 22.0844 60.7293L28.9296 65.4009L28.9985 65.4419C32.8605 67.2411 36.86 68.7537 40.9622 69.9664C41.7164 70.1998 42.5218 70.2354 43.2951 70.0694C51.0387 68.5646 55.2701 67.4693 61.6752 65.8117L64.6805 65.035C66.3028 64.6888 67.9847 64.6915 69.6059 65.0427L72.8479 65.8334L72.3445 67.7386L69.1009 66.9473C67.8192 66.6823 66.4941 66.6775 65.2104 66.9332L62.2071 67.7092C55.7644 69.3771 51.5077 70.4788 43.7195 71.9919C43.2573 72.0902 42.7854 72.14 42.312 72.1403Z" fill="#003366"></path>
                                            <path d="M36.7983 64.2267L32.3432 63.3061C31.9915 63.2324 31.6603 63.0883 31.3717 62.8835C29.7983 61.8483 28.1241 60.9592 26.372 60.2287C25.5647 59.9218 24.6944 59.7942 23.8274 59.8555C23.1308 59.889 22.4611 60.122 21.9062 60.5239L20.6133 59.016C21.4873 58.364 22.5491 57.9802 23.6578 57.9154C24.8452 57.833 26.0367 58.0073 27.143 58.4255C29.0576 59.217 30.8845 60.188 32.5967 61.3242C32.6532 61.3641 32.718 61.392 32.7868 61.4059L37.2326 62.3247L36.7983 64.2267Z" fill="#003366"></path>
                                            <path d="M39.1022 65.809C38.5989 65.811 38.1013 65.7041 37.6445 65.4958C37.1877 65.2875 36.7828 64.983 36.4584 64.6036C36.134 64.2243 35.898 63.7794 35.767 63.3003C35.636 62.8212 35.6132 62.3197 35.7003 61.831C35.9101 60.8673 36.4938 60.0227 37.3268 59.4776C38.3599 58.8032 39.4832 58.2738 40.6643 57.9046C41.7129 57.5207 42.878 57.1949 44.1113 56.8502C46.3937 56.2125 48.9808 55.4898 50.5164 54.4681C56.6999 50.3351 63.4728 49.6646 70.6435 52.4771C71.7333 52.9036 72.5951 53.3189 73.3554 53.6852C74.4505 54.2577 75.606 54.7102 76.8011 55.0345L76.2923 56.9797C74.9637 56.6229 73.6788 56.1235 72.4608 55.4904C71.6978 55.1228 70.9086 54.7428 69.8908 54.3445C63.3846 51.791 57.2506 52.3941 51.6582 56.1321C49.8506 57.3349 47.0966 58.1047 44.667 58.7837C43.4731 59.1172 42.3455 59.4319 41.432 59.7654L41.3636 59.7916C40.3671 60.1015 39.4164 60.5398 38.5363 61.0951C38.127 61.3413 37.8295 61.7334 37.7056 62.1901C37.6643 62.4245 37.6867 62.6655 37.7703 62.8886C37.8923 63.2284 38.1432 63.5083 38.4702 63.6695C38.7973 63.8306 39.175 63.8604 39.5238 63.7526L53.4084 60.6968L53.8523 62.6577L39.9677 65.7135C39.6837 65.7767 39.3934 65.8087 39.1022 65.809Z" fill="#003366"></path>
                                            <path d="M37.6721 61.0613L35.6097 60.6952C35.2678 60.6347 34.9429 60.5032 34.6569 60.3095C33.2196 59.3991 31.7019 58.6175 30.1222 57.9741C29.9755 57.9151 29.8405 57.8618 29.7187 57.818C29.4577 57.7265 29.1903 57.6534 28.9186 57.5992C28.3287 57.45 27.7128 57.4279 27.1133 57.5344C26.5138 57.6408 25.9448 57.8733 25.4454 58.2158C25.1801 58.4626 24.9709 58.7616 24.8313 59.0933L22.9883 58.3713C23.2084 57.8329 23.5344 57.3425 23.9476 56.9279C24.0253 56.8464 24.1088 56.7703 24.1973 56.7002C24.9241 56.1773 25.758 55.8161 26.6411 55.6415C27.5243 55.4668 28.4356 55.483 29.3118 55.6889C29.6806 55.7622 30.0435 55.8616 30.3977 55.9864C30.5453 56.0397 30.7029 56.1019 30.8761 56.1717C32.5869 56.8669 34.2293 57.7146 35.7825 58.7043C35.8363 58.7406 35.8973 58.7652 35.9615 58.7767L38.0248 59.1427L37.6721 61.0613Z" fill="#003366"></path>
                                            <path d="M40.7528 59.4779C39.1914 58.5289 37.5389 57.7081 35.815 57.0251C34.148 56.4251 32.3069 56.5283 31.2264 57.2862L31.202 57.3273L29.3164 56.4568C29.441 56.2192 29.6203 56.0083 29.8425 55.8382C31.5298 54.6071 34.1846 54.3833 36.6162 55.2569C38.4902 55.9955 40.2852 56.8872 41.9788 57.921L40.7528 59.4779Z" fill="#003366"></path>
                                            <path d="M28.4605 39.6926C27.1018 39.6926 25.5204 38.4371 24.9539 37.9456C20.2636 34.2882 7.12676 24.5079 3.20312 23.584L3.68646 21.6814C8.85354 22.8986 25.5657 35.9039 26.2748 36.4567C26.8669 36.9996 27.561 37.4288 28.3201 37.7211C28.6149 37.3374 28.8277 36.9013 28.9464 36.4379C29.065 35.9746 29.0871 35.4931 29.0113 35.0215C28.4472 31.2538 22.5631 27.1546 20.348 25.611C17.6397 23.7262 18.0287 21.9256 18.5017 21.0208C18.7573 20.5467 19.1282 20.1393 19.5828 19.8333C20.0375 19.5273 20.5623 19.3318 21.1124 19.2635L32.295 17.7269C32.4912 17.6985 32.6897 17.688 32.8879 17.6956C33.2424 17.7035 33.5936 17.7637 33.9291 17.8742L50.7164 23.3831C52.4641 23.9047 53.7803 23.0331 54.111 22.2762C54.2551 21.946 54.1511 21.8421 54.0452 21.7808L34.0417 14.046C33.1478 13.6991 32.2058 13.4808 31.2459 13.3982C26.9157 12.9582 22.5616 12.7744 18.2081 12.8479C14.7789 12.9382 11.3811 12.198 8.32616 10.6953L6.45704 9.78544L7.37472 8.03711L9.24384 8.94698C11.9967 10.2966 15.0554 10.9645 18.1435 10.8904C22.5789 10.8123 27.0151 10.9982 31.4267 11.4471C32.5851 11.5468 33.722 11.8102 34.8008 12.2288L54.8629 19.9866C54.8983 20.0001 54.9329 20.0157 54.9665 20.0331C55.5094 20.3066 55.9199 20.7736 56.1104 21.3343C56.3009 21.8951 56.2563 22.5054 55.9862 23.035C55.2323 24.7629 52.8196 26.0617 50.0868 25.2456L33.2756 19.729C33.1321 19.682 32.9819 19.6566 32.8304 19.6538C32.7483 19.6497 32.666 19.6536 32.5847 19.6652L21.3978 21.2031C21.1728 21.2271 20.9571 21.3031 20.7692 21.4246C20.5812 21.5462 20.4266 21.7098 20.3186 21.9013C20.1981 22.1315 19.8309 22.8342 21.5401 24.024C24.1727 25.8584 30.3375 30.1533 31.0245 34.7422C31.1446 35.5108 31.0971 36.295 30.885 37.0449C30.6729 37.7948 30.3009 38.494 29.7926 39.0977C29.7747 39.12 29.7559 39.1417 29.736 39.1621C29.5756 39.3338 29.3786 39.4701 29.1584 39.5616C28.9383 39.6532 28.7002 39.6979 28.4605 39.6926Z" fill="#003366"></path>
                                            <path d="M47.1627 28.6103C46.1766 28.5971 45.2201 28.2551 44.4317 27.6337L32.4844 19.2773L33.5939 17.5342L45.5771 25.9172C45.8862 26.1971 46.2609 26.3855 46.6626 26.4629C47.0643 26.5403 47.4784 26.504 47.8622 26.3577C47.8225 26.2992 47.7776 26.2449 47.7279 26.1954L44.7579 23.4927L46.0684 21.9105L49.0801 24.6541C50.2212 25.8176 49.9861 26.9906 49.4508 27.6535C49.1565 27.9748 48.8001 28.2264 48.4055 28.3915C48.0109 28.5565 47.5871 28.6311 47.1627 28.6103Z" fill="#003366"></path>
                                            <path d="M30.1141 18.3991L28.6992 19.8145L30.4618 21.4649L31.8767 20.0495L30.1141 18.3991Z" fill="#003366"></path>
                                            <path d="M42.1756 30.1936C41.3729 30.1718 40.5827 30.0013 39.8503 29.6921C39.1179 29.3829 38.4577 28.941 37.9075 28.3918L30.1094 21.3086L31.5522 19.9082L39.3608 27.0009C39.7338 27.3866 40.1875 27.696 40.6935 27.9098C41.1996 28.1236 41.7471 28.2371 42.3019 28.2433C42.5243 28.2406 42.7443 28.1997 42.9511 28.1228L38.3044 23.2734L39.8465 21.9695L44.6496 26.9774C44.8202 27.1497 44.9519 27.3527 45.0367 27.5741C45.1214 27.7955 45.1575 28.0308 45.1427 28.2657C45.1279 28.5005 45.0625 28.7301 44.9505 28.9406C44.8386 29.1511 44.6823 29.3381 44.4912 29.4903C43.8332 29.9763 43.0121 30.2257 42.1756 30.1936Z" fill="#003366"></path>
                                            <path d="M33.1874 29.4036C32.5406 29.3976 31.9034 29.2439 31.3224 28.9535C30.7414 28.6632 30.2315 28.2437 29.8301 27.7259C29.793 27.6777 29.7602 27.6263 29.7322 27.5721L27.4586 23.1582L24.5703 20.6297L25.8416 19.1162L28.8712 21.7687C28.9648 21.8506 29.0423 21.95 29.0997 22.0614L31.415 26.5557C31.8178 27.0332 32.3855 27.3336 32.9999 27.3942C33.2052 27.4198 33.4134 27.4048 33.6132 27.3501L30.3538 21.4059L31.1998 20.9217L31.8932 20.2111L31.8958 20.2137C31.9591 20.2782 32.0134 20.3514 32.0572 20.4311L35.418 26.557C35.6457 26.9618 35.7087 27.4416 35.5936 27.8933C35.4785 28.345 35.1944 28.7325 34.8025 28.9726C34.3142 29.2676 33.7546 29.4169 33.1874 29.4036Z" fill="#003366"></path>
                                            <path d="M46.6581 36.5273C45.0531 36.5273 43.6478 37.2229 42.7718 38.3987C41.8958 37.2229 40.4905 36.5273 38.8855 36.5273C37.6078 36.5288 36.3829 37.0409 35.4795 37.9514C34.576 38.8619 34.0678 40.0964 34.0664 41.384C34.0664 46.8674 42.1336 51.3058 42.4772 51.4891C42.5677 51.5382 42.669 51.5639 42.7718 51.5639C42.8746 51.5639 42.9758 51.5382 43.0664 51.4891C43.4099 51.3058 51.4772 46.8674 51.4772 41.384C51.4757 40.0964 50.9675 38.8619 50.0641 37.9514C49.1607 37.0409 47.9358 36.5288 46.6581 36.5273ZM42.7718 50.2201C41.3525 49.3866 35.31 45.5898 35.31 41.384C35.3113 40.4288 35.6884 39.513 36.3586 38.8375C37.0289 38.162 37.9376 37.7819 38.8855 37.7807C40.3972 37.7807 41.6665 38.5922 42.1966 39.8957C42.2435 40.0106 42.3231 40.1089 42.4256 40.1781C42.528 40.2473 42.6485 40.2843 42.7718 40.2843C42.8951 40.2843 43.0156 40.2473 43.118 40.1781C43.2204 40.1089 43.3001 40.0106 43.347 39.8957C43.8771 38.5899 45.1463 37.7807 46.6581 37.7807C47.606 37.7819 48.5147 38.162 49.185 38.8375C49.8552 39.513 50.2323 40.4288 50.2335 41.384C50.2335 45.5835 44.1895 49.3859 42.7718 50.2201Z" fill="#FFADDE"></path>
                                            <path d="M42.7718 50.2201C41.3525 49.3866 35.31 45.5898 35.31 41.384C35.3113 40.4288 35.6884 39.513 36.3586 38.8375C37.0289 38.162 37.9376 37.7819 38.8855 37.7807C40.3972 37.7807 41.6665 38.5922 42.1966 39.8957C42.2435 40.0106 42.3231 40.1089 42.4256 40.1781C42.528 40.2473 42.6485 40.2843 42.7718 40.2843C42.8951 40.2843 43.0156 40.2473 43.118 40.1781C43.2204 40.1089 43.3001 40.0106 43.347 39.8957C43.8771 38.5899 45.1463 37.7807 46.6581 37.7807C47.606 37.7819 48.5147 38.162 49.185 38.8375C49.8552 39.513 50.2323 40.4288 50.2335 41.384C50.2335 45.5835 44.1895 49.3859 42.7718 50.2201Z" fill="#FFADDE"></path>
                                            <path d="M46.6581 36.394H46.6583C47.9716 36.3955 49.2304 36.922 50.1587 37.8575C51.087 38.7931 51.609 40.0613 51.6105 41.3839V41.384C51.6105 44.1877 49.5526 46.7026 47.4888 48.5241C45.4182 50.3516 43.3054 51.5127 43.1291 51.6068C43.0193 51.6661 42.8965 51.6972 42.7718 51.6972C42.6469 51.6972 42.524 51.6661 42.4141 51.6066C42.414 51.6065 42.4138 51.6064 42.4136 51.6063L42.4772 51.4891C42.1336 51.3058 34.0664 46.8674 34.0664 41.384L46.6581 36.394ZM46.6581 36.394C45.0771 36.394 43.6795 37.0534 42.7718 38.181L46.6581 36.394ZM42.0731 39.9459L42.0731 39.946C42.1299 40.0853 42.2265 40.2046 42.3509 40.2886C42.4753 40.3727 42.6218 40.4176 42.7718 40.4176C42.9217 40.4176 43.0682 40.3727 43.1926 40.2886C43.317 40.2046 43.4137 40.0853 43.4704 39.946L43.4705 39.9459C43.9782 38.6951 45.1952 37.914 46.658 37.914C47.5702 37.9152 48.445 38.281 49.0903 38.9314C49.7357 39.5818 50.099 40.4639 50.1002 41.3842C50.1001 43.4242 48.6275 45.3905 46.9432 46.9667C45.3164 48.489 43.5223 49.6195 42.7717 50.0653C42.0204 49.62 40.2263 48.4909 38.5998 46.9693C36.9157 45.3939 35.4434 43.4273 35.4434 41.3841C35.4446 40.4638 35.8079 39.5818 36.4533 38.9314C37.0986 38.281 37.9733 37.9152 38.8855 37.914C40.3482 37.914 41.5653 38.6973 42.0731 39.9459Z" fill="#FFADDE" stroke="" stroke-width="0.266667"></path>
                                        </svg>

                                                <div class="flex flex-col gap-2 w-full sm:w-3/4">
                                                    <h3 class="text-base text-primary">Progress Towards Goal</h3>
                                                    <progress class="progress w-full progress-primary" value="<?php echo $candidate_progress; ?>" max="100"></progress>
                                                    <div class="w-full justify-between flex items-center">
                                                        <span class="text-textColor text-base font-semibold" id="amount_raised">
                                                            $<?php
                                                                if($amount_raised > $goal) {
                                                                    echo number_format($goal);
                                                                } else if($amount_raised == '' || $amount_raised == null) {
                                                                    echo 0;
                                                                } else {
                                                                    echo number_format($amount_raised);
                                                                }
                                                            ?> raised
                                                        </span>
                                                        <span class="text-textValue text-base font-semibold">of $<?php echo number_format($goal) ?></span>
                                                    </div>
                                                </div>
                                    </div>
                                    <div class="cardItem px-6 py-5 flex gap-6 items-center bg-card2 rounded-xl w-full lg:max-w-sm shadow-shadowItem">
                                        <div class="flex flex-col gap-2">
                                            <h3 class="text-base text-primary">Goal Amount</h3>

                                                    <span class="text-primary text-3xl font-semibold">$<?php echo number_format($goal) ?></span>

                                        </div>
                                    </div>
                                    <div class="cardItem px-6 py-5 flex gap-6 items-center bg-card3 rounded-xl w-full lg:max-w-sm shadow-shadowItem">
                                        <div class="flex flex-col gap-2">
                                            <h3 class="text-base text-primary">Amount remaining</h3>
                                                <span class="text-primary text-3xl font-semibold">$<?php echo number_format($amount_remaining); ?></span>
                                        </div>
                                    </div>

                                </div>

                                <div class="grid grid-cols-1 lg:grid-cols-10 gap-y-4 lg:gap-4">
                                    <div class="col-span-7 bg-white p-6 rounded-xl shadow-shadowItem">
                                        <div class="flex flex-col gap-6">
                                            <span class="text-textColor font-medium text-xl">Statistics</span>
                                            <div class="chart-container w-full flex" style="height:340px; width: 100%; background-position: center; background-image:url(<?php echo $background_image_url ?>); background-size: cover; background-repeat: no-repeat;">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-span-3 bg-white p-6 rounded-xl shadow-shadowItem h-fit">
                                        <div class="flex flex-col gap-6">
                                            <span class="text-textColor font-medium text-xl">My Received Donations</span>
                                            <div class="flex flex-col">
                                                <?php
                                                    echo !$recent_donations_view ? '<span>No Donations</span>' : $recent_donations_view;
                                                ?>
                                            </div>
                                            <button id="donations" class="w-full px-4 py-2.5 text-base bg-white rounded-lg border border-primary text-primary font-medium hover:bg-white hover:text-primary hover:scale-105 viewBtn">View All</button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>

                    <div id="tab2" class="hidden tabContent">
                        <div class="flex w-full flex-col justify-between mb-8">
                            <h1 class="text-3xl font-medium text-textColor mb-8 pl-20 xl:pl-0">My Donations</h1>
                            <div class="cardRow w-full flex gap-6 flex-wrap lg:flex-nowrap mb-8">
                                <div class="cardItem px-6 py-5 flex gap-6 items-center border border-borderColor rounded-xl w-full xl:max-w-3xl">
                                    <div class="flex flex-col gap-2 w-full">
                                        <h3 class="text-base text-primary">Progress Towards Goal</h3>

                                        <progress class="progress w-full progress-primary" value="<?php echo $candidate_progress ?>" max="100"></progress>
                                        <div class="w-full justify-between flex items-center">
                                            <span class="text-textColor text-base font-semibold">$<?php echo $amount_raised > $goal ? number_format($goal) : number_format($amount_raised) ?> raised</span>
                                            <span class="text-textValue text-base font-semibold">of $<?php echo number_format($goal) ?></span>
                                        </div>

                                    </div>
                                </div>
                                <div class="cardItem px-6 py-5 flex gap-6 items-center border border-borderColor rounded-xl w-full sm:max-w-sm">
                                    <div class="flex flex-col gap-2">
                                        <h3 class="text-base text-primary">Goal Amount</h3>

                                        <span class="text-primary text-3xl font-semibold">$<?php echo number_format($goal) ?></span>

                                    </div>
                                </div>
                                <div class="cardItem px-6 py-5 flex gap-6 items-center border border-borderColor rounded-xl w-full sm:max-w-sm">
                                    <div class="flex flex-col gap-2">
                                        <h3 class="text-base text-primary">Amount remaining</h3>
                                        <span class="text-primary text-3xl font-semibold">$<?php echo number_format($amount_remaining) ?></span>
                                    </div>
                                </div>

                            </div>
                            <div class="flex items-center justify-end gap-3 sm:gap-6 flex-wrap">
                                <select class="select select-bordered w-56 border-primary bg-white text-primary">
                                    <option disabled="" selected="">Show per page: 10</option>
                                    <option>25</option>
                                    <option>50</option>
                                    <option>100</option>
                                </select>
                                <select class="select select-bordered w-72 border-primary bg-white text-primary">
                                    <option disabled="" selected="">Sort by: Donation Amount</option>
                                    <option>value1</option>
                                    <option>value2</option>
                                </select>
                            </div>
                        </div>

                        <?php echo $recent_donations ?>

                    </div>

                    <div id="tab3" class="hidden tabContent">
                        <div class="flex w-full justify-between items-center mb-8">
                            <h1 class="text-3xl font-medium text-textColor pl-20 xl:pl-0">My Candidate Profile</h1>

                            <button class="btn text-white border-0 bg-primary hidden sm:flex" name="saveProfile">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                    <g clip-path="url(#clip0_1068_61413)">
                                    <path d="M3.75 8.56031V19.5C3.75 19.6989 3.82902 19.8897 3.96967 20.0303C4.11032 20.171 4.30109 20.25 4.5 20.25H19.5C19.6989 20.25 19.8897 20.171 20.0303 20.0303C20.171 19.8897 20.25 19.6989 20.25 19.5V4.5C20.25 4.30109 20.171 4.11032 20.0303 3.96967C19.8897 3.82902 19.6989 3.75 19.5 3.75H8.56031C8.36166 3.75009 8.17117 3.82899 8.03063 3.96938L3.96938 8.03063C3.82899 8.17117 3.75009 8.36166 3.75 8.56031Z" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                    <path d="M12 17.25C13.6569 17.25 15 15.9069 15 14.25C15 12.5931 13.6569 11.25 12 11.25C10.3431 11.25 9 12.5931 9 14.25C9 15.9069 10.3431 17.25 12 17.25Z" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                    <path d="M8.25 8.25H15.75C15.9489 8.25 16.1397 8.17098 16.2803 8.03033C16.421 7.88968 16.5 7.69891 16.5 7.5V3.75" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                    </g>
                                    <defs>
                                    <clipPath id="clip0_1068_61413">
                                        <rect width="24" height="24" fill="white"/>
                                    </clipPath>
                                    </defs>
                                </svg>
                                Save Changes
                            </button>
                        </div>
                        <div class="py-8 p-6 w-full border border-borderColor rounded-md gap-6">
                            <h3 class="w-full text-xl text-textColor mb-8">Edit information</h3>
                            <div class="flex w-full gap-4 flex-col lg:flex-row">
                                <div class="flex flex-col gap-4 items-center sm:items-start">
                                    <div class="w-64 h-80 rounded-lg border acc-avatar ">
                                        <?php echo $user_image ?>
                                    </div>
                                    <div class="flex flex-col gap-2">
                                        <div>
                                            <button class="btn bg-primary text-white border-0 w-64 hover:scale-105" id="uploadProductImage">Choose file</button>
                                            <input type="file" accept="image/*" id="newProductImage" style="display: none">
                                        </div>
                                    </div>
                                    <!-- Gallery -->

                                    <?php 

                                    $user_id = get_current_user_id(); 
                                    $field_value = get_user_meta($user_id, '_rest', true);
                                    
                                    $product_name = get_user_meta($user_id, '_rest', true); 

                                    $product = get_page_by_title($product_name, OBJECT, 'product');

                                    $product_id = $product->ID;

                                    $gallery_attachment_ids = get_post_meta($product_id, '_product_image_gallery', true);
                                    $current_gallery = array();
                                    if (!empty($gallery_attachment_ids)) {
                                        $gallery_images = array();
                                    
                                        // Loop through each gallery attachment ID
                                        foreach (explode(',', $gallery_attachment_ids) as $attachment_id) {
                                            $current_gallery[] = $attachment_id;
                                            $image_url = wp_get_attachment_url($attachment_id);
                                            if ($image_url) {
                                                $gallery_images[] = $image_url;
                                            }
                                        }
                                    
                                        if (!empty($gallery_images)) {
                                            // Output the gallery images
                                            foreach ($gallery_images as $image_url) {
                                                echo '<img src="' . $image_url . '" alt="Gallery Image">';
                                            }
                                        } else {
                                            echo 'No gallery images found for the product.';
                                        }
                                    } else {
                                        echo 'Product gallery is empty.';
                                    }
                                    
                                    ?>


                                    <form method="post" enctype="multipart/form-data">
                                        <input type="file" name="gallery_images[]" multiple>
                                        <input type="hidden" name="product_id" value="<?php echo $product_id; ?>">
                                        <button type="submit" class="btn bg-primary text-white border-0 w-64 hover:scale-105" name="update_gallery">Update Gallery</button>
                                    </form>

                                    <?php 
                                    require_once( ABSPATH . 'wp-admin/includes/image.php' );
                                    require_once( ABSPATH . 'wp-admin/includes/file.php' );
                                    require_once( ABSPATH . 'wp-admin/includes/media.php' );   
                                        // Handle form submission
                                        if (isset($_POST['update_gallery'])) {
                                            $product_id = $_POST['product_id'];
                                            $gallery_images = $_FILES['gallery_images'];

                                            // Check if there are any new gallery images
                                            if (!empty($gallery_images['name'][0])) {
                                                //$attachment_ids = array();
                                                $attachment_ids = $current_gallery;
                                                // Loop through each gallery image
                                                foreach ($gallery_images['name'] as $key => $image_name) {
                                                    $image_tmp = $gallery_images['tmp_name'][$key];
                                                    $image_type = $gallery_images['type'][$key];

                                                    // Prepare the image for upload
                                                    $upload = wp_upload_bits($image_name, null, file_get_contents($image_tmp));

                                                    if ($upload['error']) {
                                                        // Handle the upload error
                                                        echo 'Error uploading image: ' . $upload['error'];
                                                    } else {
                                                        // Create the attachment for the gallery image
                                                        $attachment = array(
                                                            'post_mime_type' => $image_type,
                                                            'post_title' => sanitize_file_name($image_name),
                                                            'post_content' => '',
                                                            'post_status' => 'inherit'
                                                        );

                                                        // Insert the attachment into the media library
                                                        $attachment_id = wp_insert_attachment($attachment, $upload['file'], $product_id);

                                                        if (!is_wp_error($attachment_id)) {
                                                            // Generate the attachment metadata
                                                            $attachment_data = wp_generate_attachment_metadata($attachment_id, $upload['file']);

                                                            // Update the attachment metadata
                                                            wp_update_attachment_metadata($attachment_id, $attachment_data);

                                                            // Add the attachment ID to the list
                                                            $attachment_ids[] = $attachment_id;
                                                        } else {
                                                            // Handle the attachment creation error
                                                            echo 'Error creating attachment: ' . $attachment_id->get_error_message();
                                                        }
                                                    }
                                                }

                                                
                                                update_post_meta($product_id, '_product_image_gallery', implode(',', $attachment_ids));

                                              
                                            } 
                                        }

                                  
                                    ?>


                                    
                                </div>
                                <div class="flex flex-col w-full">
                                    <div class="w-full flex flex-col">
                                        <label class="label">
                                            <span class="label-text">Name</span>
                                        </label>
                                        <input type="text" name="name" value="<?php echo $user->user_firstname ?>" class="input input-bordered w-full bg-white border-borderColor rounded-xl" />
                                    </div>
                                    <div class="w-full flex flex-col">
                                        <label class="label">
                                            <span class="label-text">Reason for reproductive procedure.</span>
                                        </label>
                                        <textarea class="textarea border border-borderColor bg-white w-full h-80" placeholder="Personal choice to not have children. Prioritize personal & professional development."></textarea>
                                    </div>
                                    <button class="btn text-white border-0 mt-8 bg-primary flex sm:hidden" id="saveProfile">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                            <g clip-path="url(#clip0_1068_61413)">
                                            <path d="M3.75 8.56031V19.5C3.75 19.6989 3.82902 19.8897 3.96967 20.0303C4.11032 20.171 4.30109 20.25 4.5 20.25H19.5C19.6989 20.25 19.8897 20.171 20.0303 20.0303C20.171 19.8897 20.25 19.6989 20.25 19.5V4.5C20.25 4.30109 20.171 4.11032 20.0303 3.96967C19.8897 3.82902 19.6989 3.75 19.5 3.75H8.56031C8.36166 3.75009 8.17117 3.82899 8.03063 3.96938L3.96938 8.03063C3.82899 8.17117 3.75009 8.36166 3.75 8.56031Z" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                            <path d="M12 17.25C13.6569 17.25 15 15.9069 15 14.25C15 12.5931 13.6569 11.25 12 11.25C10.3431 11.25 9 12.5931 9 14.25C9 15.9069 10.3431 17.25 12 17.25Z" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                            <path d="M8.25 8.25H15.75C15.9489 8.25 16.1397 8.17098 16.2803 8.03033C16.421 7.88968 16.5 7.69891 16.5 7.5V3.75" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                            </g>
                                            <defs>
                                            <clipPath id="clip0_1068_61413">
                                                <rect width="24" height="24" fill="white"/>
                                            </clipPath>
                                            </defs>
                                        </svg>
                                        Save Changes
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="tab4" class="hidden tabContent">
                        <?php
                            $args = array(
                                'role'       => 'medical_provider', // Specify the role
                                // get all medical_providers
                                // 'meta_query' => array(
                                //     array(
                                //         'key'   => 'speciality', // Meta key for the Specialty field
                                //         'value' => 'Urologist',  // Value to match for Specialty
                                //     ),
                                // ),
                            );

                            // Create a new instance of WP_User_Query
                            $medical_providers_query = new WP_User_Query( $args );

                            // Get the results
                            $medical_providers = $medical_providers_query->get_results();

                            $meta_keys = array();

                                    // Loop through each user
                                    foreach ($medical_providers as $user) {
                                        // Get user meta keys
                                        $user_meta_keys = get_user_meta($user->ID);
                                    //echo $user->ID .'<br>';
                                        // Merge meta keys into the main array
                                        $meta_keys = array_merge($meta_keys, array_keys($user_meta_keys));
                                    }

                                    // Remove duplicate meta keys
                                    $meta_keys = array_unique($meta_keys);

                                    // Output meta keys
                                    // echo '<pre>';
                                    //  print_r($meta_keys);
                                    // echo '</pre>';

                            //print_r($medical_providers);

                            $result_array = array();

                            // Loop through each user
                            foreach ($medical_providers as $user) {
                                $user_meta = get_user_meta($user->ID);
                                $provider_data = array(
                                    'medical_provider' => array(
                                        'data' => (array) $user,
                                        'meta' => $user_meta
                                    ),
                                    'meta_keys' => array_keys($user_meta)
                                );

                                $result_array[] = $provider_data;
                            }

                            $json_result = json_encode($result_array);
                            $items_per_page = 9;
                            $limited_providers = array_slice($result_array, 0, $items_per_page);

                            // echo "<script>";
                            // echo "console.log(" . $json_result . ");";
                            // echo "</script>";
                        ?>
                        <div class="flex w-full justify-between lg:items-center mb-8 flex-col gap-4 lg:gap-8">
                            <div class="requests-wrapper flex w-full flex-col px-2 md:p-6 rounded-lg gap-4 shadowCard">
                                <div class="w-full flex justify-end gap-4 flex-col">
                                    <div class="flex flex-col gap-4">
                                        <h2 class="text-2xl font-medium text-textColor pl-20 xl:pl-0">My Physician</h2>
                                        <span class="text-textValue">Your Physician will be displayed here when you select them</span>
                                        <?php
                                        
                                            if(count($unique_phs) !=0 ) {
                                     
                                                
                                                $object = end($unique_phs);
                                                $user_meta = get_user_meta($object->physician);
                                                $user_data = get_userdata($object->physician);
                              
                                                
                                                ?>
                                                <div class="flex flex-col gap-4 pb-1 items-center max-h-96 overflow-auto shadowCard rounded-lg">
                                                    <div class="w-full flex flex-col md:flex-row px-4 py-3  gap-4 ">
                                                        <div class="flex w-full flex-col gap-4">
                                                            <div class="flex items-center gap-4">
                                                                <h2 class="text-lg text-primary font-bold">
                                                                    <?php echo $user_data->first_name . ' ' . $user_data->last_name ?>
                                                                </h2>
                                                                <div class="badge bg-badgeM badgeM border-0 text-blueMain py-3">
                                                                    <?php echo $user_meta['speciality'][0]; ?>
                                                                </div>
                                                            </div>
                                                            <div class="flex flex-col gap-1">
                                                                <a href="tel:<?php echo $user_meta['phone'][0]; ?>">
                                                                <?php echo ($user_meta['phone'][0]) ? $user_meta['phone'][0] : 'No phone provided'; ?>
                                                                </a>
                                                                <a href="#" class="flex gap-1 items-center">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="17" viewBox="0 0 16 17" fill="none">
                                                                        <g clip-path="url(#clip0_2152_34177)">
                                                                            <path d="M3.5 15H12.5" stroke="#143A62" stroke-linecap="round" stroke-linejoin="round"/>
                                                                            <path d="M8 9C9.10457 9 10 8.10457 10 7C10 5.89543 9.10457 5 8 5C6.89543 5 6 5.89543 6 7C6 8.10457 6.89543 9 8 9Z" stroke="#143A62" stroke-linecap="round" stroke-linejoin="round"/>
                                                                            <path d="M13 7C13 11.5 8 15 8 15C8 15 3 11.5 3 7C3 5.67392 3.52678 4.40215 4.46447 3.46447C5.40215 2.52678 6.67392 2 8 2C9.32608 2 10.5979 2.52678 11.5355 3.46447C12.4732 4.40215 13 5.67392 13 7Z" stroke="#143A62" stroke-linecap="round" stroke-linejoin="round"/>
                                                                        </g>
                                                                        <defs>
                                                                            <clipPath id="clip0_2152_34177">
                                                                            <rect width="16" height="16" fill="white" transform="translate(0 0.5)"/>
                                                                            </clipPath>
                                                                        </defs>
                                                                    </svg>
                                                                    <?php echo $user_meta['business_name'][0]; ?>
                                                                </a>
                                                                <a href="<?php echo $user_meta['url'][0]; ?>">
                                                                    <?php echo $user_meta['url'][0]; ?>
                                                                </a>
                                                            </div>
                                                        </div>
                                                        <div class="flex w-full justify-end flex-wrap gap-4">
                                                            <div class="flex gap-3 items-center">
                                                                <button class="px-4 py-2 bg-primary rounded-xl text-white border-primary hover:scale-105">Approve</button>
                                                                <button class="px-4 py-2 bg-white border-warning rounded-xl text-warning hover:scale-105 hover:bg-red-200 hover:border-red-200 hover:text-red-500">Decline</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <?php
                                            } else {
                                                ?>
                                                <div class="text-center text-2xl text-textValue py-5 w-full">No request</div>
                                                <?php
                                            }
                                        ?>                                        
                                    </div>
                                    <div class="flex w-full justify-between">
                                        <div class="flex items-center text-lg gap-1 pl-6 xl:pl-0">
                                            <h2 class="text-textColor font-semibold">Request</h2>
                                            <span class="text-textValue"><?php echo count($unique_phs); ?></span>
                                        </div>
                                        <button 
                                            class="px-4 py-2.5 text-base bg-white rounded-lg border border-primary text-primary font-medium hover:bg-white hover:scale-105 hover:text-primary "
                                            onclick="allRequests.showModal()"
                                        >
                                            View All
                                        </button>
                                    </div>
                                </div>
                                <div class="flex w-full flex-col gap-4 overflow-y-auto h-40 p-2 reqCards-scroll hidden">
                                    <div class="w-full flex flex-col md:flex-row px-4 py-3 rounded-lg gap-4 shadowCard">
                                        <div class="flex w-full flex-col gap-4">
                                            <div class="flex items-center gap-4">
                                                <h2 class="text-lg text-primary font-bold">Tredinnick S.</h2>
                                                <div class="badge bg-badgeM badgeM text-blueMain py-3">Urologists</div>
                                            </div>
                                            <div class="flex flex-col gap-1">
                                                <a href="tel:+16177893000">+1 617-789-3000</a>
                                                <a href="#" class="flex gap-1 items-center">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="17" viewBox="0 0 16 17" fill="none">
                                                        <g clip-path="url(#clip0_2152_34177)">
                                                            <path d="M3.5 15H12.5" stroke="#143A62" stroke-linecap="round" stroke-linejoin="round"/>
                                                            <path d="M8 9C9.10457 9 10 8.10457 10 7C10 5.89543 9.10457 5 8 5C6.89543 5 6 5.89543 6 7C6 8.10457 6.89543 9 8 9Z" stroke="#143A62" stroke-linecap="round" stroke-linejoin="round"/>
                                                            <path d="M13 7C13 11.5 8 15 8 15C8 15 3 11.5 3 7C3 5.67392 3.52678 4.40215 4.46447 3.46447C5.40215 2.52678 6.67392 2 8 2C9.32608 2 10.5979 2.52678 11.5355 3.46447C12.4732 4.40215 13 5.67392 13 7Z" stroke="#143A62" stroke-linecap="round" stroke-linejoin="round"/>
                                                        </g>
                                                        <defs>
                                                            <clipPath id="clip0_2152_34177">
                                                            <rect width="16" height="16" fill="white" transform="translate(0 0.5)"/>
                                                            </clipPath>
                                                        </defs>
                                                    </svg>
                                                    Brigham and Women's Hospital
                                                </a>
                                                <a href="#">https://www.massgeneral.org</a>
                                            </div>
                                        </div>
                                        <div class="flex w-full justify-end flex-wrap gap-4">
                                            <div class="flex gap-3 items-center">
                                                <button class="px-4 py-2 bg-primary rounded-xl text-white border-primary hover:scale-105">Aprove</button>
                                                <button class="px-4 py-2 bg-white border-warning rounded-xl text-warning hover:scale-105 hover:bg-red-200 hover:border-red-200 hover:text-red-500">Decline</button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="w-full flex flex-col md:flex-row px-4 py-3 rounded-lg gap-4 shadowCard">
                                        <div class="flex w-full flex-col gap-4">
                                            <div class="flex items-center gap-4">
                                                <h2 class="text-lg text-primary font-bold">Tredinnick S.</h2>
                                                <div class="badge bg-badgeM badgeM text-blueMain py-3">Urologists</div>
                                            </div>
                                            <div class="flex flex-col gap-1">
                                                <a href="tel:+16177893000">+1 617-789-3000</a>
                                                <a href="#" class="flex gap-1 items-center">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="17" viewBox="0 0 16 17" fill="none">
                                                        <g clip-path="url(#clip0_2152_34177)">
                                                            <path d="M3.5 15H12.5" stroke="#143A62" stroke-linecap="round" stroke-linejoin="round"/>
                                                            <path d="M8 9C9.10457 9 10 8.10457 10 7C10 5.89543 9.10457 5 8 5C6.89543 5 6 5.89543 6 7C6 8.10457 6.89543 9 8 9Z" stroke="#143A62" stroke-linecap="round" stroke-linejoin="round"/>
                                                            <path d="M13 7C13 11.5 8 15 8 15C8 15 3 11.5 3 7C3 5.67392 3.52678 4.40215 4.46447 3.46447C5.40215 2.52678 6.67392 2 8 2C9.32608 2 10.5979 2.52678 11.5355 3.46447C12.4732 4.40215 13 5.67392 13 7Z" stroke="#143A62" stroke-linecap="round" stroke-linejoin="round"/>
                                                        </g>
                                                        <defs>
                                                            <clipPath id="clip0_2152_34177">
                                                            <rect width="16" height="16" fill="white" transform="translate(0 0.5)"/>
                                                            </clipPath>
                                                        </defs>
                                                    </svg>
                                                    Brigham and Women's Hospital
                                                </a>
                                                <a href="#" class="text-blueMain">https://www.massgeneral.org</a>
                                            </div>
                                        </div>
                                        <div class="flex w-full justify-end flex-wrap gap-4">
                                            <div class="flex gap-3 items-center">
                                                <button class="px-4 py-2 bg-primary rounded-xl text-white border-primary hover:scale-105">Aprove</button>
                                                <button class="px-4 py-2 bg-white border-warning rounded-xl text-warning hover:scale-105 hover:bg-red-200 hover:border-red-200 hover:text-red-500">Decline</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="flex flex-col items-start w-full px-2 md:px-6">
                                <div class="w-full flex items-start lg:items-center justify-between flex-col md:flex-row gap-4">
                                    <h2 class="text-3xl font-medium text-textColor">Physicians</h2>
                                    <div class="flex w-full items-end ld:items-center gap-6 justify-end flex-col lg:flex-row">
                                        <div class="w-full relative xl:max-w-sm">
                                            <input type="text" placeholder="Search by Name" class="border bg-white border-borderColor rounded-xl py-3 px-4 pr-12 w-full h-12" id="search_input" />
                                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                                    <g clip-path="url(#clip0_1354_9153)">
                                                    <path d="M10.5 18C14.6421 18 18 14.6421 18 10.5C18 6.35786 14.6421 3 10.5 3C6.35786 3 3 6.35786 3 10.5C3 14.6421 6.35786 18 10.5 18Z" stroke="#76787A" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                                    <path d="M15.8037 15.8035L21.0003 21" stroke="#76787A" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                                    </g>
                                                    <defs>
                                                    <clipPath id="clip0_1354_9153">
                                                        <rect width="24" height="24" fill="white"/>
                                                    </clipPath>
                                                    </defs>
                                                </svg>
                                            </div>
                                        </div>
                                        <div class="flex items-center gap-2 direction-column">
                                            <div class="flex items-center gap-6 flex-wrap lg:flex-nowrap justify-start">
                                                <select class="block w-56 h-12 px-4 border-borderColor text-primary rounded-md sm:max-w-xs sm:text-sm sm:leading-6 bg-white" id="providers_sorting">
                                                    <option disabled="" selected="">Sort by: Newest</option>
                                                    <option>value1</option>
                                                    <option>value2</option>
                                                </select>
                                                <select class="block w-56 h-12 px-4 border-borderColor text-primary rounded-md sm:max-w-xs sm:text-sm sm:leading-6 bg-white" id="providers_per_page">
                                                    <option value="9" selected>Show per page: 9</option>
                                                    <option value="25">25</option>
                                                    <option value="50">50</option>
                                                    <option value="100">100</option>
                                                </select>
                                                <button id="accordionButton" class="bg-white btn text-primary border border-borderColor rounded-xl normal-case hover:text-primary">
                                                    Filters
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                                                    </svg>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex w-full flex-col gap-2 items-between" id="accordionContent" style="display:none">
                                    <?php echo do_shortcode('[elementor-template id="62035"]');?>
                                </div>
                                <div class="flex w-full flex-col items-center justify-center pt-6 lg:pt-16" id="providers_list">
                                    <div class="overflow-x-auto w-full">
                                        <table class="table table-zebra">
                                            <thead>
                                                <tr class="text-xs font-semibold bg-primary bg-opacity-5 text-info border-b border-borderColor" style="color: #8497AB !important">
                                                    <th>Clinic</th>
                                                    <th>Name</th>
                                                    <th>Website</th>
                                                    <th>Phone number</th>
                                                    <th>Expertise</th>                                                    
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php   
                                                    $current_page = isset($_GET['page']) ? intval($_GET['page']) : 1;
                                                    // Loop through each provider data
                                                    foreach ($limited_providers as $provider_data) {
                                                        $medical_provider = $provider_data['medical_provider']['data'];
                                                        $meta_data = $provider_data['medical_provider']['meta'];
                                                    ?>
                                                        <tr class="h-14 border-0">
                                                            <td><span class="text-sm font-medium text-textColor whitespace-nowrap"><?php echo $meta_data['business_name'][0]; ?></span></td>
                                                            <td><span class="text-sm font-medium text-textColor whitespace-nowrap"><?php echo $meta_data['billing_first_name'][0] . ' ' . $meta_data['billing_last_name'][0]; ?></span></td>
                                                            <td><span class="text-sm font-medium text-textColor whitespace-nowrap"><?php echo $meta_data['billing_email'][0]; ?></span></td>
                                                            <td><span class="text-sm font-medium text-textColor whitespace-nowrap"><?php echo $meta_data['shipping_phone'][0]; ?></span></td>
                                                            <td><span class="text-sm font-medium text-textColor whitespace-nowrap">
                                                                <?php if ($meta_data['speciality'][0] === "Urologist"): ?>
                                                                    <div class="badge bg-badgeM badgeM text-blueMain py-3 border-0"><?php echo $meta_data['speciality'][0]; ?></div>
                                                                <?php else: ?>
                                                                    <div class="badge bg-badgeF badgeF text-blueMain py-3 border-0"><?php echo $meta_data['speciality'][0]; ?></div>
                                                                <?php endif; ?>
                                                            </span></td>
                                                        </tr>
                                                <?php } ?>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="w-full flex justify-center mt-8 gap-3.5 items-center">
                                        <?php
                                            $total_items = count($result_array);
                                            $total_pages = ceil($total_items / $items_per_page);

                                            $max_links = 5;
                                            $start_page = max(1, $current_page - floor($max_links / 2));
                                            $end_page = min($total_pages, $start_page + $max_links - 1);
                                            $prepend_dots = $start_page > 1;
                                            $append_dots = $end_page < $total_pages;

                                            $prev_page = max(1, $current_page - 1);
                                            echo '<a href="?page=' . $prev_page . '" class="page-link w-10 h-10 border-0 bg-transparent flex items-center justify-center">';
                                            echo '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" class="cursor-pointer">
                                                    <g clip-path="url(#clip0_1049_13851)">
                                                    <path d="M15 4.5L7.5 12L15 19.5" stroke="#333333" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                                    <path d="M15 4.5L7.5 12L15 19.5" stroke="black" stroke-opacity="0.2" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                                    <path d="M15 4.5L7.5 12L15 19.5" stroke="black" stroke-opacity="0.2" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                                    <path d="M15 4.5L7.5 12L15 19.5" stroke="black" stroke-opacity="0.2" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                                    </g>
                                                    <defs>
                                                    <clipPath id="clip0_1049_13851">
                                                        <rect width="24" height="24" fill="white" transform="matrix(0 -1 -1 0 24 24)"/>
                                                    </clipPath>
                                                    </defs>
                                                </svg>';
                                            echo '</a>';

                                            if ($prepend_dots) {
                                                echo '<a href="?page=1" class="page-link w-10 h-10 border-0 bg-transparent flex items-center justify-center">1</a>';
                                                echo '<span class="ellipsis">...</span>';
                                            }

                                            for ($i = $start_page; $i <= $end_page; $i++) {
                                                $active_class = $i === $current_page ? 'active' : '';
                                                echo '<a href="?page=' . $i . '" class="page-link w-10 h-10 border-0 bg-transparent flex items-center justify-center ' . $active_class . '">' . $i . '</a>';
                                            }

                                            if ($append_dots) {
                                                echo '<span class="ellipsis">...</span>';
                                                echo '<a href="?page=' . $total_pages . '" class="page-link w-10 h-10 border-0 bg-transparent flex items-center justify-center">' . $total_pages . '</a>';
                                            }

                                            $next_page = min($total_pages, $current_page + 1);
                                            echo '<a href="?page=' . $next_page . '" class="page-link w-10 h-10 border-0 bg-transparent flex items-center justify-center">';
                                            echo '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" class="cursor-pointer">
                                                    <g clip-path="url(#clip0_1049_13883)">
                                                    <path d="M9 4.5L16.5 12L9 19.5" stroke="#333333" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                                    <path d="M9 4.5L16.5 12L9 19.5" stroke="black" stroke-opacity="0.2" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                                    <path d="M9 4.5L16.5 12L9 19.5" stroke="black" stroke-opacity="0.2" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                                    <path d="M9 4.5L16.5 12L9 19.5" stroke="black" stroke-opacity="0.2" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                                    </g>
                                                    <defs>
                                                    <clipPath id="clip0_1049_13883">
                                                        <rect width="24" height="24" fill="white" transform="matrix(0 -1 1 0 0 24)"/>
                                                    </clipPath>
                                                    </defs>
                                                </svg>';
                                            echo '</a>';
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="tab5" class="hidden tabContent">
                        <div class="flex w-full justify-between items-center mb-8 lg:mb-0 flex-col md:flex-row">
                            <div class="flex gap-5 items-center w-full justify-start mb-6">

                                <h1 class="text-3xl font-medium text-textColor pl-20 xl:pl-0">Notification</h1>
                            </div>
                            <div class="flex items-center gap-6 flex-wrap md:flex-nowrap md:justify-end w-full">
                                <select class="select select-bordered w-56  border-borderColor rounded-xl bg-white text-primary">
                                    <option disabled="" selected="">Show her page: 10</option>
                                    <option>25</option>
                                    <option>50</option>
                                    <option>100</option>
                                </select>
                                <select class="select select-bordered w-40  border-borderColor rounded-xl bg-white text-primary">
                                    <option disabled="" selected="">Status: All</option>
                                    <option>Cancelled</option>
                                    <option>In progress</option>
                                    <option>Completed</option>
                                </select>
                            </div>
                        </div>

                        <div class="flex w-full h-full items-center justify-center flex-col gap-4">

                            <img src="https://childfreebc.com/wp-content/uploads/2023/11/foo2-1.png" alt="Coming soon" />
                            <span style="color: #76787A;">Feature Coming Soon</span>
                        </div>
                    </div>

                    <div id="tab6" class="hidden tabContent">
                        <div class="flex w-full justify-between items-center mb-8 lg:mb-0 flex-col md:flex-row">
                            <div class="flex gap-5 items-center w-full justify-start mb-6">
                                <h1 class="text-3xl font-medium text-textColor pl-20 xl:pl-0">Influencer Referrals</h1>
                            </div>
                        </div>
                        <?php
                        if(!$foundRef || !$product) {
                            ?>
                                <div class="flex items-center w-full justify-center h-full text-3xl text-textValue my-12">No referrals</div>
                            <?php
                        } else {
                            ?>
                                <div class="flex w-full h-full justify-start flex-col md:flex-row gap-8">
                                    <div class="w-full flex items-center flex-col md:flex-row gap-2 rounded-xl p-4 gap-6" style="background-color: #EDF3FE;">
                                        <div class="flex flex-col gap-2">
                                            <div class="flex items-center text-xl gap-1">
                                                <span>Referral</span>
                                                <span>Bonus Program</span>
                                            </div>
                                            <p>If ChildFree by Choice is a cause you can get behind, please help spread the word through our Referral Bonus Program. For any Donors that put your Username down in </p>
                                            <div class="flex items-center flex-wrap gap-2">
                                                <span>Step 2 of Donation checkout, you receive </span>
                                                <span class="py-1 px-2.5 bg-accentBg rounded-full font-medium" style="color: #8802A9;">10%</span>
                                                <span>of the Donation Amount.</span>
                                                <span>To request payout of your referral bonus please email at <a class="underline text-primary" href="mailto:info@childfreebc.com">info@childfreebc.com</a></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="flex flex-col gap-6 rounded-xl w-full lg:max-w-sm p-4" style="background-color: #F2E5FF;">
                                        <span class="text-base text-primary">Raised with referrals</span>
                                        <span class="text-primary text-3xl font-semibold">$10</span>
                                    </div>
                                </div>
                                <div class="flex flex-col gap-4">
                                    <h2 class="text-3xl font-medium text-textColor my-6">Your referral bonus</h2>

                                    <div class="overflow-x-auto" style="border-radius: 8px 8px 0 0;">
                                        <table class="table rounded-t-lg">

                                            <thead class="h-14">
                                            <tr class="text-xs font-semibold bg-primary bg-opacity-5 text-info border-b border-borderColor" style="color: #8497AB !important">
                                                <th>Candidate Name</th>
                                                <th>Donor</th>
                                                <th>Donation Amount</th>
                                                <th>Referral bonus</th>
                                                <th>Date</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <tr class="h-14 border-0 bg-white">
                                                <td class="h-14">
                                                    <div class="flex items-center space-x-4">
                                                        <div class="font-normal text-primary text-sm underline">Candidate Name</div>
                                                    </div>
                                                </td>
                                                <td class="h-14">
                                                    <div class="flex items-center space-x-4">
                                                        <div class="font-normal text-primary text-sm underline"><?php echo $ref_user_name ?></div>
                                                    </div>
                                                </td>
                                                <td class="flex gap-2 items-center h-14">
                                                    <span class="text-sm font-medium text-textColor">$100</span>
                                                </td>
                                                <td class="flex gap-2 items-center h-14">
                                                    <span class="text-sm font-medium text-textColor">$10</span>
                                                </td>
                                                <td class="h-14">
                                                    <span class="text-sm font-medium text-textColor"><?php echo $formatted_ref_order_date ?></span>
                                                </td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            <?php
                        }
                        ?>
                    </div>
                    <div id="tab7" class="hidden tabContent">
                        <?php 
                            $currUser = wp_get_current_user();
                        ?>
                        <div class="flex w-full justify-between items-center mb-8 lg:mb-6 flex-col md:flex-row">
                            <div class="flex gap-5 items-center w-full justify-start">
                                <h2 class="text-3xl font-medium text-textColor pl-20 xl:pl-0">Account Details</h2>
                            </div>
                            <button class="btn text-white border-0 bg-primary hidden sm:flex">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                    <g clip-path="url(#clip0_1068_61413)">
                                    <path d="M3.75 8.56031V19.5C3.75 19.6989 3.82902 19.8897 3.96967 20.0303C4.11032 20.171 4.30109 20.25 4.5 20.25H19.5C19.6989 20.25 19.8897 20.171 20.0303 20.0303C20.171 19.8897 20.25 19.6989 20.25 19.5V4.5C20.25 4.30109 20.171 4.11032 20.0303 3.96967C19.8897 3.82902 19.6989 3.75 19.5 3.75H8.56031C8.36166 3.75009 8.17117 3.82899 8.03063 3.96938L3.96938 8.03063C3.82899 8.17117 3.75009 8.36166 3.75 8.56031Z" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                    <path d="M12 17.25C13.6569 17.25 15 15.9069 15 14.25C15 12.5931 13.6569 11.25 12 11.25C10.3431 11.25 9 12.5931 9 14.25C9 15.9069 10.3431 17.25 12 17.25Z" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                    <path d="M8.25 8.25H15.75C15.9489 8.25 16.1397 8.17098 16.2803 8.03033C16.421 7.88968 16.5 7.69891 16.5 7.5V3.75" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                    </g>
                                    <defs>
                                    <clipPath id="clip0_1068_61413">
                                        <rect width="24" height="24" fill="white"/>
                                    </clipPath>
                                    </defs>
                                </svg>
                                Save Changes
                            </button>
                        </div>
                        <div class="w-full flex flex-col gap-8">
                            <div class="flex flex-col w-full shadow-md px-3 py-4 lg:px-8 lg:py-6 rounded-md gap-6">
                                <h1 class="text-3xl font-medium text-textColor">Personal Information</h1>
                                <div class="w-full flex flex-col gap-4">
                                    <div class="w-full flex items-center gap-4">
                                        <div class="w-full flex flex-col">
                                            <label class="label">
                                                <span class="label-text">First Name</span>
                                            </label>
                                            <input type="text" value="<?php echo $currUser->user_firstname ?>" class="input input-bordered w-full bg-white border-borderColor rounded-xl" />
                                        </div>
                                        <div class="w-full flex flex-col">
                                            <label class="label">
                                                <span class="label-text">Last Name</span>
                                            </label>
                                            <input type="text" value="<?php echo  $currUser->user_lastname ?>" class="input input-bordered w-full bg-white border-borderColor rounded-xl" />
                                        </div>
                                    </div>
                                    <div class="w-full flex flex-col">
                                        <label class="label">
                                            <span class="label-text">Email</span>
                                        </label>
                                        <input type="text" value="<?php echo $currUser->user_email ?>" class="input input-bordered w-full bg-white border-borderColor rounded-xl" />
                                    </div>
                                </div>
                            </div>

                            <button class="btn text-white border-0 bg-primary flex sm:hidden">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                    <g clip-path="url(#clip0_1068_61413)">
                                    <path d="M3.75 8.56031V19.5C3.75 19.6989 3.82902 19.8897 3.96967 20.0303C4.11032 20.171 4.30109 20.25 4.5 20.25H19.5C19.6989 20.25 19.8897 20.171 20.0303 20.0303C20.171 19.8897 20.25 19.6989 20.25 19.5V4.5C20.25 4.30109 20.171 4.11032 20.0303 3.96967C19.8897 3.82902 19.6989 3.75 19.5 3.75H8.56031C8.36166 3.75009 8.17117 3.82899 8.03063 3.96938L3.96938 8.03063C3.82899 8.17117 3.75009 8.36166 3.75 8.56031Z" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                    <path d="M12 17.25C13.6569 17.25 15 15.9069 15 14.25C15 12.5931 13.6569 11.25 12 11.25C10.3431 11.25 9 12.5931 9 14.25C9 15.9069 10.3431 17.25 12 17.25Z" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                    <path d="M8.25 8.25H15.75C15.9489 8.25 16.1397 8.17098 16.2803 8.03033C16.421 7.88968 16.5 7.69891 16.5 7.5V3.75" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                    </g>
                                    <defs>
                                    <clipPath id="clip0_1068_61413">
                                        <rect width="24" height="24" fill="white"/>
                                    </clipPath>
                                    </defs>
                                </svg>
                                Save Changes
                            </button>

                            <div class="flex flex-col w-full shadow-md px-3 py-4 lg:px-8 lg:py-6 rounded-md gap-6">
                                <h1 class="text-2xl text-textColor">Email Verification</h1>

                                <?php
                                    $user_email = $currUser->user_email;
                                    $user_email_mark = $currUser->user_status;
                                    if ($user_email_mark !== '0') {
                                        echo '<div class="w-full flex justify-between items-end gap-4">
                                                <div class="flex flex-col w-full">
                                                    <label class="label">
                                                        <span class="label-text">Email</span>
                                                    </label>
                                                    <input type="text" value="'. $user_email .'" class="input input-bordered w-full bg-white border-borderColor rounded-xl" />
                                                </div>
                                                <button id="resendVerificationButton" data-email="' . $user_email . '" class="btn bg-white border-primary text-primary flex gap-2 items-center hover:text-primary">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 12L3.269 3.126A59.768 59.768 0 0121.485 12 59.77 59.77 0 013.27 20.876L5.999 12zm0 0h7.5" />
                                                    </svg>                                              
                                                    Resend Verification
                                                </button>
                                            </div>';
                                    } else {
                                        echo '<label class="label">
                                                <span class="label-text flex gap-2 items-center">
                                                    Your email has been verified successfully.
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-green-400">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12c0 1.268-.63 2.39-1.593 3.068a3.745 3.745 0 01-1.043 3.296 3.745 3.745 0 01-3.296 1.043A3.745 3.745 0 0112 21c-1.268 0-2.39-.63-3.068-1.593a3.746 3.746 0 01-3.296-1.043 3.745 3.745 0 01-1.043-3.296A3.745 3.745 0 013 12c0-1.268.63-2.39 1.593-3.068a3.746 3.746 0 011.043-3.296 3.746 3.746 0 013.296-1.043A3.746 3.746 0 0112 3c1.268 0 2.39.63 3.068 1.593a3.746 3.746 0 013.296 1.043 3.746 3.746 0 011.043 3.296A3.745 3.745 0 0121 12z" />
                                                    </svg>
                                                </span>
                                            </label>';
                                    }
                                ?>
                            </div>
                            <div class="flex w-full shadow-md px-3 py-4 lg:px-8 lg:py-6 rounded-md gap-6 justify-between items-center" style="height: 140px;">
                                <h1 class="text-2xl text-textColor">Stripe account</h1>
                                <?php echo  $stripeChecker ?>
                            </div>

                            <div class="flex flex-col w-full shadow-md px-3 py-4 lg:px-8 lg:py-6 rounded-md gap-6">
                                <h1 class="text-2xl text-textColor">Notifications</h1>
                                <div class="flex w-full justify-between flex-col md:flex-row gap-8 md:gap-0">
                                    <div class="flex flex-col gap-4">
                                        <h3 class="text-base text-textColor">Email notification</h3>
                                        <div class=" bg-white flex items-center gap-4 w-full no-wrap">
                                            <input type="checkbox">
                                            <span class="label-text">News and updates</span>
                                        </div>
                                        <div class=" bg-white flex items-center gap-4 w-full no-wrap">
                                            <input type="checkbox" />
                                            <span class="label-text">News and updates</span>
                                        </div>
                                        <div class=" bg-white flex items-center gap-4 w-full no-wrap">
                                            <input type="checkbox" checked />
                                            <span class="label-text">Candidates statuses updates </span>
                                        </div>
                                        <div class=" bg-white flex items-center gap-4 w-full no-wrap">
                                            <input type="checkbox" />
                                            <span class="label-text">Referrals donations</span>
                                        </div>
                                        <div class=" bg-white flex items-center gap-4 w-full no-wrap">
                                            <input type="checkbox" checked />
                                            <span class="label-text">Reminders</span>
                                        </div>
                                    </div>

                                    <div class="flex flex-col gap-4">
                                        <h3 class="text-base text-textColor">Disable SMS Notifications</h3>
                                        <div class=" bg-white flex items-center gap-4 w-full no-wrap">
                                            <input type="checkbox" />
                                            <span class="label-text">Reminders</span>
                                        </div>
                                        <div class=" bg-white flex items-center gap-4 w-full no-wrap">
                                            <input type="checkbox" checked />
                                            <span class="label-text">Referrals donations</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="flex w-full justify-between shadow-md px-8 py-6 rounded-md gap-6 items-center flex-col sm:flex-row">
                                <h1 class="text-2xl text-textColor">Password</h1>
                                <a href="/reset-password/">
                                    <button class="btn bg-white border-primary text-primary hover:scale-105">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                            <g clip-path="url(#clip0_1068_61470)">
                                            <path d="M6 6C6 6 8.25 3.75 12 3.75C17.25 3.75 20.25 9 20.25 9" stroke="#143A62" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                            <path d="M18 18C18 18 15.75 20.25 12 20.25C6.75 20.25 3.75 15 3.75 15" stroke="#143A62" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                            <path d="M15.75 9H20.25V4.5" stroke="#143A62" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                            <path d="M8.25 15H3.75V19.5" stroke="#143A62" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                            </g>
                                            <defs>
                                            <clipPath id="clip0_1068_61470">
                                                <rect width="24" height="24" fill="white"/>
                                            </clipPath>
                                            </defs>
                                        </svg>
                                        Change Password
                                    </button>
                                </a>
                            </div>
                            <div class="flex w-full justify-between shadow-md px-8 py-6 rounded-md gap-6 items-center flex-col sm:flex-row">
                                <h1 class="text-2xl text-textColor">Delete account</h1>
                                <form method="post" action="">
                                    <button class="btn bg-white border-warning text-warning capitalize hover:bg-red-100 hover:text-red-500 hover:scale-105" id="delete_account" onclick="delAcc.showModal()">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                            <path d="M20.25 4.5H16.5V3.75C16.5 3.15326 16.2629 2.58097 15.841 2.15901C15.419 1.73705 14.8467 1.5 14.25 1.5H9.75C9.15326 1.5 8.58097 1.73705 8.15901 2.15901C7.73705 2.58097 7.5 3.15326 7.5 3.75V4.5H3.75C3.55109 4.5 3.36032 4.57902 3.21967 4.71967C3.07902 4.86032 3 5.05109 3 5.25C3 5.44891 3.07902 5.63968 3.21967 5.78033C3.36032 5.92098 3.55109 6 3.75 6H4.5V19.5C4.5 19.8978 4.65804 20.2794 4.93934 20.5607C5.22064 20.842 5.60218 21 6 21H18C18.3978 21 18.7794 20.842 19.0607 20.5607C19.342 20.2794 19.5 19.8978 19.5 19.5V6H20.25C20.4489 6 20.6397 5.92098 20.7803 5.78033C20.921 5.63968 21 5.44891 21 5.25C21 5.05109 20.921 4.86032 20.7803 4.71967C20.6397 4.57902 20.4489 4.5 20.25 4.5ZM9 3.75C9 3.55109 9.07902 3.36032 9.21967 3.21967C9.36032 3.07902 9.55109 3 9.75 3H14.25C14.4489 3 14.6397 3.07902 14.7803 3.21967C14.921 3.36032 15 3.55109 15 3.75V4.5H9V3.75ZM18 19.5H6V6H18V19.5ZM10.5 9.75V15.75C10.5 15.9489 10.421 16.1397 10.2803 16.2803C10.1397 16.421 9.94891 16.5 9.75 16.5C9.55109 16.5 9.36032 16.421 9.21967 16.2803C9.07902 16.1397 9 15.9489 9 15.75V9.75C9 9.55109 9.07902 9.36032 9.21967 9.21967C9.36032 9.07902 9.55109 9 9.75 9C9.94891 9 10.1397 9.07902 10.2803 9.21967C10.421 9.36032 10.5 9.55109 10.5 9.75ZM15 9.75V15.75C15 15.9489 14.921 16.1397 14.7803 16.2803C14.6397 16.421 14.4489 16.5 14.25 16.5C14.0511 16.5 13.8603 16.421 13.7197 16.2803C13.579 16.1397 13.5 15.9489 13.5 15.75V9.75C13.5 9.55109 13.579 9.36032 13.7197 9.21967C13.8603 9.07902 14.0511 9 14.25 9C14.4489 9 14.6397 9.07902 14.7803 9.21967C14.921 9.36032 15 9.55109 15 9.75Z" fill="#FF2919"/>
                                        </svg>
                                        Delete
                                    </button>
                                </form>
                            </div>
                        </div>

                    </div>

                    <div id="tab8" class="hidden tabContent">
                        <div class="flex w-full justify-between items-center mb-8">
                            <h1 class="text-3xl font-medium text-textColor pl-20 xl:pl-0">Share Profile</h1>
                        </div>

                        <div class="flex flex-col w-full gap-8 max-w-xl">
                            <div class="w-full flex flex-col gap-6">
                                <span>Fundraisers shared by Candidates through their personal and social networks raise up to <span class="underline font-medium">10 times more and 3 times quicker!</span></span>
                                <div class="flex items-center flex-wrap md:flex-nowrap gap-4 md:gap-0 justify-center">
                                    <div class="flex flex-col gap-2 w-40 items-center hover:cursor-pointer hover:transition-all hover:scale-105" id="share-on-facebook">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="56" height="56" viewBox="0 0 56 56" fill="none">
                                            <path d="M56 28.001C56 42.1427 45.5173 53.834 31.8994 55.7312C30.6261 55.9078 29.3233 56 28.001 56C26.4746 56 24.9756 55.8784 23.5159 55.6429C10.1825 53.4965 0 41.9367 0 28.001C0 12.5369 12.5369 0 27.999 0C43.4611 0 56 12.5369 56 28.001Z" fill="#1877F2"/>
                                            <path d="M31.8991 22.4823V28.582H39.4448L38.2499 36.7987H31.8991V55.7296C30.6258 55.9062 29.3231 55.9984 28.0007 55.9984C26.4743 55.9984 24.9754 55.8767 23.5157 55.6413V36.7987H16.5566V28.582H23.5157V21.1188C23.5157 16.4886 27.2689 12.7334 31.9011 12.7334V12.7373C31.9148 12.7373 31.9266 12.7334 31.9403 12.7334H39.4467V19.8396H34.5418C33.0841 19.8396 31.9011 21.0226 31.9011 22.4804L31.8991 22.4823Z" fill="white"/>
                                        </svg>
                                        Facebook
                                    </div>
                                    <div class="flex flex-col gap-2 w-40 items-center hover:cursor-pointer hover:transition-all hover:scale-105" id="share-on-twitter">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="56" height="56" viewBox="0 0 56 56" fill="none">
                                            <path d="M55.9961 28.001C55.9961 42.1427 45.5137 53.834 31.8963 55.7312C30.623 55.9078 29.3203 56 27.998 56C26.4717 56 24.9728 55.8784 23.5132 55.6429C10.1822 53.4966 0 41.9367 0 28.001C0 12.5369 12.5364 0 28 0C43.4636 0 56 12.5369 56 28.001H55.9961Z" fill="#1C1C1B"/>
                                            <path d="M11.3558 12.3486L24.2689 29.6138L11.2754 43.6516H14.2006L25.5775 31.3619L34.7689 43.6516H44.7216L31.0826 25.4152L43.1776 12.3486H40.2524L29.7759 23.6671L21.3104 12.3486H11.3578H11.3558ZM15.6563 14.5029H20.2275L40.4172 41.4974H35.846L15.6563 14.5029Z" fill="white"/>
                                        </svg>
                                        Twitter
                                    </div>
                                    <div class="flex flex-col gap-2 w-40 items-center hover:cursor-pointer hover:transition-all hover:scale-105" id="share-on-reddit">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="56" height="56" viewBox="0 0 56 56" fill="none">
                                            <path d="M27.9362 0H28.0637C43.4827 0 56 12.5173 56 27.9362V28.0638C56 43.4827 43.4827 56 28.0637 56H27.9362C12.5173 56 0 43.4827 0 28.0638V27.9362C0 12.5173 12.5173 0 27.9362 0Z" fill="#FF4500"/>
                                            <path d="M45.3487 27.299C44.8837 25.1899 42.9571 23.8225 41.0462 24.2443C40.3948 24.3875 39.8336 24.723 39.3902 25.1821C36.8181 23.3143 33.3062 22.0802 29.3843 21.8468L31.2619 14.0304L36.8319 15.3625C36.9241 16.885 38.1856 18.0916 39.7316 18.0916C41.2776 18.0916 42.6392 16.7889 42.6392 15.184C42.6392 13.5791 41.3365 12.2764 39.7316 12.2764C38.5682 12.2764 37.5656 12.9611 37.1026 13.9499L31.0598 12.504C30.585 12.3902 30.1083 12.6825 29.9925 13.1573L27.9148 21.8075C23.5396 21.8311 19.5922 23.0965 16.7591 25.1232C16.6198 24.9937 16.4708 24.8741 16.3079 24.7681C14.6579 23.7145 12.3781 24.3365 11.2166 26.1552C10.0552 27.9739 10.4495 30.3047 12.0995 31.3583C12.1976 31.4211 12.2977 31.476 12.3997 31.527C12.333 31.9332 12.2977 32.3471 12.2977 32.765C12.2977 38.8177 19.3431 43.7245 28.0345 43.7245C36.7259 43.7245 43.7713 38.8177 43.7713 32.765C43.7713 32.3412 43.734 31.9234 43.6653 31.5133C45.0054 30.7344 45.7352 29.0373 45.3526 27.299H45.3487ZM19.965 30.2263C19.965 28.847 21.0833 27.7287 22.4626 27.7287C23.8418 27.7287 24.9601 28.847 24.9601 30.2263C24.9601 31.6055 23.8418 32.7238 22.4626 32.7238C21.0833 32.7238 19.965 31.6055 19.965 30.2263ZM33.836 38.1114C33.0689 38.8078 30.95 40.4323 27.8481 40.4618C27.8207 40.4618 27.7912 40.4618 27.7637 40.4618C24.6207 40.4618 22.4743 38.8216 21.6915 38.1114C21.4404 37.8838 21.4227 37.4953 21.6503 37.2461C21.8779 36.995 22.2664 36.9773 22.5155 37.2049C23.1963 37.8249 25.0778 39.263 27.8363 39.2375C30.5183 39.212 32.3488 37.8092 33.0119 37.2069C33.2631 36.9793 33.6496 36.997 33.8772 37.2481C34.1048 37.4992 34.0871 37.8857 33.836 38.1133V38.1114ZM33.5339 32.7238C32.1546 32.7238 31.0363 31.6055 31.0363 30.2263C31.0363 28.847 32.1546 27.7287 33.5339 27.7287C34.9131 27.7287 36.0314 28.847 36.0314 30.2263C36.0314 31.6055 34.9131 32.7238 33.5339 32.7238Z" fill="white"/>
                                        </svg>
                                        Reddit
                                    </div>
                                    <div class="flex flex-col gap-2 w-40 items-center hover:cursor-pointer hover:transition-all hover:scale-105" id="share-on-whatsapp">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="56" height="56" viewBox="0 0 56 56" fill="none">
                                            <path d="M28.0638 0H27.9362C12.5075 0 0 12.5075 0 27.9362V28.0638C0 43.4925 12.5075 56 27.9362 56H28.0638C43.4925 56 56 43.4925 56 28.0638V27.9362C56 12.5075 43.4925 0 28.0638 0Z" fill="#00E510"/>
                                            <path d="M33.4569 36.1905C26.2899 36.1905 20.459 30.3576 20.457 23.1906C20.459 21.3738 21.9383 19.8965 23.7512 19.8965C23.9375 19.8965 24.1219 19.9122 24.2985 19.9436C24.687 20.0083 25.0559 20.1398 25.3953 20.3379C25.4443 20.3674 25.4777 20.4144 25.4855 20.4694L26.2428 25.2428C26.2526 25.2997 26.235 25.3546 26.1977 25.3958C25.7798 25.8589 25.2462 26.1924 24.6517 26.3592L24.3652 26.4396L24.4731 26.7162C25.4502 29.204 27.4396 31.1914 29.9293 32.1724L30.206 32.2823L30.2864 31.9958C30.4532 31.4014 30.7867 30.8677 31.2497 30.4498C31.2831 30.4184 31.3282 30.4027 31.3733 30.4027C31.3831 30.4027 31.393 30.4027 31.4047 30.4047L36.1781 31.162C36.235 31.1718 36.2821 31.2032 36.3116 31.2523C36.5078 31.5917 36.6392 31.9625 36.7059 32.351C36.7373 32.5236 36.751 32.7061 36.751 32.8964C36.751 34.7112 35.2737 36.1885 33.4569 36.1905Z" fill="#FDFDFD"/>
                                            <path d="M46.1667 26.4278C45.7802 22.0605 43.7791 18.009 40.532 15.021C37.2654 12.0153 33.0275 10.3594 28.5955 10.3594C18.8681 10.3594 10.9536 18.2739 10.9536 28.0012C10.9536 31.2659 11.8542 34.4463 13.5591 37.2165L9.75684 45.6333L21.9308 44.3365C24.0477 45.2036 26.2882 45.6431 28.5935 45.6431C29.1998 45.6431 29.8217 45.6117 30.4456 45.547C30.995 45.4881 31.5502 45.4018 32.0956 45.2919C40.2417 43.6459 46.1883 36.4161 46.2354 28.0954V28.0012C46.2354 27.4715 46.2119 26.9418 46.1648 26.4278H46.1667ZM22.3997 40.6421L15.6643 41.3602L17.6753 36.9046L17.2731 36.3651C17.2437 36.3258 17.2142 36.2866 17.1809 36.2414C15.4347 33.8302 14.5126 30.9815 14.5126 28.0032C14.5126 20.2378 20.8301 13.9203 28.5955 13.9203C35.8704 13.9203 42.0368 19.5962 42.6313 26.8417C42.6627 27.2302 42.6804 27.6206 42.6804 28.0052C42.6804 28.115 42.6784 28.2229 42.6764 28.3387C42.5273 34.8347 37.9893 40.3517 31.6404 41.7565C31.1558 41.8644 30.6595 41.9468 30.1651 41.9998C29.651 42.0586 29.1233 42.0881 28.5994 42.0881C26.7336 42.0881 24.9208 41.7271 23.208 41.0129C23.0177 40.9364 22.8313 40.854 22.6567 40.7696L22.4016 40.646L22.3997 40.6421Z" fill="#FDFDFD"/>
                                        </svg>
                                        Whatsapp
                                    </div>
                                    <div class="flex flex-col gap-2 w-40 items-center hover:cursor-pointer hover:transition-all hover:scale-105" id="share-on-email">
                                        <div class="p-3 rounded-full bg-primary flex items-center justify-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 32 32" fill="none">
                                                <g clip-path="url(#clip0_1049_12536)">
                                                <path d="M28 7L16 18L4 7" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                                <path d="M4 7H28V24C28 24.2652 27.8946 24.5196 27.7071 24.7071C27.5196 24.8946 27.2652 25 27 25H5C4.73478 25 4.48043 24.8946 4.29289 24.7071C4.10536 24.5196 4 24.2652 4 24V7Z" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                                <path d="M13.8225 16L4.3125 24.7175" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                                <path d="M27.6897 24.7175L18.1797 16" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                                </g>
                                                <defs>
                                                <clipPath id="clip0_1049_12536">
                                                    <rect width="32" height="32" fill="white"/>
                                                </clipPath>
                                                </defs>
                                            </svg>
                                        </div>
                                        Email
                                    </div>
                                </div>
                            </div>
                            <div class="py-6 flex items-center gap-2 flex-wrap md:flex-nowrap justify-center">
                                <input type="text" value="<?php echo $product_permalink  ?>" class="input border-borderColor rounded-xl bg-white w-full max-w-lg" id="accLink" />
                                <div class="tooltip tooltip-bottom tooltip-success flex" data-tip="link copied">
                                    <button class="btn bg-primary border-0 text-white capitalize hover:scale-105" id="copy-accLink">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                            <g clip-path="url(#clip0_1349_13465)">
                                            <path d="M17.25 6.75H3.75V20.25H17.25V6.75Z" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                            <path d="M6.75 3.75H20.25V17.25" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                            </g>
                                            <defs>
                                            <clipPath id="clip0_1349_13465">
                                                <rect width="24" height="24" fill="white"/>
                                            </clipPath>
                                            </defs>
                                        </svg>
                                        Copy
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="tab9" class="hidden tabContent">

                        <div class="flex w-full justify-between items-center mb-8 lg:mb-6 flex-col md:flex-row">
                            <div class="flex gap-5 items-center w-full justify-start">
                                <h1 class="text-3xl font-medium text-textColor pl-20 xl:pl-0">Switch Account</h1>
                            </div>
                        </div>
                        <div class="flex flex-col w-full gap-4">
                            <h3 class="text-xl text-textColor text-center sm:text-left">CBC permits four different user types and you can access your Account Dashboard for each user type</h3>
                            <ul>
                                <li class="text-textColor"><span class="text-primary font-medium">1. Candidate:</span> Access your Account as a Candidate user</li>
                                <li class="text-textColor"><span class="text-primary font-medium">2. Donor:</span> Access your Account as a Donor user</li>
                                <li class="text-textColor"><span class="text-primary font-medium">3. Advocate:</span> Access your Account as an Advocate user</li>
                                <li class="text-textColor"><span class="text-primary font-medium">4. Physician:</span> Access your Account as a Physician user</li>
                            </ul>
                            <div class="w-full flex flex-col">
                                <label class="label">
                                    <span class="label-text">Account type</span>
                                </label>

                                <select class="select select-bordered w-full sm:max-w-xs bg-white border-borderColor rounded-xl" id="userTypeSelect">
                                <?php echo $options ?>
                                </select>

                            </div>
                            <button class="btn bg-primary text-white w-full sm:w-56 hover:scale-105" id="switchAcc">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                    <g clip-path="url(#clip0_1068_61688)">
                                    <path d="M6 6C6 6 8.25 3.75 12 3.75C17.25 3.75 20.25 9 20.25 9" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                    <path d="M18 18C18 18 15.75 20.25 12 20.25C6.75 20.25 3.75 15 3.75 15" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                    <path d="M15.75 9H20.25V4.5" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                    <path d="M8.25 15H3.75V19.5" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                    </g>
                                    <defs>
                                    <clipPath id="clip0_1068_61688">
                                        <rect width="24" height="24" fill="white"/>
                                    </clipPath>
                                    </defs>
                                </svg>
                                Switch account
                            </button>
                        </div>

                    </div>

                    <!-- <div id="tab10" class="hidden tabContent">
                        <div class="flex w-full flex-col gap-8 items-end xl:items-start">
                            <a href="#tab2" id="reqBack" class="text-primary w-32 px-4 py-2 flex items-center gap-3">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                        <g clip-path="url(#clip0_2152_40054)">
                                            <path d="M15 4.5L7.5 12L15 19.5" stroke="#143A62" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                        </g>
                                        <defs>
                                            <clipPath id="clip0_2152_40054">
                                            <rect width="24" height="24" fill="white" transform="matrix(0 -1 -1 0 24 24)"/>
                                            </clipPath>
                                        </defs>
                                    </svg>
                                    Back
                            </a>
                            <div class="flex w-full flex-col gap-4">
                                <div class="flex items-center text-lg gap-1 font-semibold pl-5 xl:pl-0">
                                    <h2 class="text-textColor">Request</h2>
                                    <span class="text-textValue">(3)</span>
                                </div>
                                <div class="requests-wrapper flex flex-col gap-4 overflow-y-auto h-full max-h-490 reqCards-scroll p-2">
                                    <div class="w-full flex flex-col md:flex-row px-4 py-3 rounded-lg gap-4 shadowCard">
                                        <div class="flex w-full flex-col gap-4">
                                            <div class="flex items-center gap-4">
                                                <h2 class="text-lg text-primary font-bold">Tredinnick S.</h2>
                                                <div class="badge bg-badgeM badgeM text-blueMain py-3">Urologists</div>
                                            </div>
                                            <div class="flex flex-col gap-1">
                                                <a href="tel:+16177893000">+1 617-789-3000</a>
                                                <a href="#" class="flex gap-1 items-center">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="17" viewBox="0 0 16 17" fill="none">
                                                        <g clip-path="url(#clip0_2152_34177)">
                                                            <path d="M3.5 15H12.5" stroke="#143A62" stroke-linecap="round" stroke-linejoin="round"/>
                                                            <path d="M8 9C9.10457 9 10 8.10457 10 7C10 5.89543 9.10457 5 8 5C6.89543 5 6 5.89543 6 7C6 8.10457 6.89543 9 8 9Z" stroke="#143A62" stroke-linecap="round" stroke-linejoin="round"/>
                                                            <path d="M13 7C13 11.5 8 15 8 15C8 15 3 11.5 3 7C3 5.67392 3.52678 4.40215 4.46447 3.46447C5.40215 2.52678 6.67392 2 8 2C9.32608 2 10.5979 2.52678 11.5355 3.46447C12.4732 4.40215 13 5.67392 13 7Z" stroke="#143A62" stroke-linecap="round" stroke-linejoin="round"/>
                                                        </g>
                                                        <defs>
                                                            <clipPath id="clip0_2152_34177">
                                                            <rect width="16" height="16" fill="white" transform="translate(0 0.5)"/>
                                                            </clipPath>
                                                        </defs>
                                                    </svg>
                                                    Brigham and Women's Hospital
                                                </a>
                                                <a href="#" class="text-blueMain">https://www.massgeneral.org</a>
                                            </div>
                                        </div>
                                        <div class="flex w-full justify-end flex-wrap gap-4">
                                            <div class="flex gap-3 items-center">
                                                <button class="px-4 py-2 bg-primary rounded-xl text-white border-primary hover:scale-105">Aprove</button>
                                                <button class="px-4 py-2 bg-white border-warning rounded-xl text-warning hover:scale-105 hover:bg-red-200 hover:border-red-200 hover:text-red-500">Decline</button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="w-full flex flex-col md:flex-row px-4 py-3 rounded-lg gap-4 shadowCard">
                                        <div class="flex w-full flex-col gap-4">
                                            <div class="flex items-center gap-4">
                                                <h2 class="text-lg text-primary font-bold">Tredinnick S.</h2>
                                                <div class="badge bg-badgeM badgeM text-blueMain py-3">Urologists</div>
                                            </div>
                                            <div class="flex flex-col gap-1">
                                                <a href="tel:+16177893000">+1 617-789-3000</a>
                                                <a href="#" class="flex gap-1 items-center">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="17" viewBox="0 0 16 17" fill="none">
                                                        <g clip-path="url(#clip0_2152_34177)">
                                                            <path d="M3.5 15H12.5" stroke="#143A62" stroke-linecap="round" stroke-linejoin="round"/>
                                                            <path d="M8 9C9.10457 9 10 8.10457 10 7C10 5.89543 9.10457 5 8 5C6.89543 5 6 5.89543 6 7C6 8.10457 6.89543 9 8 9Z" stroke="#143A62" stroke-linecap="round" stroke-linejoin="round"/>
                                                            <path d="M13 7C13 11.5 8 15 8 15C8 15 3 11.5 3 7C3 5.67392 3.52678 4.40215 4.46447 3.46447C5.40215 2.52678 6.67392 2 8 2C9.32608 2 10.5979 2.52678 11.5355 3.46447C12.4732 4.40215 13 5.67392 13 7Z" stroke="#143A62" stroke-linecap="round" stroke-linejoin="round"/>
                                                        </g>
                                                        <defs>
                                                            <clipPath id="clip0_2152_34177">
                                                            <rect width="16" height="16" fill="white" transform="translate(0 0.5)"/>
                                                            </clipPath>
                                                        </defs>
                                                    </svg>
                                                    Brigham and Women's Hospital
                                                </a>
                                                <a href="#" class="text-blueMain">https://www.massgeneral.org</a>
                                            </div>
                                        </div>
                                        <div class="flex w-full justify-end flex-wrap gap-4">
                                            <div class="flex gap-3 items-center">
                                                <button class="px-4 py-2 bg-primary rounded-xl text-white border-primary hover:scale-105">Aprove</button>
                                                <button class="px-4 py-2 bg-white border-warning rounded-xl text-warning hover:scale-105 hover:bg-red-200 hover:border-red-200 hover:text-red-500">Decline</button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="w-full flex flex-col md:flex-row px-4 py-3 rounded-lg gap-4 shadowCard">
                                        <div class="flex w-full flex-col gap-4">
                                            <div class="flex items-center gap-4">
                                                <h2 class="text-lg text-primary font-bold">Tredinnick S.</h2>
                                                <div class="badge bg-badgeM badgeM text-blueMain py-3">Urologists</div>
                                            </div>
                                            <div class="flex flex-col gap-1">
                                                <a href="tel:+16177893000">+1 617-789-3000</a>
                                                <a href="#" class="flex gap-1 items-center">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="17" viewBox="0 0 16 17" fill="none">
                                                        <g clip-path="url(#clip0_2152_34177)">
                                                            <path d="M3.5 15H12.5" stroke="#143A62" stroke-linecap="round" stroke-linejoin="round"/>
                                                            <path d="M8 9C9.10457 9 10 8.10457 10 7C10 5.89543 9.10457 5 8 5C6.89543 5 6 5.89543 6 7C6 8.10457 6.89543 9 8 9Z" stroke="#143A62" stroke-linecap="round" stroke-linejoin="round"/>
                                                            <path d="M13 7C13 11.5 8 15 8 15C8 15 3 11.5 3 7C3 5.67392 3.52678 4.40215 4.46447 3.46447C5.40215 2.52678 6.67392 2 8 2C9.32608 2 10.5979 2.52678 11.5355 3.46447C12.4732 4.40215 13 5.67392 13 7Z" stroke="#143A62" stroke-linecap="round" stroke-linejoin="round"/>
                                                        </g>
                                                        <defs>
                                                            <clipPath id="clip0_2152_34177">
                                                            <rect width="16" height="16" fill="white" transform="translate(0 0.5)"/>
                                                            </clipPath>
                                                        </defs>
                                                    </svg>
                                                    Brigham and Women's Hospital
                                                </a>
                                                <a href="#" class="text-blueMain">https://www.massgeneral.org</a>
                                            </div>
                                        </div>
                                        <div class="flex w-full justify-end flex-wrap gap-4">
                                            <div class="flex gap-3 items-center">
                                                <button class="px-4 py-2 bg-primary rounded-xl text-white border-primary hover:scale-105">Aprove</button>
                                                <button class="px-4 py-2 bg-white border-warning rounded-xl text-warning hover:scale-105 hover:bg-red-200 hover:border-red-200 hover:text-red-500">Decline</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div> -->
                </div>
            </div>
        </div>
        <dialog id="allRequests" class="modal">
            <div class="modal-box bg-white text-textColor max-w-4xl">
                <form method="dialog">
                    <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
                </form>
                <h2 class="font-bold text-3xl px-6 pb-8 border-b border-borderColor">All Requests:</h2>
                <?php 
                
                if(count($unique_phs) !=0 ) {
                    $total_requests = count($unique_phs);                     
                    foreach($unique_phs as $object) {
                        $user_meta = get_user_meta($object->physician);
                        $user_data = get_userdata($object->physician);             

                    ?>
               
                
                    <div class="flex flex-col gap-4 pt-8 px-8 pb-1 items-center max-h-96 overflow-auto">
                            <div class="w-full flex flex-col md:flex-row px-4 py-3 rounded-lg gap-4 shadowCard">
                                <div class="flex w-full flex-col gap-4">
                                    <div class="flex items-center gap-4">
                                        <h2 class="text-lg text-primary font-bold">
                                        <?php echo $user_data->first_name . ' ' . $user_data->last_name; ?>
                                        </h2>
                                        <div class="badge bg-badgeM badgeM text-blueMain py-3">
                                            <?php echo $user_meta['speciality'][0]; ?>
                                        </div>
                                    </div>
                                    <div class="flex flex-col gap-1">
                                    <a href="tel:<?php echo $user_meta['phone'][0]; ?>"><?php echo ($user_meta['phone'][0]) ? $user_meta['phone'][0] : 'No phone provided'; ?>
                                                                </a>
                                        <a href="#" class="flex gap-1 items-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="17" viewBox="0 0 16 17" fill="none">
                                                <g clip-path="url(#clip0_2152_34177)">
                                                    <path d="M3.5 15H12.5" stroke="#143A62" stroke-linecap="round" stroke-linejoin="round"/>
                                                    <path d="M8 9C9.10457 9 10 8.10457 10 7C10 5.89543 9.10457 5 8 5C6.89543 5 6 5.89543 6 7C6 8.10457 6.89543 9 8 9Z" stroke="#143A62" stroke-linecap="round" stroke-linejoin="round"/>
                                                    <path d="M13 7C13 11.5 8 15 8 15C8 15 3 11.5 3 7C3 5.67392 3.52678 4.40215 4.46447 3.46447C5.40215 2.52678 6.67392 2 8 2C9.32608 2 10.5979 2.52678 11.5355 3.46447C12.4732 4.40215 13 5.67392 13 7Z" stroke="#143A62" stroke-linecap="round" stroke-linejoin="round"/>
                                                </g>
                                                <defs>
                                                    <clipPath id="clip0_2152_34177">
                                                    <rect width="16" height="16" fill="white" transform="translate(0 0.5)"/>
                                                    </clipPath>
                                                </defs>
                                            </svg>
                                            <?php echo $user_meta['business_name'][0]; ?>
                                        </a>
                                        <a href="<?php echo $user_meta['url'][0]; ?>">
                                            <?php echo $user_meta['url'][0]; ?>
                                        </a>
                                    </div>
                                </div>
                                <div class="flex w-full justify-end flex-wrap gap-4">
                                    <div class="flex gap-3 items-center">
                                        <button class="px-4 py-2 bg-primary rounded-xl text-white border-primary hover:scale-105">Approve</button>
                                        <button class="px-4 py-2 bg-white border-warning rounded-xl text-warning hover:scale-105 hover:bg-red-200 hover:border-red-200 hover:text-red-500">Decline</button>
                                    </div>
                                </div>
                            </div>
                    </div>
                    <?php }
                } else { ?>
                         <div class="text-center text-2xl text-textValue py-5 w-full">No request</div>
                <?php } ?>
 
                <div class="flex items-center text-lg gap-1 pl-6 mt-4 xl:pl-0">
                    
                    <h3 class="text-textColor font-semibold">Requests:</h3>
                    <span class="text-textValue">(<?php echo $total_requests; ?>)</span>
                </div> 
            </div>
        </dialog>
        <dialog id="logout" class="modal">
            <div class="modal-box bg-white text-textColor max-w-xl">
                <form method="dialog" class="m-0">
                    <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
                </form>
                <h3 class="font-bold text-3xl px-6 pb-8 border-b border-borderColor">Logout</h3>
                <div class="flex flex-col gap-6 pt-8 sm:px-8 pb-1">
                    <span>Are you sure that you want to log out from your account?</span>
                    <div class="flex items-center gap-4 justify-center w-full flex-col sm:flex-row">
                        <form method="dialog" class="m-0 w-full">
                            <button class="btn border border-primary bg-white text-primary w-full sm:w-48 hover:scale-105 hover:text-primary hover:bg-transparent">Cancel</button>
                        </form>
                        <a href="<?php echo wp_logout_url(); ?>"><button class="btn w-full sm:w-48 bg-warning text-white border-warning hover:scale-105 hover:text-red-500 hover:bg-warning hover:bg-opacity-40 hover:border-warning">Log Out</button></a>
                    </div>
                </div>
            </div>
        </dialog>
        <dialog id="delImg" class="modal">
            <div class="modal-box bg-white text-textColor max-w-xl">
                <form method="dialog">
                    <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
                </form>
                <h3 class="font-bold text-3xl px-6 pb-8 border-b border-borderColor">Delete Image</h3>
                <div class="flex flex-col gap-6 pt-8 px-8 pb-1">
                    <span>Are you sure that you want to delete image?</span>
                    <div class="flex items-center gap-4 justify-center w-full">
                        <form method="dialog" class="m-0">
                            <button class="btn border border-primary bg-white text-primary w-48">Cancel</button>
                        </form>
                        <button class="btn w-48 bg-warning text-white" id="deleteProductImage">Delete</button>
                    </div>
                </div>
            </div>
        </dialog>
        <dialog id="delAcc" class="modal">
            <div class="modal-box bg-white text-textColor max-w-4xl">
                <form method="dialog">
                    <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
                </form>
                <h3 class="font-bold text-3xl px-6 pb-8 border-b border-borderColor">Delete Account</h3>
                <div class="flex flex-col gap-6 pt-8 px-8 pb-1 items-center">
                    <img src="https://childfreebc.com/wp-content/uploads/2023/10/29268674_hand_illustration-Converted-1.svg" alt="delete account" class="w-96 h-96" />
                    <span class="text-xs text-textValue">If you would like to permanently delete your account, make any other changes to your account, or opt out of any portions of our Terms and Conditions or Privacy Policy, please e-mail us at <a href="registrations@childfreebc.com" class="text-primary font-normal">registrations@childfreebc.com</a> and we will assist with your request promptly.</span>
                </div>
            </div>
        </dialog>
    </main>

    <?php
}
?>

<!-- Charts -->
<!-- <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const data = {
labels: ['Completed: 47%', 'In Progress: 48%', 'Cancelled: 5%'],
datasets: [
    {
        label: 'My First Dataset',
        data: [300, 50, 10],
        backgroundColor: ['rgb(2, 169, 92)', 'rgb(255, 212, 90)', 'rgb(255, 41, 25)'],
        hoverOffset: 4
    }
]
};

const ctx = document.getElementById('overviewChart').getContext('2d');
const overview = new Chart(ctx, {
type: 'doughnut',
data: data,
options: {
    cutout: '90%',
    elements: {
        arc: {
            borderWidth: 1,
        }
    },
    plugins: {
        legend: {
            display: true,
            position: 'right',
            labels: {
                usePointStyle: true,
                boxWidth: 10,
                borderRadius: 100,
            }
        }
    }
}
});

</script>

<script>
const stat = {
    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
    datasets: [{
        data: [157, 370, 170, 5, 200, 120, 290, 450, 190, 0, 0],
        backgroundColor: [
            'rgba(11, 70, 160)',

        ],
        borderWidth: 0,
        borderRadius: 100,
        borderDash: [5, 5],
    }]
};

const cts = document.getElementById('statisticsChart').getContext('2d');
const statistics = new Chart(cts, {
    type: 'bar',
    data: stat,
    options: {
    scales: {
        yAxes: [{
            gridLines: {
                drawBorder: false,
                display: false
            },
            ticks: {
                display: false,
                beginAtZero: true
            },
        }],
        xAxes: [{
            gridLines: {
                color: 'rgba(132, 151, 171, 0.2)',
                borderWidth: 0.5,
                borderDash: [5, 5],
            }
        }]
    },
    legend: {
        display: false
    },
    tooltips: {
        enabled: false
    }
}
});
</script> -->
<?php get_footer() ?>