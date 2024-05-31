<?php 
use WZ\ChildFree\Models\Provider;

add_action('wp_ajax_provider-pagination-load-posts', 'providers_pagination_load_posts');
add_action('wp_ajax_nopriv_provider-pagination-load-posts', 'providers_pagination_load_posts');
function providers_pagination_load_posts()
{
    if(isset($_POST['speciality'])){
        $selected_specialties = $_POST['speciality'];
    }
    
    if (isset($_POST['order_by']) && !empty($_POST['order_by'])) {
        $phorder_by = sanitize_text_field($_POST['order_by']);
        $phorder = sanitize_text_field($_POST['order']);

    } else {
        $phorder_by = 'registered'; // this is the default sort order (no-order) when the candidates page is initially loaded in browser.
        $phorder = 'DESC';
    }

    if (isset($_POST['per_page']) && !empty($_POST['per_page'])) {
        $per_page = sanitize_text_field($_POST['per_page']);
    } else {
        $per_page = 9;
    }


    if (isset($_POST['search_title']) && !empty($_POST['search_title'])) {
        $search_title = strtolower(sanitize_text_field($_POST['search_title']));
    } else {
        $search_title = '';
    }
    $meta_query[] = array(
    'relation' => 'AND',

    array(
        'key'     => 'nickname',
        'value'   => $search_title,
        'compare' => 'LIKE'
    )
    );

    $page = sanitize_text_field($_POST['page']);
    $cur_page = $page;
    $previous_btn = true;
    $next_btn = true;
    $first_btn = true;
    $last_btn = true;

    if (isset($_POST['phzip_code']) && !empty($_POST['phzip_code'])){
        $zip_code = sanitize_text_field($_POST['phzip_code']);
        $phlat = $_POST['phlat'];
        $phlng = $_POST['phlng'];
        $phdestination = $_POST['phdestination'];
    }
    $zip_code_array = !empty($zip_code) ? array('key' => '_location', 'value' => $zip_code, 'compare' => '=') : array();


    if (isset($phdestination) && isset($_POST['phlat']) && isset($_POST['phlng'])) {

        $ph_id = find_users_by_location_and_distance($phlat, $phlng, $phdestination);
        //$ph_physicians = count($ph_id);
 
        if (!empty($ph_id)) {
            $include = $ph_id; // Include only users with specified IDs
        } else {
            $include[] = '0';
        }
       
    }

    if (!empty($selected_specialties)) {
        $meta_query = array(
            'relation' => 'OR', // Use 'OR' relation to match users with any of the selected specialties
        );

        foreach ($selected_specialties as $speciality) {
            $meta_query[] = array(
                'key'     => 'speciality', // Meta key name
                'value'   => $speciality, // Meta value (specialty)
                'compare' => '=', // Match exact meta value
            );
          
        }
    }
    
    $args = array(

        'number' => $page * $per_page,
        'orderby' => $phorder_by,
        'order' => $phorder,
        'starts_with' => $search_title,
        'role'       => 'medical_provider', 
        'meta_query' => $meta_query,
        'include' => $include
    );
//     echo '<pre>';
// print_r ($args);
// die();
    // Create a new instance of WP_User_Query
    $medical_providers_query = new WP_User_Query( $args );
    
    // Get the results
    $medical_providers = $medical_providers_query->get_results();

    //if ($ph_physicians >= 1)

  
    // Output the results
    // $providers_content .= '<table class="table table-zebra"><tr class="text-xs font-semibold bg-primary bg-opacity-5 text-info border-b border-borderColor" style="color: #8497AB !important"><th>Clinic1</th><th>Name</th><th>Website</th><th>Phone number</th><th>Expertise </th><th></th><tr>';
    // if ( ! empty( $medical_providers ) ) {

    //     $showButton = ((floor(count($medical_providers)/$per_page) + 1) > 1) ? true : false;
    //     foreach ( $medical_providers as $provider ) {
    //        // echo ('<pre>');
    //        // print_r($provider);
    //     //    echo 'YEPPP';
    //     //     echo (get_user_meta($provider -> id));
    //     //     die();
    //         $providers_content .= '<tr><td>' . $provider-> business_name. '</td><td>' . $provider->nickname . '</td><td>'.$provider -> url . '</td><td>'. $provider -> phone . '</td><td>'.$provider -> speciality . '</td><td><button class="btn select-ph" data-provider-id="'.$provider -> id.'"  data-provider-business-name="'. $provider-> business_name.'" data-provider-name="' . $provider-> nickname. '"  data-provider-url="' . $provider-> url. '" data-provider-speciality="'.$provider -> speciality.'" data-provider-phone="'.$provider -> phone.'">Select</button></td></tr>';
    //     }
    // } else {
    //     $providers_content .= 'No Medical Providers found.';
    // }
    // $providers_content .= ' </table>';
    // $cur_page++;
    // if ($showButton){
    //     $providers_content .= '<div class="show_more"><button class="btn" data-page-number="'.$cur_page.'">Show more</button></div>';
    // }
    // echo $providers_content;
    // die();

    $providers_content = '<table class="table table-zebra">';
    $providers_content .= '<tr class="text-xs font-semibold bg-primary bg-opacity-5 text-info border-b border-borderColor h-14" style="color: #8497AB !important">
                            <th>Clinic</th><th>Name</th><th>Website</th><th>Phone number</th><th>Expertise</th><th></th>
                        </tr>';

    if (!empty($medical_providers)) {
        $showButton = ((floor(count($medical_providers) / $per_page) + 1) > 1) ? true : false;
        $row_index = 0;
        foreach ($medical_providers as $provider) {
            $is_even = ($row_index % 2 === 0) ? true : false;

            $providers_content .= '<tr class="h-14 border-0 ' . ($is_even ? 'bg-primary bg-opacity-5' : 'bg-white') . '">';
            $providers_content .= '<td>' . htmlspecialchars($provider->business_name) . '</td>';
            $providers_content .= '<td>' . htmlspecialchars($provider->nickname) . '</td>';
            $providers_content .= '<td>' . htmlspecialchars($provider->url) . '</td>';
            $providers_content .= '<td>' . htmlspecialchars($provider->phone) . '</td>';
            $providers_content .= '<td>';

            if (htmlspecialchars($provider->speciality) === 'Urologist') {
                $providers_content .= '<span class="py-1 px-2 border rounded-full" style="font-family: Be Vietnam Pro; font-size: 12px; font-style: normal; font-weight: 500; line-height: 150%; color: #0B46A0; border-color: #0B46A0; background: #EDF3FE;">Urologist</span>';
            } elseif (htmlspecialchars($provider->speciality) === 'OB-GYN') {
                $providers_content .= '<span class="py-1 px-2 border rounded-full" style="font-family: Be Vietnam Pro; font-size: 12px; font-style: normal; font-weight: 500; line-height: 150%; color: #8802A9; border-color: #8802A9; background: #F3E6F6; white-space: nowrap;">OB-GYN</span>';
            } else {
                $providers_content .= '<span class="py-1 px-2 rounded-full" style="font-family: Be Vietnam Pro; font-size: 12px; font-style: normal; font-weight: 500; line-height: 150%; white-space: nowrap;">'. htmlspecialchars($provider->speciality) .'</span>';
            }

            $providers_content .= '</td>';
            $providers_content .= '<td><button class="px-4 py-2 bg-primary rounded-xl text-center text-white border-primary bg-primary hover:scale-105 select-ph" data-provider-id="' . htmlspecialchars($provider->id) . '"  
                data-provider-business-name="' . htmlspecialchars($provider->business_name) . '"
                data-provider-name="' . htmlspecialchars($provider->nickname) . '"  
                data-provider-url="' . htmlspecialchars($provider->url) . '" 
                data-provider-speciality="' . htmlspecialchars($provider->speciality) . '" 
                data-provider-phone="' . htmlspecialchars($provider->phone) . '">Select</button></td>';
            $providers_content .= '</tr>';
            $row_index++;
        }
    } else {
        $providers_content .= '<tr><td colspan="6">No Medical Providers found.</td></tr>';
    }

    $providers_content .= '</table>';
    $cur_page++;
    if ($showButton) {
        $providers_content .= '<div class="show_more"><button class="btn" data-page-number="' . $cur_page . '">Show more</button></div>';
    }

    echo $providers_content;
    die();
} 



