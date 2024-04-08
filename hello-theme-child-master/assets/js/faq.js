document.addEventListener('DOMContentLoaded', function () {
    const fqItems = document.querySelectorAll('.jet-accordion__item.jet-toggle.jet-toggle-move-up-effect');
    const fqBtn = document.getElementById('expandQ');

    const inputField = document.getElementById('elementIdInput');
    const inputHandler = document.getElementById('inputHandler');

    const allContents = document.querySelectorAll('.jet-toggle__content');
    const allArrows = document.querySelectorAll('.jet-toggle__control');

    console.log(allContents)
    console.log(allArrows)

    const formatingStrValue = (str) => {
        return str.slice(0, 1) + 'q' + str.slice(1, 3);
    };

    let isActive = false;

    fqBtn.addEventListener('click', function () {        
        
        fqItems.forEach((item) => {

            const content = item.querySelectorAll('.jet-toggle__content');
            const arrow = item.querySelectorAll('.jet-toggle__control');

            if (isActive) {
                item.classList.remove('active-toggle');
                content.forEach((i) => { i.style.height = '0px'; });
                arrow.forEach((i) => { i.classList.remove('rotate-180'); });
            } else {
                item.classList.add('active-toggle');
                content.forEach((i) => { i.style.height = 'auto'; });
                arrow.forEach((i) => { i.classList.add('rotate-180'); });
            }
        });
    
        isActive = !isActive;
    })

    inputField.addEventListener('input', () => {
        const selectedOption = document.querySelector('#questions option[value="' + inputField.value + '"]');

        if (selectedOption) {
            const res = inputField.value.length > 2 ?
                formatingStrValue(inputField.value) :
                '#q' + inputField.value;

            inputHandler.href = res.trim();
            fqBtn.disabled = false;
        } else {
            fqBtn.disabled = true;
        }
    });

    inputHandler.addEventListener('click', (event) => {
        event.preventDefault();
    
        const targetId = formatingStrValue(inputField.value).slice(1).trim();
        const showElem = document.getElementById(targetId);
    
        if (showElem) {
            fqItems.forEach(element => {
                if (element !== showElem) {
                    element.classList.remove('active-toggle');
                    allContents.forEach((i) => { i.style.height = '0px'; });
                    allArrows.forEach((i) => { i.classList.remove('rotate-180'); });
                }
            });
    
            showElem.classList.add('active-toggle');
            const elemContent = showElem.querySelector('.jet-toggle__content');
            const elemArrow = showElem.querySelector('.jet-toggle__control'); // Fix here
    
            if (elemContent && elemArrow) {
                elemContent.style.height = 'auto';
                elemArrow.classList.add('rotate-180');
            }
    
            showElem.scrollIntoView({
                behavior: 'smooth',
                block: 'center',
            });
        }
    
        inputField.value = '';
    });
    
})

