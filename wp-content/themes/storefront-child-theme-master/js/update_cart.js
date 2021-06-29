// Update cart on input changes
jQuery( function( $ ) {
	jQuery('.woocommerce-cart').on('click', 'input.qty', function(){
		jQuery("[name='update_cart']").trigger("click");
	 });
} );