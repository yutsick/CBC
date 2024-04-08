document.addEventListener('DOMContentLoaded', function () {
    const toggleBtn = document.querySelector('.filters-toggler');
    const filtersBlock = document.querySelector('#filter-block');
    const toggleBtnIcon = document.querySelector('.filters-toggler div div a span span');

    let ajaxurl = ajax_object.ajaxurl;
    //const filterBlock = document.querySelector('#filter-block');
    // Default state
    filtersBlock.style.display = 'none';
   // toggleBtnIcon.style.display = 'none';

    toggleBtn.addEventListener('click', function () {
        const element = document.querySelector('.woocommerce.elementor-element');
        const isMobile = window.innerWidth < 767;
        filtersBlock.style.display = isMobile ? toggleDisplay(filtersBlock) : toggleDisplay(filtersBlock, 'none', 'block');
       // toggleBtnIcon.style.display = isMobile ? toggleDisplay(toggleBtnIcon) : toggleDisplay(toggleBtnIcon, 'none', 'block');
        toggleBtnIcon.classList.toggle('open')
        
  
        // console.log(element.classList.contains('elementor-grid-4') ? 'elementor-grid-4' : 'elementor-grid-3');
    });

    function toggleDisplay(element, value1 = 'none', value2 = 'block') {
        return element.style.display === value1 ? value2 : value1;
    }

    let inputLeft = document.querySelectorAll(".min-value-slider");
    let inputRight = document.querySelectorAll(".max-value-slider");
    let range = document.querySelectorAll(".slider > .range");
    let valueFrom = document.querySelectorAll(".value-from");
    let valueTo = document.querySelectorAll(".value-to");

    function setValues() {
        for (let i = 0; i < inputLeft.length; i++) {
            let min = parseInt(inputLeft[i].min);
            let max = parseInt(inputLeft[i].max);

            let leftValue = parseInt(inputLeft[i].value);
            let rightValue = parseInt(inputRight[i].value);

            inputLeft[i].value = leftValue;
            inputRight[i].value = rightValue;

            let dataId = inputLeft[i].getAttribute('data-type-value');
            let minVal = '';
            let maxVal = '';

            if (dataId === 'goal-value') {
                minVal = `$`+ leftValue;
                valueFrom[i].textContent = minVal.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',');
                inputLeft[i].setAttribute('value', leftValue);

                maxVal = `$`+ rightValue;
                valueTo[i].textContent = maxVal.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',');
                inputRight[i].setAttribute('value', rightValue);
            } else {
                valueFrom[i].textContent = leftValue.toString();
                valueFrom[i].setAttribute('value', leftValue);

                valueTo[i].textContent = rightValue.toString();
                valueTo[i].setAttribute('value', rightValue);
            }

            let leftPercent = ((leftValue - min) / (max - min)) * 100;
            let rightPercent = ((rightValue - min) / (max - min)) * 100;

            range[i].style.left = leftPercent + "%";
            range[i].style.right = 100 - rightPercent + "%";
        }
    }

    setValues();

    for (let i = 0; i < inputLeft.length; i++) {
        inputLeft[i].addEventListener("input", setValues);
        inputRight[i].addEventListener("input", setValues);

        inputLeft[i].addEventListener("mouseover", (e) => {
            inputLeft[i].classList.add("hover");
        });
        inputLeft[i].addEventListener("mouseout", (e) => {
            inputLeft[i].classList.remove("hover");
        });
        inputLeft[i].addEventListener("mousedown", (e) => {
            inputLeft[i].classList.add("active");
        });
        inputLeft[i].addEventListener("mouseup", (e) => {
            inputLeft[i].classList.remove("active");
        });
        inputLeft[i].addEventListener("touchstart", (e) => {
            inputLeft[i].classList.add("active");
        });
        inputLeft[i].addEventListener("touchend", (e) => {
            inputLeft[i].classList.remove("active");
        });

        inputRight[i].addEventListener("mouseover", (e) => {
            inputRight[i].classList.add("hover");
        });
        inputRight[i].addEventListener("mouseout", (e) => {
            inputRight[i].classList.remove("hover");
        });
        inputRight[i].addEventListener("mousedown", (e) => {
            inputRight[i].classList.add("active");
        });
        inputRight[i].addEventListener("mouseup", (e) => {
            inputRight[i].classList.remove("active");
        });
        inputRight[i].addEventListener("touchstart", (e) => {
            inputRight[i].classList.add("active");
        });
        inputRight[i].addEventListener("touchend", (e) => {
            inputRight[i].classList.remove("active");
        });
    }


    const tooltip = document.querySelector('.tooltip');
    const tooltipModal = document.querySelector('.tooltip-modal');

    if(tooltip && tooltipModal) {
        tooltip.addEventListener('mouseenter', () => {
            tooltipModal.style.display = 'flex';
        });

        tooltip.addEventListener('mouseleave', () => {
            tooltipModal.style.display = 'none';
        });
    }

    
    //console.log(jQuery('.candidate-select__to-physician'))
    jQuery(document).on('click', '.candidate-select__to-physician', function() {

        let id = jQuery(this).data('id');
        let data = {
            id: id,
            candidate_status:'In progress',
            procedure_status: 'Pending',
            action: "update_physician_candidates",

        };
        
        jQuery.ajax({
            type: 'POST', url: ajaxurl, data: data, 
            // beforeSend: function () {
            //     overlayToggle();
            //     jQuery('.elementor-search-form__icon i').replaceWith('<i class="fas fa-spinner fa-spin"></i>');
            //     disableFilterButton();
            // }, 
            success: function (response) {
                // If successful, append the data into our html container
               // $(".cvf-universal-content").html(response);
                // Remove focus from all input, textarea and select elements
                // jQuery('input, textarea, select').focus(function() {
                //     this.blur();
                // });
                // End the transition
               // overlayToggle();
               // jQuery('.elementor-search-form__icon i').replaceWith('<i aria-hidden="true" class="fas fa-search"></i>');
               // enableFilterButton()
                console.log('Updated success', response);
            }, error: function (jqXHR, textStatus, errorThrown) {
                // Handle errors
                console.error('AJAX Error: ' + textStatus, errorThrown);
              //   overlayToggle();
              //  jQuery('.elementor-search-form__icon i').replaceWith('<i aria-hidden="true" class="fas fa-search"></i>');
                console.log('error');
            }
        });
    });
});