document.addEventListener('DOMContentLoaded', function () {
    const faqItems = document.querySelectorAll('.jet-accordion__item.jet-toggle.jet-toggle-move-up-effect');

    faqItems.forEach((item, index) => {
        item.setAttribute('id', `q${index + 1}`);
    });

    const button = document.createElement('button');
    button.className = 'copy-btn';

    const svg = document.createElementNS('http://www.w3.org/2000/svg', 'svg');
    svg.setAttribute('width', '24');
    svg.setAttribute('height', '24');
    svg.setAttribute('viewBox', '0 0 24 24');
    svg.setAttribute('fill', 'none');

    const g = document.createElementNS('http://www.w3.org/2000/svg', 'g');
    g.setAttribute('clip-path', 'url(#clip0_1397_18127)');

    const path1 = document.createElementNS('http://www.w3.org/2000/svg', 'path');
    path1.setAttribute('d', 'M17.25 17.25H3.75V3.75H17.25V17.25Z');
    path1.setAttribute('stroke', '#143A62');
    path1.setAttribute('stroke-width', '1.5');
    path1.setAttribute('stroke-linecap', 'round');
    path1.setAttribute('stroke-linejoin', 'round');

    const path2 = document.createElementNS('http://www.w3.org/2000/svg', 'path');
    path2.setAttribute('d', 'M6.75 20.25H20.25V6.75');
    path2.setAttribute('stroke', '#143A62');
    path2.setAttribute('stroke-width', '1.5');
    path2.setAttribute('stroke-linecap', 'round');
    path2.setAttribute('stroke-linejoin', 'round');

    g.appendChild(path1);
    g.appendChild(path2);
    svg.appendChild(g);
    button.appendChild(svg);

    faqItems.forEach((item) => {
        item.appendChild(button.cloneNode(true));
    });

    const buttons = document.querySelectorAll('.copy-btn');

    buttons.forEach((button) => {

        const copyAlert = document.createElement('span');
        copyAlert.className = 'copyAlert';
        copyAlert.style.display = 'none';
        copyAlert.innerHTML = `
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                <path d="M21.531 7.28055L9.53104 19.2806C9.46139 19.3503 9.37867 19.4056 9.28762 19.4433C9.19657 19.4811 9.09898 19.5005 9.00042 19.5005C8.90186 19.5005 8.80426 19.4811 8.71321 19.4433C8.62216 19.4056 8.53945 19.3503 8.46979 19.2806L3.21979 14.0306C3.07906 13.8898 3 13.699 3 13.4999C3 13.3009 3.07906 13.11 3.21979 12.9693C3.36052 12.8286 3.55139 12.7495 3.75042 12.7495C3.94944 12.7495 4.14031 12.8286 4.28104 12.9693L9.00042 17.6896L20.4698 6.2193C20.6105 6.07857 20.8014 5.99951 21.0004 5.99951C21.1994 5.99951 21.3903 6.07857 21.531 6.2193C21.6718 6.36003 21.7508 6.55091 21.7508 6.74993C21.7508 6.94895 21.6718 7.13982 21.531 7.28055Z" fill="#02A95C"></path>
            </svg>
            Link copied to clipboard`;

        button.appendChild(copyAlert);

        button.addEventListener('click', function () {
            const faqItem = button.closest('.jet-accordion__item');
            const itemId = faqItem.getAttribute('id');
            copyToClipboard(itemId, copyAlert);

        });
    });

    function copyToClipboard(text, copyAlert) {
        const tempInput = document.createElement('input');
        tempInput.value = `https://childfreebc.com/faq/?${text}`;
        document.body.appendChild(tempInput);
        tempInput.select();
        document.execCommand('copy');
        document.body.removeChild(tempInput);

        copyAlert.style.display = 'flex';

        setTimeout(() => {
            copyAlert.style.display = 'none';
        }, 3000);

    }

});

/* active item icon */

document.addEventListener('DOMContentLoaded', () => {
    const items = document.querySelectorAll('.jet-toggle__control');

    items.forEach((item) => {
        item.addEventListener('click', () => {
            item.classList.toggle('rotate-180');
        });
    });
});

/* Scroll target element afte DOMLoad */

document.addEventListener('DOMContentLoaded', () => {
    const url = window.location.href;

    function hasQueryParams(url) {
        return url.includes('?');
    }

    function getQueryParamValue(url) {
        const queryStringIndex = url.indexOf('?');

        if (queryStringIndex !== -1) {
            const queryString = url.slice(queryStringIndex + 1);
            return queryString;
        }

        return null;
    }

    if (hasQueryParams(url)) {
        const queryParamValue = getQueryParamValue(url);
        const targetElement = document.getElementById(queryParamValue);

        if (targetElement) {
            targetElement.classList.add('active-toggle');
            const t_content = targetElement.querySelector('.jet-toggle__content');
            const t_arrow = targetElement.querySelector('.jet-toggle__control');

            t_content.style.height = 'auto';
            t_arrow.classList.add('rotate-180');

            targetElement.scrollIntoView({
                behavior: 'smooth',
                block: 'center',
            });
        }
    }
});