"use strict";
// GLOBAL VARIABLES
let nonce = ajax_object.ajax_nonce;
let ajaxurl = ajax_object.ajaxurl;

jQuery(function ($) {

    let order = window.location.search.split('id=')[1].split('&')[0];

    // Delete all cookies, as the order is completed now.
    if (order > 0) {
        // delete all cookies that are starting with the characters 'ck' only
        let cookies = document.cookie.split(";").filter(function (c) {
            return c.trim().startsWith('ck');
        });
        for (let i = 0; i < cookies.length; i++) {
            let cookie = cookies[i];
            let eqPos = cookie.indexOf("=");
            let name = eqPos > -1 ? cookie.substr(0, eqPos) : cookie;
            document.cookie = name + "=; expires=Thu, 01-Jan-70 00:00:01 GMT; path=/;";
        }
    }

    jQuery.ajax({
        type: 'POST',
        url: ajaxurl,
        data: {
            action: 'thankyou_page_action',
            nonce: nonce,
            order_id: order
        },
        success: function (response) {
            populateOrderDetails(response);
        },
        error: function (response) {
            console.log('ajax response: ' + response);
        }
    });

    function populateOrderDetails(response) {
        let order = response.data;
        let voucherAmount = order.order_discount_total;
        let referral = order.referral_details;

        jQuery('#thankyou_donation_number span').text(order.order_id);
        jQuery('#thankyou_donation_date span').text(new Date(order.order_date).toString().substring(0,15));
        jQuery('#thankyou_payment_method span').text(
            order.order_payment_method_title
            + ' (' +
            order.order_payment_method.toUpperCase()
            + ')' );
        if (voucherAmount > 0) {
            jQuery('#thankyou_voucher_amount span').text('-$' + order.order_discount_total);
            jQuery('#thankyou_voucher_amount').parent().show();
        } else { jQuery('#thankyou_voucher_amount').parent().hide(); }
        jQuery('#thankyou_total_donation span').text('$' + order.order_total);
        if (referral[0].referrer_name !== null) {
            jQuery('#thankyou_referrer_name span').text(referral[0].referrer_name);
            jQuery('#thankyou_referrer_amount span').text('$' + referral[0].referrer_amount);
        } else { jQuery('#thankyou_referrer_name').parent().parent().hide(); }
        jQuery('#thankyou_donor_first_name span').text(order.order_billing_first_name);
        jQuery('#thankyou_donor_last_name span').text(order.order_billing_last_name);
        jQuery('#thankyou_donor_billing_address span').text(
            order.order_billing_address_1
            + ' ' +
            order.order_billing_address_2);
        jQuery('#thankyou_donor_country_name span').text(order.order_billing_country);
        jQuery('#thankyou_donor_zip_code span').text(order.order_billing_postcode);
        jQuery('#thankyou_donor_city_name span').text(order.order_billing_city);
        jQuery('#thankyou_donor_state span').text(order.order_billing_state);
        jQuery('#thankyou_donor_phone_number span').text(order.order_billing_phone);
        jQuery('#thankyou_donor_email span').text(order.order_billing_email);

        // POPULATE CART ITEMS
        let order_items = order.order_items;
        $.each(order_items, function (index, item) {
            let flexContainer = $('<div class="flex-container"></div>');
            let rowContainer = $('<div class="row-container"></div>');
            let leftDiv = $('<div class="left-div"><img src="' + item.product_image_url + '" alt="candidate-image"/></div>');
            let centerDiv = $('<div class="center-div">' + item.product_name + '</div>');
            let rightDiv = $('<div class="right-div">$' + item.product_subtotal + '</div>');

            // Append sub-divs to rowContainer
            rowContainer.append(leftDiv, centerDiv, rightDiv);

            // Append rowContainer to flexContainer
            flexContainer.append(rowContainer);

            // Append the flex-container to the 'cart_items' div
            $('#cart_items').append(flexContainer);
        });

    }

});
