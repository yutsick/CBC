// Select the target node
const targetNode = document.querySelector('div.elementor-menu-cart__container');

// Callback function to execute when mutations are observed
const callback = function(mutationsList, observer) {
    // Loop through all mutations
    for(const mutation of mutationsList) {
        // Check if nodes were added
        if (mutation.type === 'childList') {
            // Check if div.clear-all-wrapper was added
            const clearAllWrapper = targetNode.querySelector('div.clear-all-wrapper');
            if (clearAllWrapper) {
                console.log('div.clear-all-wrapper was added');
                // Perform your actions here
            }
        }
    }
};

// Create a MutationObserver instance
const observer = new MutationObserver(callback);

// Configure the observer to watch for changes in child nodes
const config = { childList: true };

// Start observing the target node for configured mutations
observer.observe(targetNode, config);
