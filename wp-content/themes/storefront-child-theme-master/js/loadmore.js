jQuery(function($){ // use jQuery code inside this to avoid "$ is not defined" error
	$('.loadmore_button').click(function(){

		var button = $(this),
		    data = {
			'action': 'loadmore',
			'query': misha_loadmore_params.posts, // that's how we get params from wp_localize_script() function
			'page' : misha_loadmore_params.current_page
		};

		$.ajax({ // you can also use $.post here
			url : misha_loadmore_params.ajaxurl, // AJAX handler
			data : data,
			type : 'POST',
			beforeSend : function ( xhr ) {
				button.text('Henter...'); // change the button text, you can also add a preloader image
			},
			success : function( data ){
				if( data ) {
					button.text( 'Se flere produkter' ).prev().before(data); // insert new posts
					misha_loadmore_params.current_page++;

					if ( misha_loadmore_params.current_page == misha_loadmore_params.max_page )
						button.remove(); // if last page, remove the button

					// you can also fire the "post-load" event here if you use a plugin that requires it
					// $( document.body ).trigger( 'post-load' );
				} else {
					button.remove(); // if no data, remove the button as well
				}
			}
		});
	});
});


// Show or hide stock
jQuery(function($){
	$("#stockButton").click(function() {
	  $(".stock_status").toggle("slide");
	});
});

//Show or hide archive filters
jQuery(function($){
	$(".show_filter").click(function() {
	  $("#secondary").slideToggle(400);
	});
});



jQuery(function($){
	var prevScrollpos = window.pageYOffset;
	window.onscroll = function() {
	var currentScrollPos = window.pageYOffset;
	  if (prevScrollpos > currentScrollPos) {
		document.getElementById("masthead").style.top = "0";
	  } else {
		document.getElementById("masthead").style.top = "-200px";
	  }
	  prevScrollpos = currentScrollPos;
	}

});
