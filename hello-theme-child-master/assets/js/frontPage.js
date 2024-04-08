document.addEventListener('DOMContentLoaded', () => {
    function handleHoverBlockEvents(hoverBlockElement, hiddenBlockElement) {
        let isHovered = false;

        hoverBlockElement.addEventListener('mouseenter', () => {
            if (!isHovered) {
                isHovered = true;
                hiddenBlockElement.style.display = 'block';
                hiddenBlockElement.style.position = 'absolute';
                hiddenBlockElement.style.height = '100%';
            }
        });

        hoverBlockElement.addEventListener('mouseleave', () => {
            if (isHovered) {
                isHovered = false;
                hiddenBlockElement.style.display = 'none';
            }
        });

        if (window.innerWidth < 1024) {
            hoverBlockElement.addEventListener('click', () => {
                hiddenBlockElement.style.display = 'block';
                hiddenBlockElement.style.position = 'absolute';
                hiddenBlockElement.style.height = '100%';
            });

            hiddenBlockElement.addEventListener('click', (e) => {
                e.stopPropagation();
                hiddenBlockElement.style.display = 'none';
            });

            hoverBlockElement.addEventListener('touchstart', () => {
                hiddenBlockElement.style.display = 'block';
                hiddenBlockElement.style.position = 'absolute';
                hiddenBlockElement.style.height = '100%';
            });

            hiddenBlockElement.addEventListener('touchstart', (e) => {
                e.stopPropagation();
                hiddenBlockElement.style.display = 'none';
            });
        }
    }

    const hoverBlock = document.querySelector('.hover-content');
    const hiddenBlock = document.querySelector('.hidden-content');
    handleHoverBlockEvents(hoverBlock, hiddenBlock);

    const hDonors = document.querySelector('.hover-content__donors');
    const hiddenDonors = document.querySelector('.hidden-contentDonor');
    handleHoverBlockEvents(hDonors, hiddenDonors);

    const hPhysicians = document.querySelector('.hover-content__physicians');
    const hiddenPhysicians = document.querySelector('.hidden-contentPhysicians');
    handleHoverBlockEvents(hPhysicians, hiddenPhysicians);
});


document.addEventListener('DOMContentLoaded', () => {
    const raisedValues = document.querySelectorAll('.raised-value div h2'); 
    const goalValues = document.querySelectorAll('.goal-value div h2');

    const convertValue = (items, addText = false) => {
        items.forEach(value => {
            const cleanedValue = value.textContent.replace(/[^0-9]/g, '');
            const numberValue = parseInt(cleanedValue);
            const formattedValue = new Intl.NumberFormat("en-IN").format(numberValue);

            value.textContent = `$${formattedValue}` + (addText ? ' raised' : '');
        });
    }

    convertValue(raisedValues, true); 
    convertValue(goalValues);
});