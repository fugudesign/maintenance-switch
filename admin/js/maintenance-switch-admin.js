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
		
		$('#ms_use_theme').on('change', function(e){
			var checked = this.checked;
			$('#ms_page_html').prop('readonly', checked);
		});
		
		$('#page-preview').on('click', function(e){
			e.preventDefault();
			var form = $('#preview-form');
			var theme = $('#ms_use_theme').prop('checked');
			if ( theme ) {
				var url = $('#ms_preview_theme_file').val();
				form.attr('action', url).submit();
			} 
			else {
				var html = $('#ms_page_html').val();
				form.attr('action', form.data('default-action')).html( $('<input/>' ).attr( { type:'hidden', id:'preview-code', name:'preview-code', value:html } ) ).submit();
			}
		});
		
		$('input[data-msg]').on('click', function(e) {
			var message = $(this).data('msg');
			if ( !confirm( message ) )
				e.preventDefault();
		});
	});


})( jQuery );