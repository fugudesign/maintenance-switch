/*
 * Function to toggle status by admin bar button
 */
 

jQuery(document).ready(function(){
	
	jQuery('#wp-admin-bar-ms-switch-button a').on('click', function(e){
		e.preventDefault();
	
		// define icons
		var iconBase = 'dashicons-admin-tools';
		var iconUpdate = 'dashicons-admin-generic';
		// get button element
		var elt = jQuery('#wp-admin-bar-ms-switch-button');
		// set ajax vars
		var data = { 'action': 'toggle_status' };
	    // toggle icon for spinner
	    jQuery(elt).find('.ab-icon').removeClass(iconBase).addClass(iconUpdate);
		//ajax request
		jQuery.get( ajaxurl, data, 
		    function(response){
			    // toggle icon for no spinner
			    jQuery(elt).find('.ab-icon').removeClass(iconUpdate).addClass(iconBase);
			    // if success toggle button class
			    if ( response.success ) {
				    switch (response.status) {
					    case 1: elt.addClass('active'); break;
					    case 0: elt.removeClass('active'); break;
				    }
				    elt.removeClass(':hover');
				}
		    }
		);
	});
});
 