(($) => {
  let state = "closed";

  const faqExpand = () => {
    $("#faq-expand-all").on("click", (e) => {
      let selector =
        state === "closed"
          ? ".elementor-tab-title:not(.elementor-active)"
          : ".elementor-tab-title.elementor-active";

      $(selector).click();

      state = state === "closed" ? "opened" : "closed";
    });
  };

  const init = () => {
    faqExpand();
  };

  // init on dom ready
  $(init);
})(jQuery);
jQuery("#switch_account_type").on("change", function (e) {
  var current_user_role = jQuery("#current_user_role").val();
  var cuser_id = jQuery("#current_user_id").val();
  var plicence_number = jQuery("#physician_licence_number").val();
  var physician_speciality = jQuery("#physician_speciality").val();

  var new_user_role = jQuery(this).val();

  console.log("Current User Role: ");
  console.log(current_user_role);

  console.log("Current User ID: ");
  console.log(cuser_id);

  console.log("Selected User Account Type: ");
  var accountType = jQuery("#switch_account_type").val();
  console.log(accountType);

  if (new_user_role == "candidate") {
    window.location.replace("/register");
  } else if (new_user_role == "medical_provider") {
    window.location.replace("/register-physician/");
  } else if (new_user_role == "customer") {
    window.location.replace("/register-advocate");
  } else if (new_user_role == "subscriber") {
    window.location.replace("/register-donor");
    
  }
  // 		if(new_user_role == "medical_provider" && physician_speciality == "" && physician_speciality == ""   ){
  // 		 window.location.replace("/register-physician/?switchto="+new_user_role);
  // 		}else{

  // //      jQuery.ajax({
  // //          type : "post",
  // //          dataType : "json",
  // //          url : ajaxurl,
  // //          data : {action: "switch_to_account", current_role : current_user_role, new_role: new_user_role,user_id:cuser_id},
  // //          success: function(response) {
  // //             if(response.type == "success") {
  // //                alert("Your account has switched successfully");
  // // 			    window.location.replace("/my-account/");
  // //             }
  // //             else {
  // //                alert("Your account unable to swtich try again.");
  // //             }
  // //          }
  // //       });
  //    }
});

jQuery(document).ready(function ($) {
    // Hide the field by default
    $("#reff_field").hide();
    //$('input[name="refered_by"]').not(':checked').prop("checked", true); // Ensure "No" is preselected

    $('#refter_custom_field input[name="refered_by"]').on("click", function () {
		$('input[name="refered_by"]').not(this).prop("checked", false);
        var refered = $('input[name="refered_by"]:checked').val();

        // Uncheck all other checkboxes except the one that was clicked
        //$('input[name="refered_by"]').not(this).prop("checked", false);

        console.log(refered);

        if (refered === "yes") {
            $("#reff_field").show();
        } else if (refered === "no") {
            $("#reff_field").hide();
        }
    });
});


