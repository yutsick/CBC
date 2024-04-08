jQuery(document).ready(function ($) {
    // Candidate Registration Form Referral Source (Referred Person)
    $(document).on('input', '#field_35m30', function () {
        let referralHidden = $('input#field_i5n9y-other_9-otext');
        referralHidden.val($(this).val());
    });
});