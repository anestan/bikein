<?php

/*
** Load more **
*/
function load_more_product_script() {

	global $wp_query;

	//wp_enqueue_script('jquery'); // In most cases it is already included on the page and this line can be removed

    // register our main script but do not enqueue it yet
	wp_register_script( 'loadmore', get_stylesheet_directory_uri() . '/js/loadmore.js', array('jquery') );

	// we have to pass parameters to app.js script but we can get the parameters values only in PHP
	wp_localize_script( 'loadmore', 'loadmore_params', array(
		'ajaxurl' => site_url() . '/wp-admin/admin-ajax.php', // WordPress AJAX
		'posts' => json_encode( $wp_query->query_vars ), // everything about your loop is here
		'current_page' => get_query_var( 'paged' ) ? get_query_var('paged') : 1,
		'max_page' => $wp_query->max_num_pages
	) );

 	wp_enqueue_script( 'loadmore' );
}

add_action( 'wp_enqueue_scripts', 'load_more_product_script' );



function loadmore_ajax_handler(){

	// prepare our arguments for the query
	$args = json_decode( stripslashes( $_POST['query'] ), true );
	$args['paged'] = $_POST['page'] + 1; // load next page
	$args['post_status'] = 'publish';
	$args['post_type'] = 'product';

	query_posts( $args );


	echo "<ul class='products columns-3'>"; //Change columns number here
	if( have_posts() ) :

		// run the loop
		while( have_posts() ): the_post();

			wc_get_template_part( 'content', 'product' );

		endwhile;

	endif;
	echo "<ul>";
	die;
}

add_action('wp_ajax_loadmore', 'loadmore_ajax_handler');
add_action('wp_ajax_nopriv_loadmore', 'loadmore_ajax_handler');
