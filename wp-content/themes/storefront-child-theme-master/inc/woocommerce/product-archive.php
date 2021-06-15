<?php

/* Remove the result count from WooCommerce */
add_action( 'init', 'remove_result_count' );

function remove_result_count() {
   remove_action( 'woocommerce_after_shop_loop', 'woocommerce_result_count', 20 );
   remove_action( 'woocommerce_before_shop_loop', 'woocommerce_result_count', 20 );
}

/* Remove sorting - Product page bottom */
add_action( 'after_setup_theme', 'remove_woocommerce_catalog_ordering', 1 );
function remove_woocommerce_catalog_ordering() {
	remove_action( 'woocommerce_after_shop_loop', 'woocommerce_catalog_ordering', 10 ); // If using Storefront, replace 30 by 10.
}

/* Move sorting - Product page top */
add_action( 'after_setup_theme', 'move_woocommerce_catalog_ordering' );
function move_woocommerce_catalog_ordering() {
	remove_action( 'woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 10 );
  add_action( 'woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 10 );
}

//Remove add to cart button
remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10);

//Remove Price Range
// function wc_varb_price_range( $wcv_price, $product ) {
//
//     $prefix = sprintf('%s ', __('', 'wcvp_range'));
//
//     $wcv_reg_min_price = $product->get_variation_regular_price( 'min', true );
//     $wcv_min_sale_price    = $product->get_variation_sale_price( 'min', true );
//     $wcv_max_price = $product->get_variation_price( 'max', true );
//     $wcv_min_price = $product->get_variation_price( 'min', true );
//
//     $wcv_price = ( $wcv_min_sale_price == $wcv_reg_min_price ) ?
//         wc_price( $wcv_reg_min_price ) :
//         '<del>' . wc_price( $wcv_reg_min_price ) . '</del>' . '<ins>' . wc_price( $wcv_min_sale_price ) . '</ins>';
//
//     return ( $wcv_min_price == $wcv_max_price ) ?
//         $wcv_price :
//         sprintf('%s%s', $prefix, $wcv_price);
// }

add_filter( 'woocommerce_variable_sale_price_html', 'wc_varb_price_range', 10, 2 );
add_filter( 'woocommerce_variable_price_html', 'wc_varb_price_range', 10, 2 );

/*
** PRODUCT BADGE - CUSTOM FIELD **
*/
function label_general_custom_field() {
    global $woocommerce, $post;
   echo '<div class="option_group">';
   echo '<h2>Labels</h2>';

    woocommerce_wp_checkbox(
    array(
    	'id'            => 'nyhed',
    	'label'         => __('Nyhed', 'woocommerce' ),
    	'description'   => __( '', 'woocommerce' )
    	)
    );
    echo '</div>';

    woocommerce_wp_checkbox(
    array(
      'id'            => 'eksklusiv',
      'label'         => __('Eksklusiv', 'woocommerce' ),
      'description'   => __( '', 'woocommerce' )
      )
    );
    echo '</div>';
}
add_action( 'woocommerce_product_options_advanced', 'label_general_custom_field' );

/* Save custom label fields */
function save_woocommerce_product_custom_fields($post_id){

  $woocommerce_checkbox = isset( $_POST['nyhed'] ) ? 'yes' : 'no';
	update_post_meta( $post_id, 'nyhed', $woocommerce_checkbox );
  $woocommerce_checkbox = isset( $_POST['eksklusiv'] ) ? 'yes' : 'no';
  update_post_meta( $post_id, 'eksklusiv', $woocommerce_checkbox );

}
add_action('woocommerce_process_product_meta', 'save_woocommerce_product_custom_fields');

/* Show labels on product archive */
function display_label_text(){
  global $product;

  echo "<div class='badge-wrapper'>";
  $new = get_post_meta( $product->get_id(), 'nyhed', true );
  if ($new == 'yes') {
    echo '<span class="shop badge nyhed">NYHED</span>';
  }
  $exclusive = get_post_meta( $product->get_id(), 'eksklusiv', true );
  if ($exclusive == 'yes') {
    echo '<span class="shop badge eksklusiv">EKSKLUSIV</span>';
  }
  echo "</div>";
}
add_action( 'woocommerce_after_shop_loop_item', 'display_label_text', 3 );
add_action( 'woocommerce_before_single_product_summary', 'display_label_text', 3 );

//Archive top filters

function archive_top_filters() {
  register_sidebar(
    array(
      'id' => 'top-filter-area',
      'name' => esc_html__( 'Archive Top Filters', 'theme-domain' ),
      'description' => esc_html__( 'Widgets til toppen af produktarkivet', 'theme-domain' ),
      'before_widget' => '<div id="%1$s" class="widget %2$s">',
      'after_widget' => '</div>',
      'before_title' => '<div class="widget-title-wrapper"><h3 class="widget-title">',
      'after_title' => '</h3></div>'
    )
  );
}
add_action( 'widgets_init', 'archive_top_filters' );

//move breadcrumbs

function move_storefront_breadcrumbs() {
  if ( is_shop() || is_product_category() ) {
      remove_action( 'storefront_before_content', 'woocommerce_breadcrumb', 10 );
  }
}
add_action( 'wp', 'move_storefront_breadcrumbs');

//rearrange archive product thumbnail
function archive_product_layout() {
  remove_action( 'woocommerce_shop_loop_item_title', 'woocommerce_template_loop_product_title', 10 );
  add_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_title', 5 );
}
add_action( 'wp', 'archive_product_layout' );
