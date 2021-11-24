<?php

/* Add EAN for simple product */
function woo_add_simple_product_custom_ean_field() {

    global $post;
	
	$product = wc_get_product($post->ID);
	
	if($product == null || $product == false)
		return;
	
	if( !$product->is_type("simple") )
		return;

	// EAN Field
	woocommerce_wp_text_input( array(
		'id'          => '_gtin',
		'label'       => __( 'EAN', 'woocommerce' ),
		'placeholder' => 'EAN / Stregkode',
		'desc_tip'    => 'true',
		'description' => __( 'Indtast EAN/Stregkode.', 'woocommerce' )
	) );
}
add_action( 'woocommerce_product_options_general_product_data', 'woo_add_simple_product_custom_ean_field' );


/* Save EAN for simple product */
function  woo_save_simple_product_custom_ean_field( $post_id ) {
	// Save EAN Field
	$gtin_field = $_POST['_gtin'];
	if ( ! empty( $gtin_field ) ) {
		update_post_meta( $post_id, '_gtin', esc_attr( $gtin_field ) );
	}
}
add_action( 'woocommerce_process_product_meta', 'woo_save_simple_product_custom_ean_field' );



/* EAN for variation product */

/* Add EAN for variation product */
function woo_add_product_variation_custom_ean_field( $loop, $variation_data, $variation ) {

	echo '<div class="options_group form-row form-row-full">';

 	// Ean Field
	woocommerce_wp_text_input(
		array(
			'id'          => 'hwp_var_gtin[' . $variation->ID . ']',
			'label'       => __( 'EAN', 'woocommerce' ),
			'placeholder' => 'EAN / Stregkode',
			'desc_tip'    => true,
			'description' => __( "Indtast EAN", "woocommerce" ),
			'value' => get_post_meta( $variation->ID, 'hwp_var_gtin', true )
		)
 	);

	echo '</div>';

}
add_action( 'woocommerce_product_after_variable_attributes', 'woo_add_product_variation_custom_ean_field', 10, 3 );


/* Save EAN for simple product */
function woo_save_product_variation_custom_ean_field( $post_id ){

 	// EAN Field
 	$woocommerce_text_field = $_POST['hwp_var_gtin'][ $post_id ];
	update_post_meta( $post_id, 'hwp_var_gtin', esc_attr( $woocommerce_text_field ) );

}
add_action( 'woocommerce_save_product_variation', 'woo_save_product_variation_custom_ean_field', 10, 2 );