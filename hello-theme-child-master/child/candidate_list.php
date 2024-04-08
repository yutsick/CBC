<?php
/* Template Name: Candidates */
get_header();
?>
    <link rel="stylesheet" href="https://code.jquery.com/mobile/1.4.5/jquery.mobile-1.4.5.min.css">

    <div class="candidate-top main-candidate-section">
        <div class="container">
            <div class="flex">
                <div class="cand-567">
                    <h1>Browse Candidates</h1>
                </div>
                <div class="cand-567 cand-568">
                    <label>Show Per Page</label>
                    <select class="candidate-per-page-select candidate-select-5" name="per_page_candidates">
                        <option value="12">12</option>
                        <option value="24">24</option>
                        <option value="48">48</option>
                        <option value="60">60</option>
                        <option value="96">100</option>
                    </select>
                    <select class="candidate-sorting-select candidate-select-5" name="sort_by_candidate">
                        <option value="sort" data-order="DESC">Sort...</option>
                        <option value="date" data-order="DESC">Registration Date (Newest)</option>
                        <option value="date" data-order="ASC">Registration Date (Oldest)</option>
                        <option value="_amount_raised" data-order="DESC">Goal Progress (Highest)</option>
                        <option value="_amount_raised" data-order="ASC">Goal Progress (Lowest)</option>
                        <option value="meta_value_num" data-order="ASC">Age (Youngest)</option>
                        <option value="meta_value_num" data-order="DESC">Age (Oldest)</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <!--Popup All candidates -->
    <!-- The Modal -->
    <div id="all_candidates_Modal" class="modal">

        <!-- Modal content -->
        <div class="modal-content">
            <span class="all_candidates_close">&times;</span>
            <h3>Fund All Candidates</h3>
            <h6> Total Candidate Goals:</span> <span> <?php echo do_shortcode('[all_candidates_goal]'); ?></h6>
            <h6>Number of Candidates:</span> <span> <?php echo do_shortcode('[all_candidates_count]'); ?></span></h6>
            <a href="<?php echo do_shortcode('[fulfill_all_candidates_link]'); ?>"
               class="elementor-button-link elementor-button">Proceed</a>
        </div>

    </div>

    <div class="main-candidate-section" id="candidate-listing-grid">
        <div class="container">
            <div class="row flex">
                <!-- sidebar	 -->
                <div class="sidebar">
                    <div class="filter-main-1">
                        <div class="jet-search-filter__input-wrapper">
                            <input class="jet-search-filter__input" id="candidate_search_title" type="search"
                                   autocomplete="off" name="candidate_search_title" value=""
                                   placeholder="Search by Name">
                        </div>
                        <hr>
                        <div class="jet-search-filter__input-wrapper">
                            <input class="jet-search-filter__input" type="number" autocomplete="off"
                                   name="candidate_zip_code" value="" placeholder="Enter Zip Code">
                        </div>
                        <div class="jet-search-filter__input-wrapper destination_fields">
                            <div class="destination_input">
                                <input type="radio" value="25" name="destination" checked><label>Exact</label>
                            </div>
                            <div class="destination_input">
                                <input type="radio" value="25" name="destination"><label>25 Miles</label>
                            </div>
                            <div class="destination_input">
                                <input type="radio" value="50" name="destination"><label>50 Miles</label>
                            </div>
                            <div class="destination_input">
                                <input type="radio" value="100" name="destination"><label>100 Miles</label>
                            </div>
                        </div>
                        <hr>

                        <h1 class="sel-gender-1">Select Gender</h1>
                        <label class="container">
                            <input type="checkbox" id="candidate_male" value="male" name="gender_type">
                            <span class="checkmark"></span>Male
                        </label>
                        <label class="container">
                            <input type="checkbox" id="candidate_female" value="female" name="gender_type">
                            <span class="checkmark"></span>Female
                        </label>

                        <h1 class="sel-gender-1">Progress To Goal</h1>
                        <div class="price_range_slider">

                            <div class="slider">
                                <div class="progress price_progress"></div>
                            </div>


                            <div class="range-input price_range_input">
                                <input type="range" class="range-min price-range-min" min="0" max="10000" value="0"
                                       step="100">
                                <input type="range" class="range-max price-range-max" min="0" max="10000" value="10000"
                                       step="100">
                            </div>
                            <div class="price-input" style="display:none">
                                <div class="field">
                                    <input type="number" class="input-min candidate_price_min" value="0" readonly>
                                </div>
                                <div class="separator">-</div>
                                <div class="field">
                                    <input type="number" class="input-max candidate_price_max" value="10000" readonly>
                                </div>
                            </div>
                            <div id="selected_price_range"></div>
                            <h1 class="sel-gender-1">Age</h1>
                            <div class="slider">
                                <div class="progress age_progress"></div>
                            </div>

                            <div class="range-input age_range_input">
                                <input type="range" class="range-min age-range-min" min="18" max="45" value="18"
                                       step="1">
                                <input type="range" class="range-max age-range-max" min="0" max="45" value="45"
                                       step="1">
                            </div>

                            <div class="price-input age_input" style="display:none">
                                <div class="field">
                                    <input type="number" class="input-min candidate_age_min" value="18" readonly>
                                </div>
                                <div class="separator">-</div>
                                <div class="field">
                                    <input type="number" class="input-max candidate_age_max" value="45" readonly>
                                </div>
                            </div>
                            <div id="selected_age_range"></div>


                        </div>

                    </div>
                    <span class='sidebar_candidate_loader' style='display:none'><img
                                src='/wp-content/uploads/2023/04/candidates-loader.gif' width='30px'></span>
                </div>


                <div class="col-md-4 content profiles-main">
                    <div class="show-234">
                        <label>Show Per page</label>
                        <select class="candidate-per-page-select" name="per_page_candidates">
                            <option value="12">12</option>
                            <option value="24">24</option>
                            <option value="48">48</option>
                            <option value="60">60</option>
                            <option value="96">100</option>
                        </select>
                    </div>
                    <!-- 					   <div class="cand-567">
                                            <select class="candidate-sorting-select candidate-select-5" name="sort_by_candidate">
                                                <option value="date" data-order="DESC">Sort...</option>
                                                <option value="date" data-order="ASC">Registration Date (Newest)</option>
                                                <option value="date" data-order="DESC">Registration Date (Oldest)</option>
                                                <option value="high progress" data-order="DESC">Goal Progress (Highest)</option>
                                                <option value="low progress" data-order="ASC">Goal Progress (Lowest)</option>
                                           </select>
                                           </div> -->

                    <div class="fund">
                        <div class="fund-btn fund-btn-1">
                            <button type="button" id="all_candidates_fund">Click to fund all Candidates</button>
                        </div>
                        <div class="fund-btn fund-btn-2">
                            <p>OR</p>
                        </div>
                        <div class="fund-btn fund-btn-1">
                            <button type="button" id="candidate-toggle-multi-select">Select Multiple Candidates</button>
                        </div>
                    </div>

                    <div class="inner-box content no-right-margin darkviolet darkviolet-567 ">

                        <div class="cvf_pag_loading" style="background: none; transition: all 1s ease-out 0s;">
                            <div class="cvf_universal_container">
                                <div class="cvf-pagination-content">
                                    <div class="cvf-universal-content"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="elementor-widget-container hidden" id="candidate-multi-select-submit-widget">
            <div class="elementor-button-wrapper">
                <a href="#" class="elementor-button-link elementor-button elementor-size-sm" role="button"
                   id="candidate-multi-select-submit">
				<span class="elementor-button-content-wrapper">
						<span class="elementor-button-text">Add To Cart</span>
		       </span>
                </a>
            </div>
        </div>
    </div>

