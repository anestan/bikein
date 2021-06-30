<?php
/**
 * The Template for displaying product archives, including the main shop page which is a post type archive
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/archive-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.4.0
 */

defined( 'ABSPATH' ) || exit;

/** Load More or View More Functionality
 * What it is doing is checking if an ajax request was fired
 * and if fired, will run the code and exit to terminate the ajax request
 */

get_header( 'shop' );


/**
 * Hook: woocommerce_before_main_content.
 *
 * @hooked woocommerce_output_content_wrapper - 10 (outputs opening divs for the content)
 * @hooked woocommerce_breadcrumb - 20
 * @hooked WC_Structured_Data::generate_website_data() - 30
 */
	?>
	<header class="woocommerce-products-header">
		<?php if ( apply_filters( 'woocommerce_show_page_title', true ) ) : ?>
			<div>
				<h1 class="woocommerce-products-header__title page-title"><?php woocommerce_page_title(); ?></h1>
				<div class="test"><?php woocommerce_breadcrumb(); ?></div>
			</div>
		<?php endif;
		/**
		 * Hook: woocommerce_archive_description.
		 *
		 * @hooked woocommerce_taxonomy_archive_description - 10
		 * @hooked woocommerce_product_archive_description - 10
		 */
		do_action( 'woocommerce_archive_description' );
		?>
	</header>
	<?php

do_action( 'woocommerce_before_main_content' );

if ( woocommerce_product_loop() ) {


	/**
	 * Hook: woocommerce_before_shop_loop.
	 *
	 * @hooked woocommerce_output_all_notices - 10
	 * @hooked woocommerce_result_count - 20
	 * @hooked woocommerce_catalog_ordering - 30
	 */
	do_action( 'woocommerce_before_shop_loop' );

	if ( is_active_sidebar( 'top-filter-area' ) ) : ?>
	<button class="show_filter" type="button" name="show_filter">Vis filtrering</button>
	<div id="archive-top-filters" class="filter-wrapper">
	<?php dynamic_sidebar( 'top-filter-area' ); ?>
	</div>
	<?php endif;

	woocommerce_product_loop_start();


	if ( wc_get_loop_prop( 'total' ) ) {
		while ( have_posts() ) {
			the_post();

			/**
			 * Hook: woocommerce_shop_loop.
			 */
			do_action( 'woocommerce_shop_loop' );

			wc_get_template_part( 'content', 'product' );
		}
	}

	woocommerce_product_loop_end();

	/**
	 * Hook: woocommerce_after_shop_loop.
	 *
	 * @hooked woocommerce_pagination - 10
	 */
	do_action( 'woocommerce_after_shop_loop' );

	global $wp_query; // you can remove this line if everything works for you

	// don't display the button if there are not enough posts
	if (  $wp_query->max_num_pages > 1 )
		echo '<button class="loadmore_button">Se flere produkter</button>';

} else {
	/**
	 * Hook: woocommerce_no_products_found.
	 *
	 * @hooked wc_no_products_found - 10
	 */
	do_action( 'woocommerce_no_products_found' );
}

/*
*
* show ekstra category description
*
*/
if ( is_product_taxonomy() ) {
	$term = get_queried_object();
	if ( $term && ! empty( get_woocommerce_term_meta( $term->term_id, 'seconddesc', true ) ) ) {
	   echo '<p class="term-description">' . wc_format_content( htmlspecialchars_decode( get_woocommerce_term_meta( $term->term_id, 'seconddesc', true ) ) ) . '</p>';
	}
 }

/**
 * Hook: woocommerce_after_main_content.
 *
 * @hooked woocommerce_output_content_wrapper_end - 10 (outputs closing divs for the content)
 */
do_action( 'woocommerce_after_main_content' );

/**
 * Hook: woocommerce_sidebar.
 *
 * @hooked woocommerce_get_sidebar - 10
 */
do_action( 'woocommerce_sidebar' );

get_footer( 'shop' );
