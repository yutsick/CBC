"use strict";
// GLOBAL VARIABLES
let nonce = obj_expansion.nonce;
let ajaxurl = obj_expansion.ajaxurl;
let userRole = obj_expansion.user_role;
let userDisplayName = obj_expansion.user_display_name;
let cartContents = obj_expansion.cart_contents;
let cartTotal = obj_expansion.cart_total;
let cartSubTotal = obj_expansion.cart_subtotal;
let is_expansion_funds_tab_selected = obj_expansion.is_expansion_funds_tab_selected;
if (is_expansion_funds_tab_selected == null) {
    is_expansion_funds_tab_selected = false;
}
// console.log('[is_expansion_funds_tab_selected]->' + is_expansion_funds_tab_selected);

let voucherAmount = 0, voucherCode = '';
let totalCartPrice = 0;
let donateAnonymously = '';
let donationReferralYesNo, donationReferrerDetail;
let
    expansionDonationAmountEditor,
    expansionDonationAmountValue,
    expansionOtherAmountElement,
    expansionDonationTypeElement,
    expansionDonationTypeValue,
    totalDonationAmountValue,
    createDonorAccountYesNo,
    expansionDonationYesNo,
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
    locationDonationAmountValue = 0,
    locationDonationTypeValue,
    locationDonationZipCodeNumber,
    locationDonationYesNo,
    radioButtonsLocationDonationAmounts
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
    eleRightPanelExpansionValue = jQuery('#right_panel_expansion_donation_amount span'),
    eleRightPanelExpansionType = jQuery('#right_panel_expansion_donation_type span'),
    eleRightPanelGeneralValue = jQuery('#right_panel_general_donation_amount span'),
    eleRightPanelGeneralType = jQuery('#right_panel_general_donation_type span'),
    eleRightPanelLocationZipValue = jQuery('#right_panel_location_donation_zip_number span'),
    eleRightPanelLocationValue = jQuery('#right_panel_location_donation_amount span'),
    eleRightPanelLocationType = jQuery('#right_panel_location_donation_type span'),
    eleRightPanelSpecificValue = jQuery('#specific_candidates_total_amount span'),
    eleRightPanelSpecificType = jQuery('.elementor-element-d724586 span'),
    eleRightPanelVoucherCode = jQuery('#right_panel_voucher_code span'),
    eleRightPanelVoucherValue = jQuery('#right_panel_voucher_amount span'),
    eleRightPanelTotalDonationsValue = jQuery('#right_panel_total_donation_value span'),
    eleRightPanelSubTotalDonationsValue = jQuery('#right_panel_subtotal_donation_value span')
;

let
    cookiePath = '; path=/;',
    ckVoucherAmount = 'ckVoucherAmount',
    ckVoucherCode = 'ckVoucherCode',
    ckExpansionDonationAmount = 'ckExpansionDonationAmount',
    ckExpansionDonationType = 'ckExpansionDonationType',
    ckExpansionDonationYesNo = 'ckExpansionDonationYesNo',
    ckGeneralDonationYesNo = 'ckGeneralDonationYesNo',
    ckGeneralDonationAmount = 'ckGeneralDonationAmount',
    ckGeneralDonationType = 'ckGeneralDonationType',
    ckLocationZipCodeNumber = 'ckLocationZipCodeNumber',
    ckLocationDonationAmount = 'ckLocationDonationAmount',
    ckLocationDonationType = 'ckLocationDonationType',
    ckLocationDonationYesNo = 'ckLocationDonationYesNo',
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
function sanitizeExpansionAmount(amount) {
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
    const sanitizedExpansionPageGeneralAmount = sanitizeExpansionAmount(String(generalDonationAmountValue));
    const sanitizedExpansionPageSpecificAmount = sanitizeExpansionAmount(String(specificDonationAmountValue));
    const sanitizedExpansionPageLocationAmount = sanitizeExpansionAmount(String(locationDonationAmountValue));
    const sanitizedExpansionPageExpansionAmount = sanitizeExpansionAmount(String(expansionDonationAmountValue));

    let totalExpansionPageDonations =
        (parseInt(sanitizedExpansionPageGeneralAmount) || 0) +
        (parseInt(sanitizedExpansionPageSpecificAmount) || 0) +
        (parseInt(sanitizedExpansionPageLocationAmount) || 0) +
        (parseInt(sanitizedExpansionPageExpansionAmount) || 0) -
        (parseInt(voucherAmount) || 0);

    let subTotalExpansionPageDonations = cartSubTotal.replace('$', '');

    if ((!isNaN(totalExpansionPageDonations)) || voucherAmount > 0 || voucherAmount !== '' || voucherAmount !== 0) {
        if (totalExpansionPageDonations < 0) {
            totalExpansionPageDonations = 0;
        }
        if (totalExpansionPageDonations === 0 && (voucherAmount === 0 || voucherAmount === '')) {
            rightPanelTotalDonationsElement.hide();
            console.log('Total Donations[Expansion Tab] is zero without voucher!');
            return;
        }

        let formattedTotalExpansionPageDonations = new Intl.NumberFormat().format(totalExpansionPageDonations);
        eleRightPanelTotalDonationsValue.text('$' + formattedTotalExpansionPageDonations);
        if (subTotalExpansionPageDonations <= 0) {
            subTotalExpansionPageDonations = totalExpansionPageDonations;
        }
        document.cookie = ckTotalDonations + '=' + totalExpansionPageDonations + cookiePath;
        rightPanelTotalDonationsElement.show();

        eleRightPanelSubTotalDonationsValue.text('$' + new Intl.NumberFormat().format(subTotalExpansionPageDonations));
        document.cookie = ckSubTotalDonations + '=' + subTotalExpansionPageDonations + cookiePath;
        // console.log('subTotal Donations[Expansion Tab]:' + subTotalExpansionPageDonations);
        eleRightPanelSubTotalDonationsValue.show();

        // console.log('Total Donations:',
        //     '\n\t$specific [' + parseInt(sanitizedExpansionPageSpecificAmount) + ']' +
        //     '\t$general [' + parseInt(sanitizedExpansionPageGeneralAmount) + ']' +
        //     '\n\t$location [' + parseInt(sanitizedExpansionPageLocationAmount) + ']' +
        //     '\t$expansion [' + parseInt(sanitizedExpansionPageExpansionAmount) + ']' +
        //     '\n\nupdateRightPanelCookies' +
        //     '\n\tspecificAmount: [' + parseInt(sanitizedExpansionPageSpecificAmount) + ']\tType: [' + specificDonationType + ']' +
        //     '\n\tlocationAmount: [' + parseInt(sanitizedExpansionPageLocationAmount) + ']\tType: [' + locationDonationTypeValue + ']' +
        //     '\n\tgeneralAmount: [' + parseInt(sanitizedExpansionPageGeneralAmount) + ']\tType: [' + generalDonationTypeValue + ']' +
        //     '\n\texpansionAmount: [' + parseInt(sanitizedExpansionPageExpansionAmount) + ']\tType: [' + expansionDonationTypeValue + ']' +
        //     '\n\tvoucherAmount: [' + voucherAmount + ']\tCode: [' + voucherCode + ']' +
        //     '\n\ttotalExpansionPageDonations: [' + totalExpansionPageDonations + ']'
        //     '\n\tsubTotalExpansionPageDonations: [' + subTotalExpansionPageDonations + ']'
        // );
    } else {
        rightPanelTotalDonationsElement.hide();
        console.log('Total Donations[Expansion Tab]:' + totalExpansionPageDonations);
    }
}