<!--TestContent-->
    <script type="text/javascript">


        jQuery(document).ready(function ($) {


            var cmin_age = jQuery(".candidate_age_min").val();
            var cmax_age = jQuery(".candidate_age_max").val();

            var cmin_price = jQuery(".candidate_price_min").val();
            var cmax_price = jQuery(".candidate_price_max").val();

            jQuery('#selected_price_range').html('<span class="min_price">$' + cmin_price + '</span>-<span class="min_price">$' + cmax_price + '</span>');

            jQuery('#selected_age_range').html('<span class="min_price min_age">' + cmin_age + '</span>-<span class="min_price max_age">' + cmax_age + '</span>');

            const rangeInput = document.querySelectorAll(".range-input input"),
                priceInput = document.querySelectorAll(".price-input input"),
                range = document.querySelector(".slider .progress");
            let priceGap = 0;

            priceInput.forEach(input => {
                input.addEventListener("input", e => {
                    let minPrice = parseInt(priceInput[0].value),
                        maxPrice = parseInt(priceInput[1].value);

                    if ((maxPrice - minPrice >= priceGap) && maxPrice <= rangeInput[1].max) {
                        if (e.target.className === "price-range-min") {
                            rangeInput[0].value = minPrice;
                            range.style.left = ((minPrice / rangeInput[0].max) * 100) + "%";
                        } else {
                            rangeInput[1].value = maxPrice;
                            range.style.right = 100 - (maxPrice / rangeInput[1].max) * 100 + "%";
                        }
                    }
                });
            });

            rangeInput.forEach(input => {
                input.addEventListener("input", e => {
                    let minVal = parseInt(rangeInput[0].value),
                        maxVal = parseInt(rangeInput[1].value);

                    if ((maxVal - minVal) < priceGap) {
                        if (e.target.className === "candidate_price_min") {
                            rangeInput[0].value = maxVal - priceGap;
                        } else {
                            rangeInput[1].value = minVal + priceGap;
                        }
                        jQuery('#selected_price_range').html('<span class="min_price">$' + maxVal - priceGap + '</span>-<span class="min_price">$' + minVal + priceGap + '</span>');
                    } else {
                        priceInput[0].value = minVal;
                        priceInput[1].value = maxVal;
                        range.style.left = ((minVal / rangeInput[0].max) * 100) + "%";
                        range.style.right = 100 - (maxVal / rangeInput[1].max) * 100 + "%";
                        jQuery('#selected_price_range').html('<span class="min_price">$' + minVal + '</span>-<span class="min_price">$' + maxVal + '</span>');
                    }
                });
            });


            //Age range filter

            const agerangeInput = document.querySelectorAll(".age_range_input input"),
                agepriceInput = document.querySelectorAll(".age_input input"),
                agerange = document.querySelector(".slider .age_progress");
            let ageGap = 0;

            agepriceInput.forEach(input => {
                input.addEventListener("input", e => {
                    let minPrice = parseInt(agepriceInput[0].value),
                        maxPrice = parseInt(agepriceInput[1].value);

                    if ((maxPrice - minPrice >= ageGap) && maxPrice <= agerangeInput[1].max) {
                        if (e.target.className === "candidate_age_min") {
                            agerangeInput[0].value = minPrice;
                            agerange.style.left = ((minPrice / agerangeInput[0].max) * 100) + "%";

                        } else {
                            agerangeInput[1].value = maxPrice;
                            agerange.style.right = 100 - (maxPrice / agerangeInput[1].max) * 100 + "%";
                        }

                        jQuery('#selected_age_range').html('<span class="min_price">' + maxPrice + '</span>-<span class="min_price">' + minPrice + '</span>');

                    }
                });
            });

            agerangeInput.forEach(input => {
                input.addEventListener("input", e => {
                    let minVal = parseInt(agerangeInput[0].value),
                        maxVal = parseInt(agerangeInput[1].value);

                    if ((maxVal - minVal) < ageGap) {
                        if (e.target.className === "age-range-min") {
                            agerangeInput[0].value = maxVal - ageGap;
                        } else {
                            agerangeInput[1].value = minVal + ageGap;
                        }
                        jQuery('#selected_age_range').html('<span class="min_price">' + maxVal + '</span>-<span class="min_price">' + minVal + '</span>');
                    } else {

                        agepriceInput[0].value = minVal;
                        agepriceInput[1].value = maxVal;
                        var setrangemin = minVal;
                        if (minVal < 44) {
                            setrangemin = minVal - 14;
                        }
                        agerange.style.left = ((setrangemin / agerangeInput[0].max) * 100) + "%";
                        agerange.style.right = 100 - (maxVal / agerangeInput[1].max) * 100 + "%";

                        jQuery('#selected_age_range').html('<span class="min_price">' + minVal + '</span>-<span class="min_price">' + maxVal + '</span>');
                    }
                });
            });


            // This is required for AJAX to work on our page
            var ajaxurl = '<?php echo admin_url('admin-ajax.php'); ?>';

            function cvf_load_all_posts(page, corder, corder_by, cper_page, search_title, gender_type, cmax_price, cmin_price, cmin_age, cmax_age, zip_code, clat, clng, destination) {
                // Start the transition

                $("body").addClass('candidate_loading');
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
                    action: "candidate-pagination-load-posts"
                };

                // Send the data
                $.post(ajaxurl, data, function (response) {
                    // If successful Append the data into our html container
                    $(".cvf_universal_container").html(response);
                    // End the transition

                    $("body").removeClass('candidate_loading');
                    jQuery(".sidebar_candidate_loader").hide();
                });
            }

            // Load page 1 as the default
            cvf_load_all_posts(1);

            // Handle the clicks

            jQuery("body").on("click", ".cvf-universal-pagination li.active", function (event) {


                var gender_male = jQuery('#candidate_male:checked').val();
                var gender_female = jQuery('#candidate_female:checked').val();

                if (gender_male !== undefined && gender_female !== undefined) {
                    var gender_type = "";
                } else {
                    var gender_type = jQuery('input[name="gender_type"]:checked').val();

                }
                var search_title = jQuery("#candidate_search_title").val();
                var cper_page = jQuery('.candidate-per-page-select').val();
                var corder_by = jQuery('.candidate-sorting-select').val();
                var corder = jQuery('.candidate-sorting-select').find(':selected').attr('data-order');

                var cmin_price = jQuery(".candidate_price_min").val();
                var cmax_price = jQuery(".candidate_price_max").val();

                var cmin_age = jQuery(".candidate_age_min").val();
                var cmax_age = jQuery(".candidate_age_max").val();
                var page = $(this).attr('p');
                if (gender_type == "undefined") {
                    gender_type = "";
                }
                var zip_code = jQuery('input[name="candidate_zip_code"]').val();
                if (zip_code.length != 5 && zip_code !== '') {
                    zip_code = '';
                }
                var destination = jQuery('input[name="destination"]:checked').val();
                if (destination != "" && zip_code.length == 5) {
                    var lat = '';
                    var lng = '';

                    var settings = {
                        "url": "https://maps.googleapis.com/maps/api/geocode/json?address=components=postal_code:" + zip_code + "&sensor=false&key=AIzaSyB5Dc2DupDK2lz6m0J3TJglvixt8gnqXSE",
                        "method": "POST",
                        "timeout": 0,
                    };

                    $.ajax(settings).done(function (response) {
                        console.log(response);
                        if (response.status == 'OK') {
                            var clat = response['results'][0]['geometry']['location']['lat'];
                            var clng = response['results'][0]['geometry']['location']['lng'];
                            zip_code = '';
                            cvf_load_all_posts(page, corder, corder_by, cper_page, search_title, gender_type, cmax_price, cmin_price, cmin_age, cmax_age, zip_code, clat, clng, destination);
                        }
                    });
                } else {

                    cvf_load_all_posts(page, corder, corder_by, cper_page, search_title, gender_type, cmax_price, cmin_price, cmin_age, cmax_age, zip_code);
                }
            });


            // Sort By ajax

            jQuery('.candidate-sorting-select').on('change', function () {

                var gender_male = jQuery('#candidate_male:checked').val();
                var gender_female = jQuery('#candidate_female:checked').val();

                if (gender_male !== undefined && gender_female !== undefined) {
                    var gender_type = "";
                } else {
                    var gender_type = jQuery('input[name="gender_type"]:checked').val();

                }

                var search_title = jQuery("#candidate_search_title").val();
                var cper_page = jQuery('.candidate-per-page-select').val();
                var corder_by = jQuery(this).val();
                var corder = jQuery('option:selected', this).attr('data-order');
                var cmin_price = jQuery(".candidate_price_min").val();
                var cmax_price = jQuery(".candidate_price_max").val();
                var cmin_age = jQuery(".candidate_age_min").val();
                var cmax_age = jQuery(".candidate_age_max").val();
                if (gender_type == "undefined") {
                    gender_type = "";
                }
                var zip_code = jQuery('input[name="candidate_zip_code"]').val();
                if (zip_code.length != 5 && zip_code !== '') {
                    zip_code = '';
                }
                var destination = jQuery('input[name="destination"]:checked').val();
                if (destination != "" && zip_code.length == 5) {
                    var lat = '';
                    var lng = '';

                    var settings = {
                        "url": "https://maps.googleapis.com/maps/api/geocode/json?address=components=postal_code:" + zip_code + "&sensor=false&key=AIzaSyB5Dc2DupDK2lz6m0J3TJglvixt8gnqXSE",
                        "method": "POST",
                        "timeout": 0,
                    };

                    $.ajax(settings).done(function (response) {
                        console.log(response);
                        if (response.status == 'OK') {
                            var clat = response['results'][0]['geometry']['location']['lat'];
                            var clng = response['results'][0]['geometry']['location']['lng'];
                            zip_code = '';
                            cvf_load_all_posts(1, corder, corder_by, cper_page, search_title, gender_type, cmax_price, cmin_price, cmin_age, cmax_age, zip_code, clat, clng, destination);
                        }
                    });
                } else {
                    cvf_load_all_posts(1, corder, corder_by, cper_page, search_title, gender_type, cmax_price, cmin_price, cmin_age, cmax_age, zip_code);
                }
            });


            jQuery('.candidate-per-page-select').on('change', function () {


                var cmin_age = jQuery(".candidate_age_min").val();
                var cmax_age = jQuery(".candidate_age_max").val();

                var cmin_price = jQuery(".candidate_price_min").val();
                var cmax_price = jQuery(".candidate_price_max").val();
                var search_title = jQuery("#candidate_search_title").val();

                var gender_male = jQuery('#candidate_male:checked').val();
                var gender_female = jQuery('#candidate_female:checked').val();

                if (gender_male !== undefined && gender_female !== undefined) {
                    var gender_type = "";
                } else {
                    var gender_type = jQuery('input[name="gender_type"]:checked').val();

                }

                var cper_page = jQuery(this).val();
                var corder_by = jQuery('.candidate-sorting-select').val();
                var corder = jQuery('.candidate-sorting-select').find(':selected').attr('data-order');
                if (gender_type == "undefined") {
                    gender_type = "";
                }
                var zip_code = jQuery('input[name="candidate_zip_code"]').val();
                if (zip_code.length != 5 && zip_code !== '') {
                    zip_code = '';
                }

                var destination = jQuery('input[name="destination"]:checked').val();
                if (destination != "" && zip_code.length == 5) {
                    var lat = '';
                    var lng = '';

                    var settings = {
                        "url": "https://maps.googleapis.com/maps/api/geocode/json?address=components=postal_code:" + zip_code + "&sensor=false&key=AIzaSyB5Dc2DupDK2lz6m0J3TJglvixt8gnqXSE",
                        "method": "POST",
                        "timeout": 0,
                    };

                    $.ajax(settings).done(function (response) {
                        console.log(response);
                        if (response.status == 'OK') {
                            var clat = response['results'][0]['geometry']['location']['lat'];
                            var clng = response['results'][0]['geometry']['location']['lng'];
                            zip_code = '';
                            cvf_load_all_posts(1, corder, corder_by, cper_page, search_title, gender_type, cmax_price, cmin_price, cmin_age, cmax_age, zip_code, clat, clng, destination);
                        }
                    });
                } else {

                    cvf_load_all_posts(1, corder, corder_by, cper_page, search_title, gender_type, cmax_price, cmin_price, cmin_age, cmax_age, zip_code);
                }
            });

            jQuery('#candidate_search_title').on('input', function () {

                jQuery(".sidebar_candidate_loader").show();
                var cmin_age = jQuery(".candidate_age_min").val();
                var cmax_age = jQuery(".candidate_age_max").val();
                var cmin_price = jQuery(".candidate_price_min").val();
                var cmax_price = jQuery(".candidate_price_max").val();

                var gender_type = jQuery('input[name="gender_type"]:checked').val();
                var search_title = jQuery(this).val();
                var cper_page = jQuery('.candidate-per-page-select').val();
                var corder_by = jQuery('.candidate-sorting-select').val();
                var corder = jQuery('.candidate-sorting-select').find(':selected').attr('data-order');
                if (gender_type == "undefined") {
                    gender_type = "";
                }
                var zip_code = jQuery('input[name="candidate_zip_code"]').val();
                if (zip_code.length != 5 && zip_code !== '') {
                    zip_code = '';
                }

                var destination = jQuery('input[name="destination"]:checked').val();
                if (destination != "" && zip_code.length == 5) {
                    var lat = '';
                    var lng = '';

                    var settings = {
                        "url": "https://maps.googleapis.com/maps/api/geocode/json?address=components=postal_code:" + zip_code + "&sensor=false&key=AIzaSyB5Dc2DupDK2lz6m0J3TJglvixt8gnqXSE",
                        "method": "POST",
                        "timeout": 0,
                    };

                    $.ajax(settings).done(function (response) {
                        console.log(response);
                        if (response.status == 'OK') {
                            var clat = response['results'][0]['geometry']['location']['lat'];
                            var clng = response['results'][0]['geometry']['location']['lng'];
                            zip_code = '';
                            cvf_load_all_posts(1, corder, corder_by, cper_page, search_title, gender_type, cmax_price, cmin_price, cmin_age, cmax_age, zip_code, clat, clng, destination);
                        }
                    });
                } else {

                    cvf_load_all_posts(1, corder, corder_by, cper_page, search_title, gender_type, cmax_price, cmin_price, cmin_age, cmax_age, zip_code);
                }
            });


            jQuery(document).on('click', 'input[name="gender_type"]', function () {
                jQuery(".sidebar_candidate_loader").show();
                var cmin_age = jQuery(".candidate_age_min").val();
                var cmax_age = jQuery(".candidate_age_max").val();

                var gender_male = jQuery('#candidate_male:checked').val();
                var gender_female = jQuery('#candidate_female:checked').val();

                if (gender_male !== undefined && gender_female !== undefined) {
                    var gender_type = "";
                } else {
                    var gender_type = jQuery('input[name="gender_type"]:checked').val();

                }
                console.log(gender_type);
                var cmin_price = jQuery(".candidate_price_min").val();
                var cmax_price = jQuery(".candidate_price_max").val();


                var search_title = jQuery("#candidate_search_title").val();
                var cper_page = jQuery('.candidate-per-page-select').val();

                var corder_by = jQuery('.candidate-sorting-select').val();
                var corder = jQuery('.candidate-sorting-select').find(':selected').attr('data-order');

                if (gender_type == "undefined") {
                    gender_type = "";
                }
                var zip_code = jQuery('input[name="candidate_zip_code"]').val();
                if (zip_code.length != 5 && zip_code !== '') {
                    zip_code = '';
                }

                var destination = jQuery('input[name="destination"]:checked').val();
                if (destination != "" && zip_code.length == 5) {
                    var lat = '';
                    var lng = '';

                    var settings = {
                        "url": "https://maps.googleapis.com/maps/api/geocode/json?address=components=postal_code:" + zip_code + "&sensor=false&key=AIzaSyB5Dc2DupDK2lz6m0J3TJglvixt8gnqXSE",
                        "method": "POST",
                        "timeout": 0,
                    };

                    $.ajax(settings).done(function (response) {
                        console.log(response);
                        if (response.status == 'OK') {
                            var clat = response['results'][0]['geometry']['location']['lat'];
                            var clng = response['results'][0]['geometry']['location']['lng'];
                            zip_code = '';
                            cvf_load_all_posts(1, corder, corder_by, cper_page, search_title, gender_type, cmax_price, cmin_price, cmin_age, cmax_age, zip_code, clat, clng, destination);
                        }
                    });
                } else {
                    console.log(corder + 'order');
                    console.log(corder_by + "order_by");
                    cvf_load_all_posts(1, corder, corder_by, cper_page, search_title, gender_type, cmax_price, cmin_price, cmin_age, cmax_age, zip_code);
                }
            });


            jQuery('input[type="range"]').on('change', function () {

                var cmin_age = jQuery(".candidate_age_min").val();
                var cmax_age = jQuery(".candidate_age_max").val();

                var cmin_price = jQuery(".candidate_price_min").val();
                var cmax_price = jQuery(".candidate_price_max").val();

                jQuery('#selected_price_range').html('<span class="min_price">$' + cmin_price + '</span>-<span class="min_price">$' + cmax_price + '</span>');

                jQuery('#selected_age_range').html('<span class="min_price">' + cmin_age + '</span>-<span class="min_price">' + cmax_age + '</span>');

                jQuery(".sidebar_candidate_loader").show();


                var gender_male = jQuery('#candidate_male:checked').val();
                var gender_female = jQuery('#candidate_female:checked').val();

                if (gender_male !== undefined && gender_female !== undefined) {
                    var gender_type = "";
                } else {
                    var gender_type = jQuery('input[name="gender_type"]:checked').val();

                }
                var search_title = jQuery("#candidate_search_title").val();
                var cper_page = jQuery('.candidate-per-page-select').val();
                var corder_by = jQuery('.candidate-sorting-select').val();
                var corder = jQuery('.candidate-sorting-select').find(':selected').attr('data-order');

                if (gender_type == "undefined") {
                    gender_type = "";
                }
                var zip_code = jQuery('input[name="candidate_zip_code"]').val();
                if (zip_code.length != 5 && zip_code !== '') {
                    zip_code = '';
                }

                var destination = jQuery('input[name="destination"]:checked').val();
                if (destination != "" && zip_code.length == 5) {
                    var lat = '';
                    var lng = '';

                    var settings = {
                        "url": "https://maps.googleapis.com/maps/api/geocode/json?address=components=postal_code:" + zip_code + "&sensor=false&key=AIzaSyB5Dc2DupDK2lz6m0J3TJglvixt8gnqXSE",
                        "method": "POST",
                        "timeout": 0,
                    };

                    $.ajax(settings).done(function (response) {
                        console.log(response);
                        if (response.status == 'OK') {
                            var clat = response['results'][0]['geometry']['location']['lat'];
                            var clng = response['results'][0]['geometry']['location']['lng'];
                            zip_code = '';
                            cvf_load_all_posts(1, corder, corder_by, cper_page, search_title, gender_type, cmax_price, cmin_price, cmin_age, cmax_age, zip_code, clat, clng, destination);
                        }
                    });
                } else {

                    cvf_load_all_posts(1, corder, corder_by, cper_page, search_title, gender_type, cmax_price, cmin_price, cmin_age, cmax_age, zip_code);
                }

            });

            jQuery('input[name="candidate_zip_code"]').on('input', function () {

                var zip_code = jQuery(this).val();
                jQuery(".zip_code_error").remove();
                if (zip_code.length != 5 && zip_code !== '') {

                    jQuery('<span class="zip_code_error container">Zip code length should be 5 .</span>').insertAfter(this);

                } else {

                    var cmin_age = jQuery(".candidate_age_min").val();
                    var cmax_age = jQuery(".candidate_age_max").val();

                    var cmin_price = jQuery(".candidate_price_min").val();
                    var cmax_price = jQuery(".candidate_price_max").val();

                    jQuery('#selected_price_range').html('<span class="min_price">$' + cmin_price + '</span>-<span class="min_price">$' + cmax_price + '</span>');

                    jQuery('#selected_age_range').html('<span class="min_price">' + cmin_age + '</span>-<span class="min_price">' + cmax_age + '</span>');

                    jQuery(".sidebar_candidate_loader").show();

                    var destination = jQuery('input[name="destination"]:checked').val();
                    var gender_male = jQuery('#candidate_male:checked').val();
                    var gender_female = jQuery('#candidate_female:checked').val();

                    if (gender_male !== undefined && gender_female !== undefined) {
                        var gender_type = "";
                    } else {
                        var gender_type = jQuery('input[name="gender_type"]:checked').val();

                    }
                    var search_title = jQuery("#candidate_search_title").val();
                    var cper_page = jQuery('.candidate-per-page-select').val();
                    var corder_by = jQuery('.candidate-sorting-select').val();
                    var corder = jQuery('.candidate-sorting-select').find(':selected').attr('data-order');

                    if (gender_type == "undefined") {
                        gender_type = "";
                    }

                    if (destination != "") {
                        var lat = '';
                        var lng = '';

                        var settings = {
                            "url": "https://maps.googleapis.com/maps/api/geocode/json?address=components=postal_code:" + zip_code + "&sensor=false&key=AIzaSyB5Dc2DupDK2lz6m0J3TJglvixt8gnqXSE",
                            "method": "POST",
                            "timeout": 0,
                        };

                        $.ajax(settings).done(function (response) {
                            console.log(response);
                            if (response.status == 'OK') {
                                var clat = response['results'][0]['geometry']['location']['lat'];
                                var clng = response['results'][0]['geometry']['location']['lng'];
                                zip_code = '';
                                cvf_load_all_posts(1, corder, corder_by, cper_page, search_title, gender_type, cmax_price, cmin_price, cmin_age, cmax_age, zip_code, clat, clng, destination);
                            }
                        });
                    } else {
                        cvf_load_all_posts(1, corder, corder_by, cper_page, search_title, gender_type, cmax_price, cmin_price, cmin_age, cmax_age, zip_code);
                    }


                }

            });

            jQuery('input[type=radio][name="destination"]').change(function () {


                var zip_code = jQuery('input[name="candidate_zip_code"]').val();
                var destination = jQuery(this).val();
                var cmin_age = jQuery(".candidate_age_min").val();
                var cmax_age = jQuery(".candidate_age_max").val();

                var cmin_price = jQuery(".candidate_price_min").val();
                var cmax_price = jQuery(".candidate_price_max").val();

                jQuery('#selected_price_range').html('<span class="min_price">$' + cmin_price + '</span>-<span class="min_price">$' + cmax_price + '</span>');

                jQuery('#selected_age_range').html('<span class="min_price">' + cmin_age + '</span>-<span class="min_price">' + cmax_age + '</span>');

                jQuery(".sidebar_candidate_loader").show();


                var gender_male = jQuery('#candidate_male:checked').val();
                var gender_female = jQuery('#candidate_female:checked').val();

                if (gender_male !== undefined && gender_female !== undefined) {
                    var gender_type = "";
                } else {
                    var gender_type = jQuery('input[name="gender_type"]:checked').val();

                }
                var search_title = jQuery("#candidate_search_title").val();
                var cper_page = jQuery('.candidate-per-page-select').val();
                var corder_by = jQuery('.candidate-sorting-select').val();
                var corder = jQuery('.candidate-sorting-select').find(':selected').attr('data-order');

                if (gender_type == "undefined") {
                    gender_type = "";
                }


                if (zip_code.length == 5 && destination != "all" && destination != "") {

                    var lat = '';
                    var lng = '';

                    var settings = {
                        "url": "https://maps.googleapis.com/maps/api/geocode/json?address=components=postal_code:" + zip_code + "&sensor=false&key=AIzaSyB5Dc2DupDK2lz6m0J3TJglvixt8gnqXSE",
                        "method": "POST",
                        "timeout": 0,
                    };

                    $.ajax(settings).done(function (response) {
                        console.log(response);
                        if (response.status == 'OK') {
                            var clat = response['results'][0]['geometry']['location']['lat'];
                            var clng = response['results'][0]['geometry']['location']['lng'];
                            zip_code = '';
                            cvf_load_all_posts(1, corder, corder_by, cper_page, search_title, gender_type, cmax_price, cmin_price, cmin_age, cmax_age, zip_code, clat, clng, destination);
                        }
                    });


                } else {

                    cvf_load_all_posts(1, corder, corder_by, cper_page, search_title, gender_type, cmax_price, cmin_price, cmin_age, cmax_age, zip_code);
                }

            });


        });


    </script>


    <script>
        // Get the modal
        var modal = document.getElementById("all_candidates_Modal");

        // Get the button that opens the modal
        var btn = document.getElementById("all_candidates_fund");

        // Get the <span> element that closes the modal
        var span = document.getElementsByClassName("all_candidates_close")[0];

        // When the user clicks the button, open the modal
        btn.onclick = function () {
            modal.style.display = "block";
        }

        // When the user clicks on <span> (x), close the modal
        span.onclick = function () {
            modal.style.display = "none";
        }

        // When the user clicks anywhere outside of the modal, close it
        window.onclick = function (event) {
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }


    </script>


<?php get_footer(); ?>