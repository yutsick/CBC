"use strict";
let is_specific_funds_tab_selected = obj_candidates.is_specific_funds_tab_selected;
if (is_specific_funds_tab_selected == null) {
    is_specific_funds_tab_selected = false;
}
console.log('[is_specific_funds_tab_selected]->' + is_specific_funds_tab_selected);

// Access the nonce variable from the custom_cart_update_nonce object
let nonce = obj_candidates.nonce;
let ajaxurl = obj_candidates.ajaxurl;
let userRole = obj_candidates.user_role;
let userDisplayName = obj_candidates.user_display_name;
let cartTotal = obj_candidates.cart_total;
let cartSubTotal = obj_candidates.cart_subtotal;
let cartContents = obj_candidates.cart_contents;
let voucherCode = obj_candidates.voucher_code;
let VoucherAmount = obj_candidates.voucher_amount;
let donateAnonymously = '';
let generalFunds = '';
let expansionFunds = '';
let createDonorAccountYesNo = '';
let donationReferralYesNo = '';
let donationReferrerDetail = '';
let originalPrices = {}; // Object to store cart item's original prices
let totalCartPrice = 0;

let expansionDonationYesNo, expansionDonationType, expansionDonationTypeElement, expansionDonationTypeValue,
    expansionDonationAmount = 0, expansionDonationAmountEditor,
    expansionOtherAmountElement, expansionDonationAmountValue, radioButtonsExpansionDonationAmounts,
    generalDonationYesNo, generalDonationTypeValue, generalDonationAmountValue, radioButtonsGeneralDonationAmounts,
    generalDonationAmountEditor, generalOtherAmountElement, generalDonationTypeElement,
    specificDonationAmount, specificDonationType, specificDonorAccountYesNo,
    locationDonationAmount = 0, locationDonationType, locationDonationZip, locationDonationYesNo, locationDonationAmountValue,
    voucherDisplayPrice, voucherCodeElement, VoucherAmountElement;

let rightPanelGeneralElement = jQuery('#right_panel_general'),
    rightPanelSpecificElement = jQuery('#right_panel_specific'),
    rightPanelLocationElement = jQuery('#right_panel_location'),
    rightPanelExpansionElement = jQuery('#right_panel_expansion'),
    rightPanelVoucherElement = jQuery('#right_panel_voucher'),
    rightPanelTotalDonationsElement = jQuery('#right_panel_cart_total');
let eleRightPanelGeneralValue = jQuery('#right_panel_general_donation_amount span'),
    eleRightPanelGeneralType = jQuery('#right_panel_general_donation_type span'),
    eleRightPanelSpecificValue = jQuery('#specific_candidates_total_amount span'),
    eleRightPanelSpecificType = jQuery('.elementor-element-d724586 span'),
    eleRightPanelLocationZipValue = jQuery('#right_panel_location_donation_zip_number span'),
    eleRightPanelLocationValue = jQuery('#right_panel_location_donation_amount span'),
    eleRightPanelLocationType = jQuery('#right_panel_location_donation_type span'),
    eleRightPanelExpansionValue = jQuery('#right_panel_expansion_donation_amount span'),
    eleRightPanelExpansionType = jQuery('#right_panel_expansion_donation_type span'),
    eleRightPanelVoucherCode = jQuery('#right_panel_voucher_code span'),
    eleRightPanelVoucherValue = jQuery('#right_panel_voucher_amount span'),
    eleRightPanelTotalDonationsValue = jQuery('#right_panel_total_donation_value span'),
    eleRightPanelSubTotalDonationsValue = jQuery('#right_panel_subtotal_donation_value span')
    ;

let cookiePath = '; path=/;',
    ckSpecificDonationTotalAmount = 'ckSpecificDonationTotalAmount',
    ckSpecificDonationType = 'ckSpecificDonationType',
    ckTotalDonations = 'ckTotalDonations',
    ckSubTotalDonations = 'ckSubTotalDonations',
    ckGeneralDonationYesNo = 'ckGeneralDonationYesNo',
    ckGeneralDonationAmount = 'ckGeneralDonationAmount',
    ckGeneralDonationType = 'ckGeneralDonationType',
    ckExpansionDonationYesNo = 'ckExpansionDonationYesNo',
    ckExpansionDonationAmount = 'ckExpansionDonationAmount',
    ckExpansionDonationType = 'ckExpansionDonationType',
    ckLocationDonationType = 'ckLocationDonationType',
    ckLocationZipCodeNumber = 'ckLocationZipCodeNumber',
    ckLocationDonationAmount = 'ckLocationDonationAmount',
    ckLocationDonationYesNo = 'ckLocationDonationYesNo',
    ckCurrentUserRole = 'ckCurrentUserRole',
    ckDonateAnonymously = 'ckDonateAnonymously',
    ckCreateDonorAccountYesNo = 'ckCreateDonorAccountYesNo',
    ckDonationReferralYesNo = 'ckDonationReferralYesNo',
    ckDonationReferrerDetail = 'ckDonationReferrerDetail',
    ckVoucherCode = 'ckVoucherCode',
    ckVoucherAmount = 'ckVoucherAmount';

const rightPanelSummarySectionsList = {
    general: 'GeneralFunds',
    candidates: 'Candidates',
    locations: 'Locations',
    expansion: 'Expansion',
    voucher: 'Voucher'
};



