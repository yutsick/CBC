// OVERLAY CONTAINER TOGGLE
function overlayToggle() {
    let overlayContainer = jQuery('#overlay-container');
    if (overlayContainer.is(":visible")) {
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
        let element = document.querySelector('.woocommerce.elementor-element');
        let elementor_grid_style = '';

        if (element.classList.contains('elementor-grid-3')) {
            elementor_grid_style = 'elementor-grid-3';
        } else if (element.classList.contains('elementor-grid-4')) {
            elementor_grid_style = 'elementor-grid-4';
        }
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
    function load_candidate_cards(page, corder, corder_by, cper_page, search_title, gender_type, cmax_price, cmin_price, cmin_age, cmax_age, zip_code, clat, clng, destination, elementor_grid_style) {


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
            action: "candidate-pagination-load-posts"
        };

        // console.log('Candidate Cards Data:', data);

        $.ajax({
            type: 'POST', url: ajaxurl, data: data, beforeSend: function () {
                // overlayToggle();
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
                // console.log('success');
            }, error: function (jqXHR, textStatus, errorThrown) {
                // Handle errors
                console.error('AJAX Error: ' + textStatus, errorThrown);
                // overlayToggle();
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

            overlayToggle();
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
                    // overlayToggle();
                    candidateLoaderToggle();
                }
            });
        } else {
            load_candidate_cards(page, corder, corder_by, cper_page, search_title, gender_type, cmax_price, cmin_price, cmin_age, cmax_age, zip_code, undefined, undefined, undefined, elementor_grid_style);
            // overlayToggle();
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

            overlayToggle();
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
                overlayToggle();
            });
        } else {
            load_candidate_cards(page, corder, corder_by, cper_page, search_title, gender_type, cmax_price, cmin_price, cmin_age, cmax_age, zip_code, undefined, undefined, undefined, elementor_grid_style);
            overlayToggle();
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

            overlayToggle();
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
                overlayToggle();
            });
        } else {
            load_candidate_cards(page, corder, corder_by, undefined, search_title, gender_type, cmax_price, cmin_price, cmin_age, cmax_age, zip_code, undefined, undefined, undefined, elementor_grid_style);
            overlayToggle();
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

            overlayToggle();
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
                overlayToggle();
            });
        } else {
            load_candidate_cards(page, corder, corder_by, cper_page, search_title, gender_type, cmax_price, cmin_price, cmin_age, cmax_age, zip_code, undefined, undefined, undefined, elementor_grid_style);
            overlayToggle();
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

//////////////////////////////////////////////////////////////////////////////////////
// POPUP MODAL HANDLERS
// RADIO BUTTONS HANDLER
//////////////////////////////////////////////////////////////////////////////////////
jQuery(document).ready(function ($) {

    let ajaxurl = ajax_object.ajaxurl;
    let nonce = ajax_object.ajax_nonce;
    let productId = '';
    let candidateAmountRemaining = 0;

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

//////////////////////////////////////////////////////////////////////////////////////
// DONATION and ADD TO CART BUTTONS HANDLER
//////////////////////////////////////////////////////////////////////////////////////

    $(document).on('click', '.elementor-button[href=\"#donation-popup\"]', function (e) {
        e.preventDefault();
        productId = $(this).closest('[id]').attr('id');
        // console.log('Selected card_id: ' + productId); // .post-26229
        let candidateName = $('.post-' + productId + ' #card-candidate-name').text();
        // console.log('candidateName: ' + candidateName);
        let candidateImageSrc = $('.post-' + productId + ' #card-candidate-image').attr('src');
        let candidateAmountRaised = $('.post-' + productId + ' #card-candidate-amount-raised').text().replace(/\D/g, '');
        let candidateAmountGoal = $('.post-' + productId + ' #card-candidate-amount-goal').text().replace(/\D/g, '');
        candidateAmountRemaining = candidateAmountGoal - candidateAmountRaised;

        let voucherCode = getCookie('ckVoucherCode');
        let voucherAmount = getCookie('ckVoucherAmount');
        let notification = $('#woocommerce_voucher_notification');
        let removeAnchor = $('#remove_coupon_code_value')[0]; // Getting the DOM element

        if (voucherAmount && voucherCode) {
            $('#voucher_code').val(voucherAmount);
            $('.acceptance-modal').hide();
            $('.elementor-field-subgroup').hide();

            notification.removeClass().addClass('woocommerce-message')
                .html('Voucher: <span style="color:#01b401;font-weight: 500;">' + voucherCode + '</span> applied' + removeAnchor.outerHTML).show();

            // $('.applied-voucher-code-message').show();
            $('#coupon_code_value').html(voucherAmount).show();
            $('#remove_coupon_code_value').attr('data-voucher_code', voucherCode);

            // $('#apply_voucher_button').trigger('click');
            console.log('Voucher cookie exists: ' + voucherCode);
        }


        // Update #donation-popup content
        $('#popdon-candidate-name > div > h1').text(candidateName);
        $('#popdon-candidate-image > div > img').attr({'srcset': '', 'src': candidateImageSrc});
        jQuery('#dAmount_full').attr({
            'id': 'dAmount_' + candidateAmountRemaining, 'value': candidateAmountRemaining
        });
        console.log('Remaining_amount: ' + candidateAmountRemaining);
        jQuery('label[for="dAmount_full"]').attr('for', 'dAmount_' + candidateAmountRemaining);

        if (candidateAmountRemaining > 0) { // Means if candidate is not fully funded.
            // Get all radio buttons with the name 'donationAmount'
            const radioButtonsDonationAmounts = document.querySelectorAll('input[name="donationAmount"]');

            radioButtonsDonationAmounts.forEach(button => {
                button.checked = false;
                const $button = $(button);
                const buttonValue = parseInt($button.val()); // Convert to number
                if (buttonValue > candidateAmountRemaining) {
                    // console.log('buttonValue: ' + buttonValue + ' remainingAmount: ' + candidateAmountRemaining);
                    $button.closest('.inputs-option').hide(); // Hide the parent span
                }
            });

            // Add event listener to each radio button
            radioButtonsDonationAmounts.forEach(button => {
                button.addEventListener('click', () => {
                    let otherAmountElement = $('#dAmount_other_input');
                    const selectedValue = button.value;
                    const formattedValue = '$' + new Intl.NumberFormat().format(selectedValue);
                    console.log('selectedValue: ' + selectedValue + ' selectedId: ' + button.id);
                    /*
                    * Before changing the text, check if the donation amount element is span or input
                    * If element is 'input', then replace it with 'span'
                    */
                    if ($(this).attr('id') !== 'dAmount_other') {
                        // Check if the element with id 'dAmount_other_input' exists
                        if (otherAmountElement.length > 0) {
                            // Replace with the specified span element code
                            var newSpan = $('<span class="elementor-heading-title elementor-size-large">$0.00</span>');
                            // Remove the appended span from donation amount input field, before populating it
                            otherAmountElement.closest('.elementor-widget-container')
                                .find('span').remove();
                            otherAmountElement.replaceWith(newSpan);
                        }
                        jQuery('#donation_amount_single_box div span').text(formattedValue);
                        // add click event listener to this span
                        $('#donation_amount_single_box div span').click(function () {
                            enable_other_amount_input();
                        });

                    } else {
                        jQuery('#donation_amount_single_box div span').text(formattedValue);
                    }
                });
            });

            function enable_other_amount_input() {
                // Create a new input element
                let newInput = $('<input type="text" id="dAmount_other_input" ' + 'class="elementor-heading-title elementor-size-large" placeholder="Type the amount here...">');
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
            $('#donation_amount_single_box > div > span').click(function () {
                enable_other_amount_input();
            });
        } else {
            // Hide the 'Donate Now' button
            $('#donate-now-button').hide();
            $('.elementor-element-588a2d49, .elementor-element-19c3324, .elementor-element-2b44284, .elementor-element-34715c91').hide();

            $('.elementor-element-450bb3a0').css({
                'display': 'flex', 'justify-content': 'center',
            });
            $('.elementor-element-450bb3a0 .elementor-button-wrapper').css({
                'display': 'flex', 'justify-content': 'center',
            });

            // add custom message
            $('#donation_amount_single_box div span').text('Sorry, this candidate is already fully funded, please select another candidate.').css({
                'font-size': '22px',
            });
        }


        setTimeout(function () {
            jQuery('#elementor-popup-modal-58203').attr('style', 'display:flex !important');
        }, 200);
    });

    // Mask the input field to not allow to type any non-numeric characters
    jQuery(document).on('input', '#donation_amount_single_box input', function (event) {
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
        if (parseInt(numericValue) > parseInt(candidateAmountRemaining)) {
            numericValue = candidateAmountRemaining;
            // empty the appended span before populating it
            $(this).closest('.elementor-widget-container').find('#amount_error_message').remove();
            $(this).closest('.elementor-widget-container').append('<span id="amount_error_message" style="color: red; font-size: 12px;">' + 'The Donation Amount must not exceed the remaining amount, which is <strong>$' + candidateAmountRemaining + '</strong>.</span>');
        }

        $(this).val('$' + numericValue);

    });

    // VOUCHER CODE APPLY
    $(document).on('click', '#apply_voucher_button', function (event) {
        event.preventDefault();

        let voucher_code = $('#voucher_code').val();
        let wc_voucher_notification = $('#woocommerce_voucher_notification');
        let voucherYesNo = $('#form-field-acceptance').next('div');
        let voucherDiv = $('.elementor-element-2b44284');
        let removeAnchor = $('#remove_coupon_code_value')[0]; // Getting the DOM element
        let cookiePath = '; path=/', ckVoucherCode = 'ckVoucherCode';

        // Apply the voucher via WooCommerce AJAX
        $.ajax({
            type: 'POST',
            url: ajaxurl,
            data: {
                action: 'apply_voucher',
                voucher_code,
            },
            // beforeSend: function() {
            //     wc_voucher_notification.prop('class', 'woocommerce-info').html('Applying voucher...').show();
            // },
            success: function (response) {
                console.log('Response-Success-Message: ', response.data.message);
                console.log('Response-Voucher-code: ', response.data.voucher_code);
                if (response.success) {
                    document.cookie = ckVoucherCode + '=' + voucher_code + cookiePath;
                    voucherYesNo.hide();
                    $('.acceptance-modal').hide();
                    $('.elementor-field-subgroup').hide();
                    wc_voucher_notification.prop('class', 'woocommerce-message').html(response.data.message + removeAnchor.outerHTML).show();
                    // $('.applied-voucher-code-message').show();
                    $('#coupon_code_value').html(response.data.voucher_code).show();
                    $('#remove_coupon_code_value').attr('data-voucher_code', response.data.voucher_code);
                    // window.location.reload();
                } else {
                    wc_voucher_notification.prop('class', 'woocommerce-error').html(response.data.message).show();
                }
            }, error: function (response) {
                wc_voucher_notification.prop('class', 'woocommerce-error').html(response.data.message).show();
            }, complete: function () {
                // Trigger the 'update_checkout' event to refresh the cart total
                $(document.body).trigger('update_checkout');
            }
        });
    });
    // check if voucher cookie exists then apply it on popup
    let ckVoucherCode = 'ckVoucherCode', ckVoucherCodeValue = getCookie(ckVoucherCode);
    let notification = $('#woocommerce_voucher_notification');
    let removeAnchor = $('#remove_coupon_code_value')[0]; // Getting the DOM element

    if (ckVoucherCodeValue) {
        $('#voucher_code').val(ckVoucherCodeValue);
        $('.acceptance-modal').hide();
        $('.elementor-field-subgroup').hide();

        notification.removeClass().addClass('woocommerce-message')
            .html('Voucher: ' + ckVoucherCodeValue + ' applied' + removeAnchor.outerHTML).show();

        // $('.applied-voucher-code-message').show();
        $('#coupon_code_value').html(ckVoucherCodeValue).show();
        $('#remove_coupon_code_value').attr('data-voucher_code', ckVoucherCode);

        // $('#apply_voucher_button').trigger('click');
        console.log('Voucher cookie exists: ' + ckVoucherCode);
    }

    // REMOVE VOUCHER CODE
    function remove_voucher_code() {
        $ = jQuery;
        let removeButton = $('#remove_coupon_code_value');
        let cookiePath = '; path=/', ckVoucherCode = 'ckVoucherCode';

        let voucher_code = removeButton.attr('data-voucher_code');
        let wc_voucher_notification = $('#woocommerce_voucher_notification');

        $.ajax({
            type: 'POST', url: ajaxurl, data: {
                action: 'remove_voucher', voucher_code,
            }, beforeSend: function () {
                removeButton.html('<img src="/wp-content/uploads/2023/04/candidates-loader.gif" width="15px">');
            }, success: function (response) {
                console.log('Response-Success-Message: ', response.data.message);
                // remove voucher code and voucher value cookie
                document.cookie = ckVoucherCode + '=' + cookiePath;
                // document.cookie = ckVoucherCodeValue + '=' + cookiePath;
                // hide voucher code and voucher value
                wc_voucher_notification.html('').hide();
                wc_voucher_notification.prop('class', 'woocommerce-message').html(response.data.message).show();
                $('.applied-voucher-code-message').hide();
                $('#coupon_code_value').html('').hide();
                $('#voucher_code').val('');
                $('#remove_coupon_code_value').hide();
                $('#form-field-acceptance').prop('checked', false);
                // Voucher DOM element
                $('.elementor-element-1c7f1e49').replaceWith('<div class="elementor-element elementor-element-1c7f1e49 elementor-widget__width-inherit elementor-widget elementor-widget-html" data-id="1c7f1e49" data-element_type="widget" data-widget_type="html.default">' + '<div class="elementor-widget-container">' + '<div class="elementor-field-subgroup">' + '<span class="elementor-field-option">' + '<input type="checkbox" name="form_fields[acceptance]" id="form-field-acceptance" class="elementor-field elementor-size-sm  elementor-acceptance-field">' + '<label for="form-field-acceptance">I have a Donation Voucher</label></span></div>' + '<div id="woocommerce_voucher_notification" class="woocommerce-message" style="display: none;"></div>' + '<div class="applied-voucher-code-message" style="display:none;">' + '<p style="color:black;">Applied Coupon Code:' + '<span id="coupon_code_value"></span>' + '<a id="remove_coupon_code_value" data-voucher_code="" style="cursor: pointer;font-weight: 700;">' + '<i aria-hidden="true" class="far fa-trash-alt"></i>' + '</a></p></div>' + '<div class="acceptance-modal">' + '<div class="code-status">' + '<input type="text" class="code-input" id="voucher_code" placeholder="Input Donation Voucher Here">' + '<button class="voucher-btn" id="apply_voucher_button">Apply</button></div></div></div></div>');
                removeButton.text('Remove');
                jQuery(document.body).trigger('wc_fragment_refresh');
            }, error: function (response) {
                removeButton.text('Remove');
                wc_voucher_notification.prop('class', 'woocommerce-error').html(response.data.message).show();
            }, complete: function () {
                removeButton.text('Remove');
                $(document.body).trigger('update_checkout');
            }
        })
    }

    $(document).on('click', '#remove_coupon_code_value', function (event) {
        event.preventDefault();
        remove_voucher_code();
    });


    // Donate Now button click event
    $(document).on('click', '#donate-now-button', function (e) {
        e.preventDefault();

        // Get the values
        var voucherCode = $('#voucher_code').val();
        if (typeof voucherCode === 'undefined') {
            voucherCode = '';
        }

        var donationAmount = $('input[name="donationAmount"]:checked').val();
        if (typeof donationAmount === 'undefined') {
            donationAmount = '';
        }

        var otherAmount = $('#dAmount_other_input').val();
        if (typeof otherAmount === 'undefined') {
            otherAmount = '';
        } else if (otherAmount !== '' && otherAmount.includes('$')) {
            otherAmount = otherAmount.replace('$', '');
        }

        if (typeof productId === 'undefined') {
            productId = '';
        }

        console.log('voucherCode: ' + voucherCode);
        console.log('donationAmount: ' + donationAmount);
        console.log('otherAmount: ' + otherAmount);
        console.log('productId: ' + productId);

        let amount = 0;
        if (donationAmount > 0) {
            amount = donationAmount;
        } else if (otherAmount > 0) {
            amount = otherAmount;
        }

        console.log('amount: ' + amount);

        $('#amount_popup_message').remove();

        if (amount <= 0 || isNaN(amount)) {
            console.log('Amount cannot be $0 or empty.');
            jQuery('#add-candidate-to-cart').prepend('<span id="amount_popup_message" style="' + 'display: flex;' + 'align-items: center;' + 'position: absolute;' + 'top: -45px;' + 'left: -200px;' + 'color: red;' + 'width: max-content;' + 'height: 35px;' + 'font-weight: normal;' + 'padding: 5px 20px;' + 'background-color: #ff000024;' + 'border: 1px solid red;' + 'text-align: center;' + 'border-radius: 12px;' + '">' + 'Amount cannot be $0 or empty. Please fill correct amount.' + '</span>');
            return;
        }

        // Create an object to store the values
        var data = {
            action: 'childfree_add_to_cart', voucher_code: voucherCode, amount: amount, // amount: donationAmount,
            // other_amount: otherAmount,
            product_id: productId, nonce: nonce
        };

        $.ajax({
            url: ajaxurl, type: 'POST', data: data, beforeSend: function () {
                overlayToggle();
            }, success: function (response) {
                if (response.success === true) {
                    console.log('Redirect to checkout page, product is added.');
                    document.cookie = 'ckVoucherCode=' + voucherCode;
                    jQuery('#amount_popup_message').remove();
                    jQuery('#add-candidate-to-cart').prepend('<span id="amount_popup_message" style="' + 'display: flex;' + 'position: absolute;' + 'top: -45px;' + 'left: -80%;' + 'color: green;' + 'width: max-content;' + 'height: 35px;' + 'font-weight: normal;' + 'padding: 5px 20px;' + 'background-color: #e1fddd;' + 'border: 1px solid green;' + 'text-align: center;' + 'border-radius: 12px;' + '">' + 'Added to cart' + '</span>');
                    setTimeout(function () {
                        // Close the popup
                        jQuery('.dialog-close-button.dialog-lightbox-close-button').trigger('click');
                        // Redirect to the checkout page
                        window.location.href = '/checkout/';
                    }, 1000);
                } else {
                    overlayToggle();
                    setTimeout(function () {
                        jQuery('#add-candidate-to-cart').prepend('<span id="amount_popup_message" style="' + 'display: flex;' + 'position: absolute;' + 'top: -45px;' + 'color: red;' + 'width: max-content;' + 'height: 35px;' + 'font-weight: normal;' + 'padding: 5px 20px;' + 'background-color: #ff000024;' + 'border: 1px solid red;' + 'text-align: center;' + 'border-radius: 12px;' + '">' + 'Error occured while adding to cart' + '</span>');
                    }, 1000);
                    jQuery(document.body).trigger('wc_fragment_refresh');
                }
            }, error: function (jqXHR, textStatus, errorThrown) {
                console.log('Error:', jqXHR, textStatus, errorThrown);
                overlayToggle();
            }
        });
    });

    // Add to cart button click event
    $(document).on('click', '#add-candidate-to-cart', function (e) {
        e.preventDefault();

        // Get the values
        let voucherCode = $('#voucher_code').val();
        if (typeof voucherCode === 'undefined') {
            voucherCode = '';
        }

        // Get Donation Amount from text box
        var donationAmount = $('input[name="donationAmount"]:checked').val();
        if (typeof donationAmount === 'undefined') {
            donationAmount = '';
        }

        let otherAmount = $('#dAmount_other_input').val();
        if (typeof otherAmount === 'undefined') {
            otherAmount = '';
        } else if (otherAmount !== '' && otherAmount.includes('$')) {
            otherAmount = otherAmount.replace('$', '');
        }

        if (typeof productId === 'undefined') {
            productId = '';
        }

        console.log('voucherCode: ' + voucherCode);
        console.log('donationAmount: ' + donationAmount);
        console.log('otherAmount: ' + otherAmount);
        console.log('productId: ' + productId);

        let amount = 0;
        if (donationAmount > 0) {
            amount = donationAmount;
        } else if (otherAmount > 0) {
            amount = otherAmount;
        }

        console.log('amount: ' + amount);

        $('#amount_popup_message').remove();

        if (amount <= 0 || isNaN(amount)) {
            console.log('Amount cannot be $0 or empty.');
            jQuery('#add-candidate-to-cart').prepend('<span id="amount_popup_message" style="' + 'display: flex;' + 'position: absolute;' + 'top: -45px;' + 'left: -200px;' + 'color: red;' + 'width: max-content;' + 'height: 35px;' + 'font-weight: normal;' + 'padding: 5px 20px;' + 'background-color: #ff000024;' + 'border: 1px solid red;' + 'text-align: center;' + 'border-radius: 12px;' + '">' + 'Amount cannot be $0 or empty. Please fill correct amount.' + '</span>');
            return;
        }

        // Create an object to store the values
        let data = {
            action: 'childfree_add_to_cart', voucher_code: voucherCode, amount: amount, // amount: donationAmount,
            // other_amount: otherAmount,
            product_id: productId, nonce: nonce
        };

        $.ajax({
            url: ajaxurl, type: 'POST', data: data, // beforeSend: function () {
            //     overlayToggle();
            // },
            success: function (response) {
                console.log('Added to cart.');
                if (response.success === true) {
                    jQuery('#add-candidate-to-cart').prepend('<span id="amount_popup_message" style="' + 'display: flex;' + 'position: absolute;' + 'top: -45px;' + 'left: -80%;' + 'color: green;' + 'width: max-content;' + 'height: 35px;' + 'font-weight: normal;' + 'padding: 5px 20px;' + 'background-color: #e1fddd;' + 'border: 1px solid green;' + 'text-align: center;' + 'border-radius: 12px;' + '">' + 'Added to cart' + '</span>');
                    // close the popup after 1 second
                    setTimeout(function () {
                        jQuery('.dialog-close-button.dialog-lightbox-close-button').trigger('click');
                        jQuery(document.body).trigger('wc_fragment_refresh');
                    }, 1000);
                } else {
                    // overlayToggle();
                    setTimeout(function () {
                        jQuery('#add-candidate-to-cart').prepend('<span id="amount_popup_message" style="' + 'display: flex;' + 'position: absolute;' + 'top: -45px;' + 'color: red;' + 'width: max-content;' + 'height: 35px;' + 'font-weight: normal;' + 'padding: 5px 20px;' + 'background-color: #ff000024;' + 'border: 1px solid red;' + 'text-align: center;' + 'border-radius: 12px;' + '">' + 'Error occured while adding to cart' + '</span>');
                    }, 1000);
                    jQuery(document.body).trigger('wc_fragment_refresh');
                }
            }, error: function (jqXHR, textStatus, errorThrown) {
                console.log('Error:', jqXHR, textStatus, errorThrown);
                overlayToggle();
            }
        });
    });

});
//////////////////////////////////////////////////////////////////////////////////////
// ACCEPTANCE CHECKBOX: I have a Donation Voucher
//////////////////////////////////////////////////////////////////////////////////////
document.addEventListener('DOMContentLoaded', function () {
    const commonAncestor = document.body; // You can use a more specific common ancestor

    commonAncestor.addEventListener('click', function (event) {
        const checkbox = event.target.closest('#form-field-acceptance');
        const acceptanceModal = document.querySelector('.acceptance-modal');

        if (checkbox) {
            acceptanceModal.style.display = acceptanceModal.style.display === 'flex' ? 'none' : 'flex';
        }
    });
});
//////////////////////////////////////////////////////////////////////////////////////


/* tooltip distance */

document.addEventListener('DOMContentLoaded', () => {
    const tooltipCheckout = document.querySelector('.tooltip-distance');
    const tooltipCheckoutContent = document.querySelector('.tooltipDistance-content');

    if (tooltipCheckout && tooltipCheckoutContent) {
        tooltipCheckout.addEventListener('click', () => {
            const isVisible = window.getComputedStyle(tooltipCheckoutContent).display === 'block';
            tooltipCheckoutContent.style.display = isVisible ? 'none' : 'block';
        });
    }
});

//////////////////////////////////////////////////////////////////////////////////////
// FUND ALL POPUP HANDLER (FundAll)
//////////////////////////////////////////////////////////////////////////////////////

jQuery(function ($) {
    let ajaxurl = ajax_object.ajaxurl;
    let nonce = ajax_object.ajax_nonce;
    let candidateAmountRemaining = 0;

    jQuery(document).on('click', '.elementor-button[href=\"#fundall-popup\"]', function (e) {
        e.preventDefault();

        let fundAllManuallyButton = $('#fund_all_manual');
        let fundAllManuallyInputElement = jQuery('.fund-all-manual-input');
        let valueInPercent = 100, percentageAmount, roundedPercentageAmount;
        let totalToBePaidPercentageElement = jQuery('#percent_amount_for_all_candidates > div > span > span');
        let totalCandidatesGoalAmount = jQuery('#total_candidates_goal_amount > div > span > span').text();
        let fundAllProceedButtonId = '#fundall_proceed_button';
        const radioButtonsDonationAmounts = document.querySelectorAll('input[name="fundAllPercentages"]');

        fundAllManuallyInputElement.hide();
        totalCandidatesGoalAmount = totalCandidatesGoalAmount.replace(/[$,]/g, '');

        // on click of fund all proceed button delegate click event
        // $(document).on('click', fundAllProceedButtonId, function (e) {
        let debounceTimeout;
        jQuery(document).off('click', fundAllProceedButtonId).on('click', fundAllProceedButtonId, function (e) {
            e.preventDefault();
            clearTimeout(debounceTimeout);
            debounceTimeout = setTimeout(function () {

                if (valueInPercent < 0) {
                    console.log('Value in percent cannot be less than ' + valueInPercent + '%');
                    // document.cookie = 'ckFundAllCandidatesStatus=false; path=/';
                    return;
                } else {
                    console.log('Proceeding with the value: ' + valueInPercent + '%');
                }

                $.ajax({
                    type: 'POST', url: ajaxurl, data: {
                        action: 'fund_all_candidates',
                        value_in_percent: valueInPercent,
                        value_in_amount: totalToBePaidPercentageElement.text(),
                        nonce: nonce,
                    }, beforeSend: function () {
                        // Hide popup modal
                        jQuery('#elementor-popup-modal-58108').attr('style', 'display:none !important');
                        overlayToggle();
                        jQuery('#fundall_processing_message').hide();
                        jQuery('#overlay').append('<p id="fundall_processing_message" style="color: #143A62;font-weight: 600;">' + 'This process can take between 2-4 minutes. Please wait...</p>');
                    }, success: function (response) {
                        console.log('Response: ', JSON.stringify(response));
                        console.log('Redirect to checkout page');
                        document.cookie = 'ckSpecificDonationTotalAmount=' + response.data.total_specific_cart_Amount + '; path=/';
                        document.cookie = 'ckValueInPercent=' + response.data.value_in_percent + '; path=/';
                        // document.cookie = 'ckFundAllCandidatesStatus=true; path=/';
                        jQuery(document.body).trigger('wc_fragment_refresh');
                        window.location.href = '/checkout/';
                        // overlayToggle();
                    }, error: function (jqXHR, textStatus, errorThrown) {
                        console.log('Error:', jqXHR, textStatus, errorThrown);
                        alert('The processing encountered delays while funding all candidates. Please try again later.');
                        // document.cookie = 'ckFundAllCandidatesStatus=false; path=/';
                        overlayToggle();
                    }
                });
            }, 500); // Adjust the delay as needed
        });

        // Add event listener to each radio button
        radioButtonsDonationAmounts.forEach(button => {
            button.addEventListener('click', () => {
                console.log('fundAllManuallyButton: ' + fundAllManuallyButton.attr('id') + '\nbValue: ' + button.value + '\nbID: ' + button.id);

                const selectedValue = button.value;
                percentageAmount = totalCandidatesGoalAmount * (selectedValue / 100);
                roundedPercentageAmount = Math.round(percentageAmount);

                if (button.id !== fundAllManuallyButton.attr('id')) {
                    fundAllManuallyInputElement.hide();
                    totalToBePaidPercentageElement.text('$' + new Intl.NumberFormat().format(roundedPercentageAmount));
                    valueInPercent = selectedValue;
                } else {
                    fundAllManuallyInputElement.show();
                    fundAllManuallyInputElement.prop('placeholder', 'Type % value here (numbers only)').show();
                    totalToBePaidPercentageElement.text('$0');
                }

            });
        });

        // Mask the input field to not allow to type any non-numeric characters
        jQuery(document).on('input', '#fund-all-manual-input', function (event) {
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
            if (parseInt(numericValue) > 101 || parseInt(numericValue) < 1) {
                numericValue = candidateAmountRemaining;
                // empty the appended span before populating it
                $(this).closest('.elementor-widget-container').find('#amount_error_message').remove();
                $(this).closest('.elementor-widget-container').append('<span id="amount_error_message" style="color: red; font-size: 12px;">' + 'Type between 1% to 100%.</span>');
            }
            $(this).val(numericValue);
            percentageAmount = totalCandidatesGoalAmount * (numericValue / 100);
            roundedPercentageAmount = Math.round(percentageAmount);

            totalToBePaidPercentageElement.text('$' + new Intl.NumberFormat().format(roundedPercentageAmount));
            valueInPercent = numericValue;
        });


        setTimeout(function () {
            jQuery('#elementor-popup-modal-58108').attr('style', 'display:flex !important');
        }, 100);

        jQuery(document).on('click', '#elementor-popup-modal-58108 > div > a', function (event) {
            event.preventDefault();
            console.log('Close button clicked');
            jQuery('#elementor-popup-modal-58108').attr('style', 'display:none !important');
        });

    });


});