document.addEventListener('DOMContentLoaded', () => {
    const valueInput = document.getElementById('value');
    const resultSpan = document.getElementById('resultSpan');
    const currency = document.querySelector('.currency');

    function updateResults(empty = false) {
        let rawValue = valueInput.value;

        if (rawValue === '') {
            rawValue = '0';
        }

        const numericValue = parseFloat(rawValue.replace(/,/g, ''));


        if (!isNaN(numericValue)) {
            let res = numericValue * 0.1;
            resultSpan.innerText = '$' + res.toLocaleString('en-US', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            });
        } else {
            resultSpan.innerText = '$' + '0.00';
        }
        if (!empty){
        const formattedValue = numericValue.toLocaleString('en-US', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2,
        });

        valueInput.value = formattedValue;
    }
        const charCount = valueInput.value.length;

        if (charCount === 7) {
            valueInput.style.fontSize = '56px'; 
            currency.style.fontSize = '56px';
        }
        
        if (charCount === 9) {
            valueInput.style.fontSize = '45px';
            currency.style.fontSize = '45px';
        }
    }

    //valueInput.addEventListener('input', updateResults);
    let keyPressed = null;
    valueInput.addEventListener('keydown', (event) => {
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
