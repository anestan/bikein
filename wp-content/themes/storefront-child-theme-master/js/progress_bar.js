// Update progress bar on product archive
jQuery(function($){
	
	$(".loadmore_button").on('click', function(e) {

	    var button = $(this),
		data = {
		'action': 'loadmore',
		'query': loadmore_params.posts
		};

		$.ajax({
			url : loadmore_params.ajaxurl, // AJAX handler
			data : data,
			type : 'POST',
			success : function( data ){
				if( data ) {
			
					// replace value with post count + post count
					var post_count = loadmore_params.post_count;
					$('#progress-bar').val(post_count + post_count);

					var post_found = loadmore_params.post_found;
					var post_count_dobble = post_count * 2;

					if ( post_count_dobble > post_found ) {
						$('.woocommerce-result-count ').replaceWith('<p class="woocommerce-result-count">Viser ' + (post_count_dobble - 1 )  + ' af ' + post_found + ' resultater</p>' );
					}
					else {
						$('.woocommerce-result-count ').replaceWith('<p class="woocommerce-result-count">Viser ' + post_count_dobble + ' af ' + post_found + ' resultater</p>' );
					}

				}
			}
		});
       

	});

});