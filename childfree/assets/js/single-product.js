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

jQuery(document).ready(function($) {

    // Amount remainig fix font-fize when - Complited

    const amountValue = $('#candidate-remaining-amount .elementor-widget-container span');

    if (amountValue.text().trim() === 'Completed') {
        amountValue.css('font-size', '18px');
    }

    // Hide voucher code default message (if any) on page load
    jQuery('.woocommerce-message').hide();

    let remainingAmount = elementData.remaining_amount;
    jQuery('#dAmount_full').attr({
        'id': 'dAmount_' + remainingAmount,
        'value': remainingAmount
    });
    console.log('elementData.remaining_amount: ' + remainingAmount);
    if (remainingAmount === '0') {
        jQuery('.candidate-inputs').hide();
        jQuery('label[for="dAmount_full"]').attr('for', 'dAmount_' + remainingAmount);
        jQuery('#donation_amount_single_box > div > span')
            .text('Fully Funded Candidate!').parent().css('text-align','center');
        // hide 'donation amount' label
        jQuery('.elementor-element-3deebb1b').hide();
        // hide the radio buttons
        jQuery('.elementor-element-e244979 *').hide();
        // hide voucher code checkbox
        jQuery('.elementor-element-375dcb7f').hide();
        // hide the donate now button
        jQuery('.elementor-element-286b6b39').hide();
        // hide the add to cart button
        jQuery('.elementor-element-13af940b').hide();
        // style 'See all donations' button
        jQuery('.elementor-element-40ec3e46').css({
            'display': 'flex',
            'width': '100%',
            'justify-content': 'space-evenly'
        });
    }
    else {
        jQuery('label[for="dAmount_full"]').attr('for', 'dAmount_' + remainingAmount);
    }

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
        if (parseInt(numericValue) > parseInt(remainingAmount)) {
            numericValue = remainingAmount;
            // Remove the appended span from donation amount input field, before populating it
            $(this).closest('.elementor-widget-container').find('#amount_error_message').remove();
            $(this).closest('.elementor-widget-container').append('<span id="amount_error_message" style="color: red; font-size: 12px;">' +
                'The Donation Amount must not exceed the remaining amount, which is <strong>$' + remainingAmount + '</strong>.</span>');
        }

        $(this).val('$' + numericValue);

    });

    // Get all radio buttons with the name 'donationAmount'
    const radioButtonsDonationAmounts = document.querySelectorAll('input[name="donationAmount"]');

    radioButtonsDonationAmounts.forEach(button => {
        const $button = $(button);
        const buttonValue = parseInt($button.val()); // Convert to number
        if (buttonValue > remainingAmount) {
            // console.log('buttonValue: ' + buttonValue + ' remainingAmount: ' + reaminingAmount);
            $button.closest('.inputs-option').hide(); // Hide the parent span
        }
    });

    // Add event listener to each radio button
    radioButtonsDonationAmounts.forEach(button => {
        button.addEventListener('click', () => {
            let otherAmountElement = $('#dAmount_other_input');
            const selectedValue = button.value;
            const formattedValue = '$' + new Intl.NumberFormat().format(selectedValue);
            console.log('formattedValue: ' + formattedValue + ' selectedId: ' + button.id);
            /*
            * Before changing the text, check if the donation amount element is span or input
            * If element is 'input', then replace it with 'span'
            */
            if (jQuery(this).attr('id') !== 'dAmount_other') {
                // Check if the element with id 'dAmount_other_input' exists
                if (otherAmountElement.length > 0) {
                    // Replace with the specified span element code
                    var newSpan = $('<span class="elementor-heading-title elementor-size-large">$0.00</span>');
                    // Remove the appended span from donation amount input field, before populating it
                    otherAmountElement.closest('.elementor-widget-container')
                        .find('span').remove();
                    otherAmountElement.replaceWith(newSpan);
                }
                jQuery('#donation_amount_single_box > div > span').text(formattedValue);
            }
            else {
                jQuery('#donation_amount_single_box > div > span').css('display', 'none');
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
        let donationAmountCompletedText = jQuery('#donation_amount_single_box > div > span').text();
        // console.log('donationAmountCompletedText: ' + donationAmountCompletedText);
        if (donationAmountCompletedText !== 'Fully Funded Candidate!') {
            enable_other_amount_input();
        }
    });

    // Function for 'Other Amount' input
    $('#dAmount_other').change(function() {
        enable_other_amount_input();
    });

});

//////////////////////////////////////////////////////////////////////////////////////
// ACCEPTANCE CHECKBOX: I have a Donation Voucher
//////////////////////////////////////////////////////////////////////////////////////
document.addEventListener('DOMContentLoaded', function () {
    const checkbox = document.getElementById('form-field-acceptance');
    const acceptanceModal = document.querySelector('.acceptance-modal');

    checkbox.addEventListener('click', () => {
        if (checkbox.checked) {
            acceptanceModal.style.display = 'flex';
        }
        else {
            acceptanceModal.style.display = 'none';
        }
        // acceptanceModal.style.display = acceptanceModal.style.display === 'flex' ? 'none' : 'flex';
    });
});

//////////////////////////////////////////////////////////////////////////////////////
// on donate now button click id: 'add-to-cart' create an ajax call that will send the voucher-code,
// amount, other-amount and product id to the server
// if the response is success then redirect to the checkout page '/checkout/'
//////////////////////////////////////////////////////////////////////////////////////
jQuery(document).ready(function($) {

    let ajaxurl = elementData.ajaxurl;
    let productId = elementData.product_id;

    // Donate Now button click event
    $('#donate-now-button').click(function(e) {
        e.preventDefault();

        // Get the values
        var voucherCode = $('#voucher_code').val();
        if (typeof voucherCode === 'undefined') { voucherCode = ''; }

        var donationAmount = $('input[name="donationAmount"]:checked').val();
        if (typeof donationAmount === 'undefined') { donationAmount = ''; }

        var otherAmount = $('#dAmount_other_input').val();
        if (typeof otherAmount === 'undefined') { otherAmount = ''; }
        else if (otherAmount !== '' && otherAmount.includes('$')) {
            otherAmount = otherAmount.replace('$', '');
        }

        if (typeof productId === 'undefined') { productId = ''; }

        // console.log('voucherCode: ' + voucherCode);
        // console.log('donationAmount: ' + donationAmount);
        // console.log('otherAmount: ' + otherAmount);
        // console.log('productId: ' + productId);
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
                '#donate-now-button').prepend(
                '<span id="amount_popup_message" style="' +
                'display: flex;' +
                'align-items: center;' +
                'position: absolute;' +
                'top: -45px;' +
                'left: 0;' +
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
            nonce: elementData.nonce
        };

        $.ajax({
            url: ajaxurl,
            type: 'POST',
            data: data,
            beforeSend: function() {
                overlayToggle();
            },
            success: function(response) {
                console.log('Redirect to checkout page');
                // overlayToggle();
                document.cookie = 'ckVoucherCode=' + voucherCode;
                window.location.href = '/checkout/';
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.log('Error:', jqXHR, textStatus, errorThrown);
                overlayToggle();
            }
        });
    });

    // Add to cart button click event
    $('#add-candidate-to-cart').click(function(e) {
        e.preventDefault();

        // Get the values
        let voucherCode = $('#voucher_code').val();
        if (typeof voucherCode === 'undefined') { voucherCode = ''; }

        // Get Donation Amount from text box
        var donationAmount = $('input[name="donationAmount"]:checked').val();
        if (typeof donationAmount === 'undefined') { donationAmount = ''; }

        let otherAmount = $('#dAmount_other_input').val();
        if (typeof otherAmount === 'undefined') { otherAmount = ''; }
        else if (otherAmount !== '' && otherAmount.includes('$')) {
            otherAmount = otherAmount.replace('$', '');
        }

        if (typeof productId === 'undefined') { productId = ''; }

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
        let data = {
            action: 'childfree_add_to_cart',
            voucher_code: voucherCode,
            amount: amount,
            // amount: donationAmount,
            // other_amount: otherAmount,
            product_id: productId,
            nonce: elementData.nonce
        };

        $.ajax({
            url: ajaxurl,
            type: 'POST',
            data: data,
            // beforeSend: function() {
                
            // },
            success: function(response) {
                console.log('Added to cart.');
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
                   
                        
                         // On success redirect to candidates page
                         
                    setTimeout(()=>{
                        overlayToggle();
                        window.location.href = '/candidates/';
                    },1000);
                
                    
                    
                }
                else {
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
            error: function(jqXHR, textStatus, errorThrown) {
                console.log('Error:', jqXHR, textStatus, errorThrown);
                overlayToggle();
            }
        });
    });

});