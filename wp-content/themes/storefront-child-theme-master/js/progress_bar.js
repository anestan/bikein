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
			
					// replace value with post count * 2
					var post_count = loadmore_params.post_count;
					$('#progress-bar').val(post_count * 2);
				
				}
			}
		});
       

	});

});