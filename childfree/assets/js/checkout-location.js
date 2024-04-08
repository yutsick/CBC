"use strict";
// GLOBAL VARIABLES
let nonce = obj_location.nonce;
let ajaxurl = obj_location.ajaxurl;
let userRole = obj_location.user_role;
let userDisplayName = obj_location.user_display_name;
let cartContents = obj_location.cart_contents;
let cartTotal = obj_location.cart_total;
let cartSubTotal = obj_location.cart_subtotal;
let is_location_funds_tab_selected = obj_location.is_location_funds_tab_selected;
if (is_location_funds_tab_selected == null) {
    is_location_funds_tab_selected = false;
}
console.log('[is_location_funds_tab_selected]->' + is_location_funds_tab_selected);

let voucherAmount = 0, voucherCode = '';
let totalCartPrice = 0;
let donateAnonymously = '';

let
    locationZipValueAmountEditor,
    locationZipCodeValue,
    locationDonationAmountEditor,
    locationDonationAmountValue = 0,
    locationOtherAmountElement,
    locationDonationTypeElement,
    locationDonationTypeValue,
    totalDonationAmountValue,
    createDonorAccountYesNo,
    donationReferralYesNo,
    donationReferrerDetail,
    locationDonationYesNo,
    radioButtonsLocationDonationAmounts
    ;

let
    expansionDonationAmountEditor,
    expansionDonationAmountValue = 0,
    expansionDonationYesNo,
    expansionOtherAmountElement,
    expansionDonationTypeElement,
    expansionDonationTypeValue,
    radioButtonsExpansionDonationAmounts
    ;

let
    generalDonationYesNo,
    generalDonationAmountEditor,
    generalDonationTypeElement,
    generalDonationAmountValue = 0,
    generalOtherAmountElement,
    generalDonationTypeValue,
    radioButtonsGeneralDonationAmounts
    ;

let
    specificDonationAmountValue = 0,
    specificDonationType
    ;

let
    rightPanelGeneralElement = jQuery('#right_panel_general'),
    rightPanelSpecificElement = jQuery('#right_panel_specific'),
    rightPanelLocationElement = jQuery('#right_panel_location'),
    rightPanelExpansionElement = jQuery('#right_panel_expansion'),
    rightPanelVoucherElement = jQuery('#right_panel_voucher'),
    rightPanelTotalDonationsElement = jQuery('#right_panel_cart_total')
    ;

let
    eleRightPanelLocationZipValue = jQuery('#right_panel_location_donation_zip_number span'),
    eleRightPanelLocationValue = jQuery('#right_panel_location_donation_amount span'),
    eleRightPanelLocationType = jQuery('#right_panel_location_donation_type span'),
    eleRightPanelSpecificValue = jQuery('#specific_candidates_total_amount span'),
    eleRightPanelSpecificType = jQuery('.elementor-element-d724586 span'),
    eleRightPanelExpansionValue = jQuery('#right_panel_expansion_donation_amount span'),
    eleRightPanelExpansionType = jQuery('#right_panel_expansion_donation_type span'),
    eleRightPanelGeneralValue = jQuery('#right_panel_general_donation_amount span'),
    eleRightPanelGeneralType = jQuery('#right_panel_general_donation_type span'),
    eleRightPanelVoucherCode = jQuery('#right_panel_voucher_code span'),
    eleRightPanelVoucherValue = jQuery('#right_panel_voucher_amount span'),
    eleRightPanelTotalDonationsValue = jQuery('#right_panel_total_donation_value span'),
    eleRightPanelSubTotalDonationsValue = jQuery('#right_panel_subtotal_donation_value span')
    ;

let
    cookiePath = '; path=/;',
    ckVoucherAmount = 'ckVoucherAmount',
    ckVoucherCode = 'ckVoucherCode',
    ckLocationZipCodeNumber = 'ckLocationZipCodeNumber',
    ckLocationDonationAmount = 'ckLocationDonationAmount',
    ckLocationDonationType = 'ckLocationDonationType',
    ckLocationDonationYesNo = 'ckLocationDonationYesNo',
    ckExpansionDonationYesNo = 'ckExpansionDonationYesNo',
    ckExpansionDonationAmount = 'ckExpansionDonationAmount',
    ckExpansionDonationType = 'ckExpansionDonationType',
    ckSpecificDonationTotalAmount = 'ckSpecificDonationTotalAmount',
    ckGeneralDonationYesNo = 'ckGeneralDonationYesNo',
    ckGeneralDonationAmount = 'ckGeneralDonationAmount',
    ckGeneralDonationType = 'ckGeneralDonationType',
    ckTotalDonations = 'ckTotalDonations',
    ckSubTotalDonations = 'ckSubTotalDonations',
    ckDonationReferralYesNo = 'ckDonationReferralYesNo',
    ckDonationReferrerDetail = 'ckDonationReferrerDetail',
    ckCreateDonorAccountYesNo = 'ckCreateDonorAccountYesNo',
    ckDonateAnonymously = 'ckDonateAnonymously'
    ;

const rightPanelSummarySectionsList = {
    general: 'GeneralFunds',
    candidates: 'Candidates',
    locations: 'Locations',
    expansion: 'Expansion',
    voucher: 'Voucher'
};

