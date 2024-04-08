"use strict";
// GLOBAL VARIABLES
let nonce = obj_general.nonce;
let ajaxurl = obj_general.ajaxurl;
let userRole = obj_general.user_role;
let userDisplayName = obj_general.user_display_name;
let cartContents = obj_general.cart_contents;
let cartTotal = obj_general.cart_total;
let cartSubTotal = obj_general.cart_subtotal;
let is_general_funds_tab_selected = obj_general.is_general_funds_tab_selected;
let donateAnonymously = '';

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
let totalCartPrice = 0;
let donationTypeElement, donationTypeValue, selectedDonationTypeValue, totalDonationAmountValue, generalDonationYesNo;
let generalDonationAmountElement, generalOtherAmountElement, generalDonationAmountValue,
    generalDonationTypeValue, radioButtonsGeneralDonationAmounts;
let specificDonationAmountValue, specificDonationType;
let voucherAmount, voucherCode;
let expansionDonationAmountValue, expansionDonationAmountElement, expansionDonationTypeElement, expansionDonationTypeValue,
    expansionOtherAmountElement, expansionDonationYesNo, radioButtonsExpansionDonationAmounts;
let locationDonationYesNo, locationDonationAmountValue, radioButtonsLocationDonationAmounts, locationZipCodeValue,
    locationDonationTypeValue, locationDonationZipCodeNumber;
let createDonorAccountYesNo, donationReferralYesNo, donationReferrerDetail = '';
let cookiePath = '; path=/;',
    ckTotalDonations = 'ckTotalDonations',
    ckSubTotalDonations = 'ckSubTotalDonations',
    ckVoucherAmount = 'ckVoucherAmount',
    ckVoucherCode = 'ckVoucherCode',
    ckGeneralDonationAmount = 'ckGeneralDonationAmount',
    ckGeneralDonationType = 'ckGeneralDonationType',
    ckGeneralDonationYesNo = 'ckGeneralDonationYesNo',
    ckSpecificDonationTotalAmount = 'ckSpecificDonationTotalAmount',
    ckSpecificDonationType = 'ckSpecificDonationType',
    ckExpansionDonationYesNo = 'ckExpansionDonationYesNo',
    ckExpansionDonationAmount = 'ckExpansionDonationAmount',
    ckExpansionDonationType = 'ckExpansionDonationType',
    ckLocationDonationAmount = 'ckLocationDonationAmount',
    ckLocationDonationType = 'ckLocationDonationType',
    ckLocationZipCodeNumber = 'ckLocationZipCodeNumber',
    ckLocationDonationYesNo = 'ckLocationDonationYesNo',
    ckCreateDonorAccountYesNo = 'ckCreateDonorAccountYesNo',
    ckDonationReferralYesNo = 'ckDonationReferralYesNo',
    ckDonationReferrerDetail = 'ckDonationReferrerDetail',
    ckDonateAnonymously = 'ckDonateAnonymously';

const rightPanelSummarySectionsList = {
    general: 'GeneralFunds',
    candidates: 'Candidates',
    locations: 'Locations',
    expansion: 'Expansion',
    voucher: 'Voucher'
};

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

function updateRemoveButtons(){
    
    if(jQuery('[data-product_id="23603"]').length != 0){
     jQuery('[data-product_id="23603"]').on('click', () => {
    jQuery('#remove_general_panel').trigger('click');

    jQuery('#frm_field_300_container input[type="radio"]').each(function(){
        jQuery(this).prop('checked', false);
    })

    try {
         let updatedSubtotal = response.subtotal;
    jQuery(eleRightPanelSubTotalDonationsValue).text(updatedSubtotal);
    } catch (error) {
        return;
    }
    
    })
    }
    if(jQuery('[data-product_id="55946"]').length != 0){
    jQuery('[data-product_id="55946"]').on('click', () => {
        //jQuery('#remove_expansion_panel').trigger('click');
        jQuery('#field_vg33t-1').trigger('click');

        try{
            let updatedSubtotal = response.subtotal;
            jQuery(eleRightPanelSubTotalDonationsValue).text(updatedSubtotal);
        } catch {
            return
        }
        
        
    }) 
    }
       

 
}

function hideAdditionalPaymentMethod(){
    let total = +getCookie(ckSubTotalDonations) - +getCookie(ckVoucherAmount);
   // console.log('YEPPP!!! ' + total);

    if(total <= 0 && document.querySelector('#other_donation_options')){
        document.querySelector('#order_review').setAttribute("hidden", true);
        document.querySelector('form [name="checkout"]').parentElement.parentElement.previousElementSibling.style.display="none";
        document.querySelector('form [name="checkout"]').setAttribute("hidden", true);
    }else {
        try {
            document.querySelector('#order_review').removeAttribute("hidden");
            document.querySelector('form [name="checkout"]').parentElement.parentElement.previousElementSibling.style.display="block";
            document.querySelector('form [name="checkout"]').removeAttribute("hidden");
        } catch (error) {
            
        }
        
    }
}

// ALL TABS CLICK HANDLER
jQuery(document).ready(function ($) {

updateRemoveButtons();    


    let currentTabId = 'e-n-tabs-title-1522';
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
            }
            else if (tabId === 'e-n-tabs-title-1522') {
                jQuery('#e-n-tabs-title-1522').attr('aria-selected', 'true').attr('tabindex', '0');
                jQuery('#e-n-tab-content-1522').show();
                console.log('[TAB]General');
            }
            else if (tabId === 'e-n-tabs-title-1523') {
                jQuery('#e-n-tabs-title-1523').attr('aria-selected', 'true').attr('tabindex', '0');
                jQuery('#e-n-tab-content-1523').show();
                console.log('[TAB]Location');
            }
            else if (tabId === 'e-n-tabs-title-1524') {
                jQuery('#e-n-tabs-title-1524').attr('aria-selected', 'true').attr('tabindex', '0');
                jQuery('#e-n-tab-content-1524').show();
                console.log('[TAB]Expansion');
            }
        }, 700);

        // OVERFLOW CONTAINER TOGGLE
        setTimeout(function () {
            overlayToggle();
        },1000);

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
        jQuery('#e-n-tab-content-1521, #e-n-tab-content-1523, #e-n-tab-content-1524')
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
    if (is_general_funds_tab_selected) {
        handleTabClick(currentTabId);
    }
});


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

// REMOVE AMOUNT FORMATTING AND RETURN THE AMOUNT WITHOUT COMMAS AND DOLLAR SIGN
function sanitizeGeneralPageAmount(amount) {
    let _amount = 0;
    if (amount === 0 || amount === null || amount === '' || amount === 'undefined' || isNaN(amount)) {
        return 0;
    }
    else if (typeof amount === 'string' && amount !== 0) {
        _amount = amount.replace(/,/g, '');
        _amount = _amount.replace('$', '');
        return _amount;
    }
}




