document.addEventListener('DOMContentLoaded', function () {

    // const calcBlock = document.getElementById('calcEstimate');
    const calcBlock = document.getElementsByClassName('calcEstimate');
    const data = [
        {
            procedure: '1',
            costs: '$26,000,000',
        },
        {
            procedure: '10',
            costs: '$260,000,000',
        },
        {
            procedure: '20',
            costs: '$520,000,000',
        },
        {
            procedure: '200',
            costs: '$5,200,000,000',
        },
        {
            procedure: '20,000',
            costs: '$520,000,000,000',
        },
        {
            procedure: '200,000',
            costs: '$5,200,000,000,000',
        },
        {
            procedure: '1,200,000',
            additional: '<span class="est-additional">(all unintended pregnancies in the USA each year)</span>',
            costs: '$31,200,000,000,000',
        },
    ];

    if (calcBlock) {
        console.log('calcBlock found');
        const renderContent = `
            <div class="calc-col">
                <div class="value-row">
                    <label for="value-calc" class="value-label">Donation Example: Learn How Much Your Donation Matters</label>
                    <select type="text" name="value-calc" value="5000" id="est-value">
                        <option value="0" class="opt-font">$5,000</option>
                        <option value="1" class="opt-font">$50,000</option>
                        <option value="2" class="opt-font">$100,000</option>
                        <option value="3" class="opt-font">$1,000,000</option>
                        <option value="4" class="opt-font">$100,000,000</option>
                        <option value="5" class="opt-font">$1,000,000,000</option>
                        <option value="6" class="opt-font">$6,000,000,000</option>
                    </select>
                </div>
                <div class="calc-row">
                    <div class="estimate-text">
                        <div style="display: inline-block;">Estimated number of Candidate procedures fully funded
                        <img style="vertical-align: bottom;" src="/wp-content/uploads/2023/10/tooltip-icon-1-1.svg" class="est-tooltipIcon" alt="tooltip" /></div>
                    </div>
                    <div class="estimate-result procedures">1</div>
                </div>
                <div class="calc-row">
                    <div class="estimate-text">
                        <div style="display: inline-block">Estimated lifetime costs prevented 
                        <img style="vertical-align: bottom;" src="/wp-content/uploads/2023/10/tooltip-icon-1-1.svg" class="est-tooltipIcon" alt="tooltip" /></div>
                    </div>
                    <div class="estimate-result costs">$26,000,000</div>
                </div>
                <div class="calc-row">
                    <div class="estimate-text">
                        <div style="display:inline-block;">Estimated lifetime preventative ROI (return on investment/donation) <img style="vertical-align: bottom;" src="/wp-content/uploads/2023/10/tooltip-icon-1-1.svg" class="est-tooltipIcon" alt="tooltip" /></div>
                    </div>
                    <div class="estimate-result roi">520,000%</div>

                </div>
            </div>
        `;

        for (let i = 0; i < calcBlock.length; i++) {
            calcBlock[i].innerHTML = renderContent;
        }
    }

    const select = document.getElementById('est-value');
    const resProcedures = document.querySelector('.estimate-result.procedures');
    const resCosts = document.querySelector('.estimate-result.costs');

    if (select) {
        select.addEventListener('change', () => {
            const selectedOptionIndex = select.selectedIndex;

            if (resProcedures && resCosts) {
                resProcedures.textContent = data[selectedOptionIndex].procedure;
                resCosts.textContent = data[selectedOptionIndex].costs;

                selectedOptionIndex === 6 ? resCosts.innerHTML = data[selectedOptionIndex].costs + data[selectedOptionIndex].additional : resCosts.textContent = data[selectedOptionIndex].costs;
                ;
            }
        });
    }
});

/* change all Donation Type radio */

document.addEventListener('DOMContentLoaded', function () {
    const item1 = document.querySelector('#frm_radio_377-0 label');
    const item2 = document.querySelector('#frm_radio_377-1 label');
    const item3 = document.querySelector('#frm_radio_313-0 label');
    const item4 = document.querySelector('#frm_radio_313-1 label');

    if (item1 && item2 && item3 && item4) {
        item1.style.backgroundColor = 'rgb(20, 58, 98)';
        item1.style.color = 'white';
        item3.style.backgroundColor = 'rgb(20, 58, 98)';
        item3.style.color = 'white';

        function toggleStyles(selectedElement, otherElement) {
            selectedElement.style.backgroundColor = 'rgb(20, 58, 98)';
            selectedElement.style.color = 'white';
            otherElement.style.backgroundColor = '';
            otherElement.style.color = '';
        }

        item1.addEventListener('click', function () {
            toggleStyles(item1, item2);
        });

        item2.addEventListener('click', function () {
            toggleStyles(item2, item1);
        });

        item3.addEventListener('click', function () {
            toggleStyles(item3, item4);
        });

        item4.addEventListener('click', function () {
            toggleStyles(item4, item3);
        });
    }
    ;
});

/* Location Specific Donation pop-up handler */
/* Question mark popup */
document.addEventListener('DOMContentLoaded', function () {
    const tooltips = document.querySelectorAll('.est-tooltipIcon');
    const locBtn = document.getElementById('location-popup');

    tooltips.forEach((item) => {
        item.addEventListener('click', () => {
            locBtn.click();
        });
    })
});