function updateRemoveButtons() {

    if (jQuery('[data-product_id="23603"]').length != 0) {
        jQuery('[data-product_id="23603"]').on('click', () => {
            jQuery('#field_fp9k8-1').trigger('click');

            try {
                let updatedSubtotal = response.subtotal;
                jQuery(eleRightPanelSubTotalDonationsValue).text(updatedSubtotal);
                return;
            } catch (error) {
                return;
            }
        })
    }
    if (jQuery('[data-product_id="55946"]').length != 0) {

        jQuery('[data-product_id="55946"]').on('click', (e) => {
            jQuery('#remove_expansion_panel').trigger('click');
            
        })

        try {
        let updatedSubtotal = response.subtotal;
        jQuery(eleRightPanelSubTotalDonationsValue).text(updatedSubtotal);
    } catch (error) {
        return;
    }
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




jQuery(document).ready(function ($) {
    updateRemoveButtons();
})



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
function sanitizeLocationPageAmount(amount) {
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

// TOTAL DONATIONS SECTION HANDLER
function updateRightPanelTotalDonations() {
    const sanitizedGeneralAmount = sanitizeLocationPageAmount(String(generalDonationAmountValue));
    const sanitizedSpecificAmount = sanitizeLocationPageAmount(String(specificDonationAmountValue));
    const sanitizedLocationAmount = sanitizeLocationPageAmount(String(locationDonationAmountValue));
    const sanitizedExpansionAmount = sanitizeLocationPageAmount(String(expansionDonationAmountValue));

    // const totalDonations = cartTotal.replace('$', '');
    let totalLocationPageDonations =
        (parseInt(sanitizedGeneralAmount) || 0) +
        (parseInt(sanitizedSpecificAmount) || 0) +
        (parseInt(sanitizedLocationAmount) || 0) +
        (parseInt(sanitizedExpansionAmount) || 0) -
        (parseInt(voucherAmount) || 0);

    let subTotalLocationPageDonations = cartSubTotal.replace('$', '');

    if ((!isNaN(totalLocationPageDonations)) || voucherAmount > 0 || voucherAmount !== '' || voucherAmount !== 0) {
        if (totalLocationPageDonations < 0) {
            totalLocationPageDonations = 0;
        }
        if (totalLocationPageDonations === 0 && (voucherAmount === 0 || voucherAmount === '')) {
            rightPanelTotalDonationsElement.hide();
            console.log('Total Donations[Location Tab] is zero without voucher!');
            return;
        }

        eleRightPanelTotalDonationsValue.text('$' + new Intl.NumberFormat().format(totalLocationPageDonations));
        if (subTotalLocationPageDonations <= 0) {
            subTotalLocationPageDonations = totalLocationPageDonations;
        }
        document.cookie = ckTotalDonations + '=' + totalLocationPageDonations + cookiePath;
        rightPanelTotalDonationsElement.show();
        eleRightPanelSubTotalDonationsValue.text(subTotalLocationPageDonations);
        //eleRightPanelSubTotalDonationsValue.text('$' + new Intl.NumberFormat().format(subTotalLocationPageDonations));
        document.cookie = ckSubTotalDonations + '=' + subTotalLocationPageDonations + cookiePath;
        // console.log('subTotal Donations[Location Tab]:' + subTotalLocationPageDonations);
        eleRightPanelSubTotalDonationsValue.show();

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
        //     '\n\tvoucherAmount[' + parseInt(voucherAmount) + ']' +
        //     '\n\ttotalLocationPageDonations: [' + totalLocationPageDonations + ']'
        //     '\n\tsubTotalLocationPageDonations: [' + subTotalLocationPageDonations + ']'
        // );
    } else {
        rightPanelTotalDonationsElement.hide();
        console.log('Total Donations[Location Tab]:' + totalLocationPageDonations);
    }
}

// UPDATE RIGHT PANEL
function updateRightPanel(sectionName) {
    locationZipCodeValue = getCookie(ckLocationZipCodeNumber);
    locationDonationAmountValue = getCookie(ckLocationDonationAmount);
    let page1Data = {
        generalProductID: 23603,
        expansionProductID: 55946,
        locationProductID: 56180,
        donate_anonymously: donateAnonymously,
        generalDonationAmount: generalDonationAmountValue,
        expansionDonationAmount: expansionDonationAmountValue,
        locationZipCodeNumber: locationZipCodeValue,
        locationDonationAmount: locationDonationAmountValue,
        totalDonationAmount: totalDonationAmountValue,
        donation_type: locationDonationTypeValue,
    };
    let jsonPage1Data = JSON.stringify(page1Data);

    if (locationZipCodeValue > 0 && locationDonationAmountValue > 0) {
        // console.log('Location Zip Code ['+locationZipCodeValue+'] and Amount ['+locationDonationAmountValue+'] are set.');
        jQuery.ajax({
            type: 'POST',
            url: ajaxurl,
            data: {
                action: 'location_checkout_action',
                location_form_page_1_data: jsonPage1Data,
                security: nonce,
            },
            success: function (response) {

                if (response.success) {
                    // console.log('Success_P1: \n' + JSON.stringify(response));
                    cartSubTotal = response.data.cart_subtotal;
                    updateRightPanelTotalDonations();
                    jQuery('.widget_shopping_cart_content').html(response.data.mini_cart);
                    jQuery(document.body).trigger('update_checkout');
                    jQuery(document.body).trigger('wc_fragments_refreshed');
                    if (jQuery('.clear-all-wrapper a').length == 0 && cartSubTotal != '$0') {
                        jQuery('.clear-all-wrapper').append('<a href="javascript:;">Clear All</a>')
                    }

                    updateRemoveButtons();

                } else {
                    console.log('Failed_P1: \n' + JSON.stringify(response));
                }

                hideAdditionalPaymentMethod();
            }
        });
    }

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
            voucherCode = getCookie(ckVoucherCode);
            voucherAmount = getCookie(ckVoucherAmount);

            let voucherAmountNoSign = voucherAmount.replace('$', '');

            if (eleRightPanelSpecificValue.text() === '$0') {
                if (voucherAmount === '') {
                    voucherAmount = 0;
                }
                console.log('cartTotal: ' + specificDonationAmountValue + ' voucherAmount: ' + voucherAmountNoSign);
                // if (voucherAmountNoSign > 0) {
                //     specificDonationAmountValue = parseInt(specificDonationAmountValue) + parseInt(voucherAmountNoSign);
                // }

                specificDonationType = getCookie('ckSpecificDonationType');
                if (cartContents.length <= 0) {
                    console.log('Cart is empty. No need to populate specific cart panel.');
                    rightPanelSpecificElement.hide();
                } else if (specificDonationAmountValue > 0) {
                    // CART TOTAL UPDATE SPECIFIC TAB
                    console.log('Cart has [' + cartContents.length + '] item(s)!');
                    if (String(specificDonationAmountValue).includes('$')) {
                        eleRightPanelSpecificValue.text(specificDonationAmountValue);
                    } else {
                        eleRightPanelSpecificValue.text('$' + new Intl.NumberFormat().format(specificDonationAmountValue));
                    }

                    if (specificDonationType) {
                        eleRightPanelSpecificType.text(specificDonationType);
                    } else {
                        specificDonationType = 'One-Time';
                        document.cookie = ckSpecificDonationType + '=' + specificDonationType + cookiePath;
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
            hideAdditionalPaymentMethod();
            return;
    }
    updateRightPanelTotalDonations();
    hideAdditionalPaymentMethod();
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

// HIDE PAYMENT FORM IF TOTAL EQUALS ZERO
function hideAdditionalPaymentMethod(){
    let rightPanelTotal = document.querySelector('#right_panel_total_donation_value span').innerHTML.replace('$','');
    
    let subtotal = +getCookie(ckSubTotalDonations) - +getCookie(ckVoucherAmount);
    let total = (+rightPanelTotal == subtotal) ? subtotal : +rightPanelTotal;
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


// ALL TABS CLICK HANDLER
jQuery(document).ready(function ($) {
    let currentTabId = 'e-n-tabs-title-1523';
    function handleTabClick(tabId) {

        // get total number of items from cartcontents
        // let totalItemsInCart = cartContents.length;
        // // check if ckFundAllCandidatesStatus cookie is true then don't populate the main cart panel
        // if (window.location.href.indexOf('fund_all=true') > -1 ||
        //     getCookie('ckFundAllCandidatesStatus') === 'true') {
        //     if (totalItemsInCart > 500) {
        //         console.log('fund_all=true -> redirecting to specific tab...');
        //         window.location.href = '/checkout/?funds=specific&fund_all=true';
        //         return;
        //     }
        // }

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
        }, 1000);

    }
    // For All Donation tabs click event listner
    jQuery('.e-n-tabs-heading').on('click',
        '#e-n-tabs-title-1521, #e-n-tabs-title-1522, #e-n-tabs-title-1523, #e-n-tabs-title-1524',
        function () {

            // OVERFLOW CONTAINER TOGGLE
            overlayToggle();

            let tabId = jQuery(this).attr('id');

            // DO NOT DELETE/COMMENT THE FOLLOWING TWO LINES OF CODE: it removes the css glitch when clicked on the tab item.
            jQuery('#e-n-tabs-title-1521, #e-n-tabs-title-1522, #e-n-tabs-title-1523, #e-n-tabs-title-1524')
                .attr('aria-selected', 'false').attr('tabindex', '-1');
            // jQuery('#e-n-tab-content-1521, #e-n-tab-content-1522, #e-n-tab-content-1523, #e-n-tab-content-1524')
            jQuery('#e-n-tab-content-1521, #e-n-tab-content-1522, #e-n-tab-content-1524')
                .hide();
            // END OF DO NOT DELETE/COMMENT THE FOLLOWING TWO LINES OF CODE

            // Check if location amount is filled and if not, then show alert message if required
            locationZipValueAmountEditor = $('#field_zip-code-number');
            if (locationDonationAmountEditor.val() > 0
                && (locationZipValueAmountEditor.val() === '' || locationZipValueAmountEditor.val() <= 0)) {

                overlayToggle();
                console.log('Location Zip Code is not set.');
                locationZipCodeValue = '';
                locationZipValueAmountEditor.val(locationZipCodeValue);
                document.cookie = ckLocationZipCodeNumber + '=' + locationZipCodeValue + cookiePath;

                jQuery('#field_zip-code-number_label').css({ 'color': 'red', 'font-weight': 'bold' });
                // Scroll the page to the target element
                let targetPosition = jQuery('#field_zip-code-number_label').offset().top;
                targetPosition -= 300;
                jQuery('html, body').animate({
                    scrollTop: targetPosition
                }, 500);

                console.log('REQUIRED: Location Zip Code is not set.');

                // stay on location tab and show the tab content.
                jQuery('#e-n-tabs-title-1523').attr('aria-selected', 'true').attr('tabindex', '0');
                jQuery('#e-n-tab-content-1523').show();

                return false;
            }
            else {
                jQuery('#field_zip-code-number_label').css({ 'color': '#000', 'font-weight': 'normal' });
                
            }

            if (tabId === 'e-n-tabs-title-1521') {
                window.location.href = '/checkout/?funds=specific';
            }
            else if (tabId === 'e-n-tabs-title-1522') {
                window.location.href = '/checkout/?funds=general';
            }
            else if (tabId === 'e-n-tabs-title-1523') {
                window.location.href = '/checkout/?funds=location';
            }
            else if (tabId === 'e-n-tabs-title-1524') {
                window.location.href = '/checkout/?funds=expansion';
            }

            // return false so that tab switching does not happen before page load.
            return false;
        });

    // ON PAGE LOAD: CHECK WHICH TAB TO SHOW ON PAGE LOAD
    if (is_location_funds_tab_selected) {
        handleTabClick(currentTabId);
    }
});

// CANDIDATES DONATION AMOUNT RIGHT PANEL HANDLER
jQuery(document).ready(function ($) {
    specificDonationAmountValue = getCookie('ckSpecificDonationTotalAmount');
    specificDonationType = getCookie('ckSpecificDonationType');
    updateRightPanel(rightPanelSummarySectionsList.candidates);

    if (getCookie(ckVoucherCode)) {
        updateRightPanel(rightPanelSummarySectionsList.voucher);
    }
});

// LOCATION DONATION AMOUNT RADIO BUTTONS HANDLER
jQuery(document).ready(function ($) {
    locationZipValueAmountEditor = $('#field_zip-code-number');
    locationZipCodeValue = getCookie(ckLocationZipCodeNumber);
    console.log('Location Zip Cookie Value: ' + locationZipCodeValue);
    locationDonationAmountEditor = $('#field_location_page_donation_amount');
    locationOtherAmountElement = $('#locationPageLocationAmount_other');
    locationDonationAmountValue = getCookie(ckLocationDonationAmount);

    if (locationZipCodeValue > 0) {
        locationZipValueAmountEditor.val(locationZipCodeValue);
    }
    else if (locationZipValueAmountEditor.val() === '') {
        locationZipCodeValue = '';
        locationZipValueAmountEditor.val(locationZipCodeValue);
        document.cookie = ckLocationZipCodeNumber + '=' + locationZipCodeValue + cookiePath;
    }

    const radioButtonsLocationDonationAmounts = document.querySelectorAll('input[name="locationPageLocationDonationAmount"]');
    // console.log('OnLoad-> Location Donation Cookie Amount: ' + locationDonationAmountValue);
    if (locationDonationAmountValue > 0) {
        for (let i = 0; i < radioButtonsLocationDonationAmounts.length; i++) {
            const buttonLocation = radioButtonsLocationDonationAmounts[i];

            if (buttonLocation.value === locationDonationAmountValue && buttonLocation.id.includes(locationDonationAmountValue)) {
                buttonLocation.checked = true;
                // console.log('In Button Value: ' + buttonLocation.value);
                // console.log('In Button ID: ' + buttonLocation.id);
                locationDonationAmountEditor.val(new Intl.NumberFormat().format(buttonLocation.value));
                break; // Exit the loop
            } else if (locationDonationAmountValue !== 0 && buttonLocation.id.includes(locationOtherAmountElement.attr('id'))) {
                // console.log('Out Button Value: ' + buttonLocation.value);
                // console.log('Out Button Amount: ' + locationDonationAmountValue);
                // console.log('Out Button ID: ' + buttonLocation.id);
                locationDonationAmountEditor.attr('readonly', false);
                locationDonationAmountEditor.val(new Intl.NumberFormat().format(locationDonationAmountValue));
                locationOtherAmountElement.click();
            } else {
                buttonLocation.checked = false;
            }
        }
        updateRightPanel(rightPanelSummarySectionsList.locations);
    }
    else {
        locationDonationAmountValue = 0;
        radioButtonsLocationDonationAmounts.forEach(button => {
            button.checked = false;
            locationDonationAmountEditor.val('');
        });
        updateRightPanel(rightPanelSummarySectionsList.locations);
    }

    // If clicked on 'Other Amount' input radio button
    locationOtherAmountElement.change(function () {
        locationDonationAmountEditor.attr('readonly', false).click().focus().attr('placeholder', 'Enter Amount');
        locationDonationAmountEditor.val(sanitizeLocationPageAmount(locationDonationAmountEditor.val()));
    });

    jQuery(locationZipValueAmountEditor).on('input', function (event) {
        if (event.which === 13) {
            event.preventDefault();
        }

        // If label color is red then change it back to normal (black)
        jQuery('#field_zip-code-number_label span').css({ 'color': '#000', 'font-weight': 'normal' });

        // Mask the input field to not allow to type any non-numeric characters
        let inputValue = $(this).val();
        let numericValue = inputValue.replace(/[^0-9]/g, '');
        $(this).val(numericValue);

        document.cookie = ckLocationZipCodeNumber + '=' + sanitizeLocationPageAmount(numericValue) + cookiePath;
        if (getCookie(ckLocationDonationAmount) > 0) {
            document.cookie = ckLocationDonationYesNo + '=Yes' + cookiePath;
        }
        updateRightPanel(rightPanelSummarySectionsList.locations);
    });

    jQuery(locationDonationAmountEditor).on('input', function (event) {
        if (event.which === 13) {
            event.preventDefault();
        }

        // If label color is red then change it back to normal (black)
        jQuery('#field_location_page_donation_amount_label span').css({ 'color': '#000', 'font-weight': 'normal' });

        // Mask the input field to not allow to type any non-numeric characters
        let inputValue = $(this).val();
        let numericValue = inputValue.replace(/[^0-9]/g, '');
        $(this).val(numericValue);

        locationOtherAmountElement.click();

        document.cookie = ckLocationDonationAmount + '=' + sanitizeLocationPageAmount(numericValue) + cookiePath;
        updateRightPanel(rightPanelSummarySectionsList.locations);
    });

    // Add click event listener to each donation amount radio button
    radioButtonsLocationDonationAmounts.forEach(button => {
        button.addEventListener('click', () => {
            let selectedLocationValue = button.value;
            if (button.id !== locationOtherAmountElement.attr('id')) {
                let formattedLocationValue = new Intl.NumberFormat().format(selectedLocationValue);
                locationDonationAmountEditor.val(formattedLocationValue);
                locationDonationAmountValue = formattedLocationValue;
                document.cookie = ckLocationDonationAmount + '=' + selectedLocationValue + cookiePath;
            } else {
                selectedLocationValue = locationDonationAmountEditor.val();
                locationDonationAmountValue = selectedLocationValue;
                document.cookie = ckLocationDonationAmount + '=' + selectedLocationValue + cookiePath;
            }
            
            document.cookie = ckLocationDonationType + '=' + locationDonationTypeValue + cookiePath;
            if (locationZipCodeValue > 0) {
                document.cookie = ckLocationDonationYesNo + '=Yes' + cookiePath;
            }
            updateRightPanel(rightPanelSummarySectionsList.locations);
            jQuery( document.body ).trigger( 'update_checkout' );
            
         
        });
    });
});

// LOCATION DONATION TYPE BUTTON HANDLER
jQuery(document).ready(function ($) {
    locationDonationTypeElement = jQuery('#frm_field_377_container .frm_opt_container input');
    locationDonationTypeValue = getCookie(ckLocationDonationType);

    // on-page load, check if cookie is set for donation type
    if (locationDonationTypeValue === 'One-Time') {
        locationDonationTypeElement.filter(':first').parent().click().prop('checked', true);
    }
    else if (locationDonationTypeValue === 'Monthly') {
        // If monthly donation type is selected, then set ckCreateDonorAccountYesNo cookie to 'Yes'
        document.cookie = ckCreateDonorAccountYesNo + '=Yes' + cookiePath;
        locationDonationTypeElement.filter(':last').parent().click().prop('checked', true);
    }
    else {
        document.cookie = ckLocationDonationType + '=One-Time' + cookiePath;
        console.log('LocationDonationType cookie not set. Defaulting to: ' + locationDonationTypeValue);
    }
    locationDonationTypeElement.filter(':checked').parent().css({
        'background-color': '#143A62',
        'color': '#fff',
    });
    console.log('LocationDonationType cookie value: ' + locationDonationTypeValue);

    locationDonationTypeElement.on('click', function () {
        locationDonationTypeElement.parent().css({
            'background-color': '#fff',
            'color': '#143A62',
        });
        $(this).parent().css({
            'background-color': '#143A62',
            'color': '#fff',
        });
        locationDonationTypeValue = jQuery(this).val();
        if (locationDonationTypeValue.toLowerCase() === 'monthly') {
            document.cookie = ckCreateDonorAccountYesNo + '=Yes' + cookiePath;
        }
        else {
            document.cookie = ckCreateDonorAccountYesNo + '=No' + cookiePath;
        }
        document.cookie = ckLocationDonationType + '=' + locationDonationTypeValue + cookiePath;
        updateRightPanel(rightPanelSummarySectionsList.locations);
        // updateRightPanel(rightPanelSummarySectionsList.expansion);
        // updateRightPanel(rightPanelSummarySectionsList.general);
    });

});

// GENERAL DONATION AMOUNT RADIO BUTTONS HANDLER
jQuery(document).ready(function ($) {
    generalDonationAmountEditor = $('#field_location_general_donation_amount');
    generalOtherAmountElement = $('#locationPageGeneralAmount_other');
    generalDonationAmountValue = sanitizeLocationPageAmount(getCookie(ckGeneralDonationAmount));
    generalDonationYesNo = getCookie(ckGeneralDonationYesNo);
    generalDonationTypeValue = getCookie(ckGeneralDonationType);

    const radioButtonsGeneralDonationAmounts = document.querySelectorAll('input[name="locationPageGeneralDonationAmount"]');

    // on load general radio buttons other than the selected one
    if (generalDonationAmountValue > 0) {
        if (generalDonationYesNo === 'Yes') {
            jQuery('#field_fp9k8-0').click().prop('checked', true);

            radioButtonsGeneralDonationAmounts.forEach(button => {
                if (button.value === generalDonationAmountValue) {
                    button.checked = true;
                    generalDonationAmountEditor.val(button.value);
                    // console.log('In genButton Value: ' + button.value + '\tID: ' + button.id);
                } else if (generalDonationAmountEditor.val() !== generalDonationAmountValue && button.id === generalOtherAmountElement.attr('id')) {
                    generalOtherAmountElement.click();
                    generalDonationAmountEditor.val(generalDonationAmountValue);
                    // console.log('Out genButton Value: ' + button.value + '\tID: ' + button.id);
                } else {
                    button.checked = false;
                }
            });
        }
        else {
            generalDonationYesNo = 'Yes';
            document.cookie = ckGeneralDonationYesNo + '=' + generalDonationYesNo + cookiePath;
            generalDonationAmountEditor.val(generalDonationAmountValue);
            jQuery('#field_fp9k8-0').click().prop('checked', true);
        }
        updateRightPanel(rightPanelSummarySectionsList.general);
    }
    else {
        generalDonationYesNo = 'No';
        setTimeout(function () {
            jQuery('#field_fp9k8-0').click().prop('checked', false);
            jQuery('#field_fp9k8-1').click().prop('checked', true);
        }, 1000);
        radioButtonsGeneralDonationAmounts.forEach(button => {
            button.checked = false;
        });
        generalDonationAmountEditor.val('');
        document.cookie = ckGeneralDonationYesNo + '=' + generalDonationYesNo + cookiePath;
        document.cookie = ckGeneralDonationAmount + '=' + '' + cookiePath;
        updateRightPanel(rightPanelSummarySectionsList.general);
    }

    // General Donation Yes/No radio button change event handler
    jQuery(document).on('change', '#field_fp9k8-0, #field_fp9k8-1', function () {
        generalDonationYesNo = jQuery(this).val();
        document.cookie = ckGeneralDonationYesNo + '=' + generalDonationYesNo + cookiePath;
        if (generalDonationYesNo === 'No') {
            jQuery('#remove_general_panel').trigger('click');
            generalDonationAmountEditor.val('');
            document.cookie = ckGeneralDonationAmount + '=' + '' + cookiePath;
            document.cookie = ckGeneralDonationType + '=' + 'One-Time' + cookiePath;
            console.log('expansion amount: ' + getCookie(ckExpansionDonationAmount) + ' type: ' + getCookie(ckExpansionDonationType));
            console.log('location amount: ' + getCookie(ckLocationDonationAmount) + ' type: ' + getCookie(ckLocationDonationType));
            if (getCookie(ckExpansionDonationType).toLowerCase() === 'one-time' && getCookie(ckLocationDonationType).toLowerCase() === 'one-time') {
                document.cookie = ckCreateDonorAccountYesNo + '=No' + cookiePath;
                console.log('ckCreateDonorAccountYesNo: ' + getCookie(ckCreateDonorAccountYesNo));
            }
            radioButtonsGeneralDonationAmounts.forEach(button => {
                button.checked = false;
            });
        }
        updateRightPanel(rightPanelSummarySectionsList.general);
    });

    // If clicked on general 'Other Amount' input radio button
    generalOtherAmountElement.change(function () {
        // remove attribute 'readonly' from the input element
        generalDonationAmountEditor.attr('readonly', false).click().focus().attr('placeholder', 'Enter Amount');
        generalDonationAmountEditor.val(sanitizeLocationPageAmount(generalDonationAmountEditor.val()));
    });
    // if typing amount manually while in other amount, update right panel and cookie
    jQuery(generalDonationAmountEditor).on('input', function (event) {
        if (event.which === 13) {
            event.preventDefault();
        }

        // If label color is red then change it back to normal (black)
        jQuery('#field_location_general_donation_amount_label').css({ 'color': '#000', 'font-weight': 'normal' });

        // Mask the input field to not allow to type any non-numeric characters
        let inputValue = $(this).val();
        let numericValue = inputValue.replace(/[^0-9]/g, '');
        $(this).val(numericValue);

        generalOtherAmountElement.click();

        document.cookie = ckGeneralDonationAmount + '=' + sanitizeLocationPageAmount(numericValue) + cookiePath;
        updateRightPanel(rightPanelSummarySectionsList.general);
    });

    // Add event listener to each donation amount radio button
    radioButtonsGeneralDonationAmounts.forEach(button => {
        button.addEventListener('click', () => {
            let selectedGeneralValue = button.value;
            if (button.id !== generalOtherAmountElement.attr('id')) {
                let formattedGeneralValue = new Intl.NumberFormat().format(selectedGeneralValue);
                generalDonationAmountEditor.val(formattedGeneralValue);
                generalDonationAmountValue = formattedGeneralValue;
                document.cookie = ckGeneralDonationAmount + '=' + '' + cookiePath;
                document.cookie = ckGeneralDonationAmount + '=' + selectedGeneralValue + cookiePath;
                updateRightPanel(rightPanelSummarySectionsList.general);
            } else {
                selectedGeneralValue = generalDonationAmountEditor.val();
                generalDonationAmountValue = selectedGeneralValue;
                document.cookie = ckGeneralDonationAmount + '=' + '' + cookiePath;
                document.cookie = ckGeneralDonationAmount + '=' + selectedGeneralValue + cookiePath;
                updateRightPanel(rightPanelSummarySectionsList.general);
            }
            jQuery(document.body).trigger('wc_fragment_refresh');
        });
    });
});

// GENERAL DONATION TYPE BUTTON HANDLER
jQuery(document).ready(function ($) {
    generalDonationTypeElement = jQuery('#frm_field_450_container .frm_opt_container input');
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
        console.log('ckGeneralDonationType cookie not found, setting to default: ' + generalDonationTypeValue);
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

// REMOVE RIGHT PANEL ON BUTTON CLICK HANDLER
jQuery(function ($) {


    jQuery(document).on('click', '#remove_general_panel', function () {
        console.log('Removing General Panel...');
        radioButtonsGeneralDonationAmounts =
            document.querySelectorAll('input[name="locationPageGeneralDonationAmount"]');
        radioButtonsGeneralDonationAmounts.forEach(button => {
            button.checked = false;
        });
        $('#field_fp9k8-1').trigger('click');
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
        } else {
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

                        $(document.body).trigger('wc_fragments_refreshed');

                    }
                }
            });
        }
        updateRightPanel(rightPanelSummarySectionsList.general);
    });
    jQuery(document).on('click', '#remove_expansion_panel', function () {
        console.log('Removing Expansion Panel...');
        radioButtonsExpansionDonationAmounts =
            document.querySelectorAll('input[name="locationPageExpansionDonationAmount"]');
        radioButtonsExpansionDonationAmounts.forEach(button => {
            button.checked = false;
        });
        $('#field_9231r-1').trigger('click');
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
        } else {
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
        }

        updateRightPanel(rightPanelSummarySectionsList.expansion);
    });
    jQuery(document).on('click', '#remove_location_panel', function () {
        console.log('Removing Location Panel...');
        radioButtonsLocationDonationAmounts =
            document.querySelectorAll('input[name="locationPageExpansionDonationAmount"]');
        radioButtonsLocationDonationAmounts.forEach(button => {
            button.checked = false;
        });
        // $('#field_fp9k8-1').trigger('click');
        locationDonationAmountValue = '';
        locationDonationAmountEditor.val(locationDonationAmountValue);
        locationZipCodeValue = '';
        locationZipValueAmountEditor.val(locationZipCodeValue);
        locationDonationYesNo = 'No';
        document.cookie = ckLocationDonationAmount + '=' + locationDonationAmountValue + cookiePath;
        document.cookie = ckLocationZipCodeNumber + '=' + locationZipCodeValue + cookiePath;
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
        } else {
            let cartItemKey = $('.elementor-menu-cart__product-remove .elementor_remove_from_cart_button[data-product_id=56180]').data('cart_item_key');

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
        }
        jQuery('#frm_field_375_container input[type="radio"]').each(function () {
            jQuery(this).prop('checked', false);
        })
        updateRightPanel(rightPanelSummarySectionsList.locations);

    });
});

