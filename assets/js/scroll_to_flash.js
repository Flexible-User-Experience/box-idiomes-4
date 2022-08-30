import jQuery from 'jquery';

jQuery(document).ready(function() {
    const errorNode = jQuery('.glyphicon-exclamation-sign');
    const envelopeNode = jQuery('.fa-thumbs-up');
    const exclamationTriangleNode = jQuery('.fa-exclamation-triangle');
    const invalidFeedbackNode = jQuery('.invalid-feedback');
    moveToNode(errorNode);
    moveToNode(envelopeNode);
    moveToNode(exclamationTriangleNode);
    moveToNode(invalidFeedbackNode);
});

function moveToNode(node) {
    if (node.length > 0) {
        jQuery('html,body').animate({scrollTop: node.offset().top - 150}, 'slow');
    }
}
