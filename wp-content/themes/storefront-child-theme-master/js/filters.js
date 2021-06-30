//Show or hide archive filters
jQuery(function($){
	$(".show_filter").click(function() {
	  $("#archive-top-filters").slideToggle(400);
		$("#archive-top-filters").css("display","flex");
	});
});
