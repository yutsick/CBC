/* CHECKOUT PAGE TABS 'i' BUTTONS HANDLER */
jQuery(document).ready(function ($) {
    let donateAnonymouslyPopup = '.tooltip-block.don-anonymous';
    let refHelpPopup = '.tooltip-block.ref';
    let donTypePopup = '.tooltip-block.don-type';
    let genTypePopup = '.tooltip-block.gen-type';
    let locationTypePopup = '.tooltip-block.location-type';
    let expTypePopup = '.tooltip-block.exp-type';
    let iButton = '<img decoding="async" style="padding-left: 5px;" class="donAnon-icon" src="/wp-content/uploads/2023/10/ad-step__icon.svg">';

    // if page url contains funds=specific
    if (window.location.href.indexOf('funds=expansion') > -1) {
        jQuery(genTypePopup).css('top','675px');
    }

    jQuery('#field_donate_anonymously_label').append(iButton);

    let iconPopupPairs = [
        { icon: $('#field_donate_anonymously_label .donAnon-icon'), popup: $(donateAnonymouslyPopup) },
        { icon: $('#field_donate_anonymously2_label .donAnon-icon'), popup: $(donateAnonymouslyPopup) },
        { icon: $('#field_donate_anonymously3_label .donAnon-icon'), popup: $(donateAnonymouslyPopup) },
        { icon: $('#field_donate_anonymously4_label .donAnon-icon'), popup: $(donateAnonymouslyPopup) },
        { icon: $('.donVoucher-icon'), popup: $('.voucher-popup') },
        { icon: $('#field_vjpse_label .donType-icon'), popup: $(donTypePopup) },
        { icon: $('#field_zip-code-number_label .donType-icon'), popup: $(locationTypePopup) },
        { icon: $('#field_x5czf2_label .donType-icon'), popup: $(donTypePopup) },

        { icon: $('#field_dx2vs_label .refHelp-icon'), popup: $(refHelpPopup) },
        { icon: $('#field_dx2vs2_label .refHelp-icon'), popup: $(refHelpPopup) },
        { icon: $('#field_tydpw_label .refHelp-icon'), popup: $(refHelpPopup) },
        { icon: $('#field_dx2vs3_label .refHelp-icon'), popup: $(refHelpPopup) },

        { icon: $('#field_3nlpf_label .donGeneral-icon'), popup: $(genTypePopup) },
        { icon: $('#field_fp9k8_label .donGeneral-icon'), popup: $(genTypePopup) },
        { icon: $('#field_39gh4_label .donGeneral-icon'), popup: $(genTypePopup) },
        { icon: $('#field_x5czf_label .donGeneral-icon'), popup: $(genTypePopup) },

        { icon: $('#field_cv96t_label .donExpansion-icon'), popup: $(expTypePopup) },
        { icon: $('#field_vg33t_label .donExpansion-icon'), popup: $(expTypePopup) },
        { icon: $('#field_9231r_label .donExpansion-icon'), popup: $(expTypePopup) },
        /* Create Donor Account i Button icon popup */
        { icon: $('.help-icon'), popup: $('.help-popup') },
    ];

    function handlePopup(icon, popup, otherPopups) {
        let isPopupClicked = false;

        icon.on('mouseenter', function () {
            if (!isPopupClicked) {
                // Hide other popups
                otherPopups.forEach(otherPair => otherPair.popup.hide());
                popup.show();
            }
        });

        icon.on('mouseleave', function () {
            if (!isPopupClicked) {
                popup.hide();
            }
        });

        icon.on('click', function (event) {
            // Prevent hiding on click if already clicked
            if (!isPopupClicked) {
                // Hide other popups
                otherPopups.forEach(otherPair => otherPair.popup.hide());
                popup.show();
            }

            isPopupClicked = !isPopupClicked;
            event.stopPropagation();
        });

        popup.on('click', function (event) {
            event.stopPropagation();
        });

        jQuery(document.body).on('click', function () {
            if (!isPopupClicked) {
                iconPopupPairs.forEach(pair => pair.popup.hide());
            }
            isPopupClicked = false;
        });
    }

    // Apply the functionality for each icon-popup pair
    iconPopupPairs.forEach((pair, index) => {
        const otherPopups = iconPopupPairs.filter((_, i) => i !== index);
        handlePopup(pair.icon, pair.popup, otherPopups);
    });
});

// CLEAR CART BUTTON HANDLER (show on each of the 4 tabs in checkout page)
jQuery(document).ready(function ($) {

    if (!$('#clear_the_cart').length) {
        jQuery('#right_panel_cart_total').append('<button type="button" id="clear_the_cart">Clear Cart</button>');
    }

    
        if (!$('.clear-all-wrapper')){
        
        jQuery('.elementor-menu-cart__main').append('<div class="clear-all-wrapper"></div>');
        
    }


    jQuery(document).on('click','#clear_the_cart', function () {
        let currentUrl = window.location.href;
        let clearCartUrl = currentUrl + '&clear-cart';
        console.log('Clearing the cart...');
        window.open(clearCartUrl, '_self');
    });
});


