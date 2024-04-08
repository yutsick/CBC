console.log('adv.js loaded');
document.addEventListener('DOMContentLoaded', function() {
    const toolBlock = document.querySelector('.adv-tooltip');
    const toolBtn = document.querySelector('.advocate-tool');

    toolBtn.addEventListener('click', (event) => {
        event.stopPropagation(); 

        if (toolBlock.style.display === 'block') {
            toolBlock.style.display = 'none';
        } else {
            toolBlock.style.display = 'block';
        }
    });

    document.addEventListener('click', (event) => {
        if (!toolBlock.contains(event.target) && event.target !== toolBtn) {
            toolBlock.style.display = 'none';
        }
    });

});


// document.addEventListener('DOMContentLoaded', function() {
//     const toolBlock = document.querySelector('.adv-tooltip');
//     const toolBtn = document.querySelector('.advocate-tool');

//     toolBtn.addEventListener('mouseenter', () => {
//         toolBlock.style.display = 'block';
//     });

//     toolBtn.addEventListener('mouseleave', () => {
//         toolBlock.style.display = 'none';
//     });

//     toolBtn.addEventListener('mouseover', () => {
//         toolBlock.style.display = 'block';
//     });

// })
