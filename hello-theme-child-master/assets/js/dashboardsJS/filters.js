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

//Filter apply
    jQuery("#apply-filters").on("click", function (event) {
        console.log("Apply Filters Button Click Event");

        // Check if page contains elementor-grid-3 or elementor-grid-4 and then load more candidates accordingly
        


        const page = 1;
        const search_title = jQuery(".search_input").val();
        const cper_page = jQuery('#providers_per_page').val();
        const corder_by = jQuery('#providers_sorting').val();
        const corder = jQuery('#providers_sorting').find(':selected').attr('data-order');
        const zip_code = jQuery('#zippbox').val();
        if (zip_code.length != 5 && zip_code !== '') {
            console.log("zip-code lenght has to be 5 and can't be empty: " + zip_code);
            zip_code = '';
        }
        const destination = jQuery('input[name="destination"]:checked').val();
        const selectedSpecialties = [];
        jQuery('input[name="physicians_type"]:checked').each(function() {
            selectedSpecialties.push(jQuery(this).val());
        });
      
        // console.log("page: " + page);
        // console.log("gender-male: " + gender_male);
        // console.log("gender-female: " + gender_female);
        // console.log("gender-type: " + gender_type);
        // console.log("search-title: " + search_title);
        // console.log("cper-page: " + cper_page);
        // console.log("corder-by: " + corder_by);
        // console.log("corder: " + corder);
        // console.log("cmin-price: " + cmin_price);
        // console.log("cmax-price: " + cmax_price);
        // console.log("cmin-age: " + cmin_age);
        // console.log("cmax_age: " + cmax_age);
        // console.log("gender-type: " + gender_type);
        // console.log("zip-code: " + zip_code);
        // console.log("destination: " + destination);

        if (destination != "" && zip_code.length == 5) {
            const lat = '';
            const lng = '';
            const settings = {
                "url": "https://maps.googleapis.com/maps/api/geocode/json?address=components=postal_code:" + zip_code + " USA&sensor=false&key=AIzaSyB5Dc2DupDK2lz6m0J3TJglvixt8gnqXSE",
                "method": "POST",
                "timeout": 0,
            };

            //overlayToggle();
            jQuery.ajax(settings).done(function (response) {
                // console.log(response);
                const southwestLat = response['results'][0]?.geometry?.viewport?.southwest?.lat;
                // console.log("lat: " + southwestLat);
                const southwestLng = response['results'][0]?.geometry?.viewport?.southwest?.lng;
                // console.log("lng: " + southwestLng);
                if (response.status == 'OK') {
                    const clat = southwestLat;
                    // console.log("clat: " + clat);
                    const clng = southwestLng;
                    // console.log("clng: " + clng);
                    // zip_code = '';
                    load_providers_cards(page, corder, corder_by, cper_page, search_title, zip_code, clat, clng, destination,selectedSpecialties);
                }
               // overlayToggle();
            });
        } else {
            load_providers_cards(page, corder, corder_by, cper_page, search_title, zip_code, undefined, undefined, undefined,selectedSpecialties);
            //overlayToggle();
        }
    });

});

 // Populate candidate cards
 function load_providers_cards(page, corder, corder_by, cper_page, search_title, zip_code, clat, clng, destination,selectedSpecialties) {


    // Data to receive from our server
    var data = {
        page: page,
        order: corder,
        order_by: corder_by,
        per_page: cper_page,
        search_title: search_title,
        czip_code: zip_code,
        clat: clat,
        clng: clng,
        cdestination: destination,
        speciality: selectedSpecialties,
        action: "provider-pagination-load-posts"
    };

    // console.log('Candidate Cards Data:', data);

    jQuery.ajax({
        type: 'POST', url: filterData.ajaxUrl, data: data, 
        //     beforeSend: function () {
        //     // overlayToggle();
        //     jQuery('.elementor-search-form__icon i').replaceWith('<i class="fas fa-spinner fa-spin"></i>');
        //     disableFilterButton();
        // }, 
        success: function (response) {
            // If successful, append the data into our html container
            jQuery("#providers_list").html(response);
            // Remove focus from all input, textarea and select elements
            // jQuery('input, textarea, select').focus(function() {
            //     this.blur();
            // });
            // End the transition
           // overlayToggle();
           // jQuery('.elementor-search-form__icon i').replaceWith('<i aria-hidden="true" class="fas fa-search"></i>');
           // enableFilterButton()
            console.log('success');
        }, error: function (jqXHR, textStatus, errorThrown) {
            // Handle errors
            console.error('AJAX Error: ' + textStatus, errorThrown);
            // overlayToggle();
          //  jQuery('.elementor-search-form__icon i').replaceWith('<i aria-hidden="true" class="fas fa-search"></i>');
            console.log('error');
        }
    });

}