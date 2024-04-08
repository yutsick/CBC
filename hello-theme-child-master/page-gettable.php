<?php get_header();?>
<h1>Hi</h1>
<?php 
global $wpdb;

$res = $wpdb->get_results(
    $wpdb->prepare(
        "
        SELECT age, sex,amount,honorarium
        FROM wp_jet_cct_donation_amounts
  
        "
    )
);
echo '<ul>';
foreach ($res as $resitem){
    echo '<li>'.$resitem->sex.' '.$resitem->age.'  '.$resitem->honorarium.'  '.$resitem->amount.'</li>';
    echo '<hr>';
}
echo '</ul><hr><br>';

//Check user subscriptions

// $res1 = $wpdb->get_results(
//     $wpdb->prepare(
//         "
//         SELECT value
//         FROM wp_e_submissions_values
  
//         "
//     )
// );
// echo '<pre>';
// print_r($res1);
// echo '</pre>';
?>
<?php get_footer();?>