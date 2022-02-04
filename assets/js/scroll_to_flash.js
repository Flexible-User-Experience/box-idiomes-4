import jQuery from 'jquery';

jQuery(document).ready(function() {
    const errorNode = jQuery('.glyphicon-exclamation-sign');
    const envelopeNode = jQuery('.fa-paper-plane');
    const exclamationTriangleNode = jQuery('.fa-exclamation-triangle');
    moveToNode(errorNode);
    moveToNode(envelopeNode);
    moveToNode(exclamationTriangleNode);
});

function moveToNode(node) {
    if (node.length > 0) {
        jQuery('html,body').animate({scrollTop: node.offset().top - 100}, 'slow');
    }
}
