// OVERLAY CONTAINER TOGGLE
function overlayToggle() {
    let overlayContainer = jQuery('#overlay-container');
    if (overlayContainer.is(":visible") || jQuery(overlayContainer).css('display') === 'flex') {
        overlayContainer.css('display', 'none');
        jQuery("body").css("overflow", "auto");
        console.log("overlayToggle: OFF")
    } else {
        overlayContainer.css('display', 'flex');
        jQuery("body").css("overflow", "hidden");
        console.log("overlayToggle: ON")
    }
}

jQuery(document).ready(function ($) {
   

    const minAgeDefault = 18, maxAgeDefault = 40, minPriceDefault = 100, maxPriceDefault = 10000,
        numGoalMinValuePercentage = 100 / (maxPriceDefault - minPriceDefault),
        numAgeMinValuePercentage = 100 / (maxAgeDefault - minAgeDefault);

    var cmin_age = jQuery(".age .multi-range-slider #min-value").val();
    var cmax_age = jQuery(".age .multi-range-slider #max-value").val();
    var cmin_price = jQuery(".goal .multi-range-slider #min-value-goal").val();
    var cmax_price = jQuery(".goal .multi-range-slider #max-value-goal").val();

    let ajaxurl = ajax_object.ajaxurl;
    let nonce = ajax_object.ajax_nonce;

    function candidateLoaderToggle() {
        let loader = $('.candidate_loader');
        if (loader.is(":visible")) {
            loader.css('display', 'none')
        } else {
            loader.css('display', 'block');
        }
    }

    // Set 3 column, 4 column grid style for candidate cards loop
    function get_elementor_grid_style() {
        let elementor_grid_style = 'elementor-grid-3';
     
        return elementor_grid_style;
    }

    function disableFilterButton() {
        const toggleBtn = $('.filters-toggler');
        $(toggleBtn).addClass('disablebtn');
        $('<div class="disable"></div>').insertAfter($(toggleBtn));
    }

    function enableFilterButton() {
        $('.filters-toggler ~.disable').remove();
        $('.filters-toggler').removeClass('disablebtn');
    }

    // Populate candidate cards
    function load_candidate_cards(page, corder, corder_by, cper_page, search_title, gender_type, cmax_price, cmin_price, cmin_age, cmax_age, zip_code, clat, clng, destination, elementor_grid_style = get_elementor_grid_style()) {


        // Data to receive from our server
        var data = {
            page: page,
            order: corder,
            order_by: corder_by,
            per_page: cper_page,
            search_title: search_title,
            search_gender: gender_type,
            cmax_price: cmax_price,
            cmin_price: cmin_price,
            cmin_age: cmin_age,
            cmax_age: cmax_age,
            czip_code: zip_code,
            clat: clat,
            clng: clng,
            cdestination: destination,
            elementor_grid_style: elementor_grid_style,
            action: "candidate-pagination-load-posts",
            referal_page: 'dashboard_physicians'
        };

        // console.log('Candidate Cards Data:', data);

        $.ajax({
            type: 'POST', url: ajaxurl, data: data, beforeSend: function () {
                overlayToggle();
                jQuery('.elementor-search-form__icon i').replaceWith('<i class="fas fa-spinner fa-spin"></i>');
                disableFilterButton();
            }, success: function (response) {
                // If successful, append the data into our html container
                $(".cvf-universal-content").html(response);
                // Remove focus from all input, textarea and select elements
                // jQuery('input, textarea, select').focus(function() {
                //     this.blur();
                // });
                // End the transition
                overlayToggle();
                jQuery('.elementor-search-form__icon i').replaceWith('<i aria-hidden="true" class="fas fa-search"></i>');
                enableFilterButton()
                console.log('success');
            }, error: function (jqXHR, textStatus, errorThrown) {
                // Handle errors
                console.error('AJAX Error: ' + textStatus, errorThrown);
                 overlayToggle();
                jQuery('.elementor-search-form__icon i').replaceWith('<i aria-hidden="true" class="fas fa-search"></i>');
                console.log('error');
            }
        });

    }

    // Load page 1 as the default
    load_candidate_cards(1);

    /////////// RANGE SLIDERS /////////////
    // Age manual input min value
    // let numAgeValuePercentage;
    let numGoalMinValue, numGoalMaxValue, numGoalValuePercentage;
    let inputGoalMinElement = '.goal-col input.value-from.min-value';
    let inputGoalMaxElement = '.goal-col input.value-to.max-value';

    // Function to format the input value with thousand separator
    function formatNumberWithSeparator(value) {
        return value.toLocaleString();
    }

    // Format the initial values of the "Progress to Goal" filter's input fields with thousand separator
    jQuery(document).ready(function () {
        let inputVal = parseInt(jQuery(inputGoalMinElement).val());
        let formattedValue = formatNumberWithSeparator(inputVal);
        jQuery(inputGoalMinElement).val(formattedValue);

        inputVal = parseInt(jQuery(inputGoalMaxElement).val());
        formattedValue = formatNumberWithSeparator(inputVal);
        jQuery(inputGoalMaxElement).val(formattedValue);

        // console.log('inputGoalMinElement: ' + jQuery(inputGoalMinElement).val());
        // console.log('inputGoalMaxElement: ' + jQuery(inputGoalMaxElement).val());
    });

    jQuery('.age-conumAgeMinValuel input.value-from.min-value').on('input', function () {
        let numAgeMinValue = jQuery(this).val();
        if (numAgeMinValue > minAgeDefault) {
            // get the percentage of the min value from the total range
            jQuery('.age > div > div > div.range').css('left', (numAgeMinValue - minAgeDefault) * numAgeMinValuePercentage + '%');
            jQuery('.age .multi-range-slider #min-value').val(numAgeMinValue);
        } else {
            jQuery('.age > div > div > div.range').css('left', '0%');
            jQuery('.age .multi-range-slider #min-value').val(minAgeDefault);
        }
        console.log('numAgeMinValue: ' + numAgeMinValue);
    });

    // Age manual input max value
    jQuery('.age-col input.value-to.max-value').on('input', function () {
        let numAgeMaxValue = parseInt(jQuery('.age-col input.value-to.max-value').val());
        // let numAgeMinValue = parseInt(jQuery('.age-col input.value-from.min-value').val());
        // let numAgeValuePercentage = (100 / maxAgeDefault - minAgeDefault); // Default value
        if (numAgeMaxValue <= maxAgeDefault) {

            //   console.log('numAgeValuePercentage: ' + numAgeValuePercentage);
            jQuery('.age > div > div > div.range').css('right', (maxAgeDefault - numAgeMaxValue) * numAgeMinValuePercentage + '%');
            jQuery('.age #max-value').val(numAgeMaxValue);
        } else {
            jQuery('.age > div > div > div.range').css('right', '0%');
            jQuery('.age .multi-range-slider #max-value-goal').val(maxAgeDefault);
        }
        // console.log('numAgeMinValue: ' + numAgeMinValue + ' numAgeMaxValue: ' + numAgeMaxValue + ' numAgeValuePercentage: ' + numAgeValuePercentage);
    });


    // Goal manual input min value
    // let numAgeValuePercentage;
    jQuery(inputGoalMinElement).on('input', function () {
        numGoalMinValue = parseInt(jQuery(inputGoalMinElement).val().replace(/[^0-9]/g, ''));
        if (numGoalMinValue > minPriceDefault) {
            // get the percentage of the min value from the total range

            jQuery('.goal > div > div > div.range').css('left', (numGoalMinValue - minPriceDefault) * numGoalMinValuePercentage + '%');
            jQuery('.goal .multi-range-slider #min-value-goal').val(numGoalMinValue);
        } else {
            jQuery('.goal > div > div > div.range').css('left', '0%');
            jQuery('.goal .multi-range-slider #min-value-goal').val(minPriceDefault);
        }
        // If value is a number, format it with thousand separator
        if (!isNaN(numGoalMinValue)) {
            let formattedValue = formatNumberWithSeparator(numGoalMinValue);
            $(this).val(formattedValue);
        }
        console.log('numGoalMinValue: ' + numGoalMinValue);
    });


    // Goal manual input max value
    jQuery(inputGoalMaxElement).on('input change', function () {
    // jQuery(inputGoalMaxElement).on('input', function () {
        // Allow only numbers to be entered
        numGoalMaxValue = parseInt(jQuery(inputGoalMaxElement).val().replace(/[^0-9]/g, ''));
        if (numGoalMaxValue <= maxPriceDefault && numGoalMaxValue >= minPriceDefault) {

            console.log('numGoalValuePercentage: ' + numGoalValuePercentage);
            jQuery('.goal > div > div > div.range').css('right', (maxPriceDefault - numGoalMaxValue) * numGoalMinValuePercentage + '%');
            jQuery('.goal #max-value-goal').val(numGoalMaxValue);

        } else {
            jQuery('.goal > div > div > div.range').css('right', '0%');
            jQuery('.goal .multi-range-slider #max-value-goal').val(maxPriceDefault);
        }

        // If value is a number, format it with thousand separator
        if (!isNaN(numGoalMaxValue)) {
            let formattedValue = formatNumberWithSeparator(numGoalMaxValue);
            $(this).val(formattedValue);
        }
        console.log('numGoalMinValue: ' + numGoalMinValue + ' numGoalMaxValue: ' + numGoalMaxValue + ' numGoalValuePercentage: ' + numGoalValuePercentage);
    });


    // Function to update text input with slider value
    function updateAgeSliderValue() {
        let sliderValue = jQuery('.age #max-value').val();
        jQuery('#sliderValue').val(sliderValue);
        console.log('ageSliderValue: ' + sliderValue);

    }

    // Initial update
    // updateAgeSliderValue();

    // Attach input event to slider
    jQuery('.age #max-value').on('input', function () {
        //console.log('age slider input: ' + jQuery(this).val());
        jQuery('.age-col input.value-to.max-value').val(jQuery(this).val());
    });

    jQuery('.age #min-value').on('input', function () {
        // console.log('age slider input: ' + jQuery(this).val());
        jQuery('.age-col input.value-from.min-value').val(jQuery(this).val());
    });


    jQuery('.goal #max-value-goal').on('input', function () {
        let formattedValue = formatNumberWithSeparator(parseInt(jQuery(this).val()));
        jQuery(inputGoalMaxElement).val(formattedValue);
    });

    jQuery('.goal #min-value-goal').on('input', function () {
        let formattedValue = formatNumberWithSeparator(parseInt(jQuery(this).val()));
        jQuery(inputGoalMinElement).val(formattedValue);
    });

    $(document).ready(function () {
        // Goal Range Sliders
        const goalMinValueInput = $('#min-value-goal');
        const goalMaxValueInput = $('#max-value-goal');
        const goalRangeSlider = $('.goal.range-slider');
    
        goalRangeSlider.on('input', '.min-value-slider.goal', function () {
            const minValue = parseInt($(this).val());
            const maxValue = parseInt(goalMaxValueInput.val());
    
            if (minValue > maxValue) {
                goalMaxValueInput.val(minValue);
                updateMaxValueVisual(goalMaxValueInput, '.value-to.max-value.goal');
            }
        });
    
        goalRangeSlider.on('input', '.max-value-slider.goal', function () {
            const minValue = parseInt(goalMinValueInput.val());
            const maxValue = parseInt($(this).val());
    
            if (maxValue < minValue) {
                goalMinValueInput.val(maxValue);
                updateMinValueVisual(goalMinValueInput, '.value-from.min-value.goal');
            }
        });
    
        // Age Range Sliders
        const ageMinValueInput = $('#min-value');
        const ageMaxValueInput = $('#max-value');
        const ageRangeSlider = $('.age.range-slider');
    
        ageRangeSlider.on('input', '.min-value-slider.age', function () {
            const minValue = parseInt($(this).val());
            const maxValue = parseInt(ageMaxValueInput.val());
    
            if (minValue > maxValue) {
                ageMaxValueInput.val(minValue);
                updateMaxValueVisual(ageMaxValueInput, '.value-to.max-value.age');
            }
        });
    
        ageRangeSlider.on('input', '.max-value-slider.age', function () {
            const minValue = parseInt(ageMinValueInput.val());
            const maxValue = parseInt($(this).val());
    
            if (maxValue < minValue) {
                ageMinValueInput.val(maxValue);
                updateMinValueVisual(ageMinValueInput, '.value-from.min-value.age');
            }
        });
    
        function updateMinValueVisual(inputElement, visualSelector) {
            const minValueVisual = $(visualSelector);
            minValueVisual.val(inputElement.val());
        }
    
        function updateMaxValueVisual(inputElement, visualSelector) {
            const maxValueVisual = $(visualSelector);
            maxValueVisual.val(inputElement.val());
        }
    });    

    /////////// FILTER ZIP CODE VALIDATION /////////////

    const inputField = document.querySelector('#zippbox');

    inputField.addEventListener('input', function (event) {
        const enteredValue = event.target.value;

        // Remove non-digit characters except for digits
        const sanitizedValue = enteredValue.replace(/\D/g, '');

        // Check if the length of the sanitized value is more than 5
        if (sanitizedValue.length > 5) {
            event.target.value = sanitizedValue.slice(0, 5); // Truncate to 5 digits
        } else {
            event.target.value = sanitizedValue; // Update the input value
        }
    });


    // Show More Candidate Button Click Event (Delegate Event)
    jQuery("body").on("click", ".cvf-universal-pagination li.active", function (event) {
        console.log(".cvf-universal-pagination li.active clicked");

        // Check if page contains elementor-grid-3 or elementor-grid-4 and then load more candidates accordingly
        var elementor_grid_style = get_elementor_grid_style();
        var page = jQuery("#pnumber").attr('p');
        var search_title = jQuery("#elementor-search-form-08120cf").val();
        var cper_page = jQuery('.candidate-per-page-select').val();
        var corder_by = jQuery('.candidate-sorting-select').val();
        var corder = jQuery('.candidate-sorting-select').find(':selected').attr('data-order');
        var cmin_price = jQuery(".goal .multi-range-slider #min-value-goal").val();
        var cmax_price = jQuery(".goal .multi-range-slider #max-value-goal").val();
        var cmin_age = jQuery(".age .multi-range-slider #min-value").val();
        var cmax_age = jQuery(".age .multi-range-slider #max-value").val();
        var gender_male = jQuery('#candidate_male:checked').val();
        var gender_female = jQuery('#candidate_female:checked').val();
        if (gender_male !== undefined && gender_female !== undefined) {
            var gender_type = "";
        } else {
            var gender_type = jQuery('input[name="gender_type"]:checked').val();
        }
        if (gender_type === "undefined") {
            gender_type = "";
        }
        var zip_code = jQuery('input[name="candidate_zip_code"]').val();
        if (zip_code.length != 5 && zip_code !== '') {
            console.log("zip-code lenght has to be 5 and can't be empty: " + zip_code);
            zip_code = '';
        }
        var destination = jQuery('input[name="destination"]:checked').val();

        // console.log(page);
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
        // console.log("page: " + page);
        // console.log("gender-type: " + gender_type);
        // console.log("zip-code: " + zip_code);
        // console.log("destination: " + destination);

        if (destination != "" && zip_code.length == 5) {
            var lat = '';
            var lng = '';
            var settings = {
                "url": "https://maps.googleapis.com/maps/api/geocode/json?address=components=postal_code:" + zip_code + " USA&sensor=false&key=AIzaSyB5Dc2DupDK2lz6m0J3TJglvixt8gnqXSE",
                "method": "POST",
                "timeout": 0,
            };

            //overlayToggle();
            $.ajax(settings).done(function (response) {
                // console.log(response);
                const southwestLat = response['results'][0]?.geometry?.viewport?.southwest?.lat;
                // console.log("lat: " + southwestLat);
                const southwestLng = response['results'][0]?.geometry?.viewport?.southwest?.lng;
                // console.log("lng: " + southwestLng);
                if (response.status == 'OK') {
                    var clat = southwestLat;
                    // console.log("clat: " + clat);
                    var clng = southwestLng;
                    // console.log("clng: " + clng);
                    // zip_code = '';
                    load_candidate_cards(page, corder, corder_by, cper_page, search_title, gender_type, cmax_price, cmin_price, cmin_age, cmax_age, zip_code, clat, clng, destination, elementor_grid_style);
                    // //overlayToggle();
                    candidateLoaderToggle();
                }
            });
        } else {
            load_candidate_cards(page, corder, corder_by, cper_page, search_title, gender_type, cmax_price, cmin_price, cmin_age, cmax_age, zip_code, undefined, undefined, undefined, elementor_grid_style);
            // //overlayToggle();
            candidateLoaderToggle();
        }
    });


    // Apply Filters Button Click Event
    jQuery("body").on("click", "#apply-filters", function (event) {
        console.log("Apply Filters Button Click Event");

        // Check if page contains elementor-grid-3 or elementor-grid-4 and then load more candidates accordingly
        var elementor_grid_style = get_elementor_grid_style();
        var page = 1;
        var search_title = jQuery("#elementor-search-form-08120cf").val();
        var cper_page = jQuery('.candidate-per-page-select').val();

        var corder_by = jQuery('.candidate-sorting-select').val();
        var corder = jQuery('.candidate-sorting-select').find(':selected').attr('data-order');

        var cmin_price = jQuery(".goal .multi-range-slider #min-value-goal").val();
        var cmax_price = jQuery(".goal .multi-range-slider #max-value-goal").val();

        var cmin_age = jQuery(".age .multi-range-slider #min-value").val();
        var cmax_age = jQuery(".age .multi-range-slider #max-value").val();

        var gender_male = jQuery('#candidate_male:checked').val();
        var gender_female = jQuery('#candidate_female:checked').val();
        if (gender_male !== undefined && gender_female !== undefined) {
            var gender_type = "";
        } else {
            var gender_type = jQuery('input[name="gender_type"]:checked').val();
        }
        if (gender_type === "undefined") {
            gender_type = "";
        }
        var zip_code = jQuery('input[name="candidate_zip_code"]').val();
        if (zip_code.length != 5 && zip_code !== '') {
            console.log("zip-code lenght has to be 5 and can't be empty: " + zip_code);
            zip_code = '';
        }
        var destination = jQuery('input[name="destination"]:checked').val();

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
            var lat = '';
            var lng = '';
            var settings = {
                "url": "https://maps.googleapis.com/maps/api/geocode/json?address=components=postal_code:" + zip_code + " USA&sensor=false&key=AIzaSyB5Dc2DupDK2lz6m0J3TJglvixt8gnqXSE",
                "method": "POST",
                "timeout": 0,
            };

            //overlayToggle();
            $.ajax(settings).done(function (response) {
                // console.log(response);
                const southwestLat = response['results'][0]?.geometry?.viewport?.southwest?.lat;
                // console.log("lat: " + southwestLat);
                const southwestLng = response['results'][0]?.geometry?.viewport?.southwest?.lng;
                // console.log("lng: " + southwestLng);
                if (response.status == 'OK') {
                    var clat = southwestLat;
                    // console.log("clat: " + clat);
                    var clng = southwestLng;
                    // console.log("clng: " + clng);
                    // zip_code = '';
                    load_candidate_cards(page, corder, corder_by, cper_page, search_title, gender_type, cmax_price, cmin_price, cmin_age, cmax_age, zip_code, clat, clng, destination, elementor_grid_style);
                }
                //overlayToggle();
            });
        } else {
            load_candidate_cards(page, corder, corder_by, cper_page, search_title, gender_type, cmax_price, cmin_price, cmin_age, cmax_age, zip_code, undefined, undefined, undefined, elementor_grid_style);
            //overlayToggle();
        }
    });


    // Sort items
    jQuery('.candidate-sorting-select').on('change', function () {
        console.log(".candidate-sorting-select changed");

        // Check if page contains elementor-grid-3 or elementor-grid-4 and then load more candidates accordingly
        var elementor_grid_style = get_elementor_grid_style();
        var page = parseInt(jQuery("#pnumber").attr('p'));
        page = (page <= 1 ? 1 : page - 1);
        var search_title = jQuery("#elementor-search-form-08120cf").val();
        var cper_page = jQuery('.candidate-per-page-select').val();
        var corder_by = jQuery('.candidate-sorting-select').val();
        var corder = jQuery('.candidate-sorting-select').find(':selected').attr('data-order');
        var cmin_price = jQuery(".goal .multi-range-slider #min-value-goal").val();
        var cmax_price = jQuery(".goal .multi-range-slider #max-value-goal").val();
        var cmin_age = jQuery(".age .multi-range-slider #min-value").val();
        var cmax_age = jQuery(".age .multi-range-slider #max-value").val();
        var gender_male = jQuery('#candidate_male:checked').val();
        var gender_female = jQuery('#candidate_female:checked').val();
        if (gender_male !== undefined && gender_female !== undefined) {
            var gender_type = "";
        } else {
            var gender_type = jQuery('input[name="gender_type"]:checked').val();
        }
        if (gender_type === "undefined") {
            gender_type = "";
        }
        var zip_code = jQuery('input[name="candidate_zip_code"]').val();
        if (zip_code.length != 5 && zip_code !== '') {
            console.log("zip-code lenght has to be 5 and can't be empty: " + zip_code);
            zip_code = '';
        }
        var destination = jQuery('input[name="destination"]:checked').val();

        if (destination != "" && zip_code.length == 5) {
            var lat = '';
            var lng = '';

            var settings = {
                "url": "https://maps.googleapis.com/maps/api/geocode/json?address=components=postal_code:" + zip_code + " USA&sensor=false&key=AIzaSyB5Dc2DupDK2lz6m0J3TJglvixt8gnqXSE",
                "method": "POST",
                "timeout": 0,
            };

            //overlayToggle();
            $.ajax(settings).done(function (response) {
                // console.log(response);
                const southwestLat = response['results'][0]?.geometry?.viewport?.southwest?.lat;
                const southwestLng = response['results'][0]?.geometry?.viewport?.southwest?.lng;
                if (response.status == 'OK') {
                    var clat = southwestLat;
                    var clng = southwestLng;
                    zip_code = '';
                    load_candidate_cards(1, corder, corder_by, cper_page, search_title, gender_type, cmax_price, cmin_price, cmin_age, cmax_age, zip_code, clat, clng, destination, elementor_grid_style);
                }
                //overlayToggle();
            });
        } else {
            load_candidate_cards(page, corder, corder_by, undefined, search_title, gender_type, cmax_price, cmin_price, cmin_age, cmax_age, zip_code, undefined, undefined, undefined, elementor_grid_style);
            //overlayToggle();
        }
    });

    // Items Per Page
    jQuery('.candidate-per-page-select').on('change', function () {
        console.log("Running from per page changed function");

        // Check if page contains elementor-grid-3 or elementor-grid-4 and then load more candidates accordingly
        var elementor_grid_style = get_elementor_grid_style();
        var page = 1;
        var search_title = jQuery("#elementor-search-form-08120cf").val();
        var cper_page = jQuery('.candidate-per-page-select').val();
        var corder_by = jQuery('.candidate-sorting-select').val();
        var corder = jQuery('.candidate-sorting-select').find(':selected').attr('data-order');
        var cmin_price = jQuery(".goal .multi-range-slider #min-value-goal").val();
        var cmax_price = jQuery(".goal .multi-range-slider #max-value-goal").val();
        var cmin_age = jQuery(".age .multi-range-slider #min-value").val();
        var cmax_age = jQuery(".age .multi-range-slider #max-value").val();
        var gender_male = jQuery('#candidate_male:checked').val();
        var gender_female = jQuery('#candidate_female:checked').val();
        if (gender_male !== undefined && gender_female !== undefined) {
            var gender_type = "";
        } else {
            var gender_type = jQuery('input[name="gender_type"]:checked').val();
        }
        if (gender_type === "undefined") {
            gender_type = "";
        }
        var zip_code = jQuery('input[name="candidate_zip_code"]').val();
        if (zip_code.length != 5 && zip_code !== '') {
            console.log("zip-code lenght has to be 5 and can't be empty: " + zip_code);
            zip_code = '';
        }
        var destination = jQuery('input[name="destination"]:checked').val();


        if (destination != "" && zip_code.length == 5) {
            var lat = '';
            var lng = '';

            var settings = {
                "url": "https://maps.googleapis.com/maps/api/geocode/json?address=components=postal_code:" + zip_code + " USA&sensor=false&key=AIzaSyB5Dc2DupDK2lz6m0J3TJglvixt8gnqXSE",
                "method": "POST",
                "timeout": 0,
            };

            //overlayToggle();
            $.ajax(settings).done(function (response) {
                // console.log(response);
                const southwestLat = response['results'][0]?.geometry?.viewport?.southwest?.lat;
                const southwestLng = response['results'][0]?.geometry?.viewport?.southwest?.lng;
                // console.log(southwestLat);
                // console.log(southwestLng);
                if (response.status == 'OK') {
                    var clat = southwestLat;
                    var clng = southwestLng;
                    zip_code = '';
                    load_candidate_cards(page, corder, corder_by, cper_page, search_title, gender_type, cmax_price, cmin_price, cmin_age, cmax_age, zip_code, clat, clng, destination, elementor_grid_style);
                }
                //overlayToggle();
            });
        } else {
            load_candidate_cards(page, corder, corder_by, cper_page, search_title, gender_type, cmax_price, cmin_price, cmin_age, cmax_age, zip_code, undefined, undefined, undefined, elementor_grid_style);
            //overlayToggle();
        }
    });

    function onSearchInputChange() {

        jQuery(".sidebar_candidate_loader").show();

        // Check if page contains elementor-grid-3 or elementor-grid-4 and then load more candidates accordingly
        var elementor_grid_style = get_elementor_grid_style();
        var search_title = jQuery("#elementor-search-form-08120cf").val();
        var cper_page = jQuery('.candidate-per-page-select').val();
        var corder_by = jQuery('.candidate-sorting-select').val();
        var corder = jQuery('.candidate-sorting-select').find(':selected').attr('data-order');
        var cmin_price = jQuery(".goal .multi-range-slider #min-value-goal").val();
        var cmax_price = jQuery(".goal .multi-range-slider #max-value-goal").val();
        var cmin_age = jQuery(".age .multi-range-slider #min-value").val();
        var cmax_age = jQuery(".age .multi-range-slider #max-value").val();
        var gender_male = jQuery('#candidate_male:checked').val();
        var gender_female = jQuery('#candidate_female:checked').val();
        if (gender_male !== undefined && gender_female !== undefined) {
            var gender_type = "";
        } else {
            var gender_type = jQuery('input[name="gender_type"]:checked').val();
        }
        if (gender_type === "undefined") {
            gender_type = "";
        }
        var zip_code = jQuery('input[name="candidate_zip_code"]').val();
        if (zip_code.length != 5 && zip_code !== '') {
            console.log("zip-code lenght has to be 5 and can't be empty: " + zip_code);
            zip_code = '';
        }
        var destination = jQuery('input[name="destination"]:checked').val();

        if (destination != "" && zip_code.length == 5) {
            var lat = '';
            var lng = '';

            var settings = {
                "url": "https://maps.googleapis.com/maps/api/geocode/json?address=components=postal_code:" + zip_code + " USA&sensor=false&key=AIzaSyB5Dc2DupDK2lz6m0J3TJglvixt8gnqXSE",
                "method": "POST",
                "timeout": 0,
            };

            $.ajax(settings).done(function (response) {
                const southwestLat = response['results'][0]?.geometry?.viewport?.southwest?.lat;
                const southwestLng = response['results'][0]?.geometry?.viewport?.southwest?.lng;
                if (response.status == 'OK') {
                    var clat = southwestLat;
                    var clng = southwestLng;
                    zip_code = '';
                    load_candidate_cards(1, corder, corder_by, cper_page, search_title, gender_type, cmax_price, cmin_price, cmin_age, cmax_age, zip_code, clat, clng, destination, elementor_grid_style);
                }
            });
        } else {
            load_candidate_cards(1, corder, corder_by, cper_page, search_title, gender_type, cmax_price, cmin_price, cmin_age, cmax_age, zip_code, undefined, undefined, undefined, elementor_grid_style);
        }
    }

    // Debounce function to add a delay while typing
    function debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    }

    // Attaching the debounced event handler
    const debouncedSearchInputChange = debounce(onSearchInputChange, 300); // Adjust the delay (in milliseconds) as needed
    jQuery('#elementor-search-form-08120cf').on('input', debouncedSearchInputChange);
    $('#elementor-search-form-08120cf').keydown(function (event) {
        if (event.key === 'Enter') {
            event.preventDefault();
        }
        // console.log(event.key);
    });


    // Clear all filters
    jQuery('#clear-all-filters').on('click', function () {
        console.log("Clear All Filters Button Click Event");

        jQuery('#elementor-search-form-08120cf').val('');
        jQuery('.goal .multi-range-slider #min-value-goal').val(minPriceDefault);
        jQuery('.goal .multi-range-slider #max-value-goal').val(maxPriceDefault);
        jQuery('.goal-col .value-from').val(minPriceDefault);
        jQuery('.goal-col .value-to').val(maxPriceDefault);
        jQuery('.age .multi-range-slider #min-value').val(minAgeDefault);
        jQuery('.age .multi-range-slider #max-value').val(maxAgeDefault);
        jQuery('.age-col .value-from').val(minAgeDefault);
        jQuery('.age-col .value-to').val(maxAgeDefault);
        jQuery('.range').css({'left': '0%', 'right': '0%'});
        jQuery('.goal-col .value-from').text('$' + minPriceDefault);
        jQuery('.goal-col .value-to').text('$' + maxPriceDefault);
        jQuery('.age-col .value-from').text(minAgeDefault);
        jQuery('.age-col .value-to').text(maxAgeDefault);
        jQuery('#candidate_male').prop('checked', false);
        jQuery('#candidate_female').prop('checked', false);
        jQuery('input[name=destination]:first').prop('checked', true);
        jQuery('#zippbox').val('');
        jQuery('#apply-filters').trigger('click');
    });

});

