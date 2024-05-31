document.addEventListener('DOMContentLoaded', function () {
//Get dashboard page
const ajaxurl = filterData.ajaxUrl;
       // console.log('URL:'+ajaxurl)
    //Show and hide filter section
    const button = document.querySelector('#accordionButton');
    const content = document.querySelector('#accordionContent');

    button.addEventListener('click', function () {
    if (content.style.display === 'none') {
        content.style.display = 'flex';
        button.classList.add('active');
    } else {
        content.style.display = 'none';
        button.classList.remove('active');
    }
    });


    // Filters
    

    
//Filter apply
    jQuery("#physicians-apply-filters").on("click", fetchProviders);
    jQuery("#search_input").on("keyup", fetchProviders);
    jQuery("#providers_per_page").on("change", fetchProviders);
    
    jQuery("#providers_sorting").on("change", fetchProviders);
    jQuery('#providers_list').on("click", '.show_more .btn', fetchProviders);

    jQuery('#providers_list').on("click", '.select-ph', function () {
        data = {    
            id: jQuery(this).data('provider-id'),
            provider_name: jQuery(this).data('provider-name'),
            provider_business_name: jQuery(this).data('provider-business-name'),
            provider_url: jQuery(this).data('provider-url'),
            provider_speciality: jQuery(this).data('provider-speciality'),
            provider_phone: jQuery(this).data('provider-phone'),
            action: "update_physician_for_candidates",
        };
        jQuery.ajax({
            type: 'POST', 
            url: filterData.ajaxUrl, 
            data: data, 
            
            //     beforeSend: function () {
            //     // overlayToggle();
            //     jQuery('.elementor-search-form__icon i').replaceWith('<i class="fas fa-spinner fa-spin"></i>');
            //     disableFilterButton();
            // }, 
            success: function (response) {
                console.log('response:', response);
                // If successful, append the data into our html container
               // jQuery("#providers_list").empty();
               // jQuery("#providers_list").html(response);
                // Remove focus from all input, textarea and select elements
                // jQuery('input, textarea, select').focus(function() {
                //     this.blur();
                // });
                // End the transition
               // overlayToggle();
               // jQuery('.elementor-search-form__icon i').replaceWith('<i aria-hidden="true" class="fas fa-search"></i>');
               // enableFilterButton()
     
            }, error: function (jqXHR, textStatus, errorThrown) {
                // Handle errors
                console.error('AJAX Error: ' + textStatus, errorThrown);
                // overlayToggle();
              //  jQuery('.elementor-search-form__icon i').replaceWith('<i aria-hidden="true" class="fas fa-search"></i>');
                console.log('error');
            }
        });
    });
    function fetchProviders(){

        let page = (jQuery('.show_more .btn').length !=0 ) ? jQuery('.show_more .btn').data('page-number') : 1;
        let search_title = jQuery("#search_input").val();
        let cper_page = jQuery('#providers_per_page').val();
        let corder_by = jQuery('#providers_sorting').val();
        let corder = jQuery('#providers_sorting').find(':selected').attr('data-order');
        let zip_code = jQuery('#zippbox').val();
        if (zip_code.length != 5 && zip_code !== '') {
            console.log("zip-code lenght has to be 5 and can't be empty: " + zip_code);
            zip_code = '';
        }
        let destination = jQuery('input[name="destination"]:checked').val();
        let selectedSpecialties = [];
        jQuery('input[name="physicians_type"]:checked').each(function() {
            selectedSpecialties.push(jQuery(this).val());
        });
      
        
        

        if (destination != "" && zip_code.length == 5) {
            const lat = '';
            const lng = '';
            const settings = {
                "url": "https://maps.googleapis.com/maps/api/geocode/json?address=" + zip_code + "&key=AIzaSyB5Dc2DupDK2lz6m0J3TJglvixt8gnqXSE",
                "method": "POST",
                "timeout": 0,
            };

            //overlayToggle();
            jQuery.ajax(settings).done(function (response) {
                 
                const southwestLat = response['results'][0]?.geometry?.viewport?.southwest?.lat;
                // console.log("lat: " + southwestLat);
                const southwestLng = response['results'][0]?.geometry?.viewport?.southwest?.lng;
                // console.log("lng: " + southwestLng);
                if (response.status == 'OK') {
                    const clat = southwestLat;
                    const clng = southwestLng;
                    
                    load_providers_cards(page, corder, corder_by, cper_page, search_title, zip_code, clat, clng, destination,selectedSpecialties);
                    
                    zip_code = '';
                }
               // overlayToggle();
            });
        } else {
            load_providers_cards(page, corder, corder_by, cper_page, search_title, zip_code, undefined, undefined, undefined,selectedSpecialties);
            //overlayToggle();
        }

       

    };


 // Populate candidate cards
 function load_providers_cards(page, corder, corder_by, cper_page, search_title, zip_code, clat, clng, destination,selectedSpecialties) {


    // Data to receive from our server
    let data = {
        page: page,
        order: corder,
        order_by: corder_by,
        per_page: cper_page,
        search_title: search_title,
        phzip_code: zip_code,
        phlat: clat,
        phlng: clng,
        phdestination: destination,
        speciality: selectedSpecialties,
        search_title: search_title,
        action: "provider-pagination-load-posts"
    };

     console.log('Candidate Cards Data:', data);

    jQuery.ajax({
        type: 'POST', url: filterData.ajaxUrl, data: data, 
        //     beforeSend: function () {
        //     // overlayToggle();
        //     jQuery('.elementor-search-form__icon i').replaceWith('<i class="fas fa-spinner fa-spin"></i>');
        //     disableFilterButton();
        // }, 
        success: function (response) {
            
            // If successful, append the data into our html container
            jQuery("#providers_list").empty();
            jQuery("#providers_list").html(response);
            // Remove focus from all input, textarea and select elements
            // jQuery('input, textarea, select').focus(function() {
            //     this.blur();
            // });
            // End the transition
           // overlayToggle();
           // jQuery('.elementor-search-form__icon i').replaceWith('<i aria-hidden="true" class="fas fa-search"></i>');
           // enableFilterButton()
 
        }, error: function (jqXHR, textStatus, errorThrown) {
            // Handle errors
            console.error('AJAX Error: ' + textStatus, errorThrown);
            // overlayToggle();
          //  jQuery('.elementor-search-form__icon i').replaceWith('<i aria-hidden="true" class="fas fa-search"></i>');
            console.log('error');
        }
    });

}

fetchProviders();
});
