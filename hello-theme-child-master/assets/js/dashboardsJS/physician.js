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
             beforeSend: function () {
                 overlayToggle();
            //     jQuery('.elementor-search-form__icon i').replaceWith('<i class="fas fa-spinner fa-spin"></i>');
            //     disableFilterButton();
             }, 
            success: function (response) {
                overlayToggle();
                jQuery('a[href=#tab3]').removeClass('active').css({'background': '', 'color': '', 'border-radius': ''});
                jQuery('a[href=#tab2]').addClass('active').css({'background': '#143A62', 'color': 'white', 'border-radius': '6px'});
                jQuery('#tab3').addClass('hidden');
                jQuery('#tab2').removeClass('hidden');

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


    // Add popup for the notes
    const notes = document.querySelectorAll('.add_note'); 
    const fileList = [];


    for (let note of notes){
        note.addEventListener('click', function() {
            document.querySelector('#physician-popup-overlay').style.display = 'flex';
            document.querySelector('#candidate_id').value = this.dataset.userId;
        });
    }
    

    document.querySelectorAll('.close').forEach(function(button) {
        button.addEventListener('click', function() {
            document.querySelector('#physician-popup-overlay').style.display = 'none';
            document.querySelector('#candidate_id').value = '';
        });
    });

    const dropzone = document.getElementById('dropzone');
    const fileInput = document.getElementById('file-upload');
    const fileListContainer = document.getElementById('file-list');

    dropzone.addEventListener('click', function() {
        fileInput.click();
    });

    dropzone.addEventListener('dragover', function(e) {
        e.preventDefault();
        dropzone.classList.add('dragover');
    });

    dropzone.addEventListener('dragleave', function() {
        dropzone.classList.remove('dragover');
    });

    dropzone.addEventListener('drop', function(e) {
        e.preventDefault();
        dropzone.classList.remove('dragover');
        const files = e.dataTransfer.files;
        handleFiles(files);
    });

    fileInput.addEventListener('change', function() {
        const files = fileInput.files;
        handleFiles(files);
    });

    function handleFiles(files) {
        for (let i = 0; i < files.length; i++) {
            const file = files[i];
            fileList.push(file);
            const fileItem = document.createElement('div');
            const fileExtension = file.name.split('.').pop();
            const fileSize = formatFileSize(file.size);

            fileItem.classList.add('flex', 'justify-between', 'items-center', 'p-2', 'border', 'rounded');
            fileItem.innerHTML = `
            <div class="flex gap-2">
                <span>${fileExtension}</span>
                <span>${file.name}</span>
                <span>${fileSize}</span>
            </div>
                <button type="button" class="border-none remove-file text-red-600 hover:text-red-800">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M20.25 4.5H16.5V3.75C16.5 3.15326 16.2629 2.58097 15.841 2.15901C15.419 1.73705 14.8467 1.5 14.25 1.5H9.75C9.15326 1.5 8.58097 1.73705 8.15901 2.15901C7.73705 2.58097 7.5 3.15326 7.5 3.75V4.5H3.75C3.55109 4.5 3.36032 4.57902 3.21967 4.71967C3.07902 4.86032 3 5.05109 3 5.25C3 5.44891 3.07902 5.63968 3.21967 5.78033C3.36032 5.92098 3.55109 6 3.75 6H4.5V19.5C4.5 19.8978 4.65804 20.2794 4.93934 20.5607C5.22064 20.842 5.60218 21 6 21H18C18.3978 21 18.7794 20.842 19.0607 20.5607C19.342 20.2794 19.5 19.8978 19.5 19.5V6H20.25C20.4489 6 20.6397 5.92098 20.7803 5.78033C20.921 5.63968 21 5.44891 21 5.25C21 5.05109 20.921 4.86032 20.7803 4.71967C20.6397 4.57902 20.4489 4.5 20.25 4.5ZM9 3.75C9 3.55109 9.07902 3.36032 9.21967 3.21967C9.36032 3.07902 9.55109 3 9.75 3H14.25C14.4489 3 14.6397 3.07902 14.7803 3.21967C14.921 3.36032 15 3.55109 15 3.75V4.5H9V3.75ZM18 19.5H6V6H18V19.5ZM10.5 9.75V15.75C10.5 15.9489 10.421 16.1397 10.2803 16.2803C10.1397 16.421 9.94891 16.5 9.75 16.5C9.55109 16.5 9.36032 16.421 9.21967 16.2803C9.07902 16.1397 9 15.9489 9 15.75V9.75C9 9.55109 9.07902 9.36032 9.21967 9.21967C9.36032 9.07902 9.55109 9 9.75 9C9.94891 9 10.1397 9.07902 10.2803 9.21967C10.421 9.36032 10.5 9.55109 10.5 9.75ZM15 9.75V15.75C15 15.9489 14.921 16.1397 14.7803 16.2803C14.6397 16.421 14.4489 16.5 14.25 16.5C14.0511 16.5 13.8603 16.421 13.7197 16.2803C13.579 16.1397 13.5 15.9489 13.5 15.75V9.75C13.5 9.55109 13.579 9.36032 13.7197 9.21967C13.8603 9.07902 14.0511 9 14.25 9C14.4489 9 14.6397 9.07902 14.7803 9.21967C14.921 9.36032 15 9.55109 15 9.75Z" fill="#76787A"/>
                </svg>
                
                </button>
            `;
            fileListContainer.appendChild(fileItem);

            fileItem.querySelector('.remove-file').addEventListener('click', function() {
                const index = fileList.indexOf(file);
                if (index > -1) {
                    fileList.splice(index, 1);
                    fileItem.remove();
                }
            });
        }
    }

    function formatFileSize(bytes) {
        const kb = 1024;
        const mb = kb * 1024;
    
        if (bytes >= mb) {
            return (bytes / mb).toFixed(2) + ' MB';
        } else if (bytes >= kb) {
            return (bytes / kb).toFixed(2) + ' KB';
        } else {
            return bytes + ' bytes';
        }
    }

    
    function clearForm() {
        document.querySelector('#popup-form').reset();
        fileList.length = 0;  
        fileListContainer.innerHTML = '';  
    }


    // send form

    document.getElementById('popup-form').addEventListener('submit', function(e) {
        e.preventDefault();

        const formData = new FormData(this);
        fileList.forEach(file => formData.append('attachments[]', file));
        formData.append('action', 'add_procedure_note');
        document.querySelector('#form_notes_loader').style.display="flex";
        fetch(ajaxurl, {
            method: 'POST',
            body: formData,
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.querySelector('#form_notes_loader').style.display="fnone";
                alert('Note added successfully!');
                document.querySelector('#physician-popup-overlay').style.display = 'none';
                clearForm(); 
            } else {
                alert('There was an error adding the note.');
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });
    });
});

