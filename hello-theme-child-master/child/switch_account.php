<?php 
add_action("wp_ajax_switch_to_account", "switch_to_account");
add_action("wp_ajax_nopriv_switch_to_account", "switch_to_account");

function switch_to_account(){
	
/* 	print_r($_POST); */
	$current_user = wp_get_current_user();
	$current_user_email =  $current_user->user_email;	
	$user_id = $_POST['user_id'];	
	$user_new_role = $_POST['new_role']; 	
	$user_current_role = $_POST['current_role']; 
	
	$cuser = new WP_User($user_id);

	$cuser->remove_role($user_current_role);

	/* if($user_new_role == 'candidate'){

     $cuser->add_role('subscriber');		
		
	} */
	
	if($user_current_role == 'candidate'){

     $cuser->remove_role('subscriber');		
		
	}
	 if($user_new_role == 'customer'){
	$status = affiliate_wp()->settings->get( 'require_approval' ) ? 'pending' : 'active';

		affwp_add_affiliate( array(
		'user_id'        => $user_id,
		'payment_email'  => $current_user_email,
		'status'         => $status,
		'website_url'    => '',
		'dynamic_coupon' => ! affiliate_wp()->settings->get( 'require_approval' ) ? 1 : '',
		) );
     }
	$cuser->add_role($user_new_role);

	$result['type'] = "success";

	$result = json_encode($result);
	
    echo $result;	
	
die();	
}
?>
