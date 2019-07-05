(function( $ ) {
	'use strict';

	let sankosha;
	let timer;
    let delay = 600;

	sankosha = {
		product_id : 0,
		total_order : 0,
		form : '',
		post_data : {},
		checkStock : function() {
			$.ajax({
				url : sankosha_var.checkStock.url,
				method : 'POST',
				dataType : 'json',
				data : {
					product_id : sankosha.product_id,
					key : sankosha_var.checkStock.key,
				},
				beforeSend : function() {

				},
				success : function(response) {

				}
			});
		},
		createOrder : function() {
			$.ajax({
				url : sankosha_var.order.url,
				method : 'POST',
				dataType : 'json',
				data : {
					product_id : sankosha.product_id,
					key : sankosha_var.order.key,
					post_data : sankosha.post_data
				},
				beforeSend : function() {

				},
				success : function(response) {

				}
			});
		}
	};

	$(document).on('change keyup keypress blur','.sankosha-total-order',function(){
		window.clearTimeout(timer);
		let value = $(this).val()
		timer = window.setTimeout(function(){
			sankosha.product_id = sankosha_var.checkStock.product_id;
			sankosha.total_order = value;
			sankosha.form = $('.sankosha-order-form"');

			sankosha.checkStock();
		},delay);
	});
})( jQuery );
