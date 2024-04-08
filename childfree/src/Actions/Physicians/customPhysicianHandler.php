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
    $meta_query = array(
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
   


    if (isset($phdestination) && isset($_POST['phlat']) && isset($_POST['phlng'])) {

        $ph_id = find_users_by_location_and_distance($phlat, $phlng, $phdestination);
        //$ph_physicians = count($ph_id);
        if (!empty($ph_id)) {
            $args['include'] = $ph_id; // Include only users with specified IDs
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
    );

    // Create a new instance of WP_User_Query
    $medical_providers_query = new WP_User_Query( $args );
    
    // Get the results
    $medical_providers = $medical_providers_query->get_results();

    //if ($ph_physicians >= 1)

  
    // Output the results
    $providers_content .= '<table><th>Clinic</th><th>Name</th><th>Website</th><th>Phone number</th><th>Expertise </th><th></th><tr>';
    if ( ! empty( $medical_providers ) ) {
        foreach ( $medical_providers as $provider ) {
            $providers_content .= '<tr><td>' . $provider-> business_name. '</td><td>' . $provider->nickname . '</td><td>'.$provider -> url . '</td><td>'. $provider -> phone . '</td><td>'.$provider -> speciality . '</td><td><button class="btn">Select</button></td></tr>';
            // Output other user data as needed
        }
    } else {
        $providers_content .= 'No Medical Providers found with the Specialty "urologist".';
    }
    $providers_content .= ' </table>';
    echo $providers_content;
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

    // Step 3-4: Filter users based on the distance criteria
    $users_within_distance = array();
    foreach ($users as $user) {
        $user_latitude = floatval(get_user_meta($user->ID, 'latitude', true));
        $user_longitude = floatval(get_user_meta($user->ID, 'longitude', true));
        $user_distance_to_location = calculate_distance($form_latitude, $form_longitude, $user_latitude, $user_longitude);
        if ($user_distance_to_location <= $user_distance) {
            $users_within_distance[] = $user;
        }
    }

    // Step 5: Return the list of users who match the criteria
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

    $new_row_data = array(
        'candidate' => $post_id, // Set the candidate post ID
        'candidate_status' => $candidate_status,
        'procedure_status' => $procedure_status
    );

    $existing_rows = get_field('candidates_for_physicians', 'user_' . $current_user_id);
    $existing_rows[] = $new_row_data;
    update_field('candidates_for_physicians', $existing_rows, 'user_' . $current_user_id);
    return ('success');
}

?>