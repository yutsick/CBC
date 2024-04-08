document.addEventListener('DOMContentLoaded', function(){

    const shortDescItems = document.querySelectorAll('.short-desc');

    shortDescItems.forEach((item) => {
        const originalText = item.textContent.trim();
        const maxLength = 250; 

        if (originalText.length > maxLength) {
            const shortText = originalText.substring(0, maxLength) + '';
            item.textContent = shortText;
            const readMore = document.createElement('span');
            readMore.style.display = 'block';
            readMore.style.width = '100%';
            readMore.textContent = ' Read more ...';
            readMore.style.fontWeight = '600';
            readMore.style.color = '#143A62';
            readMore.style.cursor = 'pointer';
            
            readMore.addEventListener('click', () => {
                item.textContent = originalText;
            });

            item.appendChild(readMore);
        }
    });

});