jQuery(document).ready(function ($) {
    const targetNode = document.querySelector('div.elementor-menu-cart__main');
    const blockEl = '<div class="block_cta"></div>'
    const ctaBtn = $('.frm_page_num_1 .frm_submit .frm_button_submit');

    // Callback function to execute when mutations are observed
    const callback = function(mutationsList, observer) {
        // Loop through all mutations
        for(const mutation of mutationsList) {
            // Check if nodes were added
            if (mutation.type === 'childList') {
                const emptyMessage = targetNode.querySelector('div.woocommerce-mini-cart__empty-message');

                if(emptyMessage){
                    try{
                        document.querySelector('.clear-all-wrapper a').remove();
                    } 
                    catch{}

                       if($('.block_cta').length == 0){
                            $('.frm_page_num_1 .frm_submit').append(blockEl);
                            $(ctaBtn).addClass('disabled')
                       }
                  
                } else {
                    $(ctaBtn).removeClass('disabled');
                    $('.block_cta').remove();
                }

                const clearAllWrapper = targetNode.querySelector('div.clear-all-wrapper');
                if (clearAllWrapper) {
                   // console.log('div.clear-all-wrapper was added');

                        jQuery(clearAllWrapper).on('click', () => {

                            // Send AJAX request to clear the cart
                            jQuery.ajax({
                                url: '/wp-admin/admin-ajax.php',
                                method: 'POST',
                                data: {
                                    action: 'clear_cart',
                                },
                                success: function(data) {
                                    // Mini cart update logic 
                                    jQuery(document.body).trigger('wc_fragment_refresh');
                                    try{
                                    jQuery('#clear_the_cart').trigger('click');  
                                    }
                                    catch{}
                                    
                                
                                },
                                error: function(xhr, status, error) {
                                    console.error('Error:', error);
                                }
                            });
                        });
                

                }
            }
        }
    };

    const observer = new MutationObserver(callback);

    
    // Configure the observer to watch for changes in child nodes
    const config = { childList: true, subtree: true };
    
    // Start observing the target node for configured mutations
    observer.observe(targetNode, config);



})

document.addEventListener('DOMContentLoaded', function() {

    const submitButtons = document.querySelectorAll('.frm_button_submit');
    const currentStep = document.querySelector('.frm_rootline_single.frm_current_page');
    const submitRow = document.querySelectorAll('.frm_submit');

    // DO NOT ADD CLICK EVENT TO THE CHECKOUT PAGE SUBMIT BUTTONS
    const specificCheckoutForm = document.querySelector('#form_specific-candidate-donation');
    const locationCheckoutForm = document.querySelector('#form_locationspecificdonation');
    const generalCheckoutForm = document.querySelector('#form_general-fund-donation');
    const expansionCheckoutForm = document.querySelector('#form_expansion-donation');

    submitButtons.forEach((submitButton) => {

        if(submitButton.contains(locationCheckoutForm) ||
            submitButton.contains(generalCheckoutForm) ||
            submitButton.contains(expansionCheckoutForm) ||
            submitButton.contains(specificCheckoutForm))
        {
            return;
        }

        submitButton.addEventListener('click', () => {
            if (currentStep) {
                const previousStep = currentStep.previousElementSibling;
                if (previousStep) {
                    previousStep.classList.add('step-completed');
                }
            }
        });
    });

    if (!locationCheckoutForm && !generalCheckoutForm && !expansionCheckoutForm && !specificCheckoutForm) {
        if (submitRow[2]) {
            // submitRow[2].style.cssText = 'justify-content: flex-end !important';
            const nBtn = submitRow[2].querySelector('.frm_button_submit');
            nBtn.addEventListener('click', () => {
                submitRow[2].style.cssText = '';
            });
        }
    }
});


// Tooltips
document.addEventListener('DOMContentLoaded', function() {

    const resetElem = document.getElementById('frm_field_150_container');

    if(resetElem){
        resetElem.classList.remove('tooltip');
        resetElem.style.cursor = 'pointer';
        resetElem.style.marginTop = '32px'
    };

    function tooltipHandler(item, tooltip) {
        let isTooltipHovered = false;
        let isClickActivated = false;

        if(tooltip) {
            // console.log('tooltip: ', tooltip);
            tooltip.addEventListener('mouseenter', () => {
                isTooltipHovered = true;
            });

            tooltip.addEventListener('mouseleave', () => {
                isTooltipHovered = false;
            });

            item.addEventListener('mouseover', () => {
                if (!isClickActivated) {
                    tooltip.style.display = 'block';
                    tooltip.style.setProperty('bottom', 'unset', 'important');
                    tooltip.style.setProperty('left', 'unset', 'important');
                    tooltip.style.setProperty('top', 'unset', 'important');
                    tooltip.style.setProperty('right', 'unset', 'important');
                }
            });

            item.addEventListener('mouseleave', () => {
                if (!isClickActivated && !isTooltipHovered) {
                    tooltip.style.display = 'none';
                }
            });

            item.addEventListener('click', (event) => {
                event.stopPropagation();
                console.log('isClickActivated: ', isClickActivated);
                isClickActivated = !isClickActivated;
                tooltip.style.display = isClickActivated ? 'block' : 'none';
            });

            document.addEventListener('click', () => {
                tooltip.style.display = 'none';
                isClickActivated = false;
            });
        }
    }

    const tooltipContent = document.querySelectorAll('.tooltip-block');
    const tooltips = document.querySelectorAll('.tl div');

    tooltips.forEach((item, index) => {
        tooltipHandler(item, tooltipContent[index]);
    });

});

document.addEventListener('DOMContentLoaded', () => {
    const cartInpts = document.querySelectorAll('.cart-item-price');

    // console.log(cartInpts)

    if (cartInpts) {
        cartInpts.forEach((inpt) => {
            inpt.addEventListener('input', () => {
                console.log('cartInpts: ' + inpt.value);
            });
        });
    }
});

