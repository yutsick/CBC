const tabLinks = document.querySelectorAll('ul li a');
const tabContents = document.querySelectorAll('div[id^="tab"]');
const mobMenu = document.querySelector('.menu')
const mobMenuBtn = document.querySelector('.mobMenuBtn')
const mobLinks = document.querySelectorAll('.menu .menuItem');
const mobTabs = document.querySelectorAll('.tabContent');

const viewBtns = document.querySelectorAll('.viewBtn');

viewBtns.forEach(function (btn) {
    btn.addEventListener('click', function (event) {
        event.preventDefault();
        setActiveTab(1);
    });
});

tabContents.forEach((content, index) => {
    if (index !== 0) {
        content.classList.add('hidden');
    }
});

function setActiveTab(index) {
    tabLinks.forEach((link, i) => {
        if (i === index) {
            link.classList.add('active');
            link.style.background = '#143A62';
            link.style.borderRadius = '6px';
            link.style.color = 'white';
        } else {
            link.classList.remove('active');
            link.style.background = ''; 
            link.style.borderRadius = '';
            link.style.color = '';
        }
    });

    tabContents.forEach((content) => {
        content.classList.add('hidden');
    });
    tabContents[index].classList.remove('hidden');
}

tabLinks[0].classList.add('active');
setActiveTab(0);

tabLinks.forEach((link, index) => {
    link.addEventListener('click', (event) => {
        event.preventDefault();
        setActiveTab(index);
        mobMenu.style.display = 'none';
    });
}); 

tabLinks.forEach((link, index) => {
    link.addEventListener('click', (event) => {
        event.preventDefault();
        setActiveTab(index, tabLinks, tabContents);
    });
});

mobLinks.forEach((link, index) => {
    link.addEventListener('click', (event) => {
        event.preventDefault();
        setActiveTab(index, mobLinks, mobTabs);
    });
});

document.addEventListener('DOMContentLoaded', () => {
    const requestBackBtn = document.getElementById('reqBack');
    const requestTabContent = document.getElementById('tabRequests');
    const candidateStatusContent = document.getElementById('tab2');

    if(requestBackBtn && requestTabContent) {
        requestBackBtn.addEventListener('click', () => {
            requestTabContent.classList.add = 'hidden';
            candidateStatusContent.classList.remove = 'hidden';
        })
    }
});