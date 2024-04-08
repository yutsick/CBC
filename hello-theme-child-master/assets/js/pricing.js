

document.addEventListener('DOMContentLoaded', () => {
    const valueInput = document.getElementById('value');
    const resultSpan1 = document.getElementById('resultSpan1');
    const resultSpan2 = document.getElementById('resultSpan2');
    const currency = document.querySelector('.currency');

    function updateResults(empty = false) {

        let rawValue = valueInput.value;//.trim().replace(/[^0-9.]/g, '');
       
        if (rawValue === '') {
            rawValue = '0';
        }
const numericValue = parseFloat(rawValue.replace(/,/g, ''));
        if (!empty){
           // const numericValue = parseFloat(rawValue);
        
        const formattedValue = numericValue.toLocaleString('en-US', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2,
        });

        valueInput.value = formattedValue; 
        }
       

        if (!isNaN(numericValue)) {
            let res1 = numericValue * 0.75;
            let res2 = numericValue * 0.25;

            resultSpan1.innerText = '$' + res1.toLocaleString('en-US', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2,
            });
            resultSpan2.innerText = '$' + res2.toLocaleString('en-US', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2,
            });
        } else {
            resultSpan1.innerText = '$' + '0.00';
            resultSpan2.innerText = '$' + '0.00';
        }

        const charCount = valueInput.value.length;

        if (charCount === 7) {
            valueInput.style.fontSize = '56px'; 
            currency.style.fontSize = '56px';
            resultSpan1.style.fontSize = '38px';
            resultSpan2.style.fontSize = '38px';
        }
        
        if (charCount === 9) {
            valueInput.style.fontSize = '45px';
            currency.style.fontSize = '45px';
            resultSpan1.style.fontSize = '36px';
            resultSpan2.style.fontSize = '36px';
        }
    }


    //valueInput.addEventListener('input',updateResults);
    let keyPressed = null;
    valueInput.addEventListener('keydown', function(event) {
        // Allow special keys like Backspace, Tab, Enter, etc.
      
        const keys = ['ArrowRight','ArrowLeft','Tab','Backspace','Enter','Escape'];
        if (keys.includes(event.key)) {
            keyPressed = event.key;
          
          return; // Allow these keys
        }
        keyPressed = event.key;
        // Prevent input if it's a number
        if (isNaN(Number(event.key))) {
          event.preventDefault();
        }
      });

   
    valueInput.addEventListener('input', () => {
   
            
        
       let startPos = ((valueInput.selectionStart) % 4 == 0   && keyPressed != 'Backspace')

            ? valueInput.selectionStart + 1
            : valueInput.selectionEnd;

            if(keyPressed == 'Backspace' && (valueInput.selectionStart) % 4 == 0 ){
                startPos = valueInput.selectionStart - 1;
                startPos = (startPos < 0) ? 1 : startPos;
            }
   
        
            // console.log(keyPressed)
            // console.log(`star: ${startPos}`)
        updateResults();
        valueInput.setSelectionRange(startPos, startPos);
        if (valueInput.value < 10) {
            valueInput.setSelectionRange(1, 1);
        }
    });
    valueInput.addEventListener('focus', () => {
        console.log(valueInput.selectionStart)
        
         valueInput.value = '';
         updateResults(true);
         valueInput.setSelectionRange(-1, -1);
    })

    valueInput.addEventListener('keydown', (event) => {
   
        if (event.key === 'Backspace' && valueInput.value.length === 1) {
            event.preventDefault();
            valueInput.value = '0.00';
            valueInput.setSelectionRange(0, 0);
            resultSpan1.innerText = '$' + '0.00';
            resultSpan2.innerText = '$' + '0.00';
            event.stopPropagation();
            updateResults();
        }
    });
    updateResults();
    
});

/* Calculator Expansion Fund */

document.addEventListener('DOMContentLoaded', function() {

    const calcBlock = document.querySelector('.calcEstimate');
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
    
    if(calcBlock){

        const renderContent = `
            <div class="calc-row">
                <h2 style="text-align: center;
                color: var( --e-global-color-text );
                font-weight: 500;
                line-height: 83px;
                letter-spacing: -1.92px;">Learn How Much Your Donation Matters</h2>
                <div class="calc__container">
                    <div class="value-row">
                        <label for="value-calc" class="value-label">Donation</label>
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
                    <div class="calc__container-col">
                        <div class="calc-row__new">
                            <span class="estimate-text">
                                <span>Estimated number of Candidate procedures fully funded</span>
                                <img src="/wp-content/uploads/2023/10/tooltip-icon-1-1.svg" class="est-tooltipIcon" alt="tooltip" />
                            </span>
                        <span class="estimate-result procedures">1</span>
                        </div>
                        <div class="calc-row__new">
                            <span class="estimate-text">
                                <span style="width: 100%;">Estimated lifetime costs prevented </span>
                                <img src="/wp-content/uploads/2023/10/tooltip-icon-1-1.svg" class="est-tooltipIcon" alt="tooltip" />
                            </span>
                            <span class="estimate-result costs">$26,000,000</span>
                        </div>
                        <div class="calc-row__new">
                            <span class="estimate-text">
                                <span>Estimated lifetime preventative ROI (return on investment/donation)</span>
                                <img src="/wp-content/uploads/2023/10/tooltip-icon-1-1.svg" class="est-tooltipIcon" alt="tooltip" />
                            </span>
                            <span class="estimate-result roi">520,000%</span>
                        </div>
                    </div>
                </div>
            </div>
        `;

        calcBlock.innerHTML = renderContent;
    }

    const select = document.getElementById('est-value');
    const resProcedures = document.querySelector('.estimate-result.procedures');
    const resCosts = document.querySelector('.estimate-result.costs');
        
    if (select) {
        select.addEventListener('change', () => {
            const selectedOptionIndex = select.selectedIndex;

            if(resProcedures && resCosts) {
                resProcedures.textContent = data[selectedOptionIndex].procedure;
                resCosts.textContent = data[selectedOptionIndex].costs;

                selectedOptionIndex === 6 ?  resCosts.innerHTML = data[selectedOptionIndex].costs + data[selectedOptionIndex].additional : resCosts.textContent = data[selectedOptionIndex].costs; ;
            }                
        });
    } 
});

/* change all Donation Type radio */

document.addEventListener('DOMContentLoaded', function() {
    const item1 = document.querySelector('#frm_radio_377-0 label');
    const item2 = document.querySelector('#frm_radio_377-1 label');
    const item3 = document.querySelector('#frm_radio_313-0 label');
    const item4 = document.querySelector('#frm_radio_313-1 label');

    if(item1 && item2 && item3 && item4 ) {
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

        item1.addEventListener('click', function() {
            toggleStyles(item1, item2);
        });

        item2.addEventListener('click', function() {
            toggleStyles(item2, item1);
        });

        item3.addEventListener('click', function() {
            toggleStyles(item3, item4);
        });

        item4.addEventListener('click', function() {
            toggleStyles(item4, item3);
        });
    };
});

/* Location Specific Donation pop-up handler */
/* Question mark popup */
document.addEventListener('DOMContentLoaded', function(){
    const tooltips = document.querySelectorAll('.est-tooltipIcon');
    const locBtn = document.getElementById('location-popup');

    tooltips.forEach((item) => {
        item.addEventListener('click', () => {
            locBtn.click();
        });
    })
});