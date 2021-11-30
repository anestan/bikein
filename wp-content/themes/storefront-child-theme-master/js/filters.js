//Show or hide archive filters
jQuery(function($){
	$(".show_filter").click(function() {
	  $("#archive-top-filters").slideToggle(300);
		$("#archive-top-filters").css("display","grid");
	});

	$(".show_cat").click(function() {
	  $(".widget_product_categories").slideToggle(300);
	});
});