// UPDATE TOTAL DONATION AMOUNT
function updateRightPanelTotalDonations() {
    const sanitizedGeneralAmount = sanitizeGeneralPageAmount(String(generalDonationAmountValue));
    const sanitizedSpecificAmount = sanitizeGeneralPageAmount(String(specificDonationAmountValue));
    const sanitizedLocationAmount = sanitizeGeneralPageAmount(String(locationDonationAmountValue));
    const sanitizedExpansionAmount = sanitizeGeneralPageAmount(String(expansionDonationAmountValue));

    // const totalDonations = cartTotal.replace('$', '');
    let totalGeneralPageDonations =
        (parseInt(sanitizedGeneralAmount) || 0) +
        (parseInt(sanitizedSpecificAmount) || 0) +
        (parseInt(sanitizedLocationAmount) || 0) +
        (parseInt(sanitizedExpansionAmount) || 0) -
        (parseInt(voucherAmount) || 0);

    let subTotalGeneralPageDonations = cartSubTotal.replace('$', '');

    if ((!isNaN(totalGeneralPageDonations)) || voucherAmount > 0 || voucherAmount !== '' || voucherAmount !== 0) {
        if (totalGeneralPageDonations < 0) {
            totalGeneralPageDonations = 0;
        }
        if (totalGeneralPageDonations === 0 && (voucherAmount === 0 || voucherAmount === '')) {
            rightPanelTotalDonationsElement.hide();
            console.log('Total Donations[General Tab] is zero without voucher!');
            return;
        }

        eleRightPanelTotalDonationsValue.text('$' + new Intl.NumberFormat().format(totalGeneralPageDonations));
        if (cartSubTotal <= 0) {
            subTotalGeneralPageDonations = totalGeneralPageDonations;
        }
        document.cookie = ckTotalDonations + '=' + totalGeneralPageDonations + cookiePath;
        rightPanelTotalDonationsElement.show();
        
        eleRightPanelSubTotalDonationsValue.text('$' + subTotalGeneralPageDonations);
        //eleRightPanelSubTotalDonationsValue.text('$' + new Intl.NumberFormat().format(subTotalGeneralPageDonations));
        document.cookie = ckSubTotalDonations + '=' + subTotalGeneralPageDonations + cookiePath;
        // console.log('subTotal Donations[General Tab]:' + subTotalGeneralPageDonations);
        eleRightPanelSubTotalDonationsValue.show();
        
        //jQuery('.widget_shopping_cart_content').append('<button type="button" id="clear_the_cart_mini">Clear Cart</button>');
        
        
        jQuery(document.body).trigger('wc_fragments_refreshed');
        jQuery(document.body).trigger('update_checkout');

        // console.log('Total Donations:',
        //     '\n\t$specific[' + parseInt(sanitizedSpecificAmount) + ']' +
        //     '\t$general[' + parseInt(sanitizedGeneralAmount) + ']' +
        //     '\n\t$location[' + parseInt(sanitizedLocationAmount) + ']' +
        //     '\t$expansion[' + parseInt(sanitizedExpansionAmount) + ']' +
        //     '\n\nupdateRightPanelCookies' +
        //     '\n\tspecificAmount: [' + sanitizedSpecificAmount + ']\tType: [' + specificDonationType + ']' +
        //     '\n\tgeneralAmount: [' + sanitizedGeneralAmount + ']\tType: [' + generalDonationTypeValue + ']' +
        //     '\n\tlocationAmount: [' + sanitizedLocationAmount + ']\tType: [' + locationDonationTypeValue + ']' +
        //     '\n\texpansionAmount: [' + sanitizedExpansionAmount + ']\tType: [' + expansionDonationTypeValue + ']' +
        //     '\n\tvoucherAmount: [' + voucherAmount + ']' +
        //     '\n\ttotalLocationPageDonations: [' + totalGeneralPageDonations + ']'
        //     '\n\tsubTotalLocationPageDonations: [' + subTotalGeneralPageDonations + ']'
        // );
    } else {
        rightPanelTotalDonationsElement.hide();
        console.log('Total Donations[General Tab]:' + totalGeneralPageDonations);
    }
}

