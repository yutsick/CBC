jQuery(document).ready(function ($) {
    function hideButtonIconOnMobile() {
        // Check if the window width is less than or equal to 767px (mobile)
        if ($(window).width() <= 767) {
            $('.elementor-button-content-wrapper .elementor-button-icon.elementor-align-icon-left > i.fas.fa-arrow-left').hide();
        }
    }
    
    // Execute on document ready
    hideButtonIconOnMobile();

    // Execute when window is resized
    $(window).resize(function () {
        hideButtonIconOnMobile();
    });
});

// Set top NavBar background color upon scroll
window.addEventListener('scroll', () => {
    const navbar = document.querySelector('.navbar');
    const scrollY = window.scrollY;

    if (scrollY > 0) {
        navbar.style.backgroundColor = 'white';
    } else {
        navbar.style.backgroundColor = '';
    }
});

// View Candidates page - change title in card tpl

document.addEventListener('DOMContentLoaded', () => {
    const infoBtns = document.querySelectorAll('.elementor-element-ef4ae14 div div a span span');

    infoBtns.forEach((btn) => {
        btn.innerText = 'Candidate Profile';
    });
})

// Filters Toggle functionality
document.addEventListener('DOMContentLoaded', function () {
    const toggleBtn = document.querySelector('.filters-toggler');
    const filtersBlock = document.querySelector('.filters');
    const toggleBtnIcon = document.querySelector('.filters-toggler div div a span span');

    // Default state
    filtersBlock.style.display = 'none';
    toggleBtnIcon.style.display = 'none';

    toggleBtn.addEventListener('click', function () {
        const element = document.querySelector('.woocommerce.elementor-element');
        const isMobile = window.innerWidth < 767;
        filtersBlock.style.display = isMobile ? toggleDisplay(filtersBlock) : toggleDisplay(filtersBlock, 'none', 'block');
        toggleBtnIcon.style.display = isMobile ? toggleDisplay(toggleBtnIcon) : toggleDisplay(toggleBtnIcon, 'none', 'block');

        
        element.classList.toggle('elementor-grid-4');
        element.classList.toggle('elementor-grid-3');
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
});

// document.addEventListener('DOMContentLoaded', () => {
//     const donBtns = document.querySelectorAll('.e-n-tab-title');
//     const amountValue = jQuery('#donation_amount_single_box div span').text();
//     const donatInpt = document.getElementById('form-field-field_e42ce96');
//
//     donatInpt.value = amountValue;
//
//     if (donBtns && amountValue && donatInpt) {
//         donatInpt.addEventListener('input', () => {
//             const inputValue = donatInpt.value;
//             if (inputValue !== '' && !inputValue.startsWith('$')) {
//                 donatInpt.value = '$' + inputValue;
//             }
//         });
//
//         donBtns.forEach((btn) => {
//             btn.addEventListener('click', () => {
//                 const buttonText = btn.querySelector('.e-n-tab-title-text').textContent;
//                 const clearBtnText = buttonText.trim();
//
//                 if (clearBtnText === 'Other') {
//                     if (!donatInpt.value.startsWith('$')) {
//                         donatInpt.value = '$' + '';
//                     }
//                     donatInpt.removeAttribute('readonly');
//                 } else if (clearBtnText === 'Fully Fund') {
//                     donatInpt.value = amountValue;
//                     donatInpt.setAttribute('readonly', 'readonly');
//                 } else {
//                     donatInpt.value = clearBtnText;
//                     donatInpt.setAttribute('readonly', 'readonly');
//                 }
//             });
//         });
//     }
// });

// document.addEventListener('DOMContentLoaded', () => {
//     const modalContainer = document.getElementById('container-share');
//
//     modalContainer.addEventListener('click', (event) => {
//         const shareBtn = event.target.closest('#share-btn');
//         const shareLink = document.getElementById('shareLink');
//
//         if (shareBtn) {
//             event.preventDefault();
//             console.log(1);
//
//             shareLink.select();
//             document.execCommand('copy');
//             shareLink.style.backgroundColor = 'rgba(2, 169, 92, 0.05)';
//             shareLink.style.borderColor = '#02A95C';
//
//             setTimeout(() => {
//                 shareLink.style.backgroundColor = '';
//             }, 5000);
//         }
//     });
// });

// document.addEventListener('DOMContentLoaded', () => {
//     const hoverBlock = document.querySelector('.hover-content');
//     const hiddenBlock = document.querySelector('.hidden-content');
//     let isHovered = false;
//
//     if(hoverBlock && hiddenBlock) {
//         hoverBlock.addEventListener('mouseenter', () => {
//             if (!isHovered) {
//                 isHovered = true;
//                 hiddenBlock.style.display = 'flex';
//                 hiddenBlock.style.position = 'absolute';
//             }
//         })
//         hoverBlock.addEventListener('mouseleave', () => {
//             if (isHovered) {
//                 isHovered = false;
//                 hiddenBlock.style.display = 'none';
//             }
//         })
//
//         if (window.innerWidth < 1024) {
//             hoverBlock.addEventListener('click', () => {
//                 hiddenBlock.style.display = 'flex';
//                 hiddenBlock.style.position = 'absolute';
//             });
//
//             hiddenBlock.addEventListener('click', (e) => {
//                 e.stopPropagation();
//                 hiddenBlock.style.display = 'none';
//             })
//
//             hoverBlock.addEventListener('touchstart', () => {
//                 hiddenBlock.style.display = 'flex';
//                 hiddenBlock.style.position = 'absolute';
//             });
//
//             hiddenBlock.addEventListener('touchstart', (e) => {
//                 e.stopPropagation();
//                 hiddenBlock.style.display = 'none';
//             });
//         }
//     }
// })