function find_users_by_location_and_distance($latitude, $longitude, $distance) {
    // Step 1: Get the latitude, longitude, and distance provided by the user
    $form_latitude = floatval($latitude);
    $form_longitude = floatval($longitude);
    $user_distance = intval($distance); // Assuming distance is provided in miles

    // Step 2: Find all users with the role "medical_provider" and meta fields "latitude" and "longitude"
    $args = array(
        'role' => 'medical_provider',
    );

    $user_query = new WP_User_Query($args);
    $users = $user_query->get_results();

    $users_within_distance = array();
    
        $form_id = 11; // ID of the register Physicians form
        $field_id = 82; // ID of the Address field in these form
        
        // Get all entries for the specified form
        $entries = FrmEntry::getAll(array('form_id' => $form_id));

        foreach ($entries as $entry) {
            $address_value = FrmProEntriesController::get_field_value_shortcode(array('field_id' => $field_id, 'entry_id' => $entry->id));
            
            $parts = explode(',', $address_value);
            $zip_code = trim(end($parts)); 

      

            // Construct the URL with the address and API key (replace YOUR_API_KEY with your actual API key)
            $url = 'https://maps.googleapis.com/maps/api/geocode/json?address=' . urlencode($address_value) . '&key=AIzaSyB5Dc2DupDK2lz6m0J3TJglvixt8gnqXSE';

            // cURL session
            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'GET',
            ));

            $response = curl_exec($curl);

            if ($response === false) {
                $error_message = curl_error($curl);
                echo "cURL error: " . $error_message;
            }

            curl_close($curl);

            // Decode the JSON response
            $geocode_data = json_decode($response, true);

            // Extract latitude and longitude from the response (assuming successful response)
            if ($geocode_data && $geocode_data['status'] === 'OK') {
                $user_latitude = $geocode_data['results'][0]['geometry']['location']['lat'];
                $user_longitude = $geocode_data['results'][0]['geometry']['location']['lng'];
               // $usertest[] = "Latitude: $latitude, Longitude: $longitude";
                $user_distance_to_location = calculate_distance($form_latitude, $form_longitude, $user_latitude, $user_longitude);
                    if ($user_distance_to_location <= $user_distance) {
                        $users_within_distance[] = $user;
                    }

            } 
        
    }
    return $users_within_distance;
}

    // Function to calculate distance between two coordinates (latitude and longitude)
    function calculate_distance($lat1, $lon1, $lat2, $lon2) {
        $earth_radius = 6371; // Radius of the earth in kilometers
        $dlat = deg2rad($lat2 - $lat1);
        $dlon = deg2rad($lon2 - $lon1);
        $a = sin($dlat / 2) * sin($dlat / 2) + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($dlon / 2) * sin($dlon / 2);
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        $distance = $earth_radius * $c;
        return $distance;
    }


