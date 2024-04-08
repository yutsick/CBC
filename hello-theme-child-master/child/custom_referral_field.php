<?php

/**

 * Add custom field to the checkout page

 */
//
//add_action('woocommerce_after_checkout_billing_form', 'custom_checkout_field', 20);
//
//function custom_checkout_field($checkout)
//{
//    echo '<div class="woocommerce-input-wrapper"><p class="form-row form-row-heading">4. Referral Section</p>';
//    echo '<div id="refter_custom_field">
//    <div class="referral_checkbox">
//    <label>' . __('Did someone refer you?') . '</label>
//          <input type="checkbox" name="refered_by" id="refered_no" value="no" class="input-checkbox" checked><label for="refered_no">No</label>
//          <input type="checkbox" name="refered_by" id="refered_yes" value="yes" class="input-checkbox"><label for="refered_yes">Yes</label>
//    </div>';
//
//    woocommerce_form_field(
//        'refering_name',
//        array(
//            'type' => 'text',
//            'class' => array(
//                'refer-field-class form-row-wide refering_name'
//            ),
//            'label' => __('Enter the username of the person who referred you below:'),
//            'placeholder' => __('Enter the username'),
//        ),
//
//        $checkout->get_value('refering_name')
//
//    );
//
//    echo '</div> </div>';
//}
///* add_action('woocommerce_checkout_process', 'customised_checkout_field_process');
//
//function customised_checkout_field_process()
//
//{
//
//$refrelname = $_POST['refering_name'];
//		$existing = username_exists($refrelname);
//
//if ($_POST['refering_name']) wc_add_notice(__('Please enter '.$existing.' value!') , 'error');
//
//} */
//
//
//function insert_refral_user_details($refreal_user_data)
//{
//    global $wpdb;
//
//    $wpdb->insert('wp_referal_users', array(
//        'order_id' => $refreal_user_data['order_id'],
//        'user_id' => $refreal_user_data['user_id'],
//        'user_code' => $refreal_user_data['user_code'],
//        'referral_amount' => $refreal_user_data['ten_percent_amount'],
//        'reward_payment_status' => 'pending',
//    ));
//}
//
//
//
//// Hook to Show the Stored value in database to the order section at admin area
//add_action('woocommerce_checkout_update_order_meta', 'bbloomer_save_new_checkout_field');
//
//function bbloomer_save_new_checkout_field($order_id)
//{
//    //creating an array for referal
//    $refreal_user_data = array();
//
//    $refering_name = isset($_POST['refering_name']) ? sanitize_user($_POST['refering_name']) : '';
//    $existing = username_exists($refering_name);
//    if ($_POST['refering_name']) {
//        $refreal_user_data['order_id'] = $order_id;
//        $refreal_user_data['user_id'] = $existing;
//        $refreal_user_data['user_code'] = $_POST['refering_name'];
//
//        $order = wc_get_order($order_id);
//        $order_total = intval($order->get_total());
//
//        error_log('totalsa' . $order_total);
//        $ten_percent_amount = (10 / 100) * $order_total;
//
//        if (is_float($ten_percent_amount)) {
//            $ten_percent_amount =  number_format((float)$ten_percent_amount = $ten_percent_amount, 2, '.', '');
//        }
//        error_log('percentage' . $ten_percent_amount);
//
//        $refreal_user_data['ten_percent_amount'] = $ten_percent_amount;
//        update_post_meta($order_id, '_refering_name', esc_attr($_POST['refering_name']));
//
//        update_post_meta($order_id, '_refering_amount', esc_attr($ten_percent_amount));
//
//        insert_refral_user_details($refreal_user_data);
//    }
//}
//
//// Hook to Show the Stored value in database to the order section at admin area
//add_action('woocommerce_admin_order_data_after_billing_address', 'bbloomer_show_new_checkout_field_order');
//// Function to show the
//function bbloomer_show_new_checkout_field_order($order)
//{
//    $order_id = $order->get_id();
//    if (get_post_meta($order_id, '_refering_name', true)) {
//        echo '<p><strong>Referral User Name:</strong> ' . get_post_meta($order_id, '_refering_name', true) . '</p>';
//
//        echo '<p><strong>Referral Amount:</strong> ' . wc_price(get_post_meta($order_id, '_refering_amount', true)) . '</p>';
//    }
//}
//
//
// add_action( 'woocommerce_view_order', 'print_custom_order_meta' );
//
//
//function print_custom_order_meta($order)
//{
//	ini_set('display_errors', 1);
// 	ini_set('display_startup_errors', 1);
//	error_reporting(E_ALL);
//	//var_dump($order);
//    //$order_id = $order->get_id();
//    if (get_post_meta($order, '_refering_name', true)) {
//         echo '<div><h2>Refferal Information</h2></div><div style="border:1px solid #91C8C8;"><table><tr><td style="padding:5px;"><strong>Referral User Name:</strong></td><td style="padding:5px;">' . get_post_meta($order, '_refering_name', true) . '</td></tr>';
//
//         echo '<tr><td style="padding:5px;"><strong>Referral Amount:</strong></td><td style="padding:5px;">' . wc_price(get_post_meta($order, '_refering_amount', true)) . '</td></tr></table></div>';
//    }
//}





/** */
add_action('admin_menu', 'referral_list_admin_meu');

function referral_list_admin_meu()
{

    add_menu_page(
        'Referral List',
        'Referral List',
        'edit_posts',
        'referral_list',
        'show_referral_list',
        'dashicons-media-spreadsheet'

    );
}

function show_referral_list()
{
    global $wpdb;
    if (is_admin()) { ?>
        <table class="wp-list-table widefat fixed striped table-view-list items">
            <thead>
                <tr>
                    <td>User Name</td>
                    <td>Orders</td>
                    <td>Referral Code</td>
                    <td>Total Reward Amount</td>
                    <td>Payment Status</td>
                </tr>
            </thead>
            <tbody id="the-list" data-wp-lists="list:item">

                <?php

                $referrals_users = $wpdb->get_results("SELECT distinct user_id FROM {$wpdb->prefix}referal_users", ARRAY_A);

                foreach ($referrals_users as $user) {
                    $user_id = $user['user_id'];
                    $user = get_userdata($user_id);
                    $display_name = $user->display_name;
                ?>

                    <tr>

                        <td><a href="/wp-admin/user-edit.php?user_id=<?php echo $user_id; ?>&wp_http_referer=%2Fwp-admin%2Fusers.php"><?php echo $display_name; ?></a></td>
                        <td>

                            <?php
                            $total_referral_amount = 0;
                            $referrals_orders = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}referal_users WHERE user_id = $user_id ", ARRAY_A);
                            foreach ($referrals_orders as $user_order) { ?>
                                <a href="/wp-admin/post.php?post=<?php echo $user_order['order_id']; ?>&action=edit"><?php echo $user_order['order_id']; ?> </a>

                                <?php $total_referral_amount += $user_order['referral_amount']; ?>

                            <?php } ?>
                            </a>
                        </td>
                        <td><?php echo $user_order['user_code']; ?></td>
                        <td><?php echo wc_price($total_referral_amount); ?></td>
                        <td><?php echo $user_order['reward_payment_status']; ?></td>
                    <?php } ?>
            </tbody>
        </table>
<?php    } else {
        wp_die('You dont have the access of this page');
    }
}
?>