// UPDATE RIGHT PANEL
function updateRightPanel(sectionName) {
    let page1Data = {
        generalProductID: 23603,
        expansionProductID: 55946,
        locationProductID: 56180,
        donate_anonymously: donateAnonymously,
        generalDonationAmount: generalDonationAmountValue,
        expansionDonationAmount: expansionDonationAmountValue,
        locationZipCodeNumber: getCookie(ckLocationZipCodeNumber),
        locationDonationAmount: getCookie(ckLocationDonationAmount),
        totalDonationAmount: totalDonationAmountValue,
        donation_type: generalDonationTypeValue,
    };
    let jsonPage1Data = JSON.stringify(page1Data);
    jQuery.ajax({
        type: 'POST',
        url: ajaxurl,
        data: {
            action: 'general_checkout_action',
            general_form_page_1_data: jsonPage1Data,
            security: nonce,
        },
        success: function(response) {

            if (response.success) {
                // console.log('Success_P1: \n' + JSON.stringify(response));
                cartSubTotal = response.data.cart_subtotal;
                updateRightPanelTotalDonations();
                jQuery('.widget_shopping_cart_content').html(response.data.mini_cart);
                jQuery(document.body).trigger('update_checkout');
                jQuery(document.body).trigger('wc_fragments_refreshed');
                updateRemoveButtons();
               
            } else {
                console.log('Failed_P1: \n' + JSON.stringify(response));
            }
            hideAdditionalPaymentMethod();
        }
    });

    switch (sectionName) {
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

        // SPECIFIC CANDIDATES DONATION SECTION HANDLER
        case rightPanelSummarySectionsList.candidates:
            specificDonationAmountValue = getCookie(ckSpecificDonationTotalAmount);
            voucherAmount = getCookie(ckVoucherAmount);
            voucherCode = getCookie(ckVoucherCode);

            let voucherAmountNoSign = voucherAmount.replace('$', '');
            console.log('RightSpecificPanel: ' + eleRightPanelSpecificValue.text());
            if (eleRightPanelSpecificValue.text() === '$0') {
                if (voucherAmount === '') {
                    voucherAmount = 0;
                }
                console.log('cartTotal: ' + specificDonationAmountValue + ' voucherAmount: ' + voucherAmountNoSign);
                // if (voucherAmount > 0) {
                //     specificDonationAmountValue = parseInt(specificDonationAmountValue) + parseInt(voucherAmountNoSign);
                // }

                specificDonationType = getCookie('ckSpecificDonationType');
                if (cartContents.length <= 0) {
                    console.log('Cart is empty. No need to populate specific cart panel.');
                    rightPanelSpecificElement.hide();
                }
                else if (specificDonationAmountValue > 0) {
                    // CART TOTAL UPDATE SPECIFIC TAB
                    console.log('Cart has [' + cartContents.length + '] item(s)!');
                    if (String(specificDonationAmountValue).includes('$')) {
                        eleRightPanelSpecificValue.text(specificDonationAmountValue);
                    } else {
                        eleRightPanelSpecificValue.text('$' + new Intl.NumberFormat().format(specificDonationAmountValue));
                    }
                    console.log('specificDonationAmountValue: ' + specificDonationAmountValue);

                    if (specificDonationType) {
                        eleRightPanelSpecificType.text(specificDonationType);
                        console.log('specificDonationType: ' + specificDonationType);
                    } else {
                        console.log('Specific Donation Type is not set.');
                    }

                    let rightPanel_cart_item_container = jQuery('#cart_items_specific_candidate_details');
                    rightPanel_cart_item_container.empty();

                    let outerContainer = jQuery('<div class="right-outer-cart-item"></div>');
                    rightPanel_cart_item_container.append(outerContainer);

                    // do not run the loop if outercontainer already has child elements
                    if (outerContainer.find('.right-inner-cart-item').length > 0) {
                        console.log('OuterContainer already has child elements. No need to populate again.');
                    } else {
                        console.log('OuterContainer does not have child elements. Populating now...');
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
                                totalCartPrice = parseInt(item.product_price) + parseInt(voucherAmount);
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
            }
            break;

        // LOCATION DONATION SECTION HANDLER
        case rightPanelSummarySectionsList.locations:
            locationZipCodeValue = getCookie(ckLocationZipCodeNumber);
            locationDonationAmountValue = getCookie(ckLocationDonationAmount);
            locationDonationTypeValue = getCookie(ckLocationDonationType);

            if (locationDonationTypeValue) {
                eleRightPanelLocationType.text(locationDonationTypeValue);
            } else {
                locationDonationTypeValue = 'One-Time';
                document.cookie = ckLocationDonationType + '=' + locationDonationTypeValue + cookiePath;
                console.log('Location Donation Type is not set.');
            }

            if (locationDonationAmountValue > 0 && locationZipCodeValue > 0) {
                eleRightPanelLocationZipValue.text(locationZipCodeValue);
                eleRightPanelLocationValue.text('$' + new Intl.NumberFormat().format(locationDonationAmountValue));
                eleRightPanelLocationValue.append('<span title="Remove this item from the cart." class="icon_remove-item_right-panel" id="remove_location_panel">' +
                    ' <i aria-hidden="true" class="far fa-trash-alt"></i></span>');
                rightPanelLocationElement.show();
            } else {
                locationZipCodeValue = '';
                locationDonationAmountValue = '';
                rightPanelLocationElement.hide();
            }
            break;

        // EXPANSION DONATION SECTION HANDLER
        case rightPanelSummarySectionsList.expansion:
            expansionDonationYesNo = getCookie(ckExpansionDonationYesNo);
            expansionDonationAmountValue = getCookie(ckExpansionDonationAmount);
            expansionDonationTypeValue = getCookie(ckExpansionDonationType);
            if (expansionDonationTypeValue) {
                eleRightPanelExpansionType.text(expansionDonationTypeValue);
            } else {
                expansionDonationTypeValue = 'One-Time';
                eleRightPanelExpansionType.text(expansionDonationTypeValue);
                document.cookie = ckExpansionDonationType + '=' + expansionDonationTypeValue + cookiePath;
                console.log('ckExpansionDonationType is not set.');
            }
            if (expansionDonationYesNo === 'Yes' && expansionDonationAmountValue > 0) {
                eleRightPanelExpansionValue.text('$' + new Intl.NumberFormat().format(expansionDonationAmountValue));
                eleRightPanelExpansionValue.append('<span title="Remove this item from the cart." class="icon_remove-item_right-panel" id="remove_expansion_panel">' +
                    ' <i aria-hidden="true" class="far fa-trash-alt"></i></span>');
                rightPanelExpansionElement.show();
            } else {
                expansionDonationAmountValue = '';
                document.cookie = ckExpansionDonationAmount + '=' + expansionDonationAmountValue + cookiePath;
                rightPanelExpansionElement.hide();
            }
            break;

        case rightPanelSummarySectionsList.voucher:
            voucherCode = getCookie('ckVoucherCode');
            voucherAmount = getCookie('ckVoucherAmount');
            if (voucherCode) {
                eleRightPanelVoucherCode.text(voucherCode);
                if (voucherAmount) {
                    eleRightPanelVoucherValue.text('-$' + voucherAmount);
                } else {
                    eleRightPanelVoucherValue.text('Not Set');
                }
                rightPanelVoucherElement.show();
            } else {
                rightPanelVoucherElement.hide();
            }
            break;

        default:
            console.log('sectionName not mentioned');
            return;
    }
    updateRightPanelTotalDonations();
    hideAdditionalPaymentMethod();
}
updateRightPanel();


jQuery(function ($) {
    if (is_general_funds_tab_selected == null) {
        is_general_funds_tab_selected = false;
    }
});

function hideAdditionalPaymentMethod(){

    let total = +getCookie(ckSubTotalDonations) - +getCookie(ckVoucherAmount);
   // console.log('YEPPP!!! ' + total);

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


// GENERAL DONATION AMOUNT RADIO BUTTONS HANDLER
jQuery(document).ready(function ($) {
    generalDonationAmountElement = $('#field_general_donation_amount');
    generalOtherAmountElement = $('#generalAmount_other');
    const radioButtonsGeneralDonationAmounts = document.querySelectorAll('input[name="generalDonationAmount"]');

    // on page load unchecked all general radio buttons other than the selected one
    let generalDonationCookieAmount = sanitizeGeneralPageAmount(getCookie('ckGeneralDonationAmount'));
    console.log('OnLoad-> General Donation Cookie Amount: ' + generalDonationCookieAmount);
    if (generalDonationCookieAmount > 0) {
        // console.log('Exp Cookie Value: ' + generalDonationCookieAmount);
        for (let i = 0; i < radioButtonsGeneralDonationAmounts.length; i++) {
            const buttonGeneral = radioButtonsGeneralDonationAmounts[i];

            if (buttonGeneral.value === generalDonationCookieAmount && buttonGeneral.id.includes(generalDonationCookieAmount)) {
                buttonGeneral.checked = true;
                // console.log('In Button Value: ' + buttonGeneral.value);
                // console.log('In Button ID: ' + buttonGeneral.id);
                generalDonationAmountElement.val(new Intl.NumberFormat().format(buttonGeneral.value));
                break; // Exit the loop
            } else if (generalDonationCookieAmount !== 0 && buttonGeneral.id.includes(generalOtherAmountElement.attr('id'))) {
                // console.log('Out Button Value: ' + buttonGeneral.value);
                // console.log('Out Button Amount: ' + generalDonationCookieAmount);
                // console.log('Out Button ID: ' + buttonGeneral.id);
                generalDonationAmountElement.attr('readonly', false);
                generalDonationAmountElement.val(new Intl.NumberFormat().format(generalDonationCookieAmount));
                generalOtherAmountElement.click();
            } else {
                buttonGeneral.checked = false;
            }
        }
        updateRightPanel(rightPanelSummarySectionsList.general);
    }
    else {
        radioButtonsGeneralDonationAmounts.forEach(button => {
            button.checked = false;
            generalDonationAmountElement.val('');
        });
        updateRightPanel(rightPanelSummarySectionsList.general);
    }

    // If clicked on 'Other Amount' input radio button
    generalOtherAmountElement.change(function () {
        generalDonationAmountElement.attr('readonly', false).click().focus().attr('placeholder', 'Enter Amount');
        generalDonationAmountElement.val(sanitizeGeneralPageAmount(generalDonationAmountElement.val()));
    });
    // if typing amount manually while in other amount, update right panel and cookie
    jQuery(generalDonationAmountElement).on('input', function (event) {
        if (event.which === 13) {
            event.preventDefault();
        }

        // Mask the input field to not allow to type any non-numeric characters
        let inputValue = $(this).val();
        let numericValue = inputValue.replace(/[^0-9]/g, '');
        $(this).val(numericValue);
        generalOtherAmountElement.click();

        document.cookie = ckGeneralDonationAmount + '=' + sanitizeGeneralPageAmount(numericValue) + cookiePath;
        updateRightPanel(rightPanelSummarySectionsList.general);
    });

    // Add event listener to each donation amount radio button
    radioButtonsGeneralDonationAmounts.forEach(button => {
        button.addEventListener('click', () => {
            let selectedGeneralValue = button.value;
            if (button.id !== 'generalAmount_other') {
                let formattedGeneralValue = new Intl.NumberFormat().format(selectedGeneralValue);
                generalDonationAmountElement.val(formattedGeneralValue);
                generalDonationAmountValue = formattedGeneralValue;
                // clear cookie value
                document.cookie = ckGeneralDonationAmount + '=' + '' + cookiePath;
                // apply new cookie value
                document.cookie = ckGeneralDonationAmount + '=' + selectedGeneralValue + cookiePath;
            } else {
                selectedGeneralValue = generalDonationAmountElement.val();
                generalDonationAmountValue = selectedGeneralValue;
                document.cookie = ckGeneralDonationAmount + '=' + '' + cookiePath;
                document.cookie = ckGeneralDonationAmount + '=' + selectedGeneralValue + cookiePath;
            }
            updateRightPanel(rightPanelSummarySectionsList.general);
            jQuery(document.body).trigger('wc_fragment_refresh');
        });
        
    });
});

// EXPANSION DONATION AMOUNT RADIO BUTTONS HANDLER
jQuery(document).ready(function ($) {
    expansionDonationAmountElement = $('#field_expansion_donation_amount');
    expansionOtherAmountElement = $('#expansionAmount_other');
    expansionDonationAmountValue = sanitizeGeneralPageAmount(getCookie(ckExpansionDonationAmount));
    expansionDonationTypeValue = getCookie(ckExpansionDonationType);
    expansionDonationYesNo = getCookie(ckExpansionDonationYesNo);

    const radioButtonsExpansionDonationAmounts = document.querySelectorAll('input[name="expansionDonationAmount"]');

    // on page load unchecked all expansion radio buttons other than the selected one
    if (expansionDonationAmountValue > 0) {

        if( expansionDonationYesNo === 'Yes' ) {
            expansionDonationYesNo = 'Yes';
            jQuery('#field_vg33t-0').click().prop('checked', true);

            for (let i = 0; i < radioButtonsExpansionDonationAmounts.length; i++) {
                const buttonExpansion = radioButtonsExpansionDonationAmounts[i];

                if (buttonExpansion.value === expansionDonationAmountValue && buttonExpansion.id.includes(expansionDonationAmountValue)) {
                    buttonExpansion.checked = true;
                    // console.log('In expButton Value: ' + buttonExpansion.value+ '\tID: ' + buttonExpansion.id);
                    expansionDonationAmountElement.val(new Intl.NumberFormat().format(buttonExpansion.value));
                    break; // Exit the loop
                }
                else if (expansionDonationAmountValue > 0 && buttonExpansion.id.includes(expansionOtherAmountElement.attr('id'))) {
                    // console.log('Out expButton Value: ' + buttonExpansion.value + '\tAmount: ' + expansionDonationAmountValue+ '\tID: ' + buttonExpansion.id);
                    expansionDonationAmountElement.val(new Intl.NumberFormat().format(expansionDonationAmountValue));
                    expansionOtherAmountElement.click();
                } else {
                    buttonExpansion.checked = false;
                }
            }
        }
        else {
            expansionDonationYesNo = 'Yes';
            document.cookie = ckExpansionDonationYesNo + '=' + expansionDonationYesNo + cookiePath;
            expansionDonationAmountElement.val(expansionDonationAmountValue);
            jQuery('#field_vg33t-0').click().prop('checked', true);
        }
        updateRightPanel(rightPanelSummarySectionsList.expansion);
    }
    else {
        radioButtonsExpansionDonationAmounts.forEach(button => {
            button.checked = false;
        });
        expansionDonationYesNo = 'No';
        document.cookie = ckExpansionDonationYesNo + '=' + expansionDonationYesNo + cookiePath;
        expansionDonationAmountElement.val('');
        document.cookie = ckExpansionDonationAmount + '=' + '' + cookiePath;
        jQuery('#field_vg33t-1').click().prop('checked', true);
        expansionDonationTypeValue = 'One-Time';
        document.cookie = ckExpansionDonationType + '=' + expansionDonationTypeValue + cookiePath;
        updateRightPanel(rightPanelSummarySectionsList.expansion);
    }

    // Expansion Yes/No radio button click handler
    jQuery(document).on('change', '#field_vg33t-0, #field_vg33t-1', function () {
        expansionDonationYesNo = jQuery(this).val();
        console.log('expansionDonationYesNo: ' + expansionDonationYesNo);
        document.cookie = ckExpansionDonationYesNo + '=' + expansionDonationYesNo + cookiePath;
        if (expansionDonationYesNo === 'No') {
            jQuery('#remove_expansion_panel').trigger('click');
            expansionDonationAmountElement.val('');
            document.cookie = ckExpansionDonationAmount + '=' + '' + cookiePath;
            document.cookie = ckExpansionDonationType + '=' + 'One-Time' + cookiePath;
            if (getCookie(ckGeneralDonationType).toLowerCase() === 'one-time' && getCookie(ckLocationDonationType).toLowerCase() === 'one-time') {
                document.cookie = ckCreateDonorAccountYesNo + '=No' + cookiePath;
                console.log('ckCreateDonorAccountYesNo: ' + getCookie(ckCreateDonorAccountYesNo));
            }
            radioButtonsExpansionDonationAmounts.forEach(button => {
                button.checked = false;
            });
            updateRightPanel(rightPanelSummarySectionsList.expansion);
        }
        jQuery(this).click().prop('checked', true);
    });

    // If clicked on expansion 'Other Amount' input radio button
    expansionOtherAmountElement.change(function () {
        // remove attribute 'readonly' from the input element
        expansionDonationAmountElement.attr('readonly', false).click().focus().attr('placeholder', 'Enter Amount');
        expansionDonationAmountElement.val(sanitizeGeneralPageAmount(expansionDonationAmountElement.val()));
    });
    // if typing amount manually while in other amount, update right panel and cookie
    jQuery(expansionDonationAmountElement).on('input', function (event) {
        if (event.which === 13) {
            event.preventDefault();
        }

        // Mask the input field to not allow to type any non-numeric characters
        let inputValue = $(this).val();
        let numericValue = inputValue.replace(/[^0-9]/g, '');
        $(this).val(numericValue);
        expansionOtherAmountElement.click();

        document.cookie = ckExpansionDonationAmount + '=' + sanitizeGeneralPageAmount(numericValue) + cookiePath;
        updateRightPanel(rightPanelSummarySectionsList.expansion);
    });

    // Add event listener to each donation amount radio button
    radioButtonsExpansionDonationAmounts.forEach(button => {
        button.addEventListener('click', () => {
            let selectedExpansionValue = button.value;
            if (button.id !== 'expansionAmount_other') {
                let formattedExpansionValue = new Intl.NumberFormat().format(selectedExpansionValue);
                expansionDonationAmountElement.val(formattedExpansionValue);
                expansionDonationAmountValue = formattedExpansionValue;
                document.cookie = ckExpansionDonationAmount + '=' + selectedExpansionValue + cookiePath;
            } else {
                selectedExpansionValue = expansionDonationAmountElement.val();
                expansionDonationAmountValue = selectedExpansionValue;
                document.cookie = ckExpansionDonationAmount + '=' + '' + cookiePath;
                document.cookie = ckExpansionDonationAmount + '=' + selectedExpansionValue + cookiePath;
            }
            updateRightPanel(rightPanelSummarySectionsList.expansion);
            jQuery(document.body).trigger('wc_fragment_refresh');
        });
    });
});

// GENERAL DONATION TYPE BUTTON HANDLER
jQuery(function ($) {
    donationTypeElement = jQuery('#frm_field_234_container .frm_opt_container input');
    donationTypeValue = getCookie(ckGeneralDonationType);
    // console.log('ckGeneralDonationType Value: ' + donationTypeValue);
    if (donationTypeValue) {
        if (donationTypeValue.toLowerCase() === 'one-time') {
            donationTypeElement.filter(':first').parent().click().prop('checked', true);
        }
        else if (donationTypeValue.toLowerCase() === 'monthly') {
            donationTypeElement.filter(':last').parent().click().prop('checked', true);
            document.cookie = ckCreateDonorAccountYesNo + '=Yes' + cookiePath;
        }
        donationTypeElement.filter(':checked').parent().css({
            'background-color': '#143A62',
            'color': '#fff',
        });
        document.cookie = ckGeneralDonationType + '=' + donationTypeValue + cookiePath;
        updateRightPanel(rightPanelSummarySectionsList.general);
    }
    else {
        document.cookie = ckGeneralDonationType + '=One-Time' + cookiePath;
        jQuery('#field_x5czf-0').filter(':first').parent().click();
        console.log('Donation Type was not set, defaulted to: ' + getCookie(ckGeneralDonationType));
    }
    updateRightPanel(rightPanelSummarySectionsList.general);

    // Style the selected donation type
    donationTypeElement.change(function () {
        donationTypeElement.parent().css({
            'background-color': '#fff',
            'color': '#143A62',
        });
        $(this).parent().css({
            'background-color': '#143A62',
            'color': '#fff',
        });
        donationTypeValue = donationTypeElement.filter(':checked').val();
        if (donationTypeValue.toLowerCase() === 'monthly') {
            document.cookie = ckCreateDonorAccountYesNo + '=Yes' + cookiePath;
        } else {
            document.cookie = ckCreateDonorAccountYesNo + '=No' + cookiePath;
        }
        document.cookie = ckGeneralDonationType + '=' + donationTypeElement.filter(':checked').val() + cookiePath;
        updateRightPanel(rightPanelSummarySectionsList.general);
    });
});

// EXPANSION DONATION TYPE BUTTON HANDLER
jQuery(document).ready(function ($) {

    expansionDonationTypeElement = jQuery('#frm_field_449_container .frm_opt_container input');
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
        console.log('Expansion Donation Type was not set, defaulted to: ' + getCookie(ckExpansionDonationType));
    }
    updateRightPanel(rightPanelSummarySectionsList.expansion);

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

// REMOVE RIGHT PANEL ON BUTTON CLICK HANDLER
jQuery(function ($) {
    jQuery(document).on('click', '#remove_general_panel', function () {
        jQuery('#frm_field_300_container input[type="radio"]').each(function(){
            jQuery(this).prop('checked', false);
        })
        console.log('Removing General Panel...');
        radioButtonsGeneralDonationAmounts =
            document.querySelectorAll('input[name="generalPageGeneralDonationAmount"]');
        radioButtonsGeneralDonationAmounts.forEach(button => {
            button.checked = false;
        });
        // $('#field_fp9k8-1').trigger('click');
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
                            console.log('Item ['+item.product_key+'] successfully removed from cart.');
                            jQuery(document.body).trigger('wc_fragment_refresh');
                            jQuery( document.body ).trigger( 'update_checkout' );
                        }
                    });
                }
            });
        }
        updateRightPanel(rightPanelSummarySectionsList.general);
    });
    jQuery(document).on('click', '#remove_expansion_panel', function () {
        console.log('Removing Expansion Panel...');
        radioButtonsExpansionDonationAmounts =
            document.querySelectorAll('input[name="generalPageExpansionDonationAmount"]');
        radioButtonsExpansionDonationAmounts.forEach(button => {
            button.checked = false;
        });
        $('#field_vg33t-1').trigger('click');
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
                            console.log('Item ['+item.product_key+'] successfully removed from cart.');
                            jQuery(document.body).trigger('wc_fragment_refresh');
                            jQuery( document.body ).trigger( 'update_checkout' );
                        }
                    });
                }
            });
        }
        updateRightPanel(rightPanelSummarySectionsList.expansion);
    });
    
    jQuery(document).on('click', '#remove_location_panel', function () {
        console.log('Removing Location Panel...');
        radioButtonsLocationDonationAmounts =
            document.querySelectorAll('input[name="generalPageExpansionDonationAmount"]');
        radioButtonsLocationDonationAmounts.forEach(button => {
            button.checked = false;
        });
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
                            console.log('Item ['+item.product_key+'] successfully removed from cart.');
                            jQuery(document.body).trigger('wc_fragment_refresh');
                            jQuery( document.body ).trigger( 'update_checkout' );
                        }
                    });
                }
            });
        }
        updateRightPanel(rightPanelSummarySectionsList.locations);
    });
});

