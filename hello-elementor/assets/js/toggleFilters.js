window.addEventListener('scroll', () => {
    const navbar = document.querySelector('.navbar');
    const scrollY = window.scrollY;

    if (scrollY > 0) {
      navbar.style.backgroundColor = '#ffffff'; 
      navbar.sty
    } else {
      navbar.style.backgroundColor = ''; 
    }
});

document.addEventListener('DOMContentLoaded', function () {
    const toggleBtn = document.querySelector('.filters-toggler');
    const filtersBlock = document.querySelector('.filters');
    const toggleBtnIcon = document.querySelector('.filters-toggler div div a span span');
    const gridTable = document.querySelector('.elementor-14 .elementor-element.elementor-element-e8e9855');

    toggleBtn?.addEventListener('click', function () {
        filtersBlock.style.display = filtersBlock.style.display === 'none' ? 'block' : 'none';
        toggleBtnIcon.style.display = toggleBtnIcon.style.display === 'none' ? 'block' : 'none';
        
        if (gridTable.style.gridTemplateColumns === 'repeat(4, 1fr)') {
            gridTable.style.gridTemplateColumns = 'repeat(3, 1fr)';
        } else {
            gridTable.style.gridTemplateColumns = 'repeat(4, 1fr)';
        }
    });

    let inputLeft = document.querySelectorAll("#input-left");
    let inputRight = document.querySelectorAll("#input-right");
    let range = document.querySelectorAll(".slider > .range");
    let valueFrom = document.querySelectorAll(".value-from");
    let valueTo = document.querySelectorAll(".value-to");

    function setValues() {
        for (let i = 0; i < inputLeft.length; i++) {
            let min = parseInt(inputLeft[i].min);
            let max = parseInt(inputLeft[i].max);

            let leftValue = parseInt(inputLeft[i].value);
            let rightValue = parseInt(inputRight[i].value);

            leftValue = Math.min(leftValue, rightValue - 5);
            rightValue = Math.max(rightValue, leftValue + 5);

            inputLeft[i].value = leftValue;
            inputRight[i].value = rightValue;

            valueFrom[i].textContent = `${leftValue * 1}`;
            valueTo[i].textContent = `${rightValue * 1}`;

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

document.addEventListener('DOMContentLoaded', function () {
    const checkbox = document.getElementById('form-field-acceptance');
    const acceptanceModal = document.querySelector('.acceptance-modal');
    const remove = document.querySelector('.status-icon');

    checkbox.addEventListener('click', () => {
        acceptanceModal.style.display = acceptanceModal.style.display === 'flex' ? 'none' : 'flex';
    });
})

document.addEventListener('DOMContentLoaded', () => {
    const donBtns = document.querySelectorAll('.e-n-tab-title');
    const amountValue = document.querySelector('#amountValue div span')?.innerText;
    const donatInpt = document.getElementById('form-field-field_e42ce96');

    donatInpt.value = amountValue;

    if (donBtns && amountValue && donatInpt) {
        donatInpt.addEventListener('input', () => {
            const inputValue = donatInpt.value;
            if (inputValue !== '' && !inputValue.startsWith('$')) {
                donatInpt.value = '$' + inputValue;
            }
        });

        donBtns.forEach((btn) => {
            btn.addEventListener('click', () => {
                const buttonText = btn.querySelector('.e-n-tab-title-text').textContent;
                const clearBtnText = buttonText.trim();

                if (clearBtnText === 'Other') {
                    if (!donatInpt.value.startsWith('$')) {
                        donatInpt.value = '$' + '';
                    }
                    donatInpt.removeAttribute('readonly');
                } else if (clearBtnText === 'Fully Fund') {
                    donatInpt.value = amountValue;
                    donatInpt.setAttribute('readonly', 'readonly');
                } else {
                    donatInpt.value = clearBtnText;
                    donatInpt.setAttribute('readonly', 'readonly');
                }
            });
        });
    }
});

document.addEventListener('DOMContentLoaded', () => {
    const modalContainer = document.getElementById('container-share');
    
    modalContainer.addEventListener('click', (event) => {
        const shareBtn = event.target.closest('#share-btn');
        const shareLink = document.getElementById('shareLink');

        if (shareBtn) {
            event.preventDefault();
            console.log(1);

            shareLink.select();
            document.execCommand('copy');
            shareLink.style.backgroundColor = 'rgba(2, 169, 92, 0.05)';
            shareLink.style.borderColor = '#02A95C';

            setTimeout(() => {
                shareLink.style.backgroundColor = '';
            }, 5000);
        }
    });
});

document.addEventListener('DOMContentLoaded', () => {
    const hoverBlock = document.querySelector('.hover-content');
    const hiddenBlock = document.querySelector('.hidden-content');
    let isHovered = false;

    if(hoverBlock && hiddenBlock) {
        hoverBlock.addEventListener('mouseenter', () => {
            if (!isHovered) {
                isHovered = true;
                hiddenBlock.style.display = 'flex';
                hiddenBlock.style.position = 'absolute';
            }
        })
        hoverBlock.addEventListener('mouseleave', () => {
            if (isHovered) {
                isHovered = false;
                hiddenBlock.style.display = 'none';
            }
        })

        if (window.innerWidth < 1024) {
            hoverBlock.addEventListener('click', () => {
                hiddenBlock.style.display = 'flex';
                hiddenBlock.style.position = 'absolute';
            });

            hiddenBlock.addEventListener('click', (e) => {
                e.stopPropagation();
                hiddenBlock.style.display = 'none';
            })

            hoverBlock.addEventListener('touchstart', () => {
                hiddenBlock.style.display = 'flex';
                hiddenBlock.style.position = 'absolute';
            });

            hiddenBlock.addEventListener('touchstart', (e) => {
                e.stopPropagation();
                hiddenBlock.style.display = 'none';
            });
        }
    }
})