document.addEventListener('DOMContentLoaded', () => {
    let clear = document.createElement("div");
        clear.classList.add('clear-all-wrapper');
    if (!document.querySelector('.clear-all-wrapper')){
        
        document.querySelector('.elementor-menu-cart__main').appendChild(clear);
        clearMiniCart();
    }
    const cartBtn = document.getElementById('elementor-menu-cart__toggle_button');
    if(cartBtn) {
        cartBtn.addEventListener('click', () => {
                
            try{
                const elem = document.querySelector('.woocommerce-mini-cart__empty-message'); 
                elem.innerHTML = 'Your Donation Cart is Empty <a href="/checkout/?funds=general" class="cartLink">Click here</a> to Donate';
                elem.style.color = '#143A62';
                if (!document.querySelector('.clear-all-wrapper')){
                    elem.parentElement.after(clear);
                    clearMiniCart();
                }
            }
            catch{}
        });
    }   


});

function clearMiniCart(){
    const clearAllWrapper = document.querySelector('.clear-all-wrapper');
    if (clearAllWrapper) {
        jQuery(clearAllWrapper).on('click', () => {
            jQuery.ajax({
                url: '/wp-admin/admin-ajax.php',
                method: 'POST',
                data: {
                    action: 'clear_cart',
                },
                success: function(data) {
                    // Mini cart update logic 
                    jQuery(document.body).trigger('wc_fragment_refresh');
                    try{
                        jQuery('#clear_the_cart').trigger('click');  
                    }
                    catch{}
                },
                error: function(xhr, status, error) {
                    console.error('Error:', error);
                }
            });
        });
    }
}
    