// CANDIDATES DONATION AMOUNT RIGHT PANEL HANDLER
jQuery(document).ready(function ($) {
    specificDonationAmountValue = getCookie(ckSpecificDonationTotalAmount);
    specificDonationType = getCookie(ckSpecificDonationType);
    updateRightPanel(rightPanelSummarySectionsList.candidates);

    if (getCookie(ckVoucherCode)) {
        updateRightPanel(rightPanelSummarySectionsList.voucher);
    }
});

// LOCATION DONATION AMOUNT RIGHT PANEL HANDLER
jQuery(document).ready(function ($) {
    locationDonationAmountValue = getCookie(ckLocationDonationAmount);
    locationDonationTypeValue = getCookie(ckLocationDonationType);
    locationDonationZipCodeNumber = getCookie(ckLocationZipCodeNumber);
    updateRightPanel(rightPanelSummarySectionsList.locations);
});

// DONATE ANONYMOUSLY RADIO BUTTON EVENT HANDLER
jQuery(document).ready(function ($) {
    if (getCookie(ckDonateAnonymously)) {
        if (getCookie(ckDonateAnonymously) === 'Yes') {
            donateAnonymously = 'Yes';
            jQuery('#field_donate_anonymously2-0').prop('checked', true);
            jQuery('#donate_anonymously').val('yes');
        } else {
            donateAnonymously = 'No';
            jQuery('#field_donate_anonymously2-1').prop('checked', true);
            jQuery('#donate_anonymously').val('no');
        }
        jQuery(document.body).trigger('update_checkout');
        console.log('DonateAnonymously Cookie Exists. Value: ' + getCookie(ckDonateAnonymously));
    } else {
        donateAnonymously = 'Yes';
        jQuery('#field_donate_anonymously2-0').prop('checked', true);
        document.cookie = ckDonateAnonymously + '=Yes' + cookiePath;
        jQuery(document.body).trigger('update_checkout');
        console.log('DonateAnonymously Cookie Does Not Exist. Setting Default Value to "Yes"');
    }
    jQuery('#field_donate_anonymously2-0, #field_donate_anonymously2-1').on('change', function () {
        donateAnonymously = jQuery(this).val();
        document.cookie = ckDonateAnonymously + '=' + donateAnonymously + cookiePath;
        console.log('Donate Anonymously Updated to [' + donateAnonymously + ']');
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

    jQuery('#frm_field_418_container').hide();
    jQuery('#frm_field_419_container').html(
    '<p><span class="reg-ref">Already logged in as: <a href="/' + userDashboard + '/" target="_blank">' + userDisplayName + '</a></span></p>'
    );
}
else {
    if( getCookie(ckCreateDonorAccountYesNo) ) {
        if( getCookie(ckCreateDonorAccountYesNo) === 'Yes' ) {
            createDonorAccountYesNo = 'Yes';
            jQuery('#field_kz8bg-0').prop('checked', true);
            jQuery('#field_kz8bg-1').prop('disabled', true);
            let createDonorAccountElementOnCheckout = jQuery('#createaccount');
            let notCreateDonorAccountElementOnCheckout =
                jQuery('.woocommerce-form__input.woocommerce-form__input-checkbox.input-checkbox.checkout_createaccount');
            // Check if the element exists in WooCommerce checkout form
            if (createDonorAccountElementOnCheckout) {
                // Check if the checkbox is already checked or not
                if( createDonorAccountElementOnCheckout.is(':checked') ) {
                    // Do nothing. Checkbox is already checked
                    console.log('Create Donor Account Checkbox is already checked');
                }
                else {
                    // Check the checkbox
                    createDonorAccountElementOnCheckout.click();
                    createDonorAccountElementOnCheckout.prop('disabled', true);
                    notCreateDonorAccountElementOnCheckout.prop('disabled', true);
                    jQuery('.woocommerce-form__label.woocommerce-form__label-for-checkbox.checkbox')
                        .append(
                            '<img decoding="async" id="createaccount_tooltip" ' +
                            'src="https://childfreebc.com/wp-content/uploads/2023/10/ad-step__icon.svg" ' +
                            'width="24px" height="24">'
                        );
                    jQuery(document.body).trigger('update_checkout');
                    console.log('Create Donor Account Checkbox is now checked');
                }
            }
        }
        else {
            createDonorAccountYesNo = 'No';
            jQuery('#field_kz8bg-1').prop('disabled', false);
            jQuery('#field_kz8bg-1').prop('checked', true);
            jQuery('.woocommerce-account-fields').hide();
        }
        console.log('CreatDonorAccount Cookie Exists. Value: ' + getCookie(ckCreateDonorAccountYesNo));
    }
    else {
        createDonorAccountYesNo = 'No';
        jQuery('#field_kz8bg-1').prop('checked', true);
        document.cookie = ckCreateDonorAccountYesNo + '=' + createDonorAccountYesNo + cookiePath;
        console.log('CreatDonorAccount Cookie Does Not Exist. Setting Default Value to [' + createDonorAccountYesNo + ']');
    }
    jQuery(document).on('change', '#field_kz8bg-0, #field_kz8bg-1, #createaccount', function () {
        let createDonorAccountClickedIdValue = jQuery(this).val();
        console.log('acctCookie: [' + getCookie('ckCreateDonorAccountYesNo') + ']' +
            '\tacctClicked: [' + createDonorAccountClickedIdValue + ']');

        if (createDonorAccountClickedIdValue === '1') {
            createDonorAccountYesNo = getCookie('ckCreateDonorAccountYesNo');
            if (createDonorAccountYesNo === 'Yes') {
                createDonorAccountYesNo = 'Yes';
                document.cookie = ckCreateDonorAccountYesNo + '=' + createDonorAccountYesNo + cookiePath;
            } else if (createDonorAccountYesNo === 'No') {
                createDonorAccountYesNo = 'No';
                document.cookie = ckCreateDonorAccountYesNo + '=' + createDonorAccountYesNo + cookiePath;
            }
        }
        if (createDonorAccountClickedIdValue === 'Yes') {
            createDonorAccountYesNo = 'Yes';
            document.cookie = ckCreateDonorAccountYesNo + '=' + createDonorAccountYesNo + cookiePath;
            jQuery('#field_kz8bg-0').prop('checked', true);
            // jQuery('#createaccount').click();
            jQuery('.woocommerce-account-fields').show();
        }
        else if (createDonorAccountClickedIdValue === 'No') {
            createDonorAccountYesNo = 'No';
            document.cookie = ckCreateDonorAccountYesNo + '=' + createDonorAccountYesNo + cookiePath;
            jQuery('#field_kz8bg-1').prop('checked', true);
            jQuery('.woocommerce-account-fields').hide();
        }
        console.log('Create Donor Account: [' + createDonorAccountYesNo + ']');
    });
}

// REFERRAL YES/NO RADIO BUTTON HANDLER
if (getCookie(ckDonationReferralYesNo)) {
    if (getCookie(ckDonationReferralYesNo) === 'Yes'
        && (getCookie(ckDonationReferrerDetail) !== '' || getCookie(ckDonationReferrerDetail) !== null)) {
        donationReferralYesNo = 'Yes';
        jQuery('#field_dx2vs2-0').prop('checked', true);
        donationReferrerDetail = getCookie(ckDonationReferrerDetail);
        jQuery('#field_general_donation_referrer_detail').val(getCookie(ckDonationReferrerDetail));
    } else {
        donationReferralYesNo = 'No';
        jQuery('#field_dx2vs2-1').prop('checked', true);
    }
    console.log('Referral Cookie Exists.\n\tValue: [' + getCookie(ckDonationReferralYesNo)
        + ']\n\tReferrer Detail: [' + donationReferrerDetail + ']');
} else {
    donationReferralYesNo = 'No';
    donationReferrerDetail = '';
    jQuery('#field_dx2vs2-1').prop('checked', true);
    document.cookie = ckDonationReferralYesNo + '=No' + cookiePath;
    console.log('Referral Cookie Does Not Exist.\n\tSetting Default Value to [' + donationReferralYesNo + ']');
}
jQuery(document).on('change', '#field_dx2vs-0, #field_dx2vs-1', function () {
    donationReferralYesNo = jQuery(this).val();
    jQuery(this).prop('checked', true);
    if (jQuery('#field_dx2vs2-0').is(':checked')) {
        donationReferrerDetail = getCookie(ckDonationReferrerDetail);
        jQuery('#field_general_donation_referrer_detail').val(donationReferrerDetail);
    } else {
        donationReferrerDetail = '';
        jQuery('#field_general_donation_referrer_detail').val('');
    }
    document.cookie = ckDonationReferralYesNo + '=' + donationReferralYesNo + cookiePath;
    console.log('Referral Yes/No: [' + donationReferralYesNo + '] Referrer Detail: [' + donationReferrerDetail + ']');
});
jQuery('#field_general_donation_referrer_detail').on('input', function (event) {
    if (event.which === 13) {
        event.preventDefault();
    }
    donationReferrerDetail = jQuery(this).val();
    document.cookie = ckDonationReferrerDetail + '=' + donationReferrerDetail + cookiePath;
    // print the key value which is pressed
    // console.log(donationReferrerDetail);
});

jQuery(document).ready(function ($) {

    // REMOVE ITEM From Specific Cart -> Event Delegation
    $(document).on('click', '#remove-this-cart-item', function (event) {
        event.preventDefault();

        var productKey = jQuery(this).data('product-key');

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
                    // console.log('response: ' + JSON.stringify(response));
                    cartContents = response.data.cart_contents;
                    console.log('Removed PID [' + productKey + '] from cart!');
                    jQuery(document.body).trigger('wc_fragment_refresh');
                    eleRightPanelSpecificValue.text('$0');
                    updateRightPanel(rightPanelSummarySectionsList.candidates);
                } else {
                    console.log('Failed! ' + response.message || 'An error occurred.');
                }
            },
            error: function (error) {
                console.log('Error removing item: ' + error.statusText);
            }
        });
    });

    // PAGE-1: HIDE (OPTIONAL) ON MOB 
    $(document).ready(function() {
        function checkResolution() {
            const screenWidth = window.innerWidth || document.documentElement.clientWidth || document.body.clientWidth;
    
            if (screenWidth <= 600) {
                const firstElement = $('div#field_vg33t_label span:first-child');
    
                if (firstElement.length) {
                    firstElement.first().text('Donate to the Expansion Fund?');
                    firstElement.css('white-space', 'nowrap');
                }
            }
        }
    
        checkResolution();
    
        $(window).resize(checkResolution);
    });

    // PAGE-1: AJAX CALL TO UPDATE GENERAL DONATION FORM SESSION VARIABLE
    jQuery(document).on('click', '#form_general-fund-donation .frm_page_num_1 .frm_submit .frm_button_submit', function (event) {
        event.preventDefault();

        generalDonationTypeValue = getCookie('ckGeneralDonationType');
        generalDonationAmountValue = getCookie('ckGeneralDonationAmount');
        // if general donation amount is zero or not set then show error and return
        if (generalDonationAmountElement.val() <= 0 ||
            generalDonationAmountElement.val() === '' ||
            generalDonationAmountValue === '' || generalDonationAmountValue <= 0) {
            let existingErrorMessage = $('#frm_error_field_general_donation_amount');
            existingErrorMessage.hide();
            let errorMessage = $(
                '<div class="frm_error" role="alert" id="frm_error_field_general_donation_amount" ' +
                'style="display:inline;">Please select a donation amount.</div>'
            );
            errorMessage.insertBefore($('#field_general_donation_amount'), $('#field_general_donation_amount_container'));

            // Scroll the page to the target element
            let targetPosition = jQuery('#frm_error_field_general_donation_amount').offset().top;
            targetPosition -= 300;
            jQuery('html, body').animate({
                scrollTop: targetPosition
            }, 500);

            return;
        }
        else {
            let existingErrorMessage = $('#frm_error_field_general_donation_amount');
            existingErrorMessage.hide();
        }

        expansionDonationAmountValue = getCookie('ckExpansionDonationAmount');
        // if expansion yes/no is yes and expansion donation amount is zero or not set then show error and return
        if (getCookie(ckExpansionDonationYesNo) === 'Yes' &&
            (expansionDonationAmountElement.val() === '' || expansionDonationAmountElement.val() === '0' ||
            expansionDonationAmountValue === '' || expansionDonationAmountValue === '0')) {
            let existingErrorMessage = $('#frm_error_field_expansion_donation_amount');
            existingErrorMessage.hide();
            let errorMessage = $(
                '<div class="frm_error" role="alert" id="frm_error_field_expansion_donation_amount" ' +
                'style="display:inline;">Please select a donation amount.</div>'
            );
            errorMessage.insertBefore($('#field_expansion_donation_amount'), $('#field_expansion_donation_amount_container'));

            // Scroll the page to the target element
            let targetPosition = jQuery('#frm_error_field_expansion_donation_amount').offset().top;
            targetPosition -= 300;
            jQuery('html, body').animate({
                scrollTop: targetPosition
            }, 500);

            return;
        }
        else {
            let existingErrorMessage = $('#frm_error_field_expansion_donation_amount');
            existingErrorMessage.hide();
        }

        totalDonationAmountValue = getCookie('ckTotalDonations');

        // remove the commas from the donationAmount value before sending it to the server
        generalDonationAmountValue = generalDonationAmountValue.replace(/,/g, '');
        generalDonationAmountValue = generalDonationAmountValue.replace('$', '');

        if (expansionDonationTypeValue.toLowerCase() === 'one-time'
            && generalDonationTypeValue.toLowerCase() === 'one-time'
            && locationDonationTypeValue.toLowerCase() === 'one-time') {
            document.cookie = ckCreateDonorAccountYesNo + '=No' + cookiePath;
        }

        let page1Data = {
            generalProductID: 23603,
            expansionProductID: 55946,
            locationProductID: 56180,
            donate_anonymously: donateAnonymously,
            generalDonationAmount: generalDonationAmountValue,
            expansionDonationAmount: expansionDonationAmountValue,
            locationZipCodeNumber: getCookie(ckLocationZipCodeNumber),
            locationDonationAmount: getCookie(ckLocationDonationAmount),
            totalDonationAmount: totalDonationAmountValue,
            donation_type: generalDonationTypeValue,
        };
        let jsonPage1Data = JSON.stringify(page1Data);
        console.log('[jsonPage1Data]=\t' + jsonPage1Data);

        $.ajax({
            type: 'POST',
            url: ajaxurl,
            data: {
                action: 'general_checkout_action',
                general_form_page_1_data: jsonPage1Data,
                security: nonce,
            },
            beforeSend: function () {
                overlayToggle();
            },
            success: function (response) {
                overlayToggle();
                // console.log("Page-1: " + JSON.stringify(response));
                if (response !== 0) {
                    jQuery('#form_general-fund-donation .frm_page_num_1 .frm_submit .frm_button_submit').submit();
                } else {
                    console.log('An error occurred.');
                }
                hideAdditionalPaymentMethod();
            },
            error: function (error) {
                console.log(JSON.stringify(error));
                overlayToggle();
            }
        });
    });

    // PAGE-2: AJAX CALL TO UPDATE GENERAL DONATION FORM SESSION VARIABLE
    jQuery(document).on('click', '#form_general-fund-donation .frm_page_num_2 .frm_submit .frm_button_submit', function (event) {
        event.preventDefault();

        let existingErrorMessage = $('#frm_error_field_general_referrer_detail');
        existingErrorMessage.hide();
        let referralYesNo = $('#frm_field_206_container .frm_opt_container input');
        let selectedReferralYesNo = $(referralYesNo).filter(':checked').val();
        document.cookie = ckDonationReferralYesNo + '=' + selectedReferralYesNo + cookiePath;
        let donationReferrerDetail = $('#field_general_donation_referrer_detail').val();
        document.cookie = ckDonationReferrerDetail + '=' + donationReferrerDetail + cookiePath;
        // console.log('[Nonce]' + nonce + ' [referralYesNo]' + selectedReferralYesNo + ' [donationReferrerDetail]' + donationReferrerDetail);

        let dataPage2 = {
            selectedReferralYesNo: selectedReferralYesNo,
            donationReferrerDetail: donationReferrerDetail,
        };
        let jsonDataPage2 = JSON.stringify(dataPage2);

        $.ajax({
            type: 'POST',
            url: ajaxurl,
            data: {
                action: 'general_checkout_action',
                general_form_page_2_data: jsonDataPage2,
                security: nonce,
            },
            beforeSend: function () {
                overlayToggle();
            },
            success: function (response) {
                overlayToggle();
                console.log(JSON.stringify(response));
                if (response !== 0) {
                    if (response.data.status === 'success') {
                        console.log('Ref_Yes/No: ' + response.data.selectedReferralYesNo);
                        console.log('Ref_ID: ' + response.data.donationReferrerDetail);
                        if (!window.location.href.includes('ref')
                            && response.data.donationReferrerDetail !== ''
                            && response.data.donationReferrerDetail !== null) {
                            window.history.replaceState(null, null, '?funds=general&ref=' + response.data.donationReferrerDetail);
                            jQuery('#form_general-fund-donation .frm_page_num_2 .frm_submit .frm_button_submit').submit();
                        } else {
                            jQuery('#form_general-fund-donation .frm_page_num_2 .frm_submit .frm_button_submit').submit();
                        }
                    } else if (response.data.status === 'error') {
                        if (existingErrorMessage.length > 0) {
                            existingErrorMessage.remove();
                        }
                        let errorMessage = $(
                            '<div class="frm_error" role="alert" id="frm_error_field_general_referrer_detail" ' +
                            'style="display:inline;">' + response.data.message + '</div>'
                        );
                        errorMessage.insertBefore($('#field_general_donation_referrer_detail'), $('#field_donation_referrer_detail_container'));
                        console.log('Failed! ' + response.data.message || 'An error occurred.');
                    }
                } else {
                    console.log('An error occurred.');
                }
                hideAdditionalPaymentMethod();
            },
            error: function (error) {
                console.log(error);
                overlayToggle();
            }
        });
    });

    // PAGE-3: FINAL SUBMISSION - AJAX CALL TO UPDATE GENERAL DONATION FORM SUBMISSION TO WOOCOMMERCE CHECKOUT PROCESS
    jQuery(document).on('click', '#form_general-fund-donation .frm_button_submit.frm_final_submit', function (event) {
        event.preventDefault();
        console.log('Final Submission Clicked...');
        // clear error message fields
        let errorMessageField = jQuery('#field_vwxl2_label span');
        let errorMessageContainer = jQuery('#frm_field_230_container');
        if (jQuery('#field_vwxl2-0').is(':checked')) {
            errorMessageField.text('');
            errorMessageContainer.css({'border':'0','padding':'0'});
        }
        else {
            errorMessageField.text('This field cannot be blank.').css('color','red');
            errorMessageContainer.css({'border':'1px solid red','padding':'10px'});
            return;
        }
        jQuery('#place_order').click();
        // scroll to overlay
        let targetPosition = jQuery('.blockUI.blockOverlay').offset().top;
        targetPosition -= 50;
        jQuery('html, body').animate({
            scrollTop: targetPosition
        }, 500);

    });

});

