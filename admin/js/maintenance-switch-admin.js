(function( $ ) {
	'use strict';
	
	$(document).ready(function(){
		
		$('#addmyip').on('click', function(e){
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
		
		$('[name=ms_use_theme]').on('change', function(e){
			var checked = this.checked;
			$('[name=ms_page_html]').prop('disabled', checked);
		});
		
		$('#page-preview').on('click', function(e){
			e.preventDefault();
			var form = $('#preview-form');
			var theme = $('[name=ms_use_theme]').prop('checked');
			if ( theme ) {
				var url = $('[name=ms_preview_theme_file]').val();
				form.attr('action', url).submit();
			} 
			else {
				var html = $('[name=ms_page_html]').val();
				form.attr('action', form.data('default-action')).append( $('<input/>' ).attr( { type:'hidden', name:'preview-code', value:html } ) ).submit();
			}
		});
		
	});


})( jQuery );