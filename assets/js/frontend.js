import '../css/frontend.scss';
import jQuery from 'jquery';

jQuery(document).ready(function() {
    jQuery('.scroll-top-wrapper').on('click', scrollToTop);
    function scrollToTop() {
        const element = jQuery('body');
        const offset = element.offset();
        const offsetTop = offset.top;
        jQuery('html, body').animate({scrollTop: offsetTop}, 1000, 'swing');
    }
});
jQuery(document).on('scroll', function() {
    if (jQuery(window).scrollTop() > 100) {
        jQuery('.scroll-top-wrapper').addClass('show');
    } else {
        jQuery('.scroll-top-wrapper').removeClass('show');
    }
});
