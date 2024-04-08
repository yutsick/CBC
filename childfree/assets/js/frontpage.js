"use strict";
jQuery(function($) {

    // Hide the donation popup
    jQuery('head').append('<style>#elementor-popup-modal-58203 {display:none !important;}</style>');

//////////////////////////////////////////////////////////////////////////////////////
// DONATION and ADD TO CART BUTTONS HANDLER
//////////////////////////////////////////////////////////////////////////////////////
    let ajaxurl = ajax_object.ajaxurl;
    let nonce = ajax_object.nonce;
    let productId = '';
    let candidateAmountRemaining = 0;

    // GET COOKIE VALUE
    function getCookie(cookieName) {
        let name = cookieName + '=';
        let decodedCookie = decodeURIComponent(document.cookie);
        let cookieArray = decodedCookie.split(';');
        for(let i = 0; i < cookieArray.length; i++) {
            let cookie = cookieArray[i];
            while (cookie.charAt(0) === ' ') {
                cookie = cookie.substring(1);
            }
            if (cookie.indexOf(name) === 0) {
                return cookie.substring(name.length, cookie.length);
            }
        }
        return '';
    }

    // OVERLAY CONTAINER TOGGLE
    function overlayToggle() {
        let overlayContainer = jQuery('#overlay-container');
        if (overlayContainer.is(":visible")) {
            overlayContainer.css('display', 'none');
            jQuery("body").css("overflow", "auto");
        } else {
            overlayContainer.css('display', 'flex');
            jQuery("body").css("overflow", "hidden");
        }
    }

    jQuery(document).on('click', '.elementor-button[href=\"#donation-popup\"]', function (e) {
        e.preventDefault();
        // productId = $(this).closest('[id]').attr('id');
        // console.log('Selected card_id: ' + productId); // .post-26229

        // Find the closest ancestor with a class containing 'e-loop-item'
        let loopItem = $(this).closest('[class*=e-loop-item]');

        // Extract the post ID from the loopItem class
        let postIdMatch = loopItem.attr('class').match(/post-(\d+)/);
        productId = postIdMatch ? postIdMatch[1] : null;

        console.log('Selected card_id: ' + productId); // Output the clicked item ID

        let candidateName = $('.post-' + productId + ' #card-candidate-name a').text();
        // console.log('candidateName: ' + candidateName);
        let candidateImageSrc = $('.post-' + productId + ' #card-candidate-image img').attr('src');
        // console.log('candidateImageSrc: ' + candidateImageSrc);
        let candidateAmountRaised = $('.post-' + productId + ' #card-candidate-amount-raised')
            .text().replace(/\D/g, '');
        let candidateAmountGoal = $('.post-' + productId + ' #card-candidate-amount-goal')
            .text().replace(/\D/g, '');
        candidateAmountRemaining = candidateAmountGoal - candidateAmountRaised;

        let voucherCode = getCookie('ckVoucherCode');
        let voucherAmount = getCookie('ckVoucherAmount');
        let notification = $('#woocommerce_voucher_notification');
        let removeAnchor = $('#remove_coupon_code_value')[0]; // Getting the DOM element

        if (voucherAmount && voucherCode) {
            $('#voucher_code').val(voucherAmount);
            $('.acceptance-modal').hide();
            $('.elementor-field-subgroup').hide();

            notification.removeClass().addClass('woocommerce-message')
                .html('Voucher: <span style="color:#01b401;font-weight: 500;">' + voucherCode + '</span> applied' + removeAnchor.outerHTML).show();

            // $('.applied-voucher-code-message').show();
            $('#coupon_code_value').html(voucherAmount).show();
            $('#remove_coupon_code_value').attr('data-voucher_code', voucherCode);

            // $('#apply_voucher_button').trigger('click');
            console.log('Voucher cookie exists: ' + voucherCode);
        }


        // Update #donation-popup content
        $('#popdon-candidate-name > div > h1').text(candidateName);
        $('#popdon-candidate-image > div > img').attr({'srcset': '', 'src': candidateImageSrc});
        jQuery('#dAmount_full').attr({
            'id': 'dAmount_' + candidateAmountRemaining,
            'value': candidateAmountRemaining
        });
        console.log('Remaining_amount: ' + candidateAmountRemaining);
        jQuery('label[for="dAmount_full"]').attr('for', 'dAmount_' + candidateAmountRemaining);

        if (candidateAmountRemaining > 0) { // Means if candidate is not fully funded.
            // Get all radio buttons with the name 'donationAmount'
            const radioButtonsDonationAmounts = document.querySelectorAll('input[name="donationAmount"]');

            radioButtonsDonationAmounts.forEach(button => {
                button.checked = false;
                const $button = $(button);
                const buttonValue = parseInt($button.val()); // Convert to number
                if (buttonValue > candidateAmountRemaining) {
                    // console.log('buttonValue: ' + buttonValue + ' remainingAmount: ' + candidateAmountRemaining);
                    $button.closest('.inputs-option').hide(); // Hide the parent span
                }
            });

            // Add event listener to each radio button
            radioButtonsDonationAmounts.forEach(button => {
                button.addEventListener('click', () => {
                    let otherAmountElement = $('#dAmount_other_input');
                    const selectedValue = button.value;
                    const formattedValue = '$' + new Intl.NumberFormat().format(selectedValue);
                    console.log('selectedValue: ' + selectedValue + ' selectedId: ' + button.id);
                    /*
                    * Before changing the text, check if the donation amount element is span or input
                    * If element is 'input', then replace it with 'span'
                    */
                    if ($(this).attr('id') !== 'dAmount_other') {
                        // Check if the element with id 'dAmount_other_input' exists
                        if (otherAmountElement.length > 0) {
                            // Replace with the specified span element code
                            var newSpan = $('<span class="elementor-heading-title elementor-size-large">$0.00</span>');
                            otherAmountElement.closest('.elementor-widget-container')
                                .find('span').remove();
                            otherAmountElement.replaceWith(newSpan);
                        }
                        jQuery('#donation_amount_single_box div span').text(formattedValue);
                        // add click event listener to this span
                        $('#donation_amount_single_box div span').click( function () {
                            enable_other_amount_input();
                        });
                    } else {
                        jQuery('#dAmount_other_input').val(formattedValue);
                    }
                });
            });

            function enable_other_amount_input () {
                // Create a new input element
                let newInput = $('<input type="text" id="dAmount_other_input" ' +
                    'class="elementor-heading-title elementor-size-large" placeholder="Type the amount here...">');
                // newInput.val('$' + selectedValue); // Set its value

                // Replace the span with the new input
                $('.elementor-heading-title.elementor-size-large').replaceWith(newInput);
                // Check the 'other' amount radio button
                jQuery('#dAmount_other').trigger('click');
                newInput.click();
                newInput.focus();
                return false;
            }

            // Function for 'Other Amount' input
            $('#dAmount_other').change(function () {
                enable_other_amount_input();
            });

            // Function for 'amount' box click
            $('#donation_amount_single_box > div > span').click( function () {
                enable_other_amount_input();
            });

            // Function for 'Other Amount' input
            // $('#dAmount_other').change(function () {
            //     var selectedValue = $(this).val();
            //
            //     // Create a new input element
            //     var newInput = $('<input type="text" id="dAmount_other_input" ' +
            //         'class="elementor-heading-title elementor-size-large" placeholder="Type the amount here...">');
            //     // newInput.val('$' + selectedValue); // Set its value
            //
            //     // Replace the span with the new input
            //     $('.elementor-heading-title.elementor-size-large').replaceWith(newInput);
            // });
        }
        else {
            // // Hide all radio buttons
            // $('.inputs-option').hide();
            // // Hide the 'Other Amount' input
            // $('#dAmount_other').hide();
            // Hide the 'Donate Now' button
            $('#donate-now-button').hide();
            $('.elementor-element-588a2d49, .elementor-element-19c3324, .elementor-element-2b44284, .elementor-element-34715c91').hide();

            $('.elementor-element-450bb3a0').css({
                'display': 'flex',
                'justify-content': 'center',
            });
            $('.elementor-element-450bb3a0 .elementor-button-wrapper').css({
                'display': 'flex',
                'justify-content': 'center',
            });

            // add custom message
            $('#donation_amount_single_box div span').text(
                'Sorry, this candidate is already fully funded, please select another candidate.').css({
                'font-size': '22px',
            });
        }


        setTimeout(function () {
            jQuery('#elementor-popup-modal-58203').attr('style', 'display:flex !important');
        }, 200);
    });

    // Mask the input field to not allow to type any non-numeric characters
    jQuery(document).on('input', '#donation_amount_single_box input', function(event) {
        if (event.which === 13) {
            event.preventDefault();
        }

        // Mask the input field to not allow to type any non-numeric characters
        let inputValue = $(this).val();
        let numericValue = inputValue.replace(/[^0-9]/g, '');

        if (numericValue !== '' && numericValue.includes('$')) {
            numericValue = numericValue.replace('$', '');
        }

        // check if the value is greater than remaining amount if yes then set it to remaining amount and show message append the input field
        if (parseInt(numericValue) > parseInt(candidateAmountRemaining)) {
            numericValue = candidateAmountRemaining;
            // empty the appended span before populating it
            $(this).closest('.elementor-widget-container').find('#amount_error_message').remove();
            $(this).closest('.elementor-widget-container').append('<span id="amount_error_message" style="color: red; font-size: 12px;">' +
                'The Donation Amount must not exceed the remaining amount, which is <strong>$' + candidateAmountRemaining + '</strong>.</span>');
        }

        $(this).val('$' + numericValue);

    });

    // VOUCHER CODE APPLY
    $(document).on('click', '#apply_voucher_button', function (event) {
        event.preventDefault();

        let voucher_code = $('#voucher_code').val();
        let wc_voucher_notification = $('#woocommerce_voucher_notification');
        let voucherYesNo = $('#form-field-acceptance').next('div');
        let voucherDiv = $('.elementor-element-2b44284');
        let removeAnchor = $('#remove_coupon_code_value')[0]; // Getting the DOM element
        let cookiePath = '; path=/',
            ckVoucherCode = 'ckVoucherCode';

        // Apply the voucher via WooCommerce AJAX
        $.ajax({
            type: 'POST',
            url: ajaxurl,
            data: {
                action: 'apply_voucher',
                voucher_code,
            },
            success: function (response) {
                console.log('Response-Success-Message: ', response.data.message);
                console.log('Response-Voucher-code: ', response.data.voucher_code);
                if (response.success) {
                    document.cookie = ckVoucherCode + '=' + voucher_code + cookiePath;
                    voucherYesNo.hide();
                    $('.acceptance-modal').hide();
                    $('.elementor-field-subgroup').hide();
                    wc_voucher_notification.prop('class', 'woocommerce-message').html(response.data.message + removeAnchor.outerHTML).show();
                    // $('.applied-voucher-code-message').show();
                    $('#coupon_code_value').html(response.data.voucher_code).show();
                    $('#remove_coupon_code_value').attr('data-voucher_code', response.data.voucher_code);
                    // window.location.reload();
                } else {
                    wc_voucher_notification.prop('class', 'woocommerce-error').html(response.data.message).show();
                }
            },
            error: function (response) {
                wc_voucher_notification.prop('class', 'woocommerce-error').html(response.data.message).show();
            },
            complete: function () {
                // Trigger the 'update_checkout' event to refresh the cart total
                $(document.body).trigger('update_checkout');
            }
        });
    });
    // check if voucher cookie exists then apply it on popup
    let ckVoucherCode = 'ckVoucherCode',
        ckVoucherCodeValue = getCookie(ckVoucherCode);
    let notification = $('#woocommerce_voucher_notification');
    let removeAnchor = $('#remove_coupon_code_value')[0]; // Getting the DOM element

    if (ckVoucherCodeValue) {
        $('#voucher_code').val(ckVoucherCodeValue);
        $('.acceptance-modal').hide();
        $('.elementor-field-subgroup').hide();

        notification.removeClass().addClass('woocommerce-message')
            .html('Voucher: ' + ckVoucherCodeValue + ' applied' + removeAnchor.outerHTML).show();

        // $('.applied-voucher-code-message').show();
        $('#coupon_code_value').html(ckVoucherCodeValue).show();
        $('#remove_coupon_code_value').attr('data-voucher_code', ckVoucherCodeValue);

        // $('#apply_voucher_button').trigger('click');
        console.log('Voucher cookie exists: ' + ckVoucherCode);
    }

    // REMOVE VOUCHER CODE
    function remove_voucher_code() {
        $=jQuery;
        let removeButton = $('#remove_coupon_code_value');
        let cookiePath = '; path=/',
            ckVoucherCode = 'ckVoucherCode';

        let voucher_code = removeButton.attr('data-voucher_code');
        let wc_voucher_notification = $('#woocommerce_voucher_notification');

        $.ajax({
            type: 'POST',
            url: ajaxurl,
            data: {
                action: 'remove_voucher',
                voucher_code,
            },
            beforeSend: function () {
                removeButton.html('<img src="/wp-content/uploads/2023/04/candidates-loader.gif" width="15px">');
            },
            success: function (response) {
                console.log('Response-Success-Message: ', response.data.message);
                // remove voucher code and voucher value cookie
                document.cookie = ckVoucherCode + '=' + cookiePath;
                // document.cookie = ckVoucherCodeValue + '=' + cookiePath;
                // hide voucher code and voucher value
                wc_voucher_notification.html('').hide();
                wc_voucher_notification.prop('class','woocommerce-message').html(response.data.message).show();
                $('.applied-voucher-code-message').hide();
                $('#coupon_code_value').html('').hide();
                $('#voucher_code').val('');
                $('#remove_coupon_code_value').hide();
                $('#form-field-acceptance').prop('checked', false);
                // Voucher DOM element
                $('.elementor-element-1c7f1e49').replaceWith(
                    '<div class="elementor-element elementor-element-1c7f1e49 elementor-widget__width-inherit elementor-widget elementor-widget-html" data-id="1c7f1e49" data-element_type="widget" data-widget_type="html.default">'
                    + '<div class="elementor-widget-container">'
                    + '<div class="elementor-field-subgroup">'
                    + '<span class="elementor-field-option">'
                    + '<input type="checkbox" name="form_fields[acceptance]" id="form-field-acceptance" class="elementor-field elementor-size-sm  elementor-acceptance-field">'
                    + '<label for="form-field-acceptance">I have a Donation Voucher</label></span></div>'
                    + '<div id="woocommerce_voucher_notification" class="woocommerce-message" style="display: none;"></div>'
                    + '<div class="applied-voucher-code-message" style="display:none;">'
                    + '<p style="color:black;">Applied Coupon Code:'
                    + '<span id="coupon_code_value"></span>'
                    + '<a id="remove_coupon_code_value" data-voucher_code="" style="cursor: pointer;font-weight: 700;">'
                    + '<i aria-hidden="true" class="far fa-trash-alt"></i>'
                    + '</a></p></div>'
                    + '<div class="acceptance-modal">'
                    + '<div class="code-status">'
                    + '<input type="text" class="code-input" id="voucher_code" placeholder="Input Donation Voucher Here">'
                    + '<button class="voucher-btn" id="apply_voucher_button">Apply</button></div></div></div></div>');
                removeButton.text('Remove');
                jQuery(document.body).trigger('wc_fragment_refresh');
            },
            error: function (response) {
                removeButton.text('Remove');
                wc_voucher_notification.prop('class','woocommerce-error').html(response.data.message).show();
            },
            complete: function () {
                removeButton.text('Remove');
                $(document.body).trigger('update_checkout');
            }
        })
    }
    $(document).on('click', '#remove_coupon_code_value', function (event) {
        event.preventDefault();
        remove_voucher_code();
    });


    // Donate Now button click event
    $(document).on('click', '#donate-now-button', function (e) {
        e.preventDefault();

        // Get the values
        var voucherCode = $('#voucher_code').val();
        if (typeof voucherCode === 'undefined') {
            voucherCode = '';
        }

        var donationAmount = $('input[name="donationAmount"]:checked').val();
        if (typeof donationAmount === 'undefined') {
            donationAmount = '';
        }

        var otherAmount = $('#dAmount_other_input').val();
        if (typeof otherAmount === 'undefined') {
            otherAmount = '';
        }
        else if (otherAmount !== '' && otherAmount.includes('$')) {
            otherAmount = otherAmount.replace('$', '');
        }

        if (typeof productId === 'undefined') {
            productId = '';
        }

        console.log('voucherCode: ' + voucherCode);
        console.log('donationAmount: ' + donationAmount);
        console.log('otherAmount: ' + otherAmount);
        console.log('productId: ' + productId);

        let amount = 0;
        if (donationAmount > 0) {
            amount = donationAmount;
        }
        else if (otherAmount > 0) {
            amount = otherAmount;
        }

        console.log('amount: ' + amount);

        $('#amount_popup_message').remove();

        if (amount <= 0 || isNaN(amount)) {
            console.log('Amount cannot be $0 or empty.');
            jQuery(
                '#add-candidate-to-cart').prepend(
                '<span id="amount_popup_message" style="' +
                'display: flex;' +
                'align-items: center;' +
                'position: absolute;' +
                'top: -45px;' +
                'left: -200px;' +
                'color: red;' +
                'width: max-content;' +
                'height: 35px;' +
                'font-weight: normal;' +
                'padding: 5px 20px;' +
                'background-color: #ff000024;' +
                'border: 1px solid red;' +
                'text-align: center;' +
                'border-radius: 12px;' +
                '">' +
                'Amount cannot be $0 or empty. Please fill correct amount.' +
                '</span>'
            );
            return;
        }

        // Create an object to store the values
        var data = {
            action: 'childfree_add_to_cart',
            voucher_code: voucherCode,
            amount: amount,
            // amount: donationAmount,
            // other_amount: otherAmount,
            product_id: productId,
            nonce: nonce
        };

        $.ajax({
            url: ajaxurl,
            type: 'POST',
            data: data,
            beforeSend: function () {
                overlayToggle();
            },
            success: function (response) {
                // console.log('Redirect to checkout page');
                // document.cookie = 'ckVoucherCode=' + voucherCode;
                // window.location.href = '/checkout/';
                // $('#overlay-container').css('display', 'none');
                if (response.success === true) {
                    console.log('Redirect to checkout page, product is added.');
                    document.cookie = 'ckVoucherCode=' + voucherCode;
                    jQuery('#amount_popup_message').remove();
                    jQuery(
                        '#add-candidate-to-cart').prepend(
                        '<span id="amount_popup_message" style="' +
                        'display: flex;' +
                        'position: absolute;' +
                        'top: -45px;' +
                        'left: -80%;' +
                        'color: green;' +
                        'width: max-content;' +
                        'height: 35px;' +
                        'font-weight: normal;' +
                        'padding: 5px 20px;' +
                        'background-color: #e1fddd;' +
                        'border: 1px solid green;' +
                        'text-align: center;' +
                        'border-radius: 12px;' +
                        '">' +
                        'Added to cart' +
                        '</span>'
                    );
                    setTimeout(function () {
                        // Close the popup
                        jQuery('.dialog-close-button.dialog-lightbox-close-button').trigger('click');
                        // Redirect to the checkout page
                        window.location.href = '/checkout/';
                    }, 1000);
                } else {
                    overlayToggle();
                    setTimeout(function () {
                        jQuery(
                            '#add-candidate-to-cart').prepend(
                            '<span id="amount_popup_message" style="' +
                            'display: flex;' +
                            'position: absolute;' +
                            'top: -45px;' +
                            'color: red;' +
                            'width: max-content;' +
                            'height: 35px;' +
                            'font-weight: normal;' +
                            'padding: 5px 20px;' +
                            'background-color: #ff000024;' +
                            'border: 1px solid red;' +
                            'text-align: center;' +
                            'border-radius: 12px;' +
                            '">' +
                            'Error occured while adding to cart' +
                            '</span>'
                        );
                    }, 1000);
                    jQuery(document.body).trigger('wc_fragment_refresh');
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                overlayToggle();
                console.log('Error:', jqXHR, textStatus, errorThrown);
            }
        });
    });

    // Add to cart button click event
    $(document).on('click', '#add-candidate-to-cart', function (e) {
        e.preventDefault();

        // Get the values
        let voucherCode = $('#voucher_code').val();
        if (typeof voucherCode === 'undefined') {
            voucherCode = '';
        }
        console.log('voucherCode: ' + voucherCode);

        // Get Donation Amount from text box
        var donationAmount = $('input[name="donationAmount"]:checked').val();
        if (typeof donationAmount === 'undefined') {
            donationAmount = '';
        }
        console.log('donationAmount: ' + donationAmount);

        let otherAmount = $('#dAmount_other_input').val();
        if (typeof otherAmount === 'undefined') {
            otherAmount = '';
        }
        else if (otherAmount !== '' && otherAmount.includes('$')) {
            otherAmount = otherAmount.replace('$', '');
        }
        console.log('otherAmount: ' + otherAmount);

        if (typeof productId === 'undefined') {
            productId = '';
        }
        console.log('productId: ' + productId);

        let amount = 0;
        if (donationAmount > 0) {
            amount = donationAmount;
        }
        else if (otherAmount > 0) {
            amount = otherAmount;
        }

        console.log('amount: ' + amount);

        $('#amount_popup_message').remove();

        if (amount <= 0 || isNaN(amount)) {
            console.log('Amount cannot be $0 or empty.');
            jQuery(
                '#add-candidate-to-cart').prepend(
                '<span id="amount_popup_message" style="' +
                'display: flex;' +
                'position: absolute;' +
                'top: -45px;' +
                'left: -200px;' +
                'color: red;' +
                'width: max-content;' +
                'height: 35px;' +
                'font-weight: normal;' +
                'padding: 5px 20px;' +
                'background-color: #ff000024;' +
                'border: 1px solid red;' +
                'text-align: center;' +
                'border-radius: 12px;' +
                '">' +
                'Amount cannot be $0 or empty. Please fill correct amount.' +
                '</span>'
            );
            return;
        }

        // Create an object to store the values
        let data = {
            action: 'childfree_add_to_cart',
            voucher_code: voucherCode,
            amount: amount,
            // amount: donationAmount,
            // other_amount: otherAmount,
            product_id: productId,
            nonce: nonce
        };

        $.ajax({
            url: ajaxurl,
            type: 'POST',
            data: data,
            // beforeSend: function () {
            //     $('#overlay-container').css('display', 'flex');
            // },
            success: function (response) {
                console.log('Added to cart.');
                // jQuery(
                //     '#add-candidate-to-cart').replaceWith(
                //     '<span style="' +
                //     'color: green;\n' +
                //     'font-weight: bold;\n' +
                //     'padding: 14px 13px;\n' +
                //     'background-color: #00800024;\n' +
                //     'border: 1px solid green;\n' +
                //     'border-radius: 15px; position: absolute; left: -32px;">' +
                //     'Added to cart' +
                //     '</span>'
                // );
                // jQuery(document.body).trigger('wc_fragment_refresh');
                // $('#overlay-container').css('display', 'none');
                if (response.success === true) {
                    jQuery(
                        '#add-candidate-to-cart').prepend(
                        '<span id="amount_popup_message" style="' +
                        'display: flex;' +
                        'position: absolute;' +
                        'top: -45px;' +
                        'left: -80%;' +
                        'color: green;' +
                        'width: max-content;' +
                        'height: 35px;' +
                        'font-weight: normal;' +
                        'padding: 5px 20px;' +
                        'background-color: #e1fddd;' +
                        'border: 1px solid green;' +
                        'text-align: center;' +
                        'border-radius: 12px;' +
                        '">' +
                        'Added to cart' +
                        '</span>'
                    );
                    // close the popup after 1 second
                    setTimeout(function () {
                        jQuery('.dialog-close-button.dialog-lightbox-close-button').trigger('click');
                        jQuery(document.body).trigger('wc_fragment_refresh');
                    }, 1000);
                }
                else {
                    // overlayToggle();
                    setTimeout(function () {
                        jQuery(
                            '#add-candidate-to-cart').prepend(
                            '<span id="amount_popup_message" style="' +
                            'display: flex;' +
                            'position: absolute;' +
                            'top: -45px;' +
                            'color: red;' +
                            'width: max-content;' +
                            'height: 35px;' +
                            'font-weight: normal;' +
                            'padding: 5px 20px;' +
                            'background-color: #ff000024;' +
                            'border: 1px solid red;' +
                            'text-align: center;' +
                            'border-radius: 12px;' +
                            '">' +
                            'Error occured while adding to cart' +
                            '</span>'
                        );
                    }, 1000);
                    jQuery(document.body).trigger('wc_fragment_refresh');
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.log('Error:', jqXHR, textStatus, errorThrown);
            }
        });
    });

});
//////////////////////////////////////////////////////////////////////////////////////
// ACCEPTANCE CHECKBOX: I have a Donation Voucher
//////////////////////////////////////////////////////////////////////////////////////
document.addEventListener('DOMContentLoaded', function () {
    const commonAncestor = document.body; // You can use a more specific common ancestor

    commonAncestor.addEventListener('click', function (event) {
        const checkbox = event.target.closest('#form-field-acceptance');
        const acceptanceModal = document.querySelector('.acceptance-modal');

        if (checkbox) {
            acceptanceModal.style.display = acceptanceModal.style.display === 'flex' ? 'none' : 'flex';
        }
    });
});
//////////////////////////////////////////////////////////////////////////////////////

