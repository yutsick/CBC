jQuery(document).ready(function() {
    jQuery("#resendVerificationButton").click(function() {
        var email = jQuery(this).data('email');

        jQuery.ajax({
            type: "POST",
            url: resend_verification_object.ajax_url,
            data: { 
                action: 'resend_verification',
                email: email 
            },
            success: function(response) {
                if (response === "success") {
                    console.log("Verification email has been resent successfully.");
                } else {
                    console.log("Failed to resend verification email. Please try again later.");
                }
            },
            error: function() {
                console.log("Failed to resend verification email. Please try again later.");
            }
        });
    });
});
