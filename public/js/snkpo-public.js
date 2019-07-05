(function( $ ) {
	'use strict';

	let timer;
    let delay = 600;

	let sankosha = {
		product_id : 0,
		total_order : 0,
		form : '',
		post_data : {},
		checkStock : function() {
			$.ajax({
				url : sankosha_var.checkStock.url,
				dataType : 'json',
				data : {
					product_id : sankosha.product_id,
					total_order : sankosha.total_order,
					key : sankosha_var.checkStock.key,
				},
				beforeSend : function() {
					sankosha.form.find('button').attr('disabled',true).html('Loading. Checking stock...');
				},
				success : function(response) {
					sankosha.form.find('.message.info').show().html(response.message);
					sankosha.form.find('button').attr('disabled',false).html('Order');
				}
			});
		},
		createOrder : function() {
			$.ajax({
				url : sankosha_var.order.url,
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
			sankosha.form = $('.sankosha-order-form');
			console.log(sankosha.product_id,sankosha.total_order);
			sankosha.checkStock();
		},delay);
	});
})( jQuery );
