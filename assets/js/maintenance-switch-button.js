function MaintenanceSwitchToggleStatus() {
	
	var elt = jQuery('#wp-admin-bar-ms-switch-button');
	
	var status = elt.hasClass('active') ? 'off' : 'on';
	console.log(status);
	var data = {
        'action': 'toggle_status',
        'status': status,
    };
	    
	jQuery.get( ajaxurl, data, 
	    function(response){
		    
		    if ( response.success ) {
			    switch (status) {
				    
				    case 'on': 
				    	elt.addClass('active'); 
				    	break;
				    	
				    case 'off': 
				    	elt.removeClass('active'); 
				    	break;
			    }
			    elt.removeClass(':hover');
			}
	    }
	);
	
	return false;
}