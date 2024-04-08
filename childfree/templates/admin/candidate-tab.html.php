<div id="candidate_product_data" class="panel woocommerce_options_panel">


    <?php
    /*
     * Admin panel for the candidate product type
     * Keywords: Candidate data panel, candidate edit panel, candidate edit admin, edit candidate admin
     * */
    woocommerce_wp_select(array(
        'id' => '_provider',
        'value' => $product_object->get_provider('edit'),
        'label' => __('Selected Physician'),
        'placeholder' => __('--'),
        'options' => $physicians
    ));

    ?>

    <hr>

    <?php

    $candidate_id = $registration_entry->metas[15];
    $available_email = get_user_meta($candidate_id, '_email', true);
    if ($available_email != NULL) {
        $available_email = $available_email;
    } else {
        $available_email = $registration_entry->metas[9];
    }
    woocommerce_wp_text_input([
        'id' => '_email',
        'value' => $available_email,
        'label' => __('Email'),
        'custom_attributes' => [
            'readonly' => 'readonly'
        ]
    ]);

    woocommerce_wp_text_input([
        'id' => '_phone',
        'value' => $registration_entry->metas[18],
        'label' => __('Phone'),
        // 'custom_attributes' => [
        // 	'readonly' => 'readonly'
        // ]
    ]);

    woocommerce_wp_text_input([
        'id' => '_ip_address',
        'value' => $registration_entry->ip,
        'label' => __('IP Address'),
        // 'custom_attributes' => [
        // 	'readonly' => 'readonly'
        // ]
    ]);

    ?>


    <?php if ($affiliate) : ?>
        <p class="form-field">
            Referred by <?php echo $affiliate->user_displayname; ?> <<?php echo $affiliate->user_email; ?>>
        </p>
    <?php endif; ?>

    <hr>

    <?php

    woocommerce_wp_text_input([
        'id' => '_honorarium',
        'value' => $product_object->get_honorarium('edit'),
        'label' => __('Honorarium'),
        'desc_tip' => true,
        'description' => __('Amount to be paid to the candidate'),
    ]);

    woocommerce_wp_text_input([
        'id' => '_goal',
        'value' => $product_object->get_goal('edit'),
        'label' => __('Goal'),
        'desc_tip' => true,
        'description' => __('Fundraising goal determined by candidates sex and age'),
    ]);

    woocommerce_wp_text_input([
        'id' => '_amount_raised',
        'value' => $product_object->get_amount_raised('edit'),
        'label' => __('Amount Raised'),
        'desc_tip' => true,
        'description' => __('Fundraising amount raised'),
        // 'custom_attributes' => [
        // 	'readonly' => 'readonly'
        // ]
    ]);

    woocommerce_wp_text_input([
        'id' => '_progress',
        'value' => $product_object->get_progress('edit'),
        'label' => __('Progress'),
        'desc_tip' => true,
        'description' => __('Fundraising progress'),
        // 'custom_attributes' => [
        // 	'readonly' => 'readonly'
        // ]
    ]);

    woocommerce_wp_select([
        'id' => '_sex',
        'value' => $product_object->get_sex('edit'),
        'label' => __('Sex'),
        'options' => array(
            'male' => 'Male',
            'female' => 'Female'
        )
    ]);

    woocommerce_wp_text_input([
        'id' => '_date_of_birth',
        'type' => 'date',
        'value' => $product_object->get_date_of_birth('edit'),
        'label' => __('Date of Birth')
    ]);

    woocommerce_wp_text_input([
        'id' => '_age',
        'value' => $product_object->get_age('edit'),
        'label' => __('Age'),
        // 'custom_attributes' => [
        // 	'readonly' => 'readonly'
        // ]
    ]);

    woocommerce_wp_text_input([
        'id' => '_location',
        'value' => $product_object->get_location('edit'),
        'label' => __('ZIP Code')
    ]);

    woocommerce_wp_text_input([
        'id' => '_latitude',
        'value' => $product_object->get_latitude('edit'),
        'label' => __('Latitude'),
        // 'custom_attributes' => [
        // 	'readonly' => 'readonly'
        // ]
    ]);

    woocommerce_wp_text_input([
        'id' => '_longitude',
        'value' => $product_object->get_longitude('edit'),
        'label' => __('Longitude'),
        // 'custom_attributes' => [
        // 	'readonly' => 'readonly'
        // ]
    ]);

    woocommerce_wp_text_input([
        'id' => '_referred_page_url',
        'value' => $product_object->get_referred_page_url('edit'), //create a function that will get the desired thing and set the value
        'label' => __('Referred Page'),
    ]);


    woocommerce_wp_text_input([
        'id' => '_referred_person',
        'value' => $product_object->get_referred_person('edit'),
        'label' => __('Referred Person'),
        // 'custom_attributes' => [
        // 	'readonly' => 'readonly'
        // ]
    ]);

    woocommerce_wp_text_input([
        'id' => '_person_user_id',
        'value' => $product_object->get_person_user_id('edit'),
        'label' => __('User ID'),
        // 'custom_attributes' => [
        // 	'readonly' => 'readonly'
        // ]
    ]);

    ?>

    <hr>

    <p class="form-field">

        <?php
        $candidate_id = get_the_ID();
        $progress = $product_object->get_progress();

        // Check if the meta key exists
        $meta_exists = metadata_exists('post', $candidate_id, '_fully_funded_and_paid');

        if (!$meta_exists) {
            add_post_meta($candidate_id, '_fully_funded_and_paid', 'unpaid', true);
        }

        $fully_funded_and_paid = get_post_meta($candidate_id, '_fully_funded_and_paid', true);

        if ($progress == 100) {
            if ($fully_funded_and_paid === 'paid') {
                echo "<label style='background-color: green; color: white; margin: auto; width: auto; text-align: center; font-size: 18px; padding: 7px; border-radius: 7px;'><input type='checkbox' style='margin-right: 10px;' name='fully_funded_paid' checked>Fully Funded & Paid!</label>";
            } else {
                echo "<label style='background-color: #2271b1; color: white; margin: auto; width: auto; text-align: center; font-size: 18px; padding: 7px; border-radius: 7px;'><input type='checkbox' name='fully_funded_paid' style='margin-right: 10px;'>Fully funded but not paid yet.</label>";
            }
        } else {
            ?>
            <a href="<?php echo admin_url('admin-post.php?action=cbc_mark_candidate_complete&candidate=' . $product_object->get_id()); ?>"
               class="button button-primary button-large">Mark Procedure Completed</a>
            <?php
        }
        ?>


        <!--		<a href="-->
        <?php //echo admin_url( 'admin-post.php?action=cbc_mark_candidate_complete&candidate=' . $product_object->get_id() ); ?><!--" class="button button-primary button-large">Mark Procedure Completed</a>-->
    </p>

    <p class="form-field">
        Honorarium Payout: <?php echo wc_price($product_object->get_honorarium()); ?>

        <?php if ($product_object->get_stripe_account() === false) : ?>
            <span class="wp-ui-notification">Candidate has not created a Stripe account yet.</span>
        <?php endif; ?>
        <br>

<!--        Physician Payout: --><?php //echo wc_price($funding->physician_fee); ?>
<!--        --><?php //if ($product_object->get_provider()->get_id() === 0) : ?>
<!--            <span class="wp-ui-notification">No physician has been selected yet.</span>-->
<!--        --><?php //elseif ($product_object->get_provider()->get_stripe_account() === false) : ?>
<!--            <span class="wp-ui-notification">Selected physician has not created a Stripe account yet.</span>-->
<!--        --><?php //endif; ?>
    </p>

</div>
