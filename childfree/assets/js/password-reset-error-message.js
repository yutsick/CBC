jQuery(document).ready(function ($) {
    // Find and replace the text
    var originalText = "That email address is not recognised";
    var replacementText = "That email address does not exist.";

    // Check if the text exists on the page
    if ($('body:contains("' + originalText + '")').length) {
        // Replace the text
        $('p.jet-reset__error-message>span').text(replacementText);
    }
});
