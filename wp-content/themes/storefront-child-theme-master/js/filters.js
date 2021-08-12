//Show or hide archive filters
jQuery(function($){
	$(".show_filter").click(function() {
	  $("#archive-top-filters").slideToggle(300);
		$("#archive-top-filters").css("display","flex");
	});
});
