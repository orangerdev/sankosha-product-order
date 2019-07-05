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
				method : 'POST',
				data : {
					product_id : sankosha.product_id,
					key : sankosha_var.order.key,
					post_data : sankosha.post_data
				},
				beforeSend : function() {
					sankosha.form.find('button').attr('disabled',true).html('Loading. Create order...');
					sankosha.form.find('.message.error').hide();
					sankosha.form.find('.message.success').hide();
				},
				success : function(response) {
					console.log()
					if(true == response.valid) {
						sankosha.form.find('.message.success').show().html(response.message);
						setTimeout(function(){
							location.reload();
						},1000);
					} else {
						sankosha.form.find('.message.error').show().html(response.message);
					}
					sankosha.form.find('button').attr('disabled',false).html('Order');
				}
			});
		}
	};

	$(document).on('submit','.sankosha-order-form',function(){
		sankosha.product_id = sankosha_var.order.product_id;
		sankosha.post_data = $(this).serializeArray();
		sankosha.form = $('.sankosha-order-form');
		sankosha.createOrder();
		return false;
	});

	$(document).on('change keyup keypress','.sankosha-total-order',function(){
		window.clearTimeout(timer);
		let value = $(this).val()
		timer = window.setTimeout(function(){
			sankosha.product_id = sankosha_var.checkStock.product_id;
			sankosha.total_order = value;
			sankosha.form = $('.sankosha-order-form');
			sankosha.checkStock();
		},delay);
	});
})( jQuery );
