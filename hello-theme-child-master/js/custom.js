jQuery(document).ready(function ($) {

    /**
     * For adding a button to the side cart widget
     * It will be added after 1 second
     * */
    setTimeout(function () {
        $('.elementor-menu-cart__footer-buttons').append('<a href="https://childfreebc.com/candidates/" class="elementor-button elementor-button--checkout elementor-size-md"><span class="elementor-button-text">Browse more Candidates</span></a>');
    }, 1000);


});