"use strict";
let is_specific_funds_tab_selected = obj_candidates.is_specific_funds_tab_selected;
if (is_specific_funds_tab_selected == null) {
    is_specific_funds_tab_selected = false;
}
console.log('[is_specific_funds_tab_selected]->' + is_specific_funds_tab_selected);

// REMOVE AMOUNT FORMATTING AND RETURN THE AMOUNT WITHOUT COMMAS AND DOLLAR SIGN
function sanitizeCandidatesPageAmount(amount) {
    let _amount = 0;
    if (amount === 0 || amount === null || amount === '' || amount === 'undefined' || isNaN(amount)) {
        return 0;
    } else if (amount !== 0) {
        _amount = amount.replace(/,/g, '');
        _amount = _amount.replace('$', '');
        return _amount;
    }
}

// ALL TABS CLICK HANDLER
jQuery(document).ready(function ($) {
    let currentTabId = 'e-n-tabs-title-5491';

    function handleTabClick(tabId) {
        setTimeout(function () {
            console.log('refreshing tabs...')
            jQuery('#e-n-tabs-title-5491, #e-n-tabs-title-5492, #e-n-tabs-title-5493, #e-n-tabs-title-5494')
                .attr('aria-selected', 'false').attr('tabindex', '-1');
            jQuery('#e-n-tab-content-5491, #e-n-tab-content-5492, #e-n-tab-content-5493, #e-n-tab-content-5494')
                .hide();

            if (tabId === 'e-n-tabs-title-5491') {
                jQuery('#e-n-tabs-title-5491').attr('aria-selected', 'true').attr('tabindex', '0');
                jQuery('#e-n-tab-content-5491').show();
                console.log('[TAB]Specific');
            } else if (tabId === 'e-n-tabs-title-5492') {
                jQuery('#e-n-tabs-title-5492').attr('aria-selected', 'true').attr('tabindex', '0');
                jQuery('#e-n-tab-content-5492').show();
                console.log('[TAB]General');
            } else if (tabId === 'e-n-tabs-title-5493') {
                jQuery('#e-n-tabs-title-5493').attr('aria-selected', 'true').attr('tabindex', '0');
                jQuery('#e-n-tab-content-5493').show();
                console.log('[TAB]Location');
            } else if (tabId === 'e-n-tabs-title-5494') {
                jQuery('#e-n-tabs-title-5494').attr('aria-selected', 'true').attr('tabindex', '0');
                jQuery('#e-n-tab-content-5494').show();
                console.log('[TAB]Expansion');
            }
        }, 500);

    }

    // Attach click event to the donation tabs
    jQuery('.e-n-tabs-heading').on('click', '#e-n-tabs-title-5491, #e-n-tabs-title-5492, #e-n-tabs-title-5493, #e-n-tabs-title-5494', function () {
        let tabId = jQuery(this).attr('id');
        handleTabClick(tabId);
        console.log('Opening tab: ' + tabId);
        if (tabId === 'e-n-tabs-title-5491') {
            window.location.href = '/checkout/?funds=specific';
        } else if (tabId === 'e-n-tabs-title-5492') {
            window.location.href = '/checkout/?funds=general';
        } else if (tabId === 'e-n-tabs-title-5493') {
            window.location.href = '/checkout/?funds=location';
        } else if (tabId === 'e-n-tabs-title-5494') {
            window.location.href = '/checkout/?funds=expansion';
        }
    });
    // ON PAGE LOAD: CHECK WHICH TAB TO SHOW ON PAGE LOAD
    if (is_specific_funds_tab_selected) {
        handleTabClick(currentTabId);
    }
});

jQuery(document).ready(function ($) {
    // Access the nonce variable from the custom_cart_update_nonce object
    let nonce = obj_candidates.nonce;
    let ajaxurl = obj_candidates.ajaxurl;
    let userRole = obj_candidates.user_role;
    let userDisplayName = obj_candidates.user_display_name;
    let cartTotal = obj_candidates.cart_total;
    let cartContents = obj_candidates.cart_contents;
    let voucherCode = obj_candidates.voucher_code;
    let VoucherAmount = obj_candidates.voucher_amount;
    let donateAnonymously = '';
    let generalFunds = '';
    let expansionFunds = '';
    let createDonorAccountYesNo = '';
    let specificReferralYesNo = '';
    let specificReferrerDetail = '';
    let originalPrices = {}; // Object to store cart item's original prices
    let totalCartPrice = 0;

    let expansionDonationYesNo, expansionDonationType, expansionDonationTypeElement, expansionDonationTypeValue,
        expansionDonationAmount = 0, expansionDonationAmountEditor,
        expansionOtherAmountElement, expansionDonationAmountValue,
        generalDonationYesNo, generalDonationTypeValue, generalDonationAmountValue,
        generalDonationAmountEditor, generalOtherAmountElement, generalDonationTypeElement,
        specificDonationAmount, specificDonationType, specificDonorAccountYesNo,
        locationDonationAmount = 0, locationDonationType, locationDonationZip,
        voucherDisplayPrice, voucherCodeElement, VoucherAmountElement;

    let rightPanelGeneralElement = jQuery('#right_panel_general'),
        rightPanelSpecificElement = jQuery('#right_panel_specific'),
        rightPanelLocationElement = jQuery('#right_panel_location'),
        rightPanelExpansionElement = jQuery('#right_panel_expansion'),
        rightPanelVoucherElement = jQuery('#right_panel_voucher'),
        rightPanelTotalDonationsElement = jQuery('#right_panel_cart_total');
    let eleRightPanelGeneralValue = jQuery('.elementor-element-73ec62f span'),
        eleRightPanelGeneralType = jQuery('.elementor-element-d99db02 span'),
        eleRightPanelSpecificValue = jQuery('#specific_candidates_total_amount span'),
        eleRightPanelSpecificType = jQuery('.elementor-element-d724586 span'),
        eleRightPanelLocationZipValue = jQuery('.elementor-element-c37afc2 span'),
        eleRightPanelLocationValue = jQuery('.elementor-element-954accc span'),
        eleRightPanelLocationType = jQuery('.elementor-element-d28ba32 span'),
        eleRightPanelExpansionValue = jQuery('.elementor-element-29158d5 span'),
        eleRightPanelExpansionType = jQuery('.elementor-element-f76c178 span'),
        eleRightPanelVoucherCode = jQuery('.elementor-element-ae73c5d span'),
        eleRightPanelVoucherValue = jQuery('.elementor-element-5df4ae5 span'),
        eleRightPanelTotalDonationsValue = jQuery('.elementor-element-001148c span');


    let cookiePath = '; path=/;',
        ckSpecificDonationTotalAmount = 'ckSpecificDonationTotalAmount',
        ckSpecificDonationType = 'ckSpecificDonationType',
        ckTotalDonations = 'ckTotalDonations',
        ckGeneralDonationYesNo = 'ckGeneralDonationYesNo',
        ckGeneralDonationAmount = 'ckGeneralDonationAmount',
        ckGeneralDonationType = 'ckGeneralDonationType',
        ckExpansionDonationYesNo = 'ckExpansionDonationYesNo',
        ckExpansionDonationAmount = 'ckExpansionDonationAmount',
        ckExpansionDonationType = 'ckExpansionDonationType',
        ckLocationDonationType = 'ckLocationDonationType',
        ckLocationZipCodeNumber = 'ckLocationZipCodeNumber',
        ckLocationDonationAmount = 'ckLocationDonationAmount',
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

    // POPULATE MAIN CART PANEL
    function populate_main_cart_panel() {
        // return if general tab is selected
        if (!is_specific_funds_tab_selected) {
            console.log('Current Tab is not SPECIFIC TAB.');
            return;
        }

        let main_cart_item_container = jQuery('#checkout_page_specific_candidate_main_details');

        let outerContainer = jQuery('<div class="main-cart-item-full-row"></div>');
        main_cart_item_container.append(outerContainer);
        let itemFormContainer = jQuery(
            '<form class="woocommerce-cart-form" action="" ' +
            'method="post">' +
            '</form>'
        );
        outerContainer.append(itemFormContainer);

        cartContents.forEach(item => {
            console.log('item.product_name: ' + item.product_name + '\n\titem.product_price: ' + item.product_price);
            // Check if the product is "General Donation" or "Expansion Donation"
            if (item.product_name === "General Fund Donation"
                || item.product_name === "Expansion Fund Donation"
                || item.product_name === "Location Fund Donation") {
                return;
            }

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
                '<button data-product-key="' + item.product_key + '" data-wp-wpnonce="' + nonce + '"' +
                ' class="remove" id="remove-this-cart-item" aria-label="Remove ' + item.product_name +
                ' from cart" data-product_id="' + item.product_id + '" data-product_sku="' + item.product_id + '">' +
                '<i aria-hidden="true" class="far fa-trash-alt"></i>' +
                '</button>' +
                '</div>' +
                '</div>'
            );

            itemFormContainer.append(row);
        });

        // Display following message if there is no candidate in the cart
        setTimeout(function () {
            if (main_cart_item_container.find('.main-cart-item-full-row form > *').length <= 0) {
                main_cart_item_container.append('<p style="font-size: 18px;\n' +
                    '    color: #143A62;font-style: italic;\n' +
                    '    padding: 0 20px;font-weight: 300;">No specific Candidate donations have been added to your Cart yet.</p>');

            } else {
                console.log('Main Cart Item Container is not empty');
            }
        }, 1000);

        updateRightPanel(rightPanelSummarySectionsList.candidates);
    }
    populate_main_cart_panel();

    // TOTAL DONATIONS SECTION HANDLER
    function updateRightPanelTotalDonations() {
        const sanitizedGeneralAmount = sanitizeCandidatesPageAmount(generalDonationAmountValue);
        const sanitizedSpecificAmount = sanitizeCandidatesPageAmount(specificDonationAmount);
        const sanitizedLocationAmount = sanitizeCandidatesPageAmount(locationDonationAmount);
        const sanitizedExpansionAmount = sanitizeCandidatesPageAmount(expansionDonationAmount);

        // const totalDonations = cartTotal.replace('$', '');
        const totalCandidatesPageDonations =
            (parseInt(sanitizedGeneralAmount) || 0) +
            (parseInt(sanitizedSpecificAmount) || 0) +
            (parseInt(sanitizedLocationAmount) || 0) +
            (parseInt(sanitizedExpansionAmount) || 0);

        if (!isNaN(totalCandidatesPageDonations) && totalCandidatesPageDonations !== 0) {
            eleRightPanelTotalDonationsValue.text('$' + new Intl.NumberFormat().format(totalCandidatesPageDonations));
            document.cookie = ckTotalDonations + '=' + totalCandidatesPageDonations + cookiePath;
            rightPanelTotalDonationsElement.show();
            console.log('Total Donations:',
                '\n\t$specific[' + parseInt(sanitizedSpecificAmount) + ']' +
                '\t$general[' + parseInt(sanitizedGeneralAmount) + ']' +
                '\n\t$location[' + parseInt(sanitizedLocationAmount) + ']' +
                '\t$expansion[' + parseInt(sanitizedExpansionAmount) + ']' +
                '\n\nupdateRightPanelCookies' +
                '\n\tspecificAmount: [' + parseInt(sanitizedSpecificAmount) + ']\tType: [' + specificDonationType + ']' +
                '\n\tlocationAmount: [' + parseInt(sanitizedLocationAmount) + ']\tType: [' + locationDonationType + ']' +
                '\n\tgeneralAmount: [' + parseInt(sanitizedGeneralAmount) + ']\tType: [' + generalDonationTypeValue + ']' +
                '\n\texpansionAmount: [' + parseInt(sanitizedExpansionAmount) + ']\tType: [' + expansionDonationType + ']' +
                '\n\tVoucherAmount: [' + parseInt(VoucherAmount) + ']\tCode: [' + voucherCode + ']' +
                '\n\ttotalCandidatesPageDonations: [' + totalCandidatesPageDonations + ']'
            );
        } else {
            rightPanelTotalDonationsElement.hide();
            console.log('Total Donations[General Tab]:' + totalCandidatesPageDonations);
        }
    }

    // POPULATE RIGHT CART PANEL
    function updateRightPanel(rightPanelDonationSectionName) {

        switch (rightPanelDonationSectionName) {
            // SPECIFIC CANDIDATES DONATION SECTION HANDLER
            case rightPanelSummarySectionsList.candidates:
                // SPECIFIC CANDIDATES DONATION SECTION HANDLER
                specificDonationAmount = getCookie(ckSpecificDonationTotalAmount);
                specificDonationType = getCookie(ckSpecificDonationType);
                let cartTotalNoSign = cartTotal.replace('$', '');
                let VoucherAmountNoSign = VoucherAmount.replace('$', '');
                if (VoucherAmount === '') {
                    VoucherAmount = 0;
                }
                console.log('cartTotal: ' + cartTotalNoSign + ' VoucherAmount: ' + VoucherAmountNoSign);
                if (specificDonationAmount === '') {
                    specificDonationAmount = parseInt(cartTotalNoSign) - parseInt(VoucherAmountNoSign);
                }
                if (cartContents.length <= 0) {
                    console.log('Cart is empty. No need to populate specific cart panel.');
                    rightPanelSpecificElement.hide();
                }
                else if (specificDonationAmount !== '' && specificDonationAmount > 0) {
                    // CART TOTAL UPDATE SPECIFIC TAB
                    console.log('Cart has [' + cartContents.length + '] item(s)!');
                    if (typeof specificDonationAmount === 'string' && specificDonationAmount.includes('$')) {
                        console.log('specificDonationAmount has $ sign. Removing it...');
                        eleRightPanelSpecificValue.text(specificDonationAmount);
                    } else {
                        console.log('specificDonationAmount does not have $ sign. Adding it...');
                        eleRightPanelSpecificValue.text('$' + new Intl.NumberFormat().format(specificDonationAmount));
                    }
                    console.log('specificDonationAmount: ' + specificDonationAmount);

                    if (specificDonationType) {
                        eleRightPanelSpecificType.text(specificDonationType);
                        console.log('ckSpecificDonationType value: ' + specificDonationType);
                    } else {
                        specificDonationType = 'One-Time';
                        document.cookie = ckSpecificDonationType + '=' + specificDonationType + cookiePath;
                        console.log('ckSpecificDonationType is not set.');
                    }

                    let rightPanel_cart_item_container = jQuery('#cart_items_specific_candidate_details');

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
                                || item.product_name === "Location Fund Donation") {
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
                                '<button data-product-key="' + item.product_key + '" data-wp-wpnonce="' + nonce + '"' +
                                ' class="remove" id="remove-this-cart-item" aria-label="Remove ' + item.product_name +
                                ' from cart" data-product_id="' + item.product_id + '" data-product_sku="' + item.product_id + '">' +
                                '<i aria-hidden="true" class="far fa-trash-alt"></i>' +
                                '</button>' +
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
                    rightPanelGeneralElement.show();
                } else {
                    generalDonationAmountValue = '';
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

                if (locationDonationAmount > 0 && locationDonationZip) {
                    eleRightPanelLocationValue.text('$' + new Intl.NumberFormat().format(locationDonationAmount));
                    eleRightPanelLocationZipValue.text(locationDonationZip);
                    rightPanelLocationElement.show();
                } else {
                    locationDonationAmount = '';
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
                    rightPanelExpansionElement.show();
                } else {
                    expansionDonationAmount = '';
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
                        eleRightPanelVoucherValue.text('$' + VoucherAmount);
                    } else {
                        eleRightPanelVoucherValue.text('Not Set');
                        console.log('Voucher Amount is not set.');
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
                        location.reload(); // Refresh the page to reflect changes
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
                    location.reload();
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
            '"> Remove</button>'
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
            success: function (response) {
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
                wc_voucher_notification.prop('class', 'woocommerce-error').html(response.data.message).show();
            },
            complete: function () {
                // Trigger the 'update_checkout' event to refresh the cart total
                $(document.body).trigger('update_checkout');
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
                location.reload();
            },
            error: function (response) {
                wc_voucher_notification.prop('class', 'woocommerce-error').html(response.data.message).show();
            },
            complete: function () {
                $(document.body).trigger('update_checkout');
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
            document.cookie = ckGeneralDonationAmount + '=' + '' + cookiePath;
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
            document.cookie = ckGeneralDonationYesNo + '=' + generalDonationYesNo + cookiePath;
            if (generalDonationYesNo === 'No') {
                generalDonationAmountValue = '';
                generalDonationAmountEditor.val(generalDonationAmountValue);
                document.cookie = ckGeneralDonationAmount + '=' + generalDonationAmountValue + cookiePath;
                document.cookie = ckGeneralDonationType + '=' + 'One-Time' + cookiePath;
                radioButtonsGeneralDonationAmounts.forEach(button => {
                    button.checked = false;
                });
            }
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
            updateRightPanel(rightPanelSummarySectionsList.expansion);
            console.log('Expansion Funds Updated to [' + expansionFunds + ']');
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
                    document.cookie = ckCreateDonorAccountYesNo + '=No' + cookiePath;
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


    // CREATE DONOR ACCOUNT RADIO BUTTON HANDLER
    // check if user is logged in or not userRole = 'subscriber'
    // console.log('User Role: ' + userRole);
    // console.log('User Display Name: ' + userDisplayName);
    if (userRole === 'subscriber') {
        jQuery('#frm_field_156_container').hide();
        jQuery('#frm_field_158_container').html(
            '<p><span class="reg-ref">Already logged in as: <a href="/dashboard-donor/" target="_blank">' + userDisplayName + '</a></span></p>'
        );
    } else {
        if (getCookie(ckCreateDonorAccountYesNo)) {
            if (getCookie(ckCreateDonorAccountYesNo) === 'Yes') {
                createDonorAccountYesNo = 'Yes';
                jQuery('#field_n68u0-0').prop('checked', true);
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
            specificReferralYesNo = 'Yes';
            jQuery('#field_dx2vs-0').prop('checked', true);
            if (getCookie(ckDonationReferrerDetail) !== '') {
                specificReferrerDetail = getCookie(ckDonationReferrerDetail);
                jQuery('#field_o8n9e').val(specificReferrerDetail);
            }
        } else {
            specificReferralYesNo = 'No';
            jQuery('#field_dx2vs-1').prop('checked', true);
        }
        console.log('Referral Cookie Exists. Value: [' + getCookie(ckDonationReferralYesNo)
            + '] Referrer Detail: [' + specificReferrerDetail + ']');
    } else {
        specificReferralYesNo = 'No';
        specificReferrerDetail = '';
        jQuery('#field_dx2vs-1').prop('checked', true);
        document.cookie = ckDonationReferralYesNo + '=No' + cookiePath;
        console.log('Referral Cookie Does Not Exist. Setting Default Value to [' + specificReferralYesNo + ']');
    }
    jQuery(document).on('change', '#field_dx2vs-0, #field_dx2vs-1', function () {
        specificReferralYesNo = jQuery(this).val();
        jQuery(this).prop('checked', true);
        if (jQuery('#field_dx2vs-0').is(':checked')) {
            specificReferrerDetail = getCookie(ckDonationReferrerDetail);
            jQuery('#field_o8n9e').val(getCookie(ckDonationReferrerDetail));
        } else {
            specificReferrerDetail = '';
            jQuery('#field_o8n9e').val('');
        }
        document.cookie = ckDonationReferralYesNo + '=' + specificReferralYesNo + cookiePath;
        console.log('Referral Yes/No: [' + specificReferralYesNo + '] Referrer Detail: [' + specificReferrerDetail + ']');
    });
    $('#field_o8n9e').on('keyup', function (event) {
        if (event.which === 13) {
            event.preventDefault();
        }
        document.cookie = ckDonationReferrerDetail + '=' + jQuery(this).val() + cookiePath;
        specificReferrerDetail = jQuery(this).val();
    });

    // SPECIFIC 1: 'NEXT' BUTTON CLICK HANDLER
    jQuery(document).on('click', '#form_specific-candidate-donation .frm_page_num_1 button.frm_button_submit', function (event) {
        event.preventDefault();

        // if general donation yes/no is no and amount is zero or not set then show error and return
        generalDonationAmountValue = getCookie(ckGeneralDonationAmount);
        if (generalDonationYesNo === 'Yes' && (generalDonationAmountValue <= 0 || generalDonationAmountValue === '')) {
            jQuery('#field_candidates_general_donation_amount_label').css({'color':'red','font-weight':'bold'});
            // Scroll the page to the target element
            let targetPosition = jQuery('#field_candidates_general_donation_amount_label').offset().top;
            targetPosition -= 300;
            jQuery('html, body').animate({
                scrollTop: targetPosition
            }, 500);
            return console.log('REQUIRED: General Donation Amount is not set.');
        }
        else {
            jQuery('#field_candidates_general_donation_amount_label').css({'color':'#000','font-weight':'normal'});
        }
        console.log('generalDonationAmountValue: ' + generalDonationAmountValue);

        // if expansion donation yes/no is no and amount is zero or not set then show error and return
        expansionDonationAmountValue = getCookie(ckExpansionDonationAmount);
        if (expansionDonationYesNo === 'Yes' && (expansionDonationAmountValue <= 0 || expansionDonationAmountValue === '')) {
            jQuery('#field_candidates_expansion_donation_amount_label').css({'color':'red','font-weight':'bold'});
            // Scroll the page to the target element
            let targetPosition = jQuery('#field_candidates_expansion_donation_amount_label').offset().top;
            targetPosition -= 300;
            jQuery('html, body').animate({
                scrollTop: targetPosition
            }, 500);
            return console.log('REQUIRED: Expansion Donation Amount is not set.');
        }
        else {
            jQuery('#field_candidates_expansion_donation_amount_label').css({'color':'#000','font-weight':'normal'});
        }

        let page1Data = {
            donate_anonymously: donateAnonymously,
            generalDonationAmount: generalDonationAmountValue,
            expansionDonationAmount: expansionDonationAmount,
            locationDonationAmount: locationDonationAmount,
            locationDonationZip: locationDonationZip,
        };
        let jsonPage1Data = JSON.stringify(page1Data);
        console.log('Page1Data:\n\t' + jsonPage1Data);

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
                    console.log('Success_P1! ' + JSON.stringify(response));
                    $(document.body).trigger('update_checkout');
                    $('#form_specific-candidate-donation .frm_page_num_1 button.frm_button_submit').submit();
                } else {
                    console.log('Failed_P1: \n' + JSON.stringify(response));
                }
            },
            error: function (error) {
                // Handle any errors, e.g., display an error message
                console.log('Error_P1: ' + JSON.stringify(error));
            }
        });
    });

    // SPECIFIC 2: 'NEXT' BUTTON CLICK HANDLER
    jQuery(document).on('click', '#form_specific-candidate-donation .frm_page_num_2 button.frm_button_submit', function (event) {
        event.preventDefault();
        console.log('Specific [Page-2-Next] Button Clicked');
        let page2Data = {
            createDonorAccountYesNo: createDonorAccountYesNo,
            specificReferralYesNo: specificReferralYesNo,
            specificReferrerDetail: specificReferrerDetail,
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
            success: function (response) {
                if (response.success) {
                    if (!window.location.href.includes('ref') && response.data.specificReferralYesNo !== '') {
                        console.log('SuccessPP_P2! ' + JSON.stringify(response));
                        window.history.replaceState(null, null, '?ref='
                            + response.data.specificReferrerDetail);
                        $('#form_specific-candidate-donation .frm_page_num_2 button.frm_button_submit').submit();
                    } else {
                        console.log('SuccessFF_P2! ' + JSON.stringify(response));
                        window.history.replaceState(null, null, window.location.pathname + window.location.hash);
                        // Go to next page of the form
                        $('#form_specific-candidate-donation .frm_page_num_2 button.frm_button_submit').submit();
                    }
                } else {
                    console.log('Failed_P2: \n' + JSON.stringify(response));
                }
            },
            error: function (error) {
                // Handle any errors, e.g., display an error message
                console.log('Error_P2: ' + JSON.stringify(error));
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
            errorMessageContainer.css({'border': '0', 'padding': '0'});
        } else {
            errorMessageField.text('This field cannot be blank.').css('color', 'red');
            errorMessageContainer.css({'border': '1px solid red', 'padding': '10px'});
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
            success: function (response) {
                if (response.success) {
                    console.log('Success_P3! ' + JSON.stringify(response));
                    $(document.body).trigger('update_checkout');
                    jQuery('#place_order').click();
                } else {
                    console.log('Failed_P3: \n' + JSON.stringify(response));
                }
            },
            error: function (error) {
                // Handle any errors, e.g., display an error message
                console.log('Error_P3: ' + JSON.stringify(error));
            }
        });
    });

});