// EXPANSION DONATION AMOUNT RADIO BUTTONS HANDLER
jQuery(document).ready(function ($) {
    expansionDonationAmountEditor = $('#field_location_expansion_donation_amount');
    expansionOtherAmountElement = $('#locationPageExpansionAmount_other');
    expansionDonationAmountValue = sanitizeLocationPageAmount(getCookie(ckExpansionDonationAmount));
    expansionDonationYesNo = getCookie(ckExpansionDonationYesNo);
    expansionDonationTypeValue = getCookie(ckExpansionDonationType);

    const radioButtonsExpansionDonationAmounts = document.querySelectorAll('input[name="locationPageExpansionDonationAmount"]');

    // on page load unchecked all expansion radio buttons other than the selected one
    if (expansionDonationAmountValue > 0) {
        if (expansionDonationYesNo === 'Yes') {
            jQuery('#field_9231r-0').click().prop('checked', true);

            for (let i = 0; i < radioButtonsExpansionDonationAmounts.length; i++) {
                const buttonExpansion = radioButtonsExpansionDonationAmounts[i];

                if (buttonExpansion.value === expansionDonationAmountValue && buttonExpansion.id.includes(expansionDonationAmountValue)) {
                    buttonExpansion.checked = true;
                    console.log('In expButton Value: ' + buttonExpansion.value + '\tID: ' + buttonExpansion.id);
                    expansionDonationAmountEditor.val(new Intl.NumberFormat().format(buttonExpansion.value));
                    break; // Exit the loop
                } else if (expansionDonationAmountValue !== 0 && buttonExpansion.id.includes(expansionOtherAmountElement.attr('id'))) {
                    console.log('Out expButton Value: ' + buttonExpansion.value + '\tAmount: ' + expansionDonationAmountValue + '\tID: ' + buttonExpansion.id);
                    expansionDonationAmountEditor.val(new Intl.NumberFormat().format(expansionDonationAmountValue));
                    expansionOtherAmountElement.click();
                } else {
                    buttonExpansion.checked = false;
                }
            }
        }
        else {
            expansionDonationYesNo = 'Yes';
            expansionDonationAmountEditor.val(expansionDonationAmountValue);
            jQuery('#field_9231r-0').click().prop('checked', true);
            document.cookie = ckExpansionDonationYesNo + '=' + expansionDonationYesNo + cookiePath;
            document.cookie = ckExpansionDonationAmount + '=' + expansionDonationAmountValue + cookiePath;
        }
        updateRightPanel(rightPanelSummarySectionsList.expansion);
    }
    else {
        expansionDonationYesNo = 'No';
        radioButtonsExpansionDonationAmounts.forEach(button => {
            button.checked = false;
        });
        expansionDonationAmountEditor.val('');
        document.cookie = ckExpansionDonationYesNo + '=' + expansionDonationYesNo + cookiePath;
        document.cookie = ckExpansionDonationAmount + '=' + '' + cookiePath;
        setTimeout(function () {
            jQuery('#field_9231r-0').click().prop('checked', false);
            jQuery('#field_9231r-1').click().prop('checked', true);
        }, 1000);
        console.log('Expansion Donation Amount is not set.')
        updateRightPanel(rightPanelSummarySectionsList.expansion);
    }

    // Expansion Donation Yes/No radio button change event handler
    jQuery(document).on('change', '#field_9231r-0, #field_9231r-1', function () {
        expansionDonationYesNo = jQuery(this).val();
        document.cookie = ckExpansionDonationYesNo + '=' + expansionDonationYesNo + cookiePath;
        if (expansionDonationYesNo === 'No') {
            jQuery('#remove_expansion_panel').trigger('click');
            expansionDonationAmountEditor.val('');
            document.cookie = ckExpansionDonationAmount + '=' + '' + cookiePath;
            console.log('general amount: ' + getCookie(generalDonationAmountValue) + ' type: ' + getCookie(ckGeneralDonationType));
            console.log('location amount: ' + getCookie(ckLocationDonationAmount) + ' type: ' + getCookie(ckLocationDonationType));
            if (getCookie(ckGeneralDonationType).toLowerCase() === 'one-time' && getCookie(ckLocationDonationType).toLowerCase() === 'one-time') {
                document.cookie = ckCreateDonorAccountYesNo + '=No' + cookiePath;
                console.log('ckCreateDonorAccountYesNo: ' + getCookie(ckCreateDonorAccountYesNo));
            }
            radioButtonsExpansionDonationAmounts.forEach(button => {
                button.checked = false;
            });
        }
        updateRightPanel(rightPanelSummarySectionsList.expansion);
    });

    expansionOtherAmountElement.change(function () {
        expansionDonationAmountEditor.attr('readonly', false).click().focus().attr('placeholder', 'Enter Amount');
        expansionDonationAmountEditor.val(sanitizeLocationPageAmount(expansionDonationAmountEditor.val()));
    });
    jQuery(expansionDonationAmountEditor).on('input', function (event) {
        if (event.which === 13) {
            event.preventDefault();
        }

        // If label color is red then change it back to normal (black)
        jQuery('#field_location_expansion_donation_amount_label').css({ 'color': '#000', 'font-weight': 'normal' });

        // Mask the input field to not allow to type any non-numeric characters
        let inputValue = $(this).val();
        let numericValue = inputValue.replace(/[^0-9]/g, '');
        $(this).val(numericValue);

        expansionOtherAmountElement.click();

        document.cookie = ckExpansionDonationAmount + '=' + sanitizeLocationPageAmount(numericValue) + cookiePath;
        updateRightPanel(rightPanelSummarySectionsList.expansion);
    });

    // Add event listener to each donation amount radio button
    radioButtonsExpansionDonationAmounts.forEach(button => {
        button.addEventListener('click', () => {
            let selectedExpansionValue = button.value;
            if (button.id !== expansionOtherAmountElement.attr('id')) {
                let formattedExpansionValue = new Intl.NumberFormat().format(selectedExpansionValue);
                expansionDonationAmountEditor.val(formattedExpansionValue);
                expansionDonationAmountValue = formattedExpansionValue;
                document.cookie = ckExpansionDonationAmount + '=' + '' + cookiePath;
                document.cookie = ckExpansionDonationAmount + '=' + selectedExpansionValue + cookiePath;
                updateRightPanel(rightPanelSummarySectionsList.expansion);
            } else {
                selectedExpansionValue = expansionDonationAmountEditor.val();
                expansionDonationAmountValue = selectedExpansionValue;
                document.cookie = ckExpansionDonationAmount + '=' + '' + cookiePath;
                document.cookie = ckExpansionDonationAmount + '=' + selectedExpansionValue + cookiePath;
                updateRightPanel(rightPanelSummarySectionsList.expansion);
            }
            jQuery(document.body).trigger('wc_fragment_refresh');
        });
    });
});

