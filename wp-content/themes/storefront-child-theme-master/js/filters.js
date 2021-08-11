//Show or hide archive filters
jQuery(function($){
	$(".show_filter").click(function() {
	  $("#archive-top-filters").slideToggle(300);
		$("#archive-top-filters").css("display","flex");
	});

	$(".show_sidebar_filter").click(function() {
	  $("#secondary .widget_layered_nav").slideToggle(300);
	});

	$(".show_sidebar_cat").click(function() {
	  $("#secondary .widget_product_categories").slideToggle(300);
	});
});