// DONOR ACCOUNT TOOLTIP FOR MONTHLY SUBSCRIPTION ON CHECKOUT BILLING FORM
jQuery(document).ready(function() {
    jQuery('#createaccount_tooltip').on('click', function () {
        jQuery('#donor_subscription_tooltip').attr('style', 'display: flex !important; top:1200px; left:125px;');
    });
    jQuery('#createaccount_tooltip').on('mouseleave', function () {
        jQuery('#donor_subscription_tooltip').attr('style', 'display: none !important; top:1200px; left:125px;');
    });
});

///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// PAYMENT METHODS & TERMS AND CONDITIONS CHECKBOX HANDLER
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
jQuery(document).ready(function() {
    let ppcButtonWrapper = '.ppc-button-wrapper';
    let ppcButtonWrapperOverlay = '.paypal_button_overlay';
    let paypalRadioButton = '#payment_method_ppcp-gateway';
    let stripeRadioButton = '#payment_method_stripe';
    let donateButton = '.frm_button_submit.frm_final_submit';
    let termsElement = '#frm_field_230_container';
    let termsCheckBox = '#field_vwxl2-0';
    let paymentMethodHeading = '#order_review h3#order_review_heading';

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
            'border':'unset',
            'padding':'unset',
            'box-shadow': 'unset',
            '-webkit-box-shadow': 'unset',
            '-moz-box-shadow': 'unset'
        });
        // console.log('Terms red border removed.');
    }

    jQuery(document).on('change', stripeRadioButton, function(event) {
        event.preventDefault();
        removeTermsRedBorder();
        jQuery(donateButton).show();
        jQuery(ppcButtonWrapper).attr('style','display: none !important;');
        jQuery(ppcButtonWrapperOverlay).hide();
        // console.log('Stripe is selected. Hiding Overlay and Paypal button...');
    });

    jQuery(document).on('change', paypalRadioButton, function(event) {
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

    jQuery(document).on('click', termsCheckBox, function() {
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

        jQuery(ppcButtonWrapper).attr('style','display: none !important;');
        jQuery(ppcButtonWrapperOverlay).hide();
        // console.log('>>> Paypal is not selected. Hiding Overlay...');
    }

    hideAdditionalPaymentMethod();



});
