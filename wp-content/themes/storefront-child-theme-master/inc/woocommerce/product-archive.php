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

  $new = get_post_meta( $product->get_id(), 'nyhed', true );
  if ($new == 'yes') {
    echo '<span class="shop badge nyhed">NYHED</span>';
  }
  $exclusive = get_post_meta( $product->get_id(), 'eksklusiv', true );
  if ($exclusive == 'yes') {
    echo '<span class="shop badge eksklusiv">EKSKLUSIV</span>';
  }

}
add_action( 'woocommerce_before_shop_loop_item_title', 'display_label_text', 3 );
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

//Remove select2 in product filters

add_action( 'wp_enqueue_scripts', 'wsis_dequeue_stylesandscripts_select2', 100 );

function wsis_dequeue_stylesandscripts_select2() {
    if ( class_exists( 'woocommerce' ) ) {
        wp_dequeue_style( 'selectWoo' );
        wp_deregister_style( 'selectWoo' );

        wp_dequeue_script( 'selectWoo');
        wp_deregister_script('selectWoo');
    }
} 