// EXPANSION DONATION TYPE BUTTON HANDLER
jQuery(document).ready(function ($) {
    expansionDonationTypeElement = jQuery('#frm_field_451_container .frm_opt_container input');
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

// DONATE ANONYMOUSLY RADIO BUTTON EVENT HANDLER
jQuery(document).ready(function ($) {
    if (getCookie(ckDonateAnonymously)) {
        if (getCookie(ckDonateAnonymously) === 'Yes') {
            donateAnonymously = 'Yes';
            jQuery('#field_donate_anonymously3-0').prop('checked', true);
            jQuery('#donate_anonymously').val('yes');
        } else {
            donateAnonymously = 'No';
            jQuery('#field_donate_anonymously3-1').prop('checked', true);
            jQuery('#donate_anonymously').val('no');
        }
        jQuery(document.body).trigger('update_checkout');
        console.log('DonateAnonymously Cookie Exists. Value: ' + getCookie(ckDonateAnonymously));
    } else {
        donateAnonymously = 'Yes';
        jQuery('#field_donate_anonymously3-0').prop('checked', true);
        document.cookie = ckDonateAnonymously + '=Yes' + cookiePath;
        jQuery(document.body).trigger('update_checkout');
        console.log('DonateAnonymously Cookie Does Not Exist. Setting Default Value to "Yes"');
    }
    jQuery('#field_donate_anonymously3-0, #field_donate_anonymously3-1').on('change', function () {
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

    jQuery('#frm_field_389_container').hide();
    jQuery('#frm_field_390_container').html(
        '<p><span class="reg-ref">Already logged in as: <a href="/' + userDashboard + '/" target="_blank">' + userDisplayName + '</a></span></p>'
    );
}
else {
    if (getCookie(ckCreateDonorAccountYesNo)) {
        if (getCookie(ckCreateDonorAccountYesNo) === 'Yes') {
            createDonorAccountYesNo = 'Yes';
            jQuery('#field_4u47x-0').prop('checked', true);
            jQuery('#field_4u47x-1').prop('disabled', true);
            let createDonorAccountElementOnCheckout = jQuery('#createaccount');
            let notCreateDonorAccountElementOnCheckout =
                jQuery('.woocommerce-form__input.woocommerce-form__input-checkbox.input-checkbox.checkout_createaccount');
            // Check if the element exists in WooCommerce checkout form
            if (createDonorAccountElementOnCheckout) {
                // Check if the checkbox is already checked or not
                if (createDonorAccountElementOnCheckout.is(':checked')) {
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
            jQuery('#field_4u47x-1').prop('disabled', false);
            jQuery('#field_4u47x-1').prop('checked', true);
            jQuery('.woocommerce-account-fields').hide();
        }
        console.log('CreatDonorAccount Cookie Exists. Value: ' + getCookie(ckCreateDonorAccountYesNo));
    }
    else {
        createDonorAccountYesNo = 'No';
        jQuery('#field_4u47x-1').prop('checked', true);
        document.cookie = ckCreateDonorAccountYesNo + '=' + createDonorAccountYesNo + cookiePath;
        console.log('CreatDonorAccount Cookie Does Not Exist. Setting Default Value to [' + createDonorAccountYesNo + ']');
    }
    jQuery(document).on('change', '#field_4u47x-0, #field_4u47x-1, #createaccount', function () {
        let createDonorAccountClickedIdValue = jQuery(this).val();

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
            jQuery('#field_4u47x-0').prop('checked', true);
            // jQuery('#createaccount').click();
            jQuery('.woocommerce-account-fields').show();
        }
        else if (createDonorAccountClickedIdValue === 'No') {
            createDonorAccountYesNo = 'No';
            document.cookie = ckCreateDonorAccountYesNo + '=' + createDonorAccountYesNo + cookiePath;
            jQuery('#field_4u47x-1').prop('checked', true);
            jQuery('.woocommerce-account-fields').hide();
        }
        // console.log('acctCookie: [' + getCookie(ckCreateDonorAccountYesNo) + ']' +
        //     '\tacctClicked: [' + createDonorAccountClickedIdValue + ']');
    });
}

// REFERRAL YES/NO RADIO BUTTON HANDLER
if (getCookie(ckDonationReferralYesNo)) {
    if (getCookie(ckDonationReferralYesNo) === 'Yes') {
        donationReferralYesNo = 'Yes';
        jQuery('#field_tydpw-0').prop('checked', true);
        if (getCookie(ckDonationReferrerDetail) !== '') {
            donationReferrerDetail = getCookie(ckDonationReferrerDetail);
            jQuery('#field_4pocj').val(donationReferrerDetail);
        }
        else {
            console.log('Error-> Referral Cookie Exists. But Referrer Detail Cookie Does Not Exist.')
        }
    } else {
        donationReferralYesNo = 'No';
        jQuery('#field_tydpw-1').prop('checked', true);
    }
    console.log('Referral Cookie Exists. Value: [' + getCookie(ckDonationReferralYesNo)
        + '] Referrer Detail: [' + donationReferrerDetail + ']');
} else {
    donationReferralYesNo = 'No';
    donationReferrerDetail = '';
    jQuery('#field_tydpw-1').prop('checked', true);
    document.cookie = ckDonationReferralYesNo + '=No' + cookiePath;
    console.log('Referral Cookie Does Not Exist. Setting Default Value to [' + donationReferralYesNo + ']');
}
jQuery(document).on('change', '#field_tydpw-0, #field_tydpw-1', function () {
    donationReferralYesNo = jQuery(this).val();
    jQuery(this).prop('checked', true);
    if (jQuery('#field_tydpw-0').is(':checked')) {
        donationReferrerDetail = getCookie(ckDonationReferrerDetail);
        jQuery('#field_4pocj').val(getCookie(ckDonationReferrerDetail));
    } else {
        donationReferrerDetail = '';
        jQuery('#field_4pocj').val('');
    }
    document.cookie = ckDonationReferralYesNo + '=' + donationReferralYesNo + cookiePath;
    console.log('Referral Yes/No: [' + donationReferralYesNo + '] Referrer Detail: [' + donationReferrerDetail + ']');
});
jQuery('#field_4pocj').on('keyup', function (event) {
    if (event.which === 13) {
        event.preventDefault();
    }
    document.cookie = ckDonationReferrerDetail + '=' + jQuery(this).val() + cookiePath;
    donationReferrerDetail = jQuery(this).val();
});

// REMOVE ITEM From Specific Cart -> Event Delegation
jQuery(document).on('click', '#remove-this-cart-item', function (event) {
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

// PAGE-1: HIDE (OPTIONAL) ON MOBILE RESOLUTION
jQuery(document).ready(function($) {
    function checkResolution() {
        const screenWidth = window.innerWidth || document.documentElement.clientWidth || document.body.clientWidth;

        if (screenWidth <= 600) {
            const firstElement = $('#field_fp9k8_label span');
            const secondElement = $('#field_9231r_label span');

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

// PAGE-1: NEXT BUTTON HANDLER
jQuery(document).on('click', '#form_locationspecificdonation .frm_page_num_1 .frm_submit .frm_button_submit', function (event) {
    event.preventDefault();

    locationDonationTypeValue = getCookie(ckLocationDonationType);
    locationZipCodeValue = getCookie(ckLocationZipCodeNumber);
    if (locationZipCodeValue === '' || locationZipCodeValue <= 0) {
        jQuery('#field_zip-code-number_label span').css({ 'color': 'red', 'font-weight': 'bold' });
        // Scroll the page to the target element
        let targetPosition = jQuery('#field_zip-code-number_label span').offset().top;
        targetPosition -= 300;
        jQuery('html, body').animate({
            scrollTop: targetPosition
        }, 500);
        return console.log('REQUIRED: Location Zip Code is not set.');
    }
    else {
        jQuery('#field_zip-code-number_label span').css({ 'color': '#000', 'font-weight': 'normal' });
    }

    // if location donation amount is zero or not set then show error and return
    locationDonationAmountValue = getCookie(ckLocationDonationAmount);
    if (locationDonationAmountValue <= 0 || locationDonationAmountValue === '') {
        jQuery('#field_location_page_donation_amount_label span').css({ 'color': 'red', 'font-weight': 'bold' });
        // Scroll the page to the target element
        let targetPosition = jQuery('#field_location_page_donation_amount_label span').offset().top;
        targetPosition -= 300;
        jQuery('html, body').animate({
            scrollTop: targetPosition
        }, 500);
        return console.log('REQUIRED: Location Donation Amount is not set.');
    }
    else {
        jQuery('#field_location_page_donation_amount_label span').css({ 'color': '#000', 'font-weight': 'normal' });
    }

    // if general donation yes/no is no and amount is zero or not set then show error and return
    generalDonationAmountValue = getCookie(ckGeneralDonationAmount);
    if (generalDonationYesNo === 'Yes' && (generalDonationAmountValue <= 0 || generalDonationAmountValue === '')) {
        jQuery('#field_location_general_donation_amount_label').css({ 'color': 'red', 'font-weight': 'bold' });
        // Scroll the page to the target element
        let targetPosition = jQuery('#field_location_general_donation_amount_label').offset().top;
        targetPosition -= 300;
        jQuery('html, body').animate({
            scrollTop: targetPosition
        }, 500);
        return console.log('REQUIRED: General Donation Amount is not set.');
    }
    else {
        jQuery('#field_location_general_donation_amount_label').css({ 'color': '#000', 'font-weight': 'normal' });
    }
    console.log('generalDonationAmountValue: ' + generalDonationAmountValue);

    // if expansion donation yes/no is no and amount is zero or not set then show error and return
    expansionDonationAmountValue = getCookie(ckExpansionDonationAmount);
    if (expansionDonationYesNo === 'Yes' && (expansionDonationAmountValue <= 0 || expansionDonationAmountValue === '')) {
        jQuery('#field_location_expansion_donation_amount_label').css({ 'color': 'red', 'font-weight': 'bold' });
        // Scroll the page to the target element
        let targetPosition = jQuery('#field_location_expansion_donation_amount_label').offset().top;
        targetPosition -= 300;
        jQuery('html, body').animate({
            scrollTop: targetPosition
        }, 500);
        return console.log('REQUIRED: Expansion Donation Amount is not set.');
    }
    else {
        jQuery('#field_location_expansion_donation_amount_label').css({ 'color': '#000', 'font-weight': 'normal' });
    }

    totalDonationAmountValue = getCookie(ckTotalDonations);

    // remove the commas from the donationAmount value before sending it to the server
    generalDonationAmountValue = sanitizeLocationPageAmount(generalDonationAmountValue);
    expansionDonationAmountValue = sanitizeLocationPageAmount(expansionDonationAmountValue);
    locationDonationAmountValue = sanitizeLocationPageAmount(locationDonationAmountValue);

    generalDonationTypeValue = getCookie(ckGeneralDonationType);
    expansionDonationTypeValue = getCookie(ckExpansionDonationType);
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
        donation_type: locationDonationTypeValue,
    };
    let jsonPage1Data = JSON.stringify(page1Data);
    // console.log('[jsonPage1Data]=\t' + jsonPage1Data);
    // console.log('nonce: ' + nonce);

    jQuery.ajax({
        type: 'POST',
        url: ajaxurl,
        data: {
            action: 'location_checkout_action',
            location_form_page_1_data: jsonPage1Data,
            security: nonce,
        },
        beforeSend: function () {
            overlayToggle();
        },
        success: function (response) {
            overlayToggle();
            // console.log("Page-1: " + JSON.stringify(response));
            if (response !== 0) {
                jQuery('#form_locationspecificdonation .frm_page_num_1 .frm_submit .frm_button_submit').submit();
            } else {
                console.log('An error occurred.');
            }
            hideAdditionalPaymentMethod();
        },
        error: function (error) {
            console.log('Error: ' + JSON.stringify(error));
            overlayToggle();
        }
    });
});

// PAGE-2: NEXT BUTTON HANDLER
jQuery(document).on('click', '#form_locationspecificdonation .frm_page_num_2 .frm_submit .frm_button_submit', function (event) {
    event.preventDefault();

    let existingErrorMessage = jQuery('#frm_error_field_general_referrer_detail');
    existingErrorMessage.hide();

    let dataPage2 = {
        selectedReferralYesNo: getCookie(ckDonationReferralYesNo),
        donationReferrerDetail: getCookie(ckDonationReferrerDetail),
    };
    let jsonDataPage2 = JSON.stringify(dataPage2);

    jQuery.ajax({
        type: 'POST',
        url: ajaxurl,
        data: {
            action: 'location_checkout_action',
            location_form_page_2_data: jsonDataPage2,
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
                        window.history.replaceState(null, null, '?funds=location&ref=' + response.data.donationReferrerDetail);
                        jQuery('#form_locationspecificdonation .frm_page_num_2 .frm_submit .frm_button_submit').submit();
                    } else {
                        jQuery('#form_locationspecificdonation .frm_page_num_2 .frm_submit .frm_button_submit').submit();
                    }
                } else if (response.data.status === 'error') {
                    if (existingErrorMessage.length > 0) {
                        existingErrorMessage.remove();
                    }
                    let errorMessage = jQuery(
                        '<div class="frm_error" role="alert" id="frm_error_field_general_referrer_detail" ' +
                        'style="display:inline;">' + response.data.message + '</div>'
                    );
                    errorMessage.insertBefore(jQuery('#field_4pocj'), jQuery('#field_donation_referrer_detail_container'));
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

// PAGE-3: FINAL SUBMISSION LOCATION DONATION FORM
jQuery(document).on('click', '#form_locationspecificdonation .frm_button_submit.frm_final_submit', function (event) {
    event.preventDefault();
    // clear error message fields
    let errorMessageField = jQuery('#field_c2l0a_label span');
    let errorMessageContainer = jQuery('#frm_field_438_container');
    if (jQuery('#field_c2l0a-0').is(':checked')) {
        errorMessageField.text('');
        errorMessageContainer.css({ 'border': '0', 'padding': '0' });
    }
    else {
        errorMessageField.text('This field cannot be blank.').css('color', 'red');
        errorMessageContainer.css({ 'border': '1px solid red', 'padding': '10px' });
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

// DONOR ACCOUNT TOOLTIP FOR MONTHLY SUBSCRIPTION ON CHECKOUT BILLING FORM
jQuery(document).ready(function () {
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
jQuery(document).ready(function () {
    let ppcButtonWrapper = '.ppc-button-wrapper';
    let ppcButtonWrapperOverlay = '.paypal_button_overlay';
    let paypalRadioButton = '#payment_method_ppcp-gateway';
    let stripeRadioButton = '#payment_method_stripe';
    let donateButton = '.frm_button_submit.frm_final_submit';
    let paymentMethodHeading = '#order_review h3#order_review_heading';
    let termsElement = '#frm_field_438_container';
    let termsCheckBox = '#field_c2l0a-0';

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

});

// NEXT BUTTON HANDLER
jQuery(document).ready(function ($) {

    // Check if the URL matches the specified pattern
    if (window.location.href.indexOf('funds=location') > -1) {
        if ($("#form_locationspecificdonation .frm_submit button").length === 2) {
            $(".frm_submit").addClass("btns");
        }
    }

    

    hideAdditionalPaymentMethod();

});