/*
 * Function to toggle status by admin bar button
 */

jQuery(document).ready(function () {
  jQuery("#wp-admin-bar-ms-switch-button a").on("click", function (e) {
    e.preventDefault();

    // Debug: Check if maintenance_switch_ajax is available
    console.log(
      "maintenance_switch_ajax:",
      typeof maintenance_switch_ajax !== "undefined"
        ? maintenance_switch_ajax
        : "UNDEFINED"
    );

    // Check if the variable exists
    if (typeof maintenance_switch_ajax === "undefined") {
      console.error("maintenance_switch_ajax is not defined!");
      return;
    }

    // define icons
    var iconBase = "dashicons-admin-tools";
    var iconUpdate = "dashicons-admin-generic";
    // get button element
    var elt = jQuery("#wp-admin-bar-ms-switch-button");
    // set ajax vars with nonce
    var data = {
      action: "toggle_status",
      nonce: maintenance_switch_ajax.nonce,
    };

    console.log("Sending AJAX data:", data);
    // toggle icon for spinner
    jQuery(elt).find(".ab-icon").removeClass(iconBase).addClass(iconUpdate);
    //ajax request - using POST for security
    jQuery
      .post(maintenance_switch_ajax.ajax_url, data, function (response) {
        console.log("AJAX Response:", response);
        // toggle icon for no spinner
        jQuery(elt).find(".ab-icon").removeClass(iconUpdate).addClass(iconBase);
        // if success toggle button class
        if (response.success) {
          console.log("Success! New status:", response.status);
          switch (response.status) {
            case 1:
              elt.addClass("active");
              break;
            case 0:
              elt.removeClass("active");
              break;
          }
          elt.removeClass(":hover");
        } else {
          console.error("AJAX Error:", response);
        }
      })
      .fail(function (xhr, textStatus, errorThrown) {
        console.error("AJAX Failed:", textStatus, errorThrown);
        jQuery(elt).find(".ab-icon").removeClass(iconUpdate).addClass(iconBase);
      });
  });
});
