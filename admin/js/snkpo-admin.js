(function( $ ) {
	'use strict';

	/**
	 * All of the code for your admin-facing JavaScript source
	 * should reside in this file.
	 *
	 * Note: It has been assumed you will write jQuery code here, so the
	 * $ function reference has been prepared for usage within the scope
	 * of this function.
	 *
	 * This enables you to define handlers, for when the DOM is ready:
	 *
	 * $(function() {
	 *
	 * });
	 *
	 * When the window is loaded:
	 *
	 * $( window ).load(function() {
	 *
	 * });
	 *
	 * ...and/or other possibilities.
	 *
	 * Ideally, it is not considered best practise to attach more than a
	 * single DOM-ready or window-load handler for a particular page.
	 * Although scripts in the WordPress core, Plugins and Themes may be
	 * practising this, we should strive to set a better example in our own work.
	 */

	$(document).ready(function(){
		$(document).on('click','.add-stock-btn',function(e){
			e.preventDefault();
			var id = $(this).data('id');
			$('#add-stock-form .product_id').val(id);
			tb_show('Add Stock','#TB_inline?&width=300&height=300&inlineId=add-stock-modal');
		});
		$(document).on('click','.reduce-stock-btn',function(e){
			e.preventDefault();
			var id = $(this).data('id');
			$('#reduce-stock-form .product_id').val(id);
			tb_show('Reduce Stock','#TB_inline?&width=300&height=300&inlineId=reduce-stock-modal');
		});
		$(document).on('submit','#add-stock-form',function(e){
			e.preventDefault();
			
			$("#add-stock-alert").html('');

			var data = $(this).serialize();

			$.ajax({
				url: snkpo.ajax_url,
				data: data,
				type: 'post',
				beforeSend: function() {
					$.blockUI({ 
						message: '<p>Please wait...</p>',
						css: { 
							backgroundColor: 'transparent', 
							color: '#fff',
							border: 0,
						}
					});
				},
				success: function( response ) {
					$.unblockUI();

					var data = {};

					if ( response.success ) {
						data.type = 'success';

						setTimeout(function(){
							location.reload(true);
						}, 1000);

					} else {
						data.type = 'error';
					}

					data.messages = response.data;

					var template = $.templates("#alert-template");
					var htmlOutput = template.render(data);
					$("#add-stock-alert").html(htmlOutput);
				}		
			});
		});
		$(document).on('submit','#reduce-stock-form',function(e){
			e.preventDefault();

			$("#reduce-stock-alert").html('');

			var data = $(this).serialize();

			$.ajax({
				url: snkpo.ajax_url,
				data: data,
				type: 'post',
				beforeSend: function() {
					$.blockUI({ 
						message: '<p>Please wait...</p>',
						css: { 
							backgroundColor: 'transparent', 
							color: '#fff',
							border: 0,
						}
					});
				},
				success: function( response ) {
					$.unblockUI();

					var data = {};

					if ( response.success ) {
						data.type = 'success';

						setTimeout(function(){
							location.reload(true);
						}, 1000);

					} else {
						data.type = 'error';
					}

					data.messages = response.data;

					var template = $.templates("#alert-template");
					var htmlOutput = template.render(data);
					$("#reduce-stock-alert").html(htmlOutput);
				}		
			});
		});
	});

})( jQuery );
