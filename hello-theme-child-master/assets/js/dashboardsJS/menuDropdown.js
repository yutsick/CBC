document.addEventListener('DOMContentLoaded', function() {
    const menuItems = document.querySelectorAll('.menuItem');

    menuItems.forEach(item => {
        item.addEventListener('click', handleClick);
    });
});

const handleClick = () => {
    const elem = document.activeElement;
    if (elem) {
        elem.blur();
    }
};