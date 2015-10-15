(function( $ ) {
	'use strict';
	
	$(document).ready(function(){
		
		$('#addmyip').click(function(e){
			e.preventDefault();
			
			var ip = $(this).data('ip');
			
			var ipRegex = /\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}/;
			var valid = ipRegex.test(ip);
			
			if ( valid == true ) {
				var ips = $('#ms_allowed_ips').val();
				var new_ips = ips != '' ? ips + ', ' + ip : ip;
				$('#ms_allowed_ips').val( new_ips );
			}
		});
		
	});


})( jQuery );