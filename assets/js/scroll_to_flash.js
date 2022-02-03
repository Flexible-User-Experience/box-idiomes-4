import jQuery from 'jquery';

jQuery(document).ready(function() {
    jQuery(document).ready(function() {
        const errorNode = jQuery(".glyphicon-exclamation-sign");
        const envelopeNode = jQuery(".fa-paper-plane-o");
        const exclamationTriangleNode = jQuery(".fa-exclamation-triangle");
        if (errorNode.length > 0) {
            jQuery('html,body').animate({scrollTop: errorNode.offset().top - 100}, "slow");
        }
        if (envelopeNode.length > 0) {
            jQuery('html,body').animate({scrollTop: envelopeNode.offset().top - 100}, "slow");
        }
        if (exclamationTriangleNode.length > 0) {
            jQuery('html,body').animate({scrollTop: exclamationTriangleNode.offset().top - 100}, "slow");
        }
    });
});