// GET COOKIE VALUE
function getCookie(cookieName) {
    let name = cookieName + '=';
    let decodedCookie = decodeURIComponent(document.cookie);
    let cookieArray = decodedCookie.split(';');
    for (let i = 0; i < cookieArray.length; i++) {
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
function updateRemoveButtons() {


        if (jQuery('[data-product_id="23603"]').length != 0) {
            jQuery('[data-product_id="23603"]').on('click', () => {
                jQuery('#remove_general_panel').trigger('click');
                try {
                    let updatedSubtotal = response.subtotal;
                    jQuery(eleRightPanelSubTotalDonationsValue).text(updatedSubtotal);
                } catch (error) {
                    return;
                }
            })
        }
        if (jQuery('[data-product_id="55946"]').length != 0) {
            jQuery('[data-product_id="55946"]').on('click', () => {
                jQuery('#remove_expansion_panel').trigger('click');
                try {
                    let updatedSubtotal = response.subtotal;
                    jQuery(eleRightPanelSubTotalDonationsValue).text(updatedSubtotal);
                } catch (error) {
                    return;
                }
            })
        }
        if (jQuery('[data-product_id="56180"]').length != 0) {
            jQuery('[data-product_id="56180"]').on('click', () => {
            jQuery('#remove_location_panel').trigger('click');


                try {
                    let updatedSubtotal = response.subtotal;
                    jQuery(eleRightPanelSubTotalDonationsValue).text(updatedSubtotal);
                } catch (error) {
                    return;
                }
            })
        }
  

}

// HIDE PAYMENT FORM IF TOTAL EQUALS ZERO
function hideAdditionalPaymentMethod(){
    let total = +getCookie(ckSubTotalDonations) - +getCookie(ckVoucherAmount);
    //console.log('YEPPP!!! ' + total);

    if(total <= 0 && document.querySelector('#other_donation_options')){
        document.querySelector('#order_review').setAttribute("hidden", true);
        document.querySelector('form [name="checkout"]').parentElement.parentElement.previousElementSibling.style.display="none";
        document.querySelector('form [name="checkout"]').setAttribute("hidden", true);
        document.querySelector('span.policy-form').innerHTML = 'Your personal data and identity will ONLY be used to process your donation and for the other limited purposes described in our <a href="/privacy-policy/" target="_blank" class="policy-form__accent">Privacy Policy</a> to enhance your user experience. If you use a Donation Vaucher and your Total Donation Amount is zero then you do not have to provide any personal information, simply click the "Donate Now Securely" button.'
        document.querySelector('span.policy-form').parentElement.previousElementSibling.style.display = 'none';
        document.querySelector('span.policy-form').parentElement.previousElementSibling.previousElementSibling.style.display = 'none';
    }else {
        try {
            document.querySelector('#order_review').removeAttribute("hidden");
            document.querySelector('form [name="checkout"]').parentElement.parentElement.previousElementSibling.style.display="block";
            document.querySelector('form [name="checkout"]').removeAttribute("hidden");
            document.querySelector('span.policy-form').innerHTML = 'Your personal data and identity will ONLY be used to process your donation and for the other limited purposes described in our <a href="/privacy-policy/" target="_blank" class="policy-form__accent">Privacy Policy</a> to enhance your user experience.'
            document.querySelector('span.policy-form').parentElement.previousElementSibling.style.display = 'block';
            document.querySelector('span.policy-form').parentElement.previousElementSibling.previousElementSibling.style.display = 'block';
        } catch (error) {
            
        }
        
    }
}


updateRemoveButtons();


// ALL TABS CLICK HANDLER
jQuery(document).ready(function ($) {



    let currentTabId = 'e-n-tabs-title-1521';

    function handleTabClick(tabId) {
        setTimeout(function () {
            console.log('refreshing tabs...')
            jQuery('#e-n-tabs-title-1521, #e-n-tabs-title-1522, #e-n-tabs-title-1523, #e-n-tabs-title-1524')
                .attr('aria-selected', 'false').attr('tabindex', '-1');
            jQuery('#e-n-tab-content-1521, #e-n-tab-content-1522, #e-n-tab-content-1523, #e-n-tab-content-1524')
                .hide();

            if (tabId === 'e-n-tabs-title-1521') {
                jQuery('#e-n-tabs-title-1521').attr('aria-selected', 'true').attr('tabindex', '0');
                jQuery('#e-n-tab-content-1521').show();
                console.log('[TAB]Specific');
            } else if (tabId === 'e-n-tabs-title-1522') {
                jQuery('#e-n-tabs-title-1522').attr('aria-selected', 'true').attr('tabindex', '0');
                jQuery('#e-n-tab-content-1522').show();
                console.log('[TAB]General');
            } else if (tabId === 'e-n-tabs-title-1523') {
                jQuery('#e-n-tabs-title-1523').attr('aria-selected', 'true').attr('tabindex', '0');
                jQuery('#e-n-tab-content-1523').show();
                console.log('[TAB]Location');
            } else if (tabId === 'e-n-tabs-title-1524') {
                jQuery('#e-n-tabs-title-1524').attr('aria-selected', 'true').attr('tabindex', '0');
                jQuery('#e-n-tab-content-1524').show();
                console.log('[TAB]Expansion');
            }
        }, 700);

        // OVERFLOW CONTAINER TOGGLE
        setTimeout(function () {
            overlayToggle();
        }, 1000);

    }

    jQuery('.e-n-tabs-heading').on('click',
        '#e-n-tabs-title-1521, #e-n-tabs-title-1522, #e-n-tabs-title-1523, #e-n-tabs-title-1524',
        function () {

            overlayToggle();

            let tabId = jQuery(this).attr('id');

            // DO NOT DELETE/COMMENT THE FOLLOWING TWO LINES OF CODE: it removes the css glitch when clicked on the tab item.
            jQuery('#e-n-tabs-title-1521, #e-n-tabs-title-1522, #e-n-tabs-title-1523, #e-n-tabs-title-1524')
                .attr('aria-selected', 'false').attr('tabindex', '-1');
            // jQuery('#e-n-tab-content-1521, #e-n-tab-content-1522, #e-n-tab-content-1523, #e-n-tab-content-1524')
            jQuery('#e-n-tab-content-1522, #e-n-tab-content-1523, #e-n-tab-content-1524')
                .hide();
            // END OF DO NOT DELETE/COMMENT THE FOLLOWING TWO LINES OF CODE

            let fundsType;

            switch (tabId) {
                case 'e-n-tabs-title-1521':
                    fundsType = 'specific';
                    break;
                case 'e-n-tabs-title-1522':
                    fundsType = 'general';
                    break;
                case 'e-n-tabs-title-1523':
                    fundsType = 'location';
                    break;
                case 'e-n-tabs-title-1524':
                    fundsType = 'expansion';
                    break;
                default:
                    fundsType = 'specific';
                    break;
            }

            if (fundsType) {
                // history.pushState(null, null, '/checkout/?funds=' + fundsType);
                window.location.href = '/checkout/?funds=' + fundsType;
            }

            // return false so that tab switching does not happen before page load.
            return false;
        });
    // ON PAGE LOAD: CHECK WHICH TAB TO SHOW ON PAGE LOAD
    if (is_specific_funds_tab_selected) {
        handleTabClick(currentTabId);
    }
});

jQuery(document).ready(function ($) {
    // REMOVE AMOUNT FORMATTING AND RETURN THE AMOUNT WITHOUT COMMAS AND DOLLAR SIGN
    function sanitizeCandidatesPageAmount(amount) {
        if (amount === 0 || amount === null || amount === '' || amount === undefined || isNaN(amount)) {
            return 0;
        } else if (typeof amount === 'string' && amount !== 0) {
            let _amount = 0;
            _amount = amount.replace(/,/g, '');
            _amount = _amount.replace('$', '');
            return _amount;
        }
    }

    // POPULATE MAIN CART PANEL
    function populate_main_cart_panel() {

        // return if general tab is selected
        if (!is_specific_funds_tab_selected) {
            console.log('Current Tab is not SPECIFIC TAB.');
            return;
        }

        let main_cart_item_container = jQuery('#checkout_page_specific_candidate_main_details');

        if (cartContents !== null) {
            // Clearing the main-cart before populating it again.
            main_cart_item_container.html('');
            cartContents.forEach(item => {
                // Check if the product is "General Donation" or "Expansion Donation"
                if (item.product_name === "General Fund Donation"
                    || item.product_name === "Expansion Fund Donation"
                    || item.product_name === "Specific Location Fund Donation") {
                    return;
                }

                console.log('Populating Main Cart Panel...');
                console.log('main-item.product_name: ' + item.product_name + '\n\titem.product_price: ' + item.product_price);
                let outerContainer = jQuery('<div class="main-cart-item-full-row"></div>');
                main_cart_item_container.append(outerContainer);
                let itemFormContainer = jQuery(
                    '<form class="woocommerce-cart-form" action="" ' +
                    'method="post">' +
                    '</form>'
                );
                outerContainer.append(itemFormContainer);

                let row = jQuery('<div class="main-cart-item-full-row"></div>');

                row.append(
                    '<div class="main-cart-inner-image-and-name">' +
                    '   <div id="cart_item_specific_candidate_image">' + item.product_thumbnail + '</div>' +
                    '   <div id="cart_item_specific_candidate_name"><a href="' + item.product_url + '" target="_blank">' + item.product_name + '</a></div>' +
                    '</div>'
                );
                if (item.product_price <= 0) {
                    totalCartPrice = parseInt(item.product_price) + parseInt(VoucherAmount);
                } else {
                    totalCartPrice = parseInt(item.product_price);
                }
                row.append(
                    '<div class="main-cart-inner-amount-and-remove">' +
                    '<div id="checkout_page_specific_candidate_amount">' +
                    '<div>$</div>' +
                    '<div>' +
                    '<input type="number" id="cart-item-donation_' + item.product_key + '" ' +
                    'class="cart-item-price" data-product-id="' + item.product_id + '" data-cart-item-key="' +
                    item.product_key + '" value="' + totalCartPrice + '" />' +
                    '</div>' +
                    '</div>' +
                    '<div class="product-remove">' +
                    '<button title="Remove this item from the cart." data-product-key="' + item.product_key + '" data-wp-wpnonce="' + nonce + '"' +
                    ' class="remove" id="remove-this-cart-item" aria-label="Remove ' + item.product_name +
                    ' from cart" data-product_id="' + item.product_id + '" data-product_sku="' + item.product_id + '">' +
                    '<i aria-hidden="true" class="far fa-trash-alt"></i>' +
                    '</button>' +
                    '</div>' +
                    '</div>'
                );

                itemFormContainer.append(row);
            });
        }
        else {
            console.log('clearing main-cart element, because cart is empty.');
            main_cart_item_container.html('');
        }

        // Display following message if there is no candidate in the cart
        setTimeout(function () {
            // Check if the main cart is empty
            // let main_cart_item_container = jQuery('#checkout_page_specific_candidate_main_details');
            if (main_cart_item_container.find('.main-cart-item-full-row form > *').length <= 0) {
                main_cart_item_container.append('<p style="font-size: 18px;\n' +
                    '    color: #143A62;font-style: italic;\n' +
                    '    padding: 0 20px;font-weight: 300;">Specific Candidate Donation: No Candidate donations have ' +
                    'been added to your Cart yet, please <strong><a href="/candidates/">View All Candidates</a></strong>' +
                    ' to add donations to your cart.</p>');

            } else {
                console.log('Main-Cart already populated!');
            }
        }, 1000);

        updateRightPanel(rightPanelSummarySectionsList.candidates);
    }
    populate_main_cart_panel();

    // TOTAL DONATIONS SECTION HANDLER
    function updateRightPanelTotalDonations() {
        const sanitizedGeneralAmount = sanitizeCandidatesPageAmount(String(generalDonationAmountValue));
        const sanitizedSpecificAmount = sanitizeCandidatesPageAmount(String(specificDonationAmount));
        const sanitizedLocationAmount = sanitizeCandidatesPageAmount(String(locationDonationAmount));
        const sanitizedExpansionAmount = sanitizeCandidatesPageAmount(String(expansionDonationAmount));

        // const totalDonations = cartTotal.replace('$', '');
        let totalCandidatesPageDonations =
            (parseInt(sanitizedGeneralAmount) || 0) +
            (parseInt(sanitizedSpecificAmount) || 0) +
            (parseInt(sanitizedLocationAmount) || 0) +
            (parseInt(sanitizedExpansionAmount) || 0) -
            (parseInt(VoucherAmount) || 0);

        let subTotalSpecificPageDonations = cartSubTotal.replace('$', '');

        // if ((!isNaN(totalCandidatesPageDonations) && totalCandidatesPageDonations > 0 )|| $('#right_panel_voucher').is(':visible')) {
        // if ((!isNaN(totalCandidatesPageDonations) && totalCandidatesPageDonations !== 0) || $('#right_panel_voucher').is(':visible')) {
        if ((!isNaN(totalCandidatesPageDonations)) || VoucherAmount > 0 || VoucherAmount !== '' || VoucherAmount !== 0) {
            if (totalCandidatesPageDonations < 0) {
                totalCandidatesPageDonations = 0;
            }
            if (totalCandidatesPageDonations === 0 && (VoucherAmount === 0 || VoucherAmount === '')) {
                rightPanelTotalDonationsElement.hide();
                console.log('Total Donations[Specific Tab] is zero without voucher!');
                return;
            }

            eleRightPanelTotalDonationsValue.text('$' + new Intl.NumberFormat().format(totalCandidatesPageDonations));
            if (subTotalSpecificPageDonations <= 0) {
                subTotalSpecificPageDonations = totalCandidatesPageDonations;
            }

            document.cookie = ckTotalDonations + '=' + totalCandidatesPageDonations + cookiePath;
            eleRightPanelSubTotalDonationsValue.text('$' + subTotalSpecificPageDonations);
            //eleRightPanelSubTotalDonationsValue.text('$' + new Intl.NumberFormat().format(subTotalSpecificPageDonations));
            document.cookie = ckSubTotalDonations + '=' + subTotalSpecificPageDonations + cookiePath;
            // console.log('subTotal Donations[Specific Tab]:' + subTotalSpecificPageDonations);

            rightPanelTotalDonationsElement.show();
            // console.log('Total Donations:',
            //     '\n\t$specific[' + sanitizedSpecificAmount + ']' +
            //     '\t$general[' + sanitizedGeneralAmount + ']' +
            //     '\n\t$location[' + sanitizedLocationAmount + ']' +
            //     '\t$expansion[' + sanitizedExpansionAmount + ']' +
            //     '\n\nupdateRightPanelCookies' +
            //     '\n\tspecificAmount: [' + parseInt(sanitizedSpecificAmount) + ']\tType: [' + specificDonationType + ']' +
            //     '\n\tlocationAmount: [' + parseInt(sanitizedLocationAmount) + ']\tType: [' + locationDonationType + ']' +
            //     '\n\tgeneralAmount: [' + parseInt(sanitizedGeneralAmount) + ']\tType: [' + generalDonationTypeValue + ']' +
            //     '\n\texpansionAmount: [' + parseInt(sanitizedExpansionAmount) + ']\tType: [' + expansionDonationType + ']' +
            //     '\n\tVoucherAmount: [' + parseInt(VoucherAmount) + ']\tCode: [' + voucherCode + ']' +
            //     '\n\ttotalCandidatesPageDonations: [' + totalCandidatesPageDonations + ']'
            // );
        } else {
            rightPanelTotalDonationsElement.hide();
            console.log('Total Donations[Specific Tab]:' + totalCandidatesPageDonations);
        }
    }


    // POPULATE RIGHT CART PANEL
    function updateRightPanel(rightPanelDonationSectionName) {
        let page1Data = {
            donate_anonymously: getCookie(ckDonateAnonymously),
            generalDonationAmount: getCookie(ckGeneralDonationAmount),
            expansionDonationAmount: getCookie(ckExpansionDonationAmount),
            locationDonationAmount: getCookie(ckLocationDonationAmount),
            locationZipCodeNumber: getCookie(ckLocationZipCodeNumber),
        };
        let jsonPage1Data = JSON.stringify(page1Data);
        $.ajax({
            type: 'POST',
            url: ajaxurl,
            data: {
                action: 'specific_checkout_action',
                specific_form_page_1_data: jsonPage1Data,
                security: nonce,
            },
            success: function (response) {

                if (response.success) {
                    cartSubTotal = response.data.cart_subtotal;
                    updateRightPanelTotalDonations();
                    $('.widget_shopping_cart_content').html(response.data.mini_cart);

                    $(document.body).trigger('update_checkout');
                    $(document.body).trigger('wc_fragments_refreshed');
                    
                    if($('.clear-all-wrapper a').length == 0 && cartSubTotal != '$0'){
                        
                        $('.clear-all-wrapper').append('<a href="javascript:;">Clear All</a>')
                    }
                    
                    updateRemoveButtons();
                } else {
                    console.log('Failed_P1: \n' + JSON.stringify(response));
                }
                hideAdditionalPaymentMethod();
            }
        });


        // $(document.body).trigger('update_checkout');
        switch (rightPanelDonationSectionName) {
            // SPECIFIC CANDIDATES DONATION SECTION HANDLER
            case rightPanelSummarySectionsList.candidates:
                specificDonationAmount = getCookie(ckSpecificDonationTotalAmount);
                specificDonationType = getCookie(ckSpecificDonationType);
                let VoucherAmountNoSign = VoucherAmount.replace('$', '');
                if (VoucherAmount === '') {
                    VoucherAmount = 0;
                }
                console.log('cartTotal: ' + specificDonationAmount + ' VoucherAmount: ' + VoucherAmountNoSign);
                // if (VoucherAmountNoSign > 0) {
                //     specificDonationAmount = parseInt(specificDonationAmount) + parseInt(VoucherAmountNoSign);
                // }
                if (cartContents === null) {
                    console.log('Cart is empty. No need to populate specific cart right panel.');
                    rightPanelSpecificElement.hide();
                }
                else if (specificDonationAmount !== '' && specificDonationAmount > 0) {
                    // CART TOTAL UPDATE SPECIFIC TAB
                    console.log('Cart has [' + cartContents.length + '] item(s)!');
                    if (typeof specificDonationAmount === 'string' && specificDonationAmount.includes('$')) {
                        eleRightPanelSpecificValue.text(specificDonationAmount);
                    } else {
                        eleRightPanelSpecificValue.text('$' + new Intl.NumberFormat().format(specificDonationAmount));
                    }

                    if (specificDonationType) {
                        eleRightPanelSpecificType.text(specificDonationType);
                    } else {
                        specificDonationType = 'One-Time';
                        document.cookie = ckSpecificDonationType + '=' + specificDonationType + cookiePath;
                    }

                    let rightPanel_cart_item_container = jQuery('#cart_items_specific_candidate_details');
                    rightPanel_cart_item_container.empty();

                    let outerContainer = jQuery('<div class="right-outer-cart-item"></div>');
                    rightPanel_cart_item_container.append(outerContainer);

                    // do not run the loop if outercontainer already has child elements
                    if (outerContainer.find('.right-inner-cart-item').length > 0) {
                        console.log('Right Panel already populated!');
                    }
                    else {
                        cartContents.forEach(item => {
                            // Check if the product is "General Donation" or "Expansion Donation"
                            if (item.product_name === "General Fund Donation"
                                || item.product_name === "Expansion Fund Donation"
                                || item.product_name === "Specific Location Fund Donation") {
                                return;
                            }

                            let row = jQuery('<div class="right-inner-cart-item"></div>');

                            row.append(
                                '<div class="right-inner-image-and-name">' +
                                '   <div id="cart_item_specific_candidate_image">' + item.product_thumbnail + '</div>' +
                                '   <div id="cart_item_specific_candidate_name"><a href="' + item.product_url + '" target="_blank">' + item.product_name + '</a></div>' +
                                '</div>'
                            );

                            if (item.product_price <= 0) {
                                totalCartPrice = parseInt(item.product_price) + parseInt(VoucherAmount);
                            } else {
                                totalCartPrice = parseInt(item.product_price);
                            }
                            row.append('<div id="checkout_page_specific_candidate_amount"><strong>$' + Number(totalCartPrice).toLocaleString() + '</strong></div>');
                            row.append('<div class="product-remove">' +
                                '<span data-product-key="' + item.product_key + '" title="Remove this item from the cart." data-wp-wpnonce="' + nonce + '"' +
                                ' class="remove icon_remove-item_right-panel" id="remove-this-cart-item" aria-label="Remove ' + item.product_name +
                                ' from cart" data-product_id="' + item.product_id + '" data-product_sku="' + item.product_id + '">' +
                                '<i aria-hidden="true" class="far fa-trash-alt"></i>' +
                                '</span>' +
                                '</div>');

                            outerContainer.append(row);
                        });
                    }
                    rightPanelSpecificElement.show();
                }
                break;

            // GENERAL DONATION SECTION HANDLER
            case rightPanelSummarySectionsList.general:
                generalDonationYesNo = getCookie(ckGeneralDonationYesNo);
                generalDonationAmountValue = getCookie(ckGeneralDonationAmount);
                generalDonationTypeValue = getCookie(ckGeneralDonationType);

                if (generalDonationTypeValue) {
                    eleRightPanelGeneralType.text(generalDonationTypeValue);
                } else {
                    generalDonationTypeValue = 'One-Time';
                    document.cookie = ckGeneralDonationType + '=' + generalDonationTypeValue + cookiePath;
                    console.log('General Donation Type is not set.');
                }
                if (generalDonationAmountValue > 0) {
                    generalDonationYesNo = 'Yes';
                    document.cookie = ckGeneralDonationYesNo + '=' + generalDonationYesNo + cookiePath;
                }
                if (generalDonationYesNo === 'Yes' && generalDonationAmountValue > 0) {
                    eleRightPanelGeneralValue.text('$' + new Intl.NumberFormat().format(generalDonationAmountValue));
                    eleRightPanelGeneralValue.append('<span title="Remove this item from the cart." class="icon_remove-item_right-panel" id="remove_general_panel">' +
                        ' <i aria-hidden="true" class="far fa-trash-alt"></i></span>');
                    rightPanelGeneralElement.show();
                } else {
                    generalDonationAmountValue = 0;
                    rightPanelGeneralElement.hide();
                }
                break;

            // SPECIFIC LOCATION DONATION SECTION HANDLER
            case rightPanelSummarySectionsList.locations:
                locationDonationAmount = getCookie(ckLocationDonationAmount);
                locationDonationType = getCookie(ckLocationDonationType);
                locationDonationZip = getCookie(ckLocationZipCodeNumber);

                if (locationDonationType) {
                    eleRightPanelLocationType.text(locationDonationType);
                } else {
                    eleRightPanelLocationType.text('One-Time');
                    document.cookie = ckLocationDonationType + '=' + locationDonationType + cookiePath;
                    console.log('Location Donation Type is not set.');
                }

                if (locationDonationAmount > 0 && locationDonationZip > 0) {
                    eleRightPanelLocationValue.text('$' + new Intl.NumberFormat().format(locationDonationAmount));
                    eleRightPanelLocationValue.append('<span title="Remove this item from the cart." class="icon_remove-item_right-panel" id="remove_location_panel">' +
                        ' <i aria-hidden="true" class="far fa-trash-alt"></i></span>');
                    eleRightPanelLocationZipValue.text(locationDonationZip);
                    rightPanelLocationElement.show();
                } else {
                    locationDonationZip = '';
                    locationDonationAmount = 0;
                    rightPanelLocationElement.hide();
                }
                break;

            // EXPANSION DONATION SECTION HANDLER
            case rightPanelSummarySectionsList.expansion:
                expansionDonationYesNo = getCookie('ckExpansionDonationYesNo');
                expansionDonationType = getCookie('ckExpansionDonationType');
                expansionDonationAmount = getCookie(ckExpansionDonationAmount);
                if (expansionDonationType) {
                    eleRightPanelExpansionType.text(expansionDonationType);
                } else {
                    eleRightPanelExpansionType.text('One-Time');
                    document.cookie = ckExpansionDonationType + '=' + expansionDonationType + cookiePath;
                    console.log('Expansion Donation Type is not set.');
                }

                if (expansionDonationYesNo === 'Yes' && expansionDonationAmount > 0) {
                    eleRightPanelExpansionValue.text('$' + new Intl.NumberFormat().format(expansionDonationAmount));
                    eleRightPanelExpansionValue.append('<span title="Remove this item from the cart." class="icon_remove-item_right-panel" id="remove_expansion_panel">' +
                        ' <i aria-hidden="true" class="far fa-trash-alt"></i></span>');
                    rightPanelExpansionElement.show();
                } else {
                    expansionDonationAmount = 0;
                    rightPanelExpansionElement.hide();
                }
                break;

            // DONATION VOUCHER SECTION HANDLER
            case rightPanelSummarySectionsList.voucher:
                voucherCode = getCookie('ckVoucherCode');
                VoucherAmount = getCookie('ckVoucherAmount');
                if (voucherCode) {
                    eleRightPanelVoucherCode.text(voucherCode);
                    if (VoucherAmount) {
                        eleRightPanelVoucherValue.text('-$' + VoucherAmount);
                    } else {
                        eleRightPanelVoucherValue.text('Not Set');
                    }
                    rightPanelVoucherElement.show();
                } else {
                    rightPanelVoucherElement.hide();
                }
                break;

            default:
                console.log('Error: sectionName not mentioned');
                return;
        }

        updateRightPanelTotalDonations();
        hideAdditionalPaymentMethod();
    }

    // UPDATE CART
    $('#update-cart-button').on('click', function () {
        // Define an array to store the modified cart items
        var modifiedCartItems = [];

        // Iterate through elements with IDs starting with "cart-item-price_"
        $('[id^="cart-item-donation_"]').each(function () {
            var element = $(this);
            var cartItemKey = element.data('cart-item-key');
            var productID = element.data('product-id');
            var newPrice = element.val();

            // Check if the price has changed
            if (!isNaN(newPrice) && newPrice !== originalPrices[cartItemKey]) {
                // Create an object with the details of the modified item
                var modifiedItem = {
                    cartItemKey: cartItemKey,
                    productID: productID,
                    newPrice: newPrice,
                };
                // Push this object to the modifiedCartItems array
                modifiedCartItems.push(modifiedItem);
            }
        });

        // Check if there are any modified items
        if (modifiedCartItems.length > 0) {
            // Send an AJAX request to update the modified cart item prices
            $.ajax({
                type: 'POST',
                url: ajaxurl, // Use the WordPress AJAX URL
                data: {
                    action: 'update_cart_item_prices', // Create this action in your PHP
                    modifiedItems: modifiedCartItems,
                    security: nonce,
                },
                success: function (response) {
                    if (response.success) {
                        // Cart updated successfully
                        console.log('Success! ' + response.data.message);
                        cartSubTotal = response.data.cart_subtotal;
                        cartContents = response.data.cart_contents;
                        updateRightPanel(rightPanelSummarySectionsList.candidates);
                       // $('.widget_shopping_cart_content').html(response.data.mini_cart);
                        $(document.body).trigger('wc_fragments_refreshed');
                        $(document.body).trigger('update_checkout');

                    } else {
                        console.log('Failed! ' + response.data.message);
                    }
                },
                error: function (error) {
                    // Handle any errors, e.g., display an error message
                    console.log('Error updating cart: ' + error.statusText);
                }
            });
        } else {
            alert('No changes to update.');
        }
    });

    // REMOVE ITEM -> Event Delegation


    jQuery(document).on('click', '#remove-this-cart-item', function (event) {
        event.preventDefault();

        let productKey = jQuery(this).data('product-key');

        jQuery.ajax({
            type: 'POST',
            url: ajaxurl,
            data: {
                action: 'remove_custom_cart_item',
                product_key: productKey,
                security: nonce
            },
            success: function (response) {
                if (response.success) {
                    console.log('Success!');
                    // console.log('item-removal-response: ' + JSON.stringify(response));
                    cartContents = response.data.cart_contents;
                    console.log('Removed PID [' + productKey + '] from cart!');
                    eleRightPanelSpecificValue.text('$0');
                    populate_main_cart_panel();
                    jQuery(document.body).trigger('wc_fragment_refresh');
                } else {
                    console.log('Failed! ' + response.message || 'An error occurred.');
                }
            },
            error: function (error) {
                console.log('Error removing item: ' + error.statusText);
            }
        });
    });


    // VOUCHER CODE STATUS CHECK
    if (voucherCode == null || voucherCode === '' && getCookie(ckVoucherCode) === '') {
        updateRightPanel(rightPanelSummarySectionsList.voucher);
    }
    // SHOW APPLIED VOUCHER CODE
    else if (getCookie(ckVoucherCode) !== '') {
        console.log('Voucher Code Exists. Setting Cookie...');
        jQuery('#frm_field_147_container').html(
            'Applied voucher code: <span id="coupon_code_value"><strong style="color: #3cb371;">'
            + getCookie(ckVoucherCode)
            + '</strong></span>' +
            '<button id="remove_coupon_code_value" data-voucher_code="' +
            +getCookie(ckVoucherCode) +
            '"><i aria-hidden="true" class="far fa-trash-alt"></i></button>'
        );
        jQuery('.acceptance-modal.code-status.voucher-row').hide();
        updateRightPanel(rightPanelSummarySectionsList.voucher);
    }
    // VOUCHER CODE APPLY
    jQuery('#apply_voucher_button').on('click', function (event) {
        event.preventDefault();

        let voucher_code = $('#voucher_code').val();
        let wc_voucher_notification = $('#woocommerce_voucher_notification');
        let voucherYesNo = $('#field_1wwqv_label').next('div');
        let voucherDiv = $('#frm_field_149_container');

        // Apply the voucher via WooCommerce AJAX
        $.ajax({
            type: 'POST',
            url: ajaxurl,
            data: {
                action: 'apply_voucher',
                voucher_code,
            },
            beforeSend: function () {
                overlayToggle();
            },
            success: function (response) {
                overlayToggle();
                if (response.success) {
                    document.cookie = ckVoucherCode + '=' + voucher_code + cookiePath;
                    document.cookie = ckVoucherAmount + '=' + response.data.voucher_amount + cookiePath;
                    wc_voucher_notification.prop('class', 'woocommerce-message').html(response.data.message).show();
                    voucherYesNo.hide();
                    voucherDiv.hide();
                    $('.applied-voucher-code-message').show();
                    $('#coupon_code_value').html(response.data.voucher_code).show();
                    window.location.reload();
                } else {
                    wc_voucher_notification.prop('class', 'woocommerce-error').html(response.data.message).show();
                }
            },
            error: function (response) {
                overlayToggle();
                wc_voucher_notification.prop('class', 'woocommerce-error').html(response.data.message).show();
            }
        });
    });
    // REMOVE VOUCHER CODE
    $('#remove_coupon_code_value').on('click', function (event) {
        event.preventDefault();

        let voucher_code = getCookie(ckVoucherCode);
        let wc_voucher_notification = $('#woocommerce_voucher_notification');

        $.ajax({
            type: 'POST',
            url: ajaxurl,
            data: {
                action: 'remove_voucher',
                voucher_code,
            },
            success: function (response) {
                wc_voucher_notification.html('').hide();
                wc_voucher_notification.prop('class', 'woocommerce-message').html(response.data.message).show();
                $('.applied-voucher-code-message').hide();
                $('#coupon_code_value').html('').hide();
                $('#voucher_code').val('');
                $('#remove_coupon_code_value').hide();
                $('#form-field-acceptance').prop('checked', false);
                $('#form-field-acceptance').next('label').html(
                    'I have a Donation Voucher'
                );
                $(document.body).trigger('update_checkout');
                location.reload();
            },
            error: function (response) {
                wc_voucher_notification.prop('class', 'woocommerce-error').html(response.data.message).show();
            }
        })
    });

    // DONATE ANONYMOUSLY RADIO BUTTON EVENT HANDLER
    if (getCookie(ckDonateAnonymously)) {
        if (getCookie(ckDonateAnonymously) === 'Yes') {
            donateAnonymously = 'Yes';
            jQuery('#field_donate_anonymously-0').prop('checked', true);
            jQuery('#donate_anonymously').val('yes');
        } else {
            donateAnonymously = 'No';
            jQuery('#field_donate_anonymously-1').prop('checked', true);
            jQuery('#donate_anonymously').val('no');
        }
        jQuery(document.body).trigger('update_checkout');
        console.log('DonateAnonymously Cookie Exists. Value: ' + getCookie(ckDonateAnonymously));
    } else {
        donateAnonymously = 'Yes';
        jQuery('#field_donate_anonymously-0').prop('checked', true);
        document.cookie = ckDonateAnonymously + '=Yes' + cookiePath;
        jQuery(document.body).trigger('update_checkout');
        console.log('DonateAnonymously Cookie Does Not Exist. Setting Default Value to "Yes"');
    }
    jQuery('#field_donate_anonymously-0, #field_donate_anonymously-1').on('change', function () {
        donateAnonymously = jQuery(this).val();
        document.cookie = ckDonateAnonymously + '=' + donateAnonymously + cookiePath;
        console.log('Donate Anonymously Updated to [' + donateAnonymously + ']');
    });

    // GENERAL DONATION HANDLER
    jQuery(document).ready(function ($) {
        generalDonationAmountEditor = $('#field_candidates_general_donation_amount');
        generalOtherAmountElement = $('#candidatesGeneralAmount_other');
        generalDonationAmountValue = getCookie(ckGeneralDonationAmount);
        generalDonationYesNo = getCookie(ckGeneralDonationYesNo);
        const radioButtonsGeneralDonationAmounts =
            document.querySelectorAll('input[name="candidatesGeneralDonationAmount"]');

        // On page load uncheck all general radio buttons other than the selected one
        if (generalDonationAmountValue > 0) {
            if (generalDonationYesNo === 'Yes') {
                jQuery('#field_3nlpf-0').click().prop('checked', true);
                generalDonationAmountEditor.val(new Intl.NumberFormat().format(generalDonationAmountValue));

                for (let i = 0; i < radioButtonsGeneralDonationAmounts.length; i++) {
                    const buttonGeneral = radioButtonsGeneralDonationAmounts[i];

                    if (buttonGeneral.value === generalDonationAmountValue && buttonGeneral.id.includes(generalDonationAmountValue)) {
                        buttonGeneral.checked = true;
                        // console.log('In Button Value: ' + buttonGeneral.value);
                        // console.log('In Button ID: ' + buttonGeneral.id);
                        generalDonationAmountEditor.val(new Intl.NumberFormat().format(buttonGeneral.value));
                        break; // Exit the loop
                    } else if (parseInt(generalDonationAmountValue) !== 0 && buttonGeneral.id.includes(generalOtherAmountElement.attr('id'))) {
                        // console.log('Out Button Value: ' + buttonGeneral.value);
                        // console.log('Out Button Amount: ' + generalDonationAmountValue);
                        // console.log('Out Button ID: ' + buttonGeneral.id);
                        generalDonationAmountEditor.attr('readonly', false);
                        generalDonationAmountEditor.val(new Intl.NumberFormat().format(generalDonationAmountValue));
                        generalOtherAmountElement.click();
                    } else {
                        buttonGeneral.checked = false;
                    }
                }
            }
            else {
                generalDonationYesNo = 'Yes';
                document.cookie = ckGeneralDonationYesNo + '=' + generalDonationYesNo + cookiePath;
                generalDonationAmountEditor.val(generalDonationAmountValue);
                jQuery('#field_3nlpf-0').click().prop('checked', true);
            }
            updateRightPanel(rightPanelSummarySectionsList.general);
        } else {
            generalDonationAmountEditor.val('');
            jQuery('#field_3nlpf-1').click().prop('checked', true);
            document.cookie = ckGeneralDonationAmount + '=0' + cookiePath;
            document.cookie = ckGeneralDonationYesNo + '=No' + cookiePath;
            document.cookie = ckGeneralDonationType + '=' + 'One-Time' + cookiePath;
            radioButtonsGeneralDonationAmounts.forEach(button => {
                button.checked = false;
            });
            updateRightPanel(rightPanelSummarySectionsList.general);
        }

        // General donation Yes/No radio button event handler
        jQuery('#field_3nlpf-0, #field_3nlpf-1').on('change', function () {
            generalDonationYesNo = jQuery(this).val();
            if (generalDonationYesNo === 'No') {
                setTimeout(function () {
                    jQuery('#remove_general_panel').trigger('click');
                    let cartItemKey = $('.elementor-menu-cart__product-remove .elementor_remove_from_cart_button[data-product_id=23603]').data('cart_item_key');

                    // AJAX request to remove the product from the cart
                    $.ajax({
                        type: 'POST',
                        url: wc_cart_fragments_params.wc_ajax_url.toString().replace('%%endpoint%%', 'remove_from_cart'),
                        data: {
                            action: 'remove_from_cart',
                            cart_item_key: cartItemKey
                        },
                        success: function (response) {
                            if (response && response.fragments) {
                                // Replace mini-cart fragments
                                $.each(response.fragments, function (key, value) {
                                    $(key).replaceWith(value);
                                });

                                // Trigger event for other scripts to listen
                                let updatedSubtotal = response.subtotal;
                                $(eleRightPanelSubTotalDonationsValue).text(updatedSubtotal);

                               // $(document.body).trigger('wc_fragments_refreshed');

                            }
                        }
                    });



                    console.log('General Donation Removed from Cart!');
                }, 1000);
                generalDonationAmountValue = '';
                generalDonationAmountEditor.val(generalDonationAmountValue);
                document.cookie = ckGeneralDonationAmount + '=' + generalDonationAmountValue + cookiePath;
                document.cookie = ckGeneralDonationType + '=' + 'One-Time' + cookiePath;
                if ((parseInt(expansionDonationAmount) >= 0 && expansionDonationType !== 'Monthly')
                    && (parseInt(locationDonationAmount) >= 0 && locationDonationType !== 'Monthly')) {
                    document.cookie = ckCreateDonorAccountYesNo + '=No' + cookiePath;
                }
                radioButtonsGeneralDonationAmounts.forEach(button => {
                    button.checked = false;
                });
            }
            document.cookie = ckGeneralDonationYesNo + '=' + generalDonationYesNo + cookiePath;
            updateRightPanel(rightPanelSummarySectionsList.general);
        });


        // If clicked on 'Other Amount' input radio button
        generalOtherAmountElement.change(function () {
            generalDonationAmountEditor.attr('readonly', false).click().focus().attr('placeholder', 'Enter Amount');
        });

        // update cookie while typing in the amount input field
        jQuery(generalDonationAmountEditor).on('input', function (event) {
            if (event.which === 13) {
                event.preventDefault();
            }

            // Mask the input field to not allow to type any non-numeric characters
            let inputValue = $(this).val();
            let numericValue = inputValue.replace(/[^0-9]/g, '');
            $(this).val(numericValue);

            generalOtherAmountElement.click();

            document.cookie = ckGeneralDonationAmount + '=' + sanitizeCandidatesPageAmount(numericValue) + cookiePath;
            updateRightPanel(rightPanelSummarySectionsList.general);
        });

        // Add event listener to each donation amount radio button
        radioButtonsGeneralDonationAmounts.forEach(button => {
            button.addEventListener('click', () => {
                console.log('clicked: ' + button.value);
                let selectedGeneralValue = button.value;
                if (button.id !== 'candidatesGeneralAmount_other') {
                    let formattedGeneralValue = new Intl.NumberFormat().format(selectedGeneralValue);
                    generalDonationAmountEditor.val(formattedGeneralValue);
                    generalDonationAmountValue = formattedGeneralValue;
                    // remove cookie value
                    document.cookie = ckGeneralDonationAmount + '=' + '' + cookiePath;
                    // apply new cookie value
                    document.cookie = ckGeneralDonationAmount + '=' + selectedGeneralValue + cookiePath;
                } else {
                    selectedGeneralValue = generalDonationAmountEditor.val();
                    generalDonationAmountValue = selectedGeneralValue;
                    document.cookie = ckGeneralDonationAmount + '=' + '' + cookiePath;
                    document.cookie = ckGeneralDonationAmount + '=' + selectedGeneralValue + cookiePath;
                }
                updateRightPanel(rightPanelSummarySectionsList.general);
            });
        });

        // GENERAL DONATION TYPE BUTTON HANDLER
        jQuery(document).ready(function ($) {
            generalDonationTypeElement = jQuery('#frm_field_447_container .frm_opt_container input');
            generalDonationTypeValue = getCookie(ckGeneralDonationType);
            // on-page load, check if cookie is set for donation type
            if (generalDonationTypeValue === 'One-Time') {
                generalDonationTypeElement.filter(':first').parent().click().prop('checked', true);
                updateRightPanel(rightPanelSummarySectionsList.general);
            }
            else if (generalDonationTypeValue === 'Monthly') {
                generalDonationTypeElement.filter(':last').parent().click().prop('checked', true);
                // If monthly donation type is selected, then set ckCreateDonorAccountYesNo cookie to 'Yes'
                document.cookie = ckCreateDonorAccountYesNo + '=Yes' + cookiePath;
                updateRightPanel(rightPanelSummarySectionsList.general);
            }
            else {
                document.cookie = ckGeneralDonationType + '=One-Time' + cookiePath;
                generalDonationTypeElement.filter(':first').parent().click().prop('checked', true);
                updateRightPanel(rightPanelSummarySectionsList.general);
            }
            generalDonationTypeElement.filter(':checked').parent().css({
                'background-color': '#143A62',
                'color': '#fff',
            });

            generalDonationTypeElement.on('click', function () {
                generalDonationTypeValue = jQuery(this).val();
                generalDonationTypeElement.parent().css({
                    'background-color': '#fff',
                    'color': '#143A62',
                });
                jQuery(this).parent().css({
                    'background-color': '#143A62',
                    'color': '#fff',
                });

                // If monthly donation type is selected, then set ckCreateDonorAccountYesNo cookie to 'Yes'
                if (generalDonationTypeValue.toLowerCase() === 'monthly') {
                    document.cookie = ckCreateDonorAccountYesNo + '=Yes' + cookiePath;
                } else {
                    document.cookie = ckCreateDonorAccountYesNo + '=No' + cookiePath;
                }

                document.cookie = ckGeneralDonationType + '=' + generalDonationTypeValue + cookiePath;
                updateRightPanel(rightPanelSummarySectionsList.general);
            });

        });

    });

    // LOCATION DONATION HANDLER
    jQuery(document).ready(function ($) {
        if (getCookie(ckLocationDonationAmount) > 0) {
            updateRightPanel(rightPanelSummarySectionsList.locations);
        }
    });

    // EXPANSION DONATION HANDLER
    jQuery(document).ready(function ($) {
        expansionDonationAmountEditor = $('#field_candidates_expansion_donation_amount');
        expansionOtherAmountElement = $('#candidatesExpansionAmount_other');
        const radioButtonsExpansionDonationAmounts = document.querySelectorAll('input[name="candidatesExpansionDonationAmount"]');

        // on page load unchecked all expansion radio buttons other than the selected one
        expansionDonationAmount = sanitizeCandidatesPageAmount(getCookie(ckExpansionDonationAmount));
        expansionDonationYesNo = getCookie(ckExpansionDonationYesNo);
        if (expansionDonationAmount > 0) {
            if (expansionDonationYesNo === 'Yes') {
                jQuery('#field_cv96t-0').click().prop('checked', true);
                // console.log('Exp Cookie Value: ' + expansionDonationAmount);
                for (let i = 0; i < radioButtonsExpansionDonationAmounts.length; i++) {
                    const buttonExpansion = radioButtonsExpansionDonationAmounts[i];

                    if (buttonExpansion.value === expansionDonationAmount && buttonExpansion.id.includes(expansionDonationAmount)) {
                        buttonExpansion.checked = true;
                        // console.log('In Button Value: ' + buttonExpansion.value);
                        // console.log('In Button ID: ' + buttonExpansion.id);
                        expansionDonationAmountEditor.val(new Intl.NumberFormat().format(buttonExpansion.value));
                        break; // Exit the loop
                    } else if (expansionDonationAmount !== 0 && buttonExpansion.id.includes(expansionOtherAmountElement.attr('id'))) {
                        // console.log('Out Button Value: ' + buttonExpansion.value);
                        // console.log('Out Button Amount: ' + expansionDonationAmountValue);
                        // console.log('Out Button ID: ' + buttonExpansion.id);
                        expansionDonationAmountEditor.attr('readonly', false);
                        expansionDonationAmountEditor.val(new Intl.NumberFormat().format(expansionDonationAmount));
                        expansionOtherAmountElement.click();
                    } else {
                        buttonExpansion.checked = false;
                    }
                }
                updateRightPanel(rightPanelSummarySectionsList.expansion);
            } else {
                expansionDonationYesNo = 'No';
                jQuery('#field_cv96t-1').click().prop('checked', true);
                document.cookie = ckExpansionDonationYesNo + '=' + expansionDonationYesNo + cookiePath;
                document.cookie = ckExpansionDonationAmount + '=' + '' + cookiePath;
                radioButtonsExpansionDonationAmounts.forEach(button => {
                    button.checked = false;
                    expansionDonationAmountEditor.val('');
                });
                updateRightPanel(rightPanelSummarySectionsList.expansion);
            }
        }
        else {
            expansionDonationAmount = '';
            document.cookie = ckExpansionDonationAmount + '=' + '' + cookiePath;
            document.cookie = ckExpansionDonationYesNo + '=' + 'No' + cookiePath;
            document.cookie = ckExpansionDonationType + '=' + 'One-Time' + cookiePath;
            expansionDonationAmountEditor.val(expansionDonationAmount);
            radioButtonsExpansionDonationAmounts.forEach(button => {
                button.checked = false;
            });
            updateRightPanel(rightPanelSummarySectionsList.expansion);
        }

        // Expansion donation Yes/No radio button change event handler
        jQuery('#field_cv96t-0, #field_cv96t-1').on('change', function () {
            expansionFunds = jQuery(this).val();
            document.cookie = ckExpansionDonationYesNo + '=' + expansionFunds + cookiePath;
            console.log('Expansion Funds Updated to [' + expansionFunds + ']');
            if (expansionFunds === 'No') {
                //setTimeout(function () {
                    jQuery('#remove_expansion_panel').trigger('click');
                    let cartItemKey = $('.elementor-menu-cart__product-remove .elementor_remove_from_cart_button[data-product_id=55946]').data('cart_item_key');

                    // AJAX request to remove the product from the cart
                    $.ajax({
                        type: 'POST',
                        url: wc_cart_fragments_params.wc_ajax_url.toString().replace('%%endpoint%%', 'remove_from_cart'),
                        data: {
                            action: 'remove_from_cart',
                            cart_item_key: cartItemKey
                        },
                        success: function (response) {
                            if (response && response.fragments) {
                                // Replace mini-cart fragments
                                // $.each(response.fragments, function (key, value) {
                                //     $(key).replaceWith(value);
                                // });

                                // Trigger event for other scripts to listen
                                //console.log('gg' + response.subtotal)
                                // let updatedSubtotal = response.subtotal;
                                // $(eleRightPanelSubTotalDonationsValue).text(updatedSubtotal);

                             //   $(document.body).trigger('wc_fragments_refreshed');

                            }
                        }
                    });

                    console.log('Expansion Donation Removed from Cart!');
               // }, 1000);
                if ((parseInt(generalDonationAmountValue) >= 0 && getCookie(ckGeneralDonationType) !== 'Monthly')
                    && (parseInt(locationDonationAmount) >= 0 && locationDonationType !== 'Monthly')) {
                    document.cookie = ckCreateDonorAccountYesNo + '=No' + cookiePath;
                }
                radioButtonsExpansionDonationAmounts.forEach(button => {
                    button.checked = false;
                });
            }
            updateRightPanel(rightPanelSummarySectionsList.expansion);
        });

        // If clicked on expansion 'Other Amount' input radio button
        expansionOtherAmountElement.change(function () {
            expansionDonationAmountEditor.attr('readonly', false).click().focus().attr('placeholder', 'Enter Amount');
            expansionDonationAmountEditor.val(sanitizeCandidatesPageAmount(expansionDonationAmountEditor.val()));
        });
        // if typing amount manually while in other amount, update right panel and cookie
        jQuery(expansionDonationAmountEditor).on('input', function (event) {
            if (event.which === 13) {
                event.preventDefault();
            }

            // Mask the input field to not allow to type any non-numeric characters
            let inputValue = $(this).val();
            let numericValue = inputValue.replace(/[^0-9]/g, '');
            $(this).val(numericValue);

            expansionOtherAmountElement.click();

            document.cookie = ckExpansionDonationAmount + '=' + sanitizeCandidatesPageAmount(numericValue) + cookiePath;
            updateRightPanel(rightPanelSummarySectionsList.expansion);
        });

        // Add event listener to each donation amount radio button
        radioButtonsExpansionDonationAmounts.forEach(button => {
            button.addEventListener('click', () => {
                let selectedExpansionValue = button.value;
                if (button.id !== 'candidatesExpansionAmount_other') {
                    let formattedExpansionValue = new Intl.NumberFormat().format(selectedExpansionValue);
                    expansionDonationAmountEditor.val(formattedExpansionValue);
                    expansionDonationAmountValue = formattedExpansionValue;
                    document.cookie = ckExpansionDonationAmount + '=' + selectedExpansionValue + cookiePath;
                } else {
                    selectedExpansionValue = expansionDonationAmountEditor.val();
                    expansionDonationAmountValue = selectedExpansionValue;
                    document.cookie = ckExpansionDonationAmount + '=' + selectedExpansionValue + cookiePath;
                }
                updateRightPanel(rightPanelSummarySectionsList.expansion);
            });
        });

        // EXPANSION DONATION TYPE BUTTON HANDLER
        jQuery(document).ready(function ($) {
            expansionDonationTypeElement = jQuery('#frm_field_448_container .frm_opt_container input');
            expansionDonationTypeValue = getCookie(ckExpansionDonationType);

            if (expansionDonationTypeValue) {
                if (expansionDonationTypeValue.toLowerCase() === 'one-time') {
                    expansionDonationTypeElement.filter(':first').parent().click().prop('checked', true);
                }
                else if (expansionDonationTypeValue.toLowerCase() === 'monthly') {
                    expansionDonationTypeElement.filter(':last').parent().click().prop('checked', true);
                    document.cookie = ckCreateDonorAccountYesNo + '=Yes' + cookiePath;
                }
                else {
                    document.cookie = ckExpansionDonationType + '=One-Time' + cookiePath;
                    expansionDonationTypeElement.filter(':first').parent().click().prop('checked', true);
                    console.log('ckExpansionDonationType cookie not found, setting to default: ' + expansionDonationTypeValue);
                }

                expansionDonationTypeElement.filter(':checked').parent().css({
                    'background-color': '#143A62',
                    'color': '#fff',
                });

                document.cookie = ckExpansionDonationType + '=' + expansionDonationTypeValue + cookiePath;
                updateRightPanel(rightPanelSummarySectionsList.expansion);
            }
            else {
                document.cookie = ckExpansionDonationType + '=One-Time' + cookiePath;
                expansionDonationTypeElement.filter(':first').parent().click().prop('checked', true);
                updateRightPanel(rightPanelSummarySectionsList.expansion);
                console.log('Expansion Donation Type was not set, defaulted to: ' + getCookie(ckExpansionDonationType));
            }

            // Style the selected donation type
            expansionDonationTypeElement.on('click', function () {
                expansionDonationTypeElement.parent().css({
                    'background-color': '#fff',
                    'color': '#143A62',
                });
                $(this).parent().css({
                    'background-color': '#143A62',
                    'color': '#fff',
                });
                expansionDonationTypeValue = expansionDonationTypeElement.filter(':checked').val();
                console.log('expansionDonationTypeValue: ' + expansionDonationTypeValue);
                if (expansionDonationTypeValue.toLowerCase() === 'monthly') {
                    document.cookie = ckCreateDonorAccountYesNo + '=Yes' + cookiePath;
                } else {
                    document.cookie = ckCreateDonorAccountYesNo + '=No' + cookiePath;
                }
                document.cookie = ckExpansionDonationType + '=' + expansionDonationTypeElement.filter(':checked').val() + cookiePath;
                updateRightPanel(rightPanelSummarySectionsList.expansion);
            });
        });

    });

    // REMOVE RIGHT PANEL ON BUTTON CLICK HANDLER
    jQuery(function ($) {
        if (cartContents.length > 0) {
            cartContents.forEach(item => {
               
                console.log('Product Name: ' + item.product_name);
                console.log('Product Key: ' + item.product_key);
            });
        }
        jQuery(document).on('click', '#remove_general_panel', function () {

            console.log('Removing General Panel...');
            radioButtonsGeneralDonationAmounts =
                document.querySelectorAll('input[name="frm_field_410_container"]');
            radioButtonsGeneralDonationAmounts.forEach(button => {
                button.checked = false;
            });
            $('#field_3nlpf-1').trigger('click');
            generalDonationAmountValue = '';
            generalDonationYesNo = 'No';
            document.cookie = ckGeneralDonationAmount + '=' + generalDonationAmountValue + cookiePath;
            document.cookie = ckGeneralDonationYesNo + '=' + generalDonationYesNo + cookiePath;
            document.cookie = ckGeneralDonationType + '=' + 'One-Time' + cookiePath;
            // check if the general donation amount is in cartcontents
            if (cartContents.length > 0) {
                cartContents.forEach(item => {
                    if (item.product_name === "General Fund Donation") {
                        console.log('General Fund Donation is in cart. Removing it...');
                        console.log('Product Key: ' + item.product_key);
                        jQuery.ajax({
                            url: ajaxurl,
                            type: 'POST',
                            data: {
                                action: 'remove_custom_cart_item',
                                product_key: item.product_key,
                                security: nonce
                            },
                            success: function (response) {
                                // console.log(JSON.stringify(response));
                                console.log('Item [' + item.product_key + '] successfully removed from cart.');
                                jQuery(document.body).trigger('wc_fragment_refresh');
                                jQuery(document.body).trigger('update_checkout');
                            }
                        });
                    }
                });
            }
            let cartItemKey = $('.elementor-menu-cart__product-remove .elementor_remove_from_cart_button[data-product_id=23603]').data('cart_item_key');
            console.log('BANE' + cartItemKey);
            // AJAX request to remove the product from the cart
            $.ajax({
                type: 'POST',
                url: wc_cart_fragments_params.wc_ajax_url.toString().replace('%%endpoint%%', 'remove_from_cart'),
                data: {
                    action: 'remove_from_cart',
                    cart_item_key: cartItemKey
                },
                success: function (response) {
                    if (response && response.fragments) {
                        // Replace mini-cart fragments
                        $.each(response.fragments, function (key, value) {
                            $(key).replaceWith(value);
                        });

                        // Trigger event for other scripts to listen
                        let updatedSubtotal = response.subtotal;
                        $(eleRightPanelSubTotalDonationsValue).text(updatedSubtotal);

                        $(document.body).trigger('wc_fragments_refreshed');

                    }
                }
            });
            cartItemKey = $('.elementor-menu-cart__product-remove .elementor_remove_from_cart_button[data-product_id=55946]').data('cart_item_key');

            // AJAX request to remove the product from the cart
            $.ajax({
                type: 'POST',
                url: wc_cart_fragments_params.wc_ajax_url.toString().replace('%%endpoint%%', 'remove_from_cart'),
                data: {
                    action: 'remove_from_cart',
                    cart_item_key: cartItemKey
                },
                success: function (response) {
                    if (response && response.fragments) {
                        // Replace mini-cart fragments
                        $.each(response.fragments, function (key, value) {
                            $(key).replaceWith(value);
                        });

                        // Trigger event for other scripts to listen
                        let updatedSubtotal = response.subtotal;
                        $(eleRightPanelSubTotalDonationsValue).text(updatedSubtotal);

                        $(document.body).trigger('wc_fragments_refreshed');

                    }
                }
            });
            updateRightPanel(rightPanelSummarySectionsList.general);
        });
        jQuery(document).on('click', '#remove_expansion_panel', function () {
            console.log('Removing Expansion Panel...');
            radioButtonsExpansionDonationAmounts =
                document.querySelectorAll('input[name="frm_field_409_container"]');
            radioButtonsExpansionDonationAmounts.forEach(button => {
                button.checked = false;
            });
            $('#field_cv96t-1').trigger('click');
            expansionDonationAmountValue = '';
            expansionDonationYesNo = 'No';
            document.cookie = ckExpansionDonationAmount + '=' + expansionDonationAmountValue + cookiePath;
            document.cookie = ckExpansionDonationYesNo + '=' + expansionDonationYesNo + cookiePath;
            document.cookie = ckExpansionDonationType + '=' + 'One-Time' + cookiePath;
            // check if the general donation amount is in cart contents
            if (cartContents.length > 0) {
                cartContents.forEach(item => {
                    if (item.product_name === "Expansion Fund Donation") {
                        console.log('Removing Product Key: ' + item.product_key);
                        jQuery.ajax({
                            url: ajaxurl,
                            type: 'POST',
                            data: {
                                action: 'remove_custom_cart_item',
                                product_key: item.product_key,
                                security: nonce
                            },
                            success: function (response) {
                                // console.log(JSON.stringify(response));
                                console.log('Item [' + item.product_key + '] successfully removed from cart.');
                                jQuery(document.body).trigger('wc_fragment_refresh');
                                jQuery(document.body).trigger('update_checkout');
                            }
                        });
                    }
                });
            }
            updateRightPanel(rightPanelSummarySectionsList.expansion);
        });


        jQuery(document).on('click', '#remove_location_panel', function () {
            console.log('Removing Location Panel...');
            // radioButtonsLocationDonationAmounts =
            //     document.querySelectorAll('input[name="locationPageExpansionDonationAmount"]');
            // radioButtonsLocationDonationAmounts.forEach(button => {
            //     button.checked = false;
            // });
            // $('#field_fp9k8-1').trigger('click');
            locationDonationAmountValue = '';
            locationDonationYesNo = 'No';
            document.cookie = ckLocationDonationAmount + '=' + locationDonationAmountValue + cookiePath;
            document.cookie = ckLocationDonationYesNo + '=' + locationDonationYesNo + cookiePath;
            document.cookie = ckLocationDonationType + '=' + 'One-Time' + cookiePath;
            // check if the general donation amount is in cart contents
            if (cartContents.length > 0) {
                cartContents.forEach(item => {
                    if (item.product_name === "Specific Location Fund Donation") {
                        console.log('Removing Product Key: ' + item.product_key);
                        jQuery.ajax({
                            url: ajaxurl,
                            type: 'POST',
                            data: {
                                action: 'remove_custom_cart_item',
                                product_key: item.product_key,
                                security: nonce
                            },
                            success: function (response) {
                                // console.log(JSON.stringify(response));
                                console.log('Item [' + item.product_key + '] successfully removed from cart.');
                                jQuery(document.body).trigger('wc_fragment_refresh');
                                jQuery(document.body).trigger('update_checkout');
                            }
                        });
                    }
                });
            }
            updateRightPanel(rightPanelSummarySectionsList.locations);
        });
    });


    // CREATE DONOR ACCOUNT RADIO BUTTON HANDLER
    // check if user is logged in or not userRole = 'subscriber'
    // console.log('User Role: ' + userRole);
    // console.log('User Display Name: ' + userDisplayName);
    if (userRole !== '') {
        let userDashboard = '';
        switch (userRole) {
            case 'subscription':
                userDashboard = 'dashboard-donor';
                break;
            case 'candidate':
                userDashboard = 'dashboard-candidate';
                break;
            case 'customer':
                userDashboard = 'dashboard-advocate';
                break;
            case 'medical_provider':
                userDashboard = 'dashboard-physician';
                break;
            default:
                userDashboard = 'dashboard-donor';
                break;
        }

        jQuery('#frm_field_156_container').hide();
        jQuery('#frm_field_158_container').html(
            '<p><span class="reg-ref">Already logged in as: <a href="/' + userDashboard + '/" target="_blank">' + userDisplayName + '</a></span></p>'
        );
    } else {
        if (getCookie(ckCreateDonorAccountYesNo)) {
            if (getCookie(ckCreateDonorAccountYesNo) === 'Yes') {
                createDonorAccountYesNo = 'Yes';
                jQuery('#field_n68u0-0').prop('checked', true);
                jQuery('#field_n68u0-1').prop('disabled', true);
                let createDonorAccountElementOnCheckout = jQuery('#createaccount');
                // Check if the element exists in WooCommerce checkout form
                if (createDonorAccountElementOnCheckout) {
                    // Check if the checkbox is already checked or not
                    if (createDonorAccountElementOnCheckout.is(':checked')) {
                        // Do nothing. Checkbox is already checked
                        console.log('Create Donor Account Checkbox is already checked');
                    } else {
                        // Check the checkbox
                        createDonorAccountElementOnCheckout.click();
                        jQuery(document.body).trigger('update_checkout');
                        console.log('Create Donor Account Checkbox is now checked');
                    }
                }
            } else {
                createDonorAccountYesNo = 'No';
                jQuery('#field_n68u0-1').prop('disabled', false);
                jQuery('#field_n68u0-1').prop('checked', true);
                jQuery('.woocommerce-account-fields').hide();
            }
            console.log('CreatDonorAccount Cookie Exists. Value: ' + getCookie(ckCreateDonorAccountYesNo));
        } else {
            specificDonorAccountYesNo = 'No';
            jQuery('#field_dx2vs-1').prop('checked', true);
            document.cookie = ckCreateDonorAccountYesNo + '=' + specificDonorAccountYesNo + cookiePath;
            console.log('CreatDonorAccount Cookie Does Not Exist. Setting Default Value to [' + specificDonorAccountYesNo + ']');
        }
        jQuery(document).on('change', '#field_n68u0-0, #field_n68u0-1, #createaccount', function () {
            createDonorAccountYesNo = jQuery(this).val();
            document.cookie = ckCreateDonorAccountYesNo + '=' + createDonorAccountYesNo + cookiePath;
            console.log('Create Donor Account: [' + createDonorAccountYesNo + ']');
        });
    }

    // REFERRAL YES/NO RADIO BUTTON HANDLER
    if (getCookie(ckDonationReferralYesNo)) {
        if (getCookie(ckDonationReferralYesNo) === 'Yes') {
            donationReferralYesNo = 'Yes';
            jQuery('#field_dx2vs-0').trigger('click').prop('checked', true);
            if (getCookie(ckDonationReferrerDetail) !== '') {
                donationReferrerDetail = getCookie(ckDonationReferrerDetail);
                jQuery('#field_o8n9e').val(donationReferrerDetail);
            }
        } else {
            donationReferralYesNo = 'No';
            jQuery('#field_dx2vs-1').trigger('click').prop('checked', true);
        }
        console.log('Referral Cookie Exists. Value: [' + getCookie(ckDonationReferralYesNo)
            + '] Referrer Detail: [' + donationReferrerDetail + ']');
    } else {
        donationReferralYesNo = 'No';
        donationReferrerDetail = '';
        jQuery('#field_dx2vs-1').trigger('click').prop('checked', true);
        document.cookie = ckDonationReferralYesNo + '=No' + cookiePath;
        console.log('Referral Cookie Does Not Exist. Setting Default Value to [' + donationReferralYesNo + ']');
    }
    jQuery(document).on('change', '#field_dx2vs-0, #field_dx2vs-1', function () {
        donationReferralYesNo = jQuery(this).val();
        jQuery(this).trigger('click').prop('checked', true);
        if (jQuery('#field_dx2vs-0').is(':checked')) {
            donationReferrerDetail = getCookie(ckDonationReferrerDetail);
            jQuery('#field_o8n9e').val(getCookie(ckDonationReferrerDetail));
        } else {
            donationReferrerDetail = '';
            jQuery('#field_o8n9e').val('');
        }
        document.cookie = ckDonationReferralYesNo + '=' + donationReferralYesNo + cookiePath;
        console.log('Referral Yes/No: [' + donationReferralYesNo + '] Referrer Detail: [' + donationReferrerDetail + ']');
    });
    $('#field_o8n9e').on('keyup', function (event) {
        if (event.which === 13) {
            event.preventDefault();
        }
        document.cookie = ckDonationReferrerDetail + '=' + jQuery(this).val() + cookiePath;
        donationReferrerDetail = jQuery(this).val();
    });

    // PAGE-1: HIDE (OPTIONAL) ON MOBILE
    jQuery(document).ready(function($) {
        function checkResolution() {
            const screenWidth = window.innerWidth || document.documentElement.clientWidth || document.body.clientWidth;
    
            if (screenWidth <= 600) {
                const firstElement = $('#field_cv96t_label span');
                const secondElement = $('#field_3nlpf_label span');
    
                if (firstElement.length && secondElement.length) {
                    firstElement.first().text('Donate to the General Fund?');
                    firstElement.css('white-space', 'nowrap');
                    secondElement.first().text('Donate to the Expansion Fund?');
                    secondElement.css('white-space', 'nowrap');
                }
            }
        }
    
        checkResolution();
    
        $(window).resize(checkResolution);
    });

    // PAGE-1: 'NEXT' BUTTON CLICK HANDLER
    jQuery(document).on('click', '#form_specific-candidate-donation .frm_page_num_1 button.frm_button_submit', function (event) {
        event.preventDefault();

        // if general donation yes/no is no and amount is zero or not set then show error and return
        generalDonationAmountValue = getCookie(ckGeneralDonationAmount);
        if (generalDonationYesNo === 'Yes' && (generalDonationAmountValue <= 0 || generalDonationAmountValue === '')) {
            jQuery('#field_candidates_general_donation_amount_label').css({ 'color': 'red', 'font-weight': 'bold' });
            // Scroll the page to the target element
            let targetPosition = jQuery('#field_candidates_general_donation_amount_label').offset().top;
            targetPosition -= 300;
            jQuery('html, body').animate({
                scrollTop: targetPosition
            }, 500);
            return console.log('REQUIRED: General Donation Amount is not set.');
        }
        else {
            jQuery('#field_candidates_general_donation_amount_label').css({ 'color': '#000', 'font-weight': 'normal' });
        }

        // if expansion donation yes/no is no and amount is zero or not set then show error and return
        expansionDonationAmountValue = getCookie(ckExpansionDonationAmount);
        if (expansionDonationYesNo === 'Yes' && (expansionDonationAmountValue <= 0 || expansionDonationAmountValue === '')) {
            jQuery('#field_candidates_expansion_donation_amount_label').css({ 'color': 'red', 'font-weight': 'bold' });
            // Scroll the page to the target element
            let targetPosition = jQuery('#field_candidates_expansion_donation_amount_label').offset().top;
            targetPosition -= 300;
            jQuery('html, body').animate({
                scrollTop: targetPosition
            }, 500);
            return console.log('REQUIRED: Expansion Donation Amount is not set.');
        }
        else {
            jQuery('#field_candidates_expansion_donation_amount_label').css({ 'color': '#000', 'font-weight': 'normal' });
        }

        if (generalDonationTypeValue === 'Monthly' || expansionDonationTypeValue === 'Monthly' || locationDonationType === 'Monthly') {
            document.cookie = ckCreateDonorAccountYesNo + '=Yes' + cookiePath;
        }
        else {
            document.cookie = ckCreateDonorAccountYesNo + '=No' + cookiePath;
        }

        let page1Data = {
            donate_anonymously: donateAnonymously,
            generalDonationAmount: generalDonationAmountValue,
            expansionDonationAmount: expansionDonationAmount,
            locationDonationAmount: locationDonationAmount,
            locationZipCodeNumber: locationDonationZip,
        };
        let jsonPage1Data = JSON.stringify(page1Data);
        // console.log('Page1Data:\n\t' + jsonPage1Data);

        $.ajax({
            type: 'POST',
            url: ajaxurl,
            data: {
                action: 'specific_checkout_action',
                specific_form_page_1_data: jsonPage1Data,
                security: nonce,
            },
            beforeSend: function () {
                overlayToggle();
            },
            success: function (response) {
                overlayToggle();
                if (response.success) {
                    // console.log('Success_P1! ' + JSON.stringify(response));
                    $(document.body).trigger('update_checkout');
                    $('#form_specific-candidate-donation .frm_page_num_1 button.frm_button_submit').trigger('submit');

                    
                } else {
                    console.log('Failed_P1: \n' + JSON.stringify(response));
                }
                hideAdditionalPaymentMethod();
            },
            error: function (error) {
                // Handle any errors, e.g., display an error message
                console.log('Error_P1: ' + JSON.stringify(error));
                overlayToggle();
            }
        });
    });

    // PAGE-2: 'NEXT' BUTTON CLICK HANDLER
    jQuery(document).on('click', '#form_specific-candidate-donation .frm_page_num_2 button.frm_button_submit', function (event) {
        event.preventDefault();
        let existingErrorMessage = $('#frm_error_field_general_referrer_detail');
        existingErrorMessage.hide();

        let page2Data = {
            createDonorAccountYesNo: createDonorAccountYesNo,
            specificReferralYesNo: donationReferralYesNo,
            specificReferrerDetail: donationReferrerDetail,
        };
        let jsonPage2Data = JSON.stringify(page2Data);
        console.log('Page2Data: ' + jsonPage2Data);

        $.ajax({
            type: 'POST',
            url: ajaxurl,
            data: {
                action: 'specific_checkout_action',
                specific_form_page_2_data: jsonPage2Data,
                security: nonce,
            },
            beforeSend: function () {
                overlayToggle();
            },
            success: function (response) {
                overlayToggle();
                if (response.success) {
                    if (!window.location.href.includes('ref')
                        && response.data.specificReferralYesNo !== ''
                        && response.data.specificReferralYesNo !== null) {
                        // console.log('SuccessPP_P2-1! ' + JSON.stringify(response));
                        window.history.replaceState(null, null, '?ref='
                            + response.data.specificReferrerDetail);
                    }
                    else if (window.location.href.includes('ref')
                        && response.data.specificReferralYesNo !== ''
                        && response.data.specificReferralYesNo !== null) {
                        // console.log('SuccessFF_P2-2! ' + JSON.stringify(response));
                        window.history.replaceState(null, null, window.location.pathname + window.location.hash);
                    }
                    else {
                        window.history.replaceState(null, null, '');
                    }
                    // Go to next page of the form
                    $('#form_specific-candidate-donation .frm_page_num_2 button.frm_button_submit').submit();

                    hideAdditionalPaymentMethod();
                }
                else {
                    console.log('Failed_P2: \n' + JSON.stringify(response));
                    if (existingErrorMessage.length > 0) {
                        existingErrorMessage.remove();
                    }
                    let errorMessage = $(
                        '<div class="frm_error" role="alert" id="frm_error_field_general_referrer_detail" ' +
                        'style="display:inline;">' + response.data.message + '</div>'
                    );
                    errorMessage.insertBefore($('#field_o8n9e'), $('#field_donation_referrer_detail_container'));
                    console.log('Failed! ' + response.data.message || 'An error occurred.');
                }
            },
            error: function (error) {
                // Handle any errors, e.g., display an error message
                console.log('Error_P2: ' + JSON.stringify(error));
                overlayToggle();
            }
        });
    });



    // SPECIFIC 'DONATE NOW SECURELY' BUTTON CLICK HANDLER
    jQuery(document).on('click', '#form_specific-candidate-donation button.frm_button_submit.frm_final_submit', function (event) {
        event.preventDefault();

        console.log('Specific [Donate Now Securely] Button Clicked');
        // clear error message fields
        let errorMessageField = jQuery('#field_vwxl_label span');
        let errorMessageContainer = jQuery('#frm_field_177_container');
        if (jQuery('#field_vwxl-0').is(':checked')) {
            errorMessageField.text('');
            errorMessageContainer.css({ 'border': '0', 'padding': '0' });
        } else {
            errorMessageField.text('This field cannot be blank.').css('color', 'red');
            errorMessageContainer.css({ 'border': '1px solid red', 'padding': '10px' });
            return;
        }

        let page3Data = {
            donate_anonymously: donateAnonymously,
            display_name: jQuery('#billing_first_name').val(),
        };
        let jsonPage3Data = JSON.stringify(page3Data);

        $.ajax({
            type: 'POST',
            url: ajaxurl,
            data: {
                action: 'specific_checkout_action',
                specific_form_page_3_data: jsonPage3Data,
                security: nonce,
            },
            beforeSend: function () {
                // overlayToggle();
            },
            success: function (response) {
                // overlayToggle();
                if (response.success) {
                     console.log('Success_P3! ' + JSON.stringify(response));
                    jQuery('#place_order').click();
                    // scroll to overlay
                    let targetPosition = jQuery('.blockUI.blockOverlay').offset().top;
                    targetPosition -= 50;
                    jQuery('html, body').animate({
                        scrollTop: targetPosition
                    }, 500);


                    
                } else {
                    console.log('Failed_P3: \n' + JSON.stringify(response));
                }
                hideAdditionalPaymentMethod();
            },
            error: function (error) {
                // Handle any errors, e.g., display an error message
                console.log('Error_P3: ' + JSON.stringify(error));
                // overlayToggle();
            }
        });
    });



});

///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// PAYMENT METHODS & TERMS AND CONDITIONS CHECKBOX HANDLER
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
jQuery(document).ready(function () {
    let ppcButtonWrapper = '.ppc-button-wrapper';
    let ppcButtonWrapperOverlay = '.paypal_button_overlay';
    let paypalRadioButton = '#payment_method_ppcp-gateway';
    let stripeRadioButton = '#payment_method_stripe';
    let donateButton = '.frm_button_submit.frm_final_submit';
    let paymentMethodHeading = '#order_review h3#order_review_heading';
    let termsElement = '#frm_field_177_container';
    let termsCheckBox = '#field_vwxl-0';

    jQuery(ppcButtonWrapper).prepend('<div class="paypal_button_overlay"></div>');

    if (jQuery(paymentMethodHeading).length === 0 && getCookie(ckTotalDonations) > 0) {
        jQuery('#order_review').prepend(
            '<h3 id="order_review_heading" class="frm_pos_top frm_section_spacing" ' +
            'style="display: flex !important;border-bottom:0 !important;margin-bottom:0 !important;padding-bottom:0;">Payment Method</h3>')
    }
    else {
        jQuery(paymentMethodHeading).hide();
    }

    function removeTermsRedBorder() {
        // console.log('Removing red border from Terms...');
        jQuery(termsElement).removeAttr('style');
        jQuery(termsElement).css({
            'border': 'unset',
            'padding': 'unset',
            'box-shadow': 'unset',
            '-webkit-box-shadow': 'unset',
            '-moz-box-shadow': 'unset'
        });
        // console.log('Terms red border removed.');
    }

    jQuery(document).on('change', stripeRadioButton, function (event) {
        event.preventDefault();
        removeTermsRedBorder();
        jQuery(donateButton).show();
        jQuery(ppcButtonWrapper).attr('style', 'display: none !important;');
        jQuery(ppcButtonWrapperOverlay).attr('style', 'display: none !important;');
        // console.log('Stripe is selected. Hiding Overlay and Paypal button...');
    });

    jQuery(document).on('change', paypalRadioButton, function (event) {
        event.preventDefault();
        removeTermsRedBorder();
        jQuery(donateButton).hide();
        jQuery(ppcButtonWrapper).show();
        if (!jQuery(termsCheckBox).is(':checked')) {
            jQuery(ppcButtonWrapperOverlay).show();
        }
        // console.log('Paypal is selected. Showing Paypal button...');
    });

    jQuery(document).on('click', ppcButtonWrapperOverlay, function (event) {
        // console.log('Overlay clicked!!!');
        if (!jQuery(termsCheckBox).is(':checked')) {
            jQuery(termsElement).css({
                'border': '2px solid red',
                'padding': '10px',
                'box-shadow': '0px 0px 5px 2px rgba(255,0,0,0.75)',
                '-webkit-box-shadow': '0px 0px 5px 2px rgba(255,0,0,0.75)',
                '-moz-box-shadow': '0px 0px 5px 2px rgba(255,0,0,0.75)'
            });

            // scroll to paypal_button_overlay
            let targetPosition = jQuery(ppcButtonWrapperOverlay).offset().top;
            targetPosition -= 50;
            jQuery('html, body').animate({
                scrollTop: targetPosition
            }, 500);
            // console.log('Overlay clicked. Showing Paypal button...');
        }
    });

    jQuery(document).on('click', termsCheckBox, function () {
        removeTermsRedBorder();
        if (jQuery(this).is(':checked') && jQuery(paypalRadioButton).is(':checked')) {
            jQuery(ppcButtonWrapperOverlay).hide();
        } else {
            jQuery(ppcButtonWrapperOverlay).show();
        }
    });

    if (jQuery(termsCheckBox).is(':checked') && jQuery(paypalRadioButton).is(':checked')) {
        jQuery(donateButton).hide();
        // console.log('Donate Now Button Hidden.')

        jQuery(ppcButtonWrapperOverlay).hide();
        // console.log('>>> Terms are selected. Showing Paypal button...');
    }
    else if (!jQuery(termsCheckBox).is(':checked') && jQuery(paypalRadioButton).is(':checked')) {
        jQuery(donateButton).hide();
        // console.log('Donate Now Button Hidden.')

        jQuery(ppcButtonWrapperOverlay).show();
        // console.log('>>> Terms are not selected. Showing Overlay...');
    }
    else {
        jQuery(donateButton).show();
        // console.log('Donate Now Button Shown.')

        jQuery(ppcButtonWrapper).attr('style', 'display: none !important;');
        jQuery(ppcButtonWrapperOverlay).hide();
        // console.log('>>> Paypal is not selected. Hiding Overlay...');
    }

    hideAdditionalPaymentMethod();

});