// UPDATE RIGHT PANEL
function updateRightPanel(sectionName) {
    let page1Data = {
        generalProductID: 23603,
        expansionProductID: 55946,
        donate_anonymously: donateAnonymously,
        generalDonationAmount: generalDonationAmountValue,
        expansionDonationAmount: expansionDonationAmountValue,
        locationDonationAmount: locationDonationAmountValue,
        locationZipCodeNumber: getCookie(ckLocationZipCodeNumber),
        totalDonationAmount: totalDonationAmountValue,
        donation_type: expansionDonationTypeValue,
    };
    let jsonPage1Data = JSON.stringify(page1Data);
    jQuery.ajax({
        type: 'POST',
        url: ajaxurl,
        data: {
            action: 'expansion_checkout_action',
            expansion_form_page_1_data: jsonPage1Data,
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
                if (generalDonationYesNo === 'Yes') {
                    eleRightPanelGeneralValue.text('$' + new Intl.NumberFormat().format(generalDonationAmountValue));
                    eleRightPanelGeneralValue.append('<span title="Remove this item from the cart." class="icon_remove-item_right-panel" id="remove_general_panel">' +
                        ' <i aria-hidden="true" class="far fa-trash-alt"></i></span>');
                    rightPanelGeneralElement.show();
                } else {
                    generalDonationYesNo = 'Yes';
                    eleRightPanelGeneralValue.text('$' + new Intl.NumberFormat().format(generalDonationAmountValue));
                    eleRightPanelGeneralValue.append('<span title="Remove this item from the cart." class="icon_remove-item_right-panel" id="remove_general_panel">' +
                        ' <i aria-hidden="true" class="far fa-trash-alt"></i></span>');
                    rightPanelGeneralElement.show();
                }
            } else {
                generalDonationAmountValue = 0;
                generalDonationYesNo = 'No';
                rightPanelGeneralElement.hide();
            }
            break;

        // SPECIFIC CANDIDATES DONATION SECTION HANDLER
        case rightPanelSummarySectionsList.candidates:
            specificDonationAmountValue = getCookie('ckSpecificDonationTotalAmount');
            voucherCode = getCookie(ckVoucherCode);
            voucherAmount = getCookie(ckVoucherAmount);

            let voucherAmountNoSign = voucherAmount.replace('$', '');

            if (eleRightPanelSpecificValue.text() === '$0') {
                if (voucherAmount === '') {
                    voucherAmount = 0;
                }
                console.log('\ncartTotal: ' + specificDonationAmountValue
                    + '\nvoucherAmount: ' + voucherAmountNoSign);
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
                        specificDonationType = expansionDonationTypeValue;
                        console.log('Specific Donation Type is not set. Defaulting to [' + specificDonationType + ']');
                    }

                    let rightPanel_cart_item_container = jQuery('#cart_items_specific_candidate_details');
                    rightPanel_cart_item_container.empty();

                    let outerContainer = jQuery('<div class="right-outer-cart-item"></div>');
                    rightPanel_cart_item_container.append(outerContainer);

                    // do not run the loop if outercontainer already has child elements
                    if (outerContainer.find('.right-inner-cart-item').length > 0) {
                        console.log('Specific cart already populated!');
                    } else {
                        console.log('Populating specific cart items now...');
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
            else {
                rightPanelSpecificElement.hide();
            }
            break;

        // LOCATION DONATION SECTION HANDLER
        case rightPanelSummarySectionsList.locations:
            locationDonationAmountValue = getCookie(ckLocationDonationAmount);
            locationDonationZipCodeNumber = getCookie(ckLocationZipCodeNumber);
            locationDonationTypeValue = getCookie(ckLocationDonationType);
            let locationDonationZip = getCookie(ckLocationZipCodeNumber);
            if (locationDonationAmountValue > 0 && locationDonationZipCodeNumber > 0) {
                eleRightPanelLocationValue.text('$' + new Intl.NumberFormat().format(locationDonationAmountValue));
                eleRightPanelLocationValue.append('<span title="Remove this item from the cart." class="icon_remove-item_right-panel" id="remove_location_panel">' +
                    ' <i aria-hidden="true" class="far fa-trash-alt"></i></span>');
                eleRightPanelLocationZipValue.text(locationDonationZip);
                console.log('[updateRightPanelCookie]->locationDonationAmountValue: ' + locationDonationAmountValue);
                if (locationDonationTypeValue) {
                    eleRightPanelLocationType.text(locationDonationTypeValue);
                    // console.log('[updateRightPanelCookie]->Type: ' + locationDonationType);
                } else {
                    console.log('Location Donation Type is not set.');
                }
                rightPanelLocationElement.show();
            } else {
                locationDonationZipCodeNumber = '';
                locationDonationAmountValue = '';
                rightPanelLocationElement.hide();
                // console.log('Location Donation Amount is not set.');
            }
            break;

        // EXPANSION DONATION SECTION HANDLER
        case rightPanelSummarySectionsList.expansion:
            expansionDonationAmountValue = getCookie(ckExpansionDonationAmount);
            expansionDonationTypeValue = getCookie(ckExpansionDonationType);
            if (expansionDonationTypeValue) {
                eleRightPanelExpansionType.text(expansionDonationTypeValue);
            } else {
                console.log('Expansion Donation Type is not set.');
            }
            if (expansionDonationAmountValue) {
                eleRightPanelExpansionValue.text('$' + new Intl.NumberFormat().format(expansionDonationAmountValue));
                eleRightPanelExpansionValue.append('<span title="Remove this item from the cart." class="icon_remove-item_right-panel" id="remove_expansion_panel">' +
                    ' <i aria-hidden="true" class="far fa-trash-alt"></i></span>');
                rightPanelExpansionElement.show();
            } else {
                expansionDonationAmountValue = 0;
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



// ALL TABS CLICK HANDLER
jQuery(document).ready(function ($) {
    let currentTabId = 'e-n-tabs-title-1524';
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

            // Trigger a custom event
            jQuery(document).trigger('tabSelected', [tabId]);
        }, 700);

        // OVERFLOW CONTAINER TOGGLE
        setTimeout(function () {
            overlayToggle();
        },1000);

    }

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
        jQuery('#e-n-tab-content-1521, #e-n-tab-content-1522, #e-n-tab-content-1523')
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
    if (is_expansion_funds_tab_selected) {
        handleTabClick(currentTabId);
    }
});

// EXPANSION DONATION AMOUNT RADIO BUTTONS HANDLER
jQuery(function ($) {
// jQuery(document).on('tabSelected', function (event, tabId) {
    expansionDonationAmountEditor = $('#field_expansion_page_donation_amount');
    expansionOtherAmountElement = $('#expansionPageExpAmount_other');
    expansionDonationAmountValue = getCookie('ckExpansionDonationAmount');

    const radioButtonsExpansionPageDonationAmounts = document.querySelectorAll('input[name="expansionPageExpDonationAmount"]');

    // on page load unchecked all general radio buttons other than the selected one
    if (expansionDonationAmountValue > 0) {
        for (let i = 0; i < radioButtonsExpansionPageDonationAmounts.length; i++) {
            const buttonExpansion = radioButtonsExpansionPageDonationAmounts[i];

            if (buttonExpansion.value === expansionDonationAmountValue && buttonExpansion.id.includes(expansionDonationAmountValue)) {
                buttonExpansion.checked = true;
                // console.log('In expButton Value: ' + buttonExpansion.value);
                // console.log('In expButton ID: ' + buttonExpansion.id);
                expansionDonationAmountEditor.val(new Intl.NumberFormat().format(buttonExpansion.value));
                break; // Exit the loop
            } else if (expansionDonationAmountValue !== 0 && buttonExpansion.id.includes(expansionOtherAmountElement.attr('id'))) {
                // console.log('Out expButton Value: ' + buttonExpansion.value);
                // console.log('Out expButton Amount: ' + expansionDonationAmountValue);
                // console.log('Out expButton ID: ' + buttonExpansion.id);
                expansionDonationAmountEditor.attr('readonly', false);
                expansionDonationAmountEditor.val(new Intl.NumberFormat().format(expansionDonationAmountValue));
                expansionOtherAmountElement.click();
            } else {
                buttonExpansion.checked = false;
            }
        }
        updateRightPanel(rightPanelSummarySectionsList.expansion);
    } else {
        expansionDonationAmountEditor.val('');
        radioButtonsExpansionPageDonationAmounts.forEach(buttonExpansion => {
            buttonExpansion.checked = false;
        });
        updateRightPanel(rightPanelSummarySectionsList.expansion);
    }

    // If clicked on 'Other Amount' input radio button
    expansionOtherAmountElement.change(function () {
        expansionDonationAmountEditor.attr('readonly', false).click().focus().attr('placeholder', 'Enter Amount');
        expansionDonationAmountEditor.val(sanitizeExpansionAmount(expansionDonationAmountValue));
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

        document.cookie = ckExpansionDonationAmount + '=' + sanitizeExpansionAmount(jQuery(this).val()) + cookiePath;
        updateRightPanel(rightPanelSummarySectionsList.expansion);
    });

    // Add click event listener to each donation amount radio button
    radioButtonsExpansionPageDonationAmounts.forEach(button => {
        button.addEventListener('click', () => {
            let selectedExpansionValue = button.value;
            if (button.id !== expansionOtherAmountElement.attr('id')) {
                let formattedExpansionValue = new Intl.NumberFormat().format(selectedExpansionValue);
                expansionDonationAmountValue = formattedExpansionValue;
                expansionDonationAmountEditor.val(formattedExpansionValue);
                document.cookie = ckExpansionDonationAmount + '=' + selectedExpansionValue + cookiePath;
            } else {
                selectedExpansionValue = expansionDonationAmountEditor.val();
                expansionDonationAmountValue = selectedExpansionValue;
                document.cookie = ckExpansionDonationAmount + '=' + selectedExpansionValue + cookiePath;
            }
            document.cookie = ckExpansionDonationYesNo + '=Yes' + cookiePath;
            document.cookie = ckExpansionDonationType + '=One-Time' + cookiePath;
            updateRightPanel(rightPanelSummarySectionsList.expansion);
            jQuery(document.body).trigger('wc_fragment_refresh');
        });
    });
});
// EXPANSION DONATION TYPE BUTTON HANDLER
jQuery(document).ready(function ($) {
    expansionDonationTypeElement = jQuery('#frm_field_313_container .frm_opt_container input');
    expansionDonationTypeValue = getCookie(ckExpansionDonationType);
    // on-page load, check if cookie is set for donation type
    if (expansionDonationTypeValue === 'One-Time') {
        expansionDonationTypeElement.filter(':first').parent().click();
        updateRightPanel(rightPanelSummarySectionsList.expansion);
    }
    else if (expansionDonationTypeValue === 'Monthly') {
        expansionDonationTypeElement.filter(':last').parent().click();
        // If monthly donation type is selected, then set ckCreateDonorAccountYesNo cookie to 'Yes'
        document.cookie = ckCreateDonorAccountYesNo + '=Yes' + cookiePath;
        updateRightPanel(rightPanelSummarySectionsList.expansion);
    }
    else {
        document.cookie = ckExpansionDonationType + '=Not Set' + cookiePath;
        expansionDonationTypeElement.filter(':first').parent().click();
        console.log('ckExpansionDonationType cookie not found, setting to default: ' + expansionDonationTypeValue);
        updateRightPanel(rightPanelSummarySectionsList.expansion);
    }

    expansionDonationTypeElement.on('click', function () {
        expansionDonationTypeValue = jQuery(this).val();
        // If monthly donation type is selected, then set ckCreateDonorAccountYesNo cookie to 'Yes'
        if (expansionDonationTypeValue.toLowerCase() === 'monthly') {
            document.cookie = ckCreateDonorAccountYesNo + '=Yes' + cookiePath;
        }
        else {
            document.cookie = ckCreateDonorAccountYesNo + '=No' + cookiePath;
        }
        document.cookie = ckExpansionDonationType + '=' + expansionDonationTypeValue + cookiePath;
        updateRightPanel(rightPanelSummarySectionsList.expansion);
    });

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

// LOCATION DONATION AMOUNT RIGHT PANEL HANDLER
jQuery(document).ready(function ($) {
    locationDonationAmountValue = getCookie(ckLocationDonationAmount);
    locationDonationTypeValue = getCookie(ckLocationDonationType);
    locationDonationZipCodeNumber = getCookie(ckLocationZipCodeNumber);
    updateRightPanel(rightPanelSummarySectionsList.locations);
});

// GENERAL DONATION AMOUNT RADIO BUTTONS HANDLER
jQuery(function ($) {
    generalDonationAmountEditor = $('#field_expansion_general_donation_amount');
    generalOtherAmountElement = $('#expansionPageGeneralAmount_other');
    generalDonationAmountValue = getCookie(ckGeneralDonationAmount);
    generalDonationYesNo = getCookie(ckGeneralDonationYesNo);

    const radioButtonsGeneralDonationAmounts = document.querySelectorAll('input[name="expansionPageGeneralDonationAmount"]');

    // on page load unchecked all expansion radio buttons other than the selected one
    if (generalDonationAmountValue > 0) {
        if (generalDonationYesNo === 'Yes') {
            jQuery('#field_39gh4-0').click().prop('checked', true);

            radioButtonsGeneralDonationAmounts.forEach(button => {
                if (button.value === generalDonationAmountValue) {
                    button.checked = true;
                    generalDonationAmountEditor.val(button.value);
                    // console.log('In genButton Value: ' + button.value + '\n\tIn genButton ID: ' + button.id);
                }
                else if (generalDonationAmountEditor.val() !== generalDonationAmountValue
                    && button.id === generalOtherAmountElement.attr('id')) {
                    generalOtherAmountElement.click();
                    generalDonationAmountEditor.attr('readonly', false);
                    generalDonationAmountEditor.val(generalDonationAmountValue);
                    // console.log('Out genButton Value: ' + button.value + '\n\tOut genButton ID: ' + button.id);
                } else {
                    button.checked = false;
                }
            });
        }
        else {
            generalDonationYesNo = 'Yes';
            document.cookie = ckGeneralDonationYesNo + '=' + generalDonationYesNo + cookiePath;
            generalDonationAmountEditor.val(generalDonationAmountValue);
            jQuery('#field_39gh4-0').click().prop('checked', true);
        }
        updateRightPanel(rightPanelSummarySectionsList.general);
    }
    else {
        generalDonationYesNo = 'No';
        generalDonationAmountEditor.val('');
        jQuery('#field_39gh4-1').trigger('click').prop('checked', true);
        radioButtonsGeneralDonationAmounts.forEach(button => {
            button.checked = false;
        });
        document.cookie = ckGeneralDonationAmount + '=' + '' + cookiePath;
        document.cookie = ckGeneralDonationYesNo + '=No' + cookiePath;
        document.cookie = ckGeneralDonationType + '=One-Time' + cookiePath;
        updateRightPanel(rightPanelSummarySectionsList.general);
    }

    // General Donation Yes/No radio button change event handler
    jQuery(document).on('change', '#field_39gh4-0, #field_39gh4-1', function () {
        generalDonationYesNo = jQuery(this).val();
        if (generalDonationYesNo === 'No') {
            jQuery('#remove_general_panel').trigger('click');
            generalDonationAmountEditor.val('');
            document.cookie = ckGeneralDonationAmount + '=' + '' + cookiePath;
            document.cookie = ckGeneralDonationType + '=One-Time' + cookiePath;
            if (getCookie(ckExpansionDonationType).toLowerCase() === 'one-time' && getCookie(ckLocationDonationType).toLowerCase() === 'one-time') {
                document.cookie = ckCreateDonorAccountYesNo + '=No' + cookiePath;
                console.log('ckCreateDonorAccountYesNo: ' + getCookie(ckCreateDonorAccountYesNo));
            }
            radioButtonsGeneralDonationAmounts.forEach(button => {
                button.checked = false;
            });
        }
        document.cookie = ckGeneralDonationYesNo + '=' + generalDonationYesNo + cookiePath;
        updateRightPanel(rightPanelSummarySectionsList.general);
    });

    // If clicked on general 'Other Amount' input radio button
    generalOtherAmountElement.change(function () {
        // remove attribute 'readonly' from the input element
        generalDonationAmountEditor.attr('readonly', false).click().focus().attr('placeholder', 'Enter Amount');
        generalDonationAmountEditor.val(sanitizeExpansionAmount(generalDonationAmountValue));
    });
    // if typing amount manually while in other amount, update right panel and cookie
    jQuery(generalDonationAmountEditor).on('input', function (event) {
        if (event.which === 13) {
            event.preventDefault();
        }

        // Mask the input field to not allow to type any non-numeric characters
        let inputValue = $(this).val();
        let numericValue = inputValue.replace(/[^0-9]/g, '');
        $(this).val(numericValue);
        generalOtherAmountElement.click();

        document.cookie = ckGeneralDonationAmount + '=' + sanitizeExpansionAmount(numericValue) + cookiePath;
        updateRightPanel(rightPanelSummarySectionsList.general);
    });

    // Add event listener to each donation amount radio button
    radioButtonsGeneralDonationAmounts.forEach(button => {
        button.addEventListener('click', () => {
            let clickedGeneralButtonValue = button.value;
            if (button.id !== generalOtherAmountElement.attr('id')) {
                let formattedGeneralValue = new Intl.NumberFormat().format(clickedGeneralButtonValue);
                generalDonationAmountEditor.val(formattedGeneralValue);
                generalDonationAmountValue = formattedGeneralValue;
                document.cookie = ckGeneralDonationAmount + '=' + clickedGeneralButtonValue + cookiePath;
                console.log('GenCLICK1: ' + clickedGeneralButtonValue);
                updateRightPanel(rightPanelSummarySectionsList.general);
            } else {
                clickedGeneralButtonValue = generalDonationAmountEditor.val();
                generalDonationAmountValue = clickedGeneralButtonValue;
                document.cookie = ckGeneralDonationAmount + '=' + clickedGeneralButtonValue + cookiePath;
                console.log('GenCLICK2: ' + clickedGeneralButtonValue);
                updateRightPanel(rightPanelSummarySectionsList.general);
            }
            jQuery(document.body).trigger('wc_fragment_refresh');
        });
    });
});

// GENERAL DONATION TYPE BUTTON HANDLER
jQuery(document).ready(function ($) {
    generalDonationTypeElement = jQuery('#frm_field_452_container .frm_opt_container input');
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
            document.querySelectorAll('input[name="expansionPageGeneralDonationAmount"]');
        radioButtonsGeneralDonationAmounts.forEach(button => {
            button.checked = false;
        });
        $('#field_39gh4-1').trigger('click');
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
            document.querySelectorAll('input[name="expansionPageExpansionDonationAmount"]');
        radioButtonsExpansionDonationAmounts.forEach(button => {
            button.checked = false;
        });
        // $('#field_fp9k8-1').trigger('click');
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
            document.querySelectorAll('input[name="expansionPageExpansionDonationAmount"]');
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


// REFERRAL YES/NO RADIO BUTTON HANDLER
if (getCookie(ckDonationReferralYesNo)) {
    if (getCookie(ckDonationReferralYesNo) === 'Yes'
        && (getCookie(ckDonationReferrerDetail) !== '' || getCookie(ckDonationReferrerDetail) !== null)) {
        donationReferralYesNo = 'Yes';
        jQuery('#field_dx2vs3-0').prop('checked', true);
        donationReferrerDetail = getCookie(ckDonationReferrerDetail);
        jQuery('#field_expansion_donation_referrer_detail').val(getCookie(ckDonationReferrerDetail));
    } else {
        donationReferralYesNo = 'No';
        jQuery('#field_dx2vs3-1').prop('checked', true);
    }
    console.log('Referral Cookie Exists.\n\tValue: [' + getCookie(ckDonationReferralYesNo)
        + ']\n\tReferrer Detail: [' + donationReferrerDetail + ']');
} else {
    donationReferralYesNo = 'No';
    donationReferrerDetail = '';
    jQuery('#field_dx2vs3-1').prop('checked', true);
    document.cookie = ckDonationReferralYesNo + '=No' + cookiePath;
    console.log('Referral Cookie Does Not Exist.\n\tSetting Default Value to [' + donationReferralYesNo + ']');
}
jQuery(document).on('change', '#field_dx2vs-0, #field_dx2vs-1', function () {
    donationReferralYesNo = jQuery(this).val();
    jQuery(this).prop('checked', true);
    if (jQuery('#field_dx2vs3-0').is(':checked')) {
        donationReferrerDetail = getCookie(ckDonationReferrerDetail);
        jQuery('#field_expansion_donation_referrer_detail').val(donationReferrerDetail);
    } else {
        donationReferrerDetail = '';
        jQuery('#field_expansion_donation_referrer_detail').val('');
    }
    document.cookie = ckDonationReferralYesNo + '=' + donationReferralYesNo + cookiePath;
    console.log('Referral Yes/No: [' + donationReferralYesNo + '] Referrer Detail: [' + donationReferrerDetail + ']');
});
jQuery('#field_expansion_donation_referrer_detail').on('input', function (event) {
    if (event.which === 13) {
        event.preventDefault();
    }
    donationReferrerDetail = jQuery(this).val();
    document.cookie = ckDonationReferrerDetail + '=' + donationReferrerDetail + cookiePath;
    // print the key value which is pressed
    // console.log(donationReferrerDetail);
});


// DONATE ANONYMOUSLY RADIO BUTTON EVENT HANDLER
jQuery(document).ready(function ($) {
    if (getCookie(ckDonateAnonymously)) {
        if (getCookie(ckDonateAnonymously) === 'Yes') {
            donateAnonymously = 'Yes';
            jQuery('#field_donate_anonymously4-0').prop('checked', true);
            jQuery('#donate_anonymously').val('yes');
        } else {
            donateAnonymously = 'No';
            jQuery('#field_donate_anonymously4-1').prop('checked', true);
            jQuery('#donate_anonymously').val('no');
        }
        jQuery(document.body).trigger('update_checkout');
        console.log('DonateAnonymously Cookie Exists. Value: ' + getCookie(ckDonateAnonymously));
    } else {
        donateAnonymously = 'Yes';
        jQuery('#field_donate_anonymously4-0').prop('checked', true);
        document.cookie = ckDonateAnonymously + '=Yes' + cookiePath;
        jQuery(document.body).trigger('update_checkout');
        console.log('DonateAnonymously Cookie Does Not Exist. Setting Default Value to "Yes"');
    }
    jQuery('#field_donate_anonymously4-0, #field_donate_anonymously4-1').on('change', function () {
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

    jQuery('#frm_field_401_container').hide();
    jQuery('#frm_field_402_container').html(
        '<p><span class="reg-ref">Already logged in as: <a href="/' + userDashboard + '/" target="_blank">' + userDisplayName + '</a></span></p>'
    );
}
else {
    if( getCookie(ckCreateDonorAccountYesNo) ) {
        if( getCookie(ckCreateDonorAccountYesNo) === 'Yes' ) {
            createDonorAccountYesNo = 'Yes';
            jQuery('#field_a9yqn-0').prop('checked', true);
            jQuery('#field_a9yqn-1').prop('disabled', true);
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
                    notCreateDonorAccountElementOnCheckout.prop('disabled', true);
                    createDonorAccountElementOnCheckout.prop('disabled', true);
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
            jQuery('#field_a9yqn-1').prop('disabled', false);
            jQuery('#field_a9yqn-1').prop('checked', true);
            jQuery('.woocommerce-account-fields').hide();
        }
        console.log('CreateDonorAccount Cookie Exists. Value: ' + getCookie(ckCreateDonorAccountYesNo));
    }
    else {
        createDonorAccountYesNo = 'No';
        jQuery('#field_a9yqn-1').prop('checked', true);
        document.cookie = ckCreateDonorAccountYesNo + '=' + createDonorAccountYesNo + cookiePath;
        console.log('CreateDonorAccount Cookie Does Not Exist. Setting Default Value to [' + createDonorAccountYesNo + ']');
    }

    jQuery(document).on('change', '#field_a9yqn-0, #field_a9yqn-1, #createaccount', function () {
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
            jQuery('#field_a9yqn-0').prop('checked', true);
            jQuery('#createaccount').click();
            jQuery('.woocommerce-account-fields').show();
        }
        else if (createDonorAccountClickedIdValue === 'No') {
            createDonorAccountYesNo = 'No';
            document.cookie = ckCreateDonorAccountYesNo + '=' + createDonorAccountYesNo + cookiePath;
            jQuery('#field_a9yqn-1').prop('checked', true);
            jQuery('.woocommerce-account-fields').hide();
        }
        console.log('Create Donor Account: [' + createDonorAccountYesNo + ']');
    });
}

// REMOVE ITEM From Specific Cart -> Event Delegation
jQuery(document).on('click', '#remove-this-cart-item', function (event) {
    event.preventDefault();
    console.log('Removing item from cart...');
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

// PAGE-1: HIDE (OPTIONAL) ON MOB 
jQuery(document).ready(function($) {
    function checkResolution() {
        const screenWidth = window.innerWidth || document.documentElement.clientWidth || document.body.clientWidth;

        if (screenWidth <= 600) {
            const firstElement = $('div#field_39gh4_label span:first-child');

            if (firstElement.length) {
                firstElement.first().text('Donate to the General Fund?');
                firstElement.css('white-space', 'nowrap');
            }
        }
    }

    checkResolution();

    $(window).resize(checkResolution);
});


// PAGE-1: NEXT BUTTON HANDLER
jQuery(document).on('click', '#form_expansion-donation .frm_page_num_1 .frm_submit .frm_button_submit', function (event) {
    event.preventDefault();

    generalDonationAmountValue = getCookie(ckGeneralDonationAmount);
    generalDonationYesNo = getCookie(ckGeneralDonationYesNo);
    generalDonationAmountValue = sanitizeExpansionAmount(generalDonationAmountValue);
    generalDonationTypeValue = getCookie(ckGeneralDonationType);

    // if general donation yes/no is set to yes and amount value is zero or not set then show error and return
    if (generalDonationYesNo === 'Yes' &&
        (generalDonationAmountEditor.val() <= '0' || generalDonationAmountEditor.val() === ''
            || generalDonationAmountValue <= 0 || generalDonationAmountValue === '')) {
        jQuery('#field_expansion_general_donation_amount_label')
            .css({'color':'red','font-weight':'bold','margin-bottom':'5px'})
            .text('This field is required, and cannot be zero.');
        // Scroll the page to the target element
        let targetPosition = jQuery('#field_expansion_general_donation_amount_label').offset().top;
        targetPosition -= 300;
        jQuery('html, body').animate({
            scrollTop: targetPosition
        }, 500);
        return console.log('REQUIRED: General Donation Amount is not set.');
    }
    else {
        jQuery('#field_expansion_general_donation_amount_label')
            .css({'color':'#000','font-weight':'normal','margin-bottom':'5px'})
            .text('General Donation Amount');
    }

    expansionDonationTypeValue = getCookie(ckExpansionDonationType);
    locationDonationTypeValue = getCookie(ckLocationDonationType);
    // if expansionDonationTypeValue is set to one-time then set ckCreateDonorAccountYesNo cookie to 'No'
    if (expansionDonationTypeValue.toLowerCase() === 'one-time'
        && generalDonationTypeValue.toLowerCase() === 'one-time'
        && locationDonationTypeValue.toLowerCase() === 'one-time') {
        document.cookie = ckCreateDonorAccountYesNo + '=No' + cookiePath;
    }
    expansionDonationAmountValue = getCookie(ckExpansionDonationAmount);
    // if expansion donation amount value is zero or not set then show error and return
    if (expansionDonationAmountEditor.val() <= 0 || expansionDonationAmountEditor.val() === ''
        || expansionDonationAmountValue <= 0 || expansionDonationAmountValue === '') {
        jQuery('#frm_field_310_container')
            .css({'color':'red','font-weight':'bold','margin-bottom':'5px'})
            .text('This field is required, and cannot be zero.');
        // Scroll the page to the target element
        let targetPosition = jQuery('#frm_field_310_container').offset().top;
        targetPosition -= 300;
        jQuery('html, body').animate({
            scrollTop: targetPosition
        }, 500);
        return console.log('REQUIRED: Expansion Donation Amount is not set.');
    }
    else {
        jQuery('#frm_field_310_container')
            .css({'color':'#000','font-weight':'normal','margin-bottom':'5px'})
            .text('Expansion Donation Amount');
    }

    totalDonationAmountValue = getCookie(ckTotalDonations);

    let page1Data = {
        generalProductID: 23603,
        expansionProductID: 55946,
        donate_anonymously: donateAnonymously,
        generalDonationAmount: generalDonationAmountValue,
        expansionDonationAmount: expansionDonationAmountValue,
        locationDonationAmount: locationDonationAmountValue,
        locationZipCodeNumber: getCookie(ckLocationZipCodeNumber),
        totalDonationAmount: totalDonationAmountValue,
        donation_type: expansionDonationTypeValue,
    };
    let jsonPage1Data = JSON.stringify(page1Data);
    // console.log('[jsonPage1Data]=\t' + jsonPage1Data);

    jQuery.ajax({
        type: 'POST',
        url: ajaxurl,
        data: {
            action: 'expansion_checkout_action',
            expansion_form_page_1_data: jsonPage1Data,
            security: nonce,
        },
        beforeSend: function () {
            overlayToggle();
        },
        success: function (response) {
            overlayToggle();
            console.log("Page-1: " + JSON.stringify(response));
            if (response !== 0) {
                jQuery('#form_expansion-donation .frm_page_num_1 .frm_submit .frm_button_submit').submit();
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

// PAGE-2: NEXT BUTTON HANDLER
jQuery(document).on('click', '#form_expansion-donation .frm_page_num_2 .frm_submit .frm_button_submit', function (event) {
    event.preventDefault();

    let existingErrorMessage = jQuery('#frm_error_field_general_referrer_detail');
    existingErrorMessage.hide();
    let referralYesNo = jQuery('#frm_field_317_container .frm_opt_container input');
    let selectedReferralYesNo = jQuery(referralYesNo).filter(':checked').val();
    document.cookie = ckDonationReferralYesNo + '=' + selectedReferralYesNo + cookiePath;
    let donationReferrerDetail = jQuery('#field_expansion_donation_referrer_detail').val();
    document.cookie = ckDonationReferrerDetail + '=' + donationReferrerDetail + cookiePath;
    // console.log('[Nonce]' + nonce + ' [referralYesNo]' + selectedReferralYesNo + ' [donationReferrerDetail]' + donationReferrerDetail);

    let dataPage2 = {
        selectedReferralYesNo: selectedReferralYesNo,
        donationReferrerDetail: donationReferrerDetail,
    };
    let jsonDataPage2 = JSON.stringify(dataPage2);

    jQuery.ajax({
        type: 'POST',
        url: ajaxurl,
        data: {
            action: 'expansion_checkout_action',
            expansion_form_page_2_data: jsonDataPage2,
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
                        window.history.replaceState(null, null, '?funds=expansion&ref=' + response.data.donationReferrerDetail);
                        jQuery('#form_expansion-donation .frm_page_num_2 .frm_submit .frm_button_submit').submit();
                    } else {
                        jQuery('#form_expansion-donation .frm_page_num_2 .frm_submit .frm_button_submit').submit();
                    }
                } else if (response.data.status === 'error') {
                    if (existingErrorMessage.length > 0) {
                        existingErrorMessage.remove();
                    }
                    let errorMessage = jQuery(
                        '<div class="frm_error" role="alert" id="frm_error_field_general_referrer_detail" ' +
                        'style="display:inline;">' + response.data.message + '</div>'
                    );
                    errorMessage.insertBefore(jQuery('#field_expansion_donation_referrer_detail'), jQuery('#field_donation_referrer_detail_container'));
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

// PAGE-3: FINAL SUBMISSION EXPANSION DONATION FORM
jQuery(document).on('click', '#form_expansion-donation .frm_button_submit.frm_final_submit', function (event) {
    event.preventDefault();
    // clear error message fields
    let errorMessageField = jQuery('#field_vwxl3_label span');
    let errorMessageContainer = jQuery('#frm_field_329_container');
    if (jQuery('#field_vwxl3-0').is(':checked')) {
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
    let termsElement = '#frm_field_329_container';
    let termsCheckBox = '#field_vwxl3-0';
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