add_action('wp_ajax_update_physician_candidates', 'update_physician_candidates_list');
add_action('wp_ajax_nopriv_update_physician_candidates', 'update_physician_candidates_list');

function update_physician_candidates_list(){
    $current_user_id = get_current_user_id();

    if (isset($_POST['id']) && !empty($_POST['id'])){
        $post_id = sanitize_text_field($_POST['id']);
    } 

    if (isset($_POST['candidate_status']) && !empty($_POST['candidate_status'])){
        $candidate_status = sanitize_text_field($_POST['candidate_status']);
    } 

    if (isset($_POST['procedure_status']) && !empty($_POST['procedure_status'])){
        $procedure_status = sanitize_text_field($_POST['procedure_status']);
    } 
    // Update the candidates list for the current physician

    $new_row_data = array(
        'candidate' => $post_id, // Set the candidate post ID
        'candidate_status' => $candidate_status,
        'procedure_status' => $procedure_status
    );

    $existing_rows = get_field('candidates_for_physicians', 'user_' . $current_user_id);
    $existing_rows[] = $new_row_data;
    update_field('candidates_for_physicians', $existing_rows, 'user_' . $current_user_id);

    // Update the physician list for the selected candidate
    $product_title = preg_replace('/[^a-zA-Z0-9\s]/', '', get_the_title($post_id));
    //$product_title = get_the_title($post_id);
    $args = array(
        'role' => 'candidate', // Filter users by role 'candidate'
        'search' => '*' . $product_title . '*', // Search for display name similar to the product title
        'search_columns' => array('display_name'), // Search only in display names
        'number' => 1, // Limit to 1 result (assuming you want only one user ID)
    );
    
    // Create a new WP_User_Query instance with the search arguments
    $user_query = new WP_User_Query($args);
    
    // Get the results
    $candidates = $user_query->get_results();

    $candidate_id = $candidates[0]->ID; 

    $user_meta = get_user_meta($current_user_id);

    $new_row_data = array(
        'physician' => $current_user_id, 
        'name' => $user_meta['nickname'][0],
        'speciality' => $user_meta['speciality'][0],
        'phone_number'  =>  $user_meta['phone'][0],
        'url' => $user_meta['url'][0],
        'clinic' => $user_meta['business_name'][0]
    );



    $existing_rows = get_field('physicians_for_candidate', 'user_' . $candidate_id);
    $existing_rows[] = $new_row_data;
    update_field('physicians_for_candidate', $existing_rows, 'user_' . $candidate_id);


    echo ('success rrr');
    die();
}

?>