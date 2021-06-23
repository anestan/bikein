<?php

/* WooCommerce - single product image size */
add_filter( 'woocommerce_get_image_size_single', function( $size ) {
    return array(
        'width'  => 600,
        'height' => 600,
        'crop'   => 0,
    );
} );

//Rearrange price and short desc
add_action('woocommerce_single_product_summary', 'move_single_product_price', 1);
function move_single_product_price() {
    remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_price', 10);
    add_action('woocommerce_single_product_summary', 'woocommerce_template_single_price', 29);
}

/* Change number of related products */
function woo_related_products_limit() {
    global $product;

      $args['posts_per_page'] = 6;
      return $args;
  }
  add_filter( 'woocommerce_output_related_products_args', 'jk_related_products_args', 20 );
    function jk_related_products_args( $args ) {
      $args['posts_per_page'] = 4; // 4 related products
      $args['columns'] = 4; // arranged in 4 columns
      return $args;
  }



/* Replace variable price range with single variation price */
//Remove Price Range
function wc_varb_price_range( $wcv_price, $product ) {
    $prefix = sprintf('%s ', __('', 'wcvp_range'));

    $wcv_reg_min_price = $product->get_variation_regular_price( 'min', true );
    $wcv_min_sale_price    = $product->get_variation_sale_price( 'min', true );
    $wcv_max_price = $product->get_variation_price( 'max', true );
    $wcv_min_price = $product->get_variation_price( 'min', true );

    $wcv_price = ( $wcv_min_sale_price == $wcv_reg_min_price ) ?
        wc_price( $wcv_reg_min_price ) :
        '<del>' . wc_price( $wcv_reg_min_price ) . '</del>' . '<ins>' . wc_price( $wcv_min_sale_price ) . '</ins>';

    return ( $wcv_min_price == $wcv_max_price ) ?
        $wcv_price :
        sprintf('Fra: %s%s', $prefix, $wcv_price);
}

add_filter( 'woocommerce_variable_sale_price_html', 'wc_varb_price_range', 10, 2 );
add_filter( 'woocommerce_variable_price_html', 'wc_varb_price_range', 10, 2 );


// Add product custom shipping text fields
function add_custom_shipping_settings_fields() {

    echo '<div class="options_group">';
    echo '<h2>Leveringsinfo</h2>';

    woocommerce_wp_text_input( array(
        'id'          => '_shipping_heading',
        'label'       => __( 'Overskrift', 'woocommerce' ),
    ) );

    woocommerce_wp_text_input( array(
        'id'          => '_shipping_text',
        'label'       => __( 'Beskrivelse', 'woocommerce' ),
    ) );

    echo '</div>';
}
add_action( 'woocommerce_product_options_shipping', 'add_custom_shipping_settings_fields' );

// Save custom shipping text fields
function save_custom_shipping_settings_fields_values($post_id){

    $shipping_heading = $_POST['_shipping_heading'];
	update_post_meta( $post_id, '_shipping_heading', esc_attr( $shipping_heading ) );

    $shipping_text = $_POST['_shipping_text'];
	update_post_meta( $post_id, '_shipping_text', esc_attr( $shipping_text ) );

 }
 add_action( 'woocommerce_process_product_meta', 'save_custom_shipping_settings_fields_values');


// Show shipping text field on product page
add_action( 'woocommerce_after_add_to_cart_button', 'display_custom_fields', 15 );
function display_custom_fields() {
    global $product;

    $fields_values = array(); // Initializing

    if( $text_field_1 = $product->get_meta('_shipping_heading') )
        $fields_values[] = $text_field_1; // Set the value in the array

    if( $text_field_2 = $product->get_meta('_shipping_text') )
        $fields_values[] = $text_field_2; // Set the value in the array

    // If the array of values is not empty
    if( sizeof( $fields_values ) > 0 ){

        echo '<div class="shipping-info-wrapper">';

        // Loop through each existing custom field value
        foreach( $fields_values as $key => $value ) {
            echo '<p class="info-text">' . $value . '</p>';
        }

        echo '</div>';

    }
}


//Variant flavor text field

/* add_action( 'woocommerce_product_options_advanced', 'add_custom_general_fields' );

function add_custom_general_fields() {

    echo '<div class="options_group">';
    echo '<h2>Varia</h2>';

    woocommerce_wp_text_input( array(
        'id'          => '_variant_text_field_1',
        'label'       => __( 'Overskrift', 'woocommerce' ),
        'class'    => array('show_if_variable'),
    ) );

    woocommerce_wp_textarea_input( array(
        'id'          => '_variant_text_field_2',
        'label'       => __( 'Beskrivelse', 'woocommerce' ),
        'class'    => array('show_if_variable'),
    ) );

    echo '</div>';
}

// Save custom shipping text fields
add_action( 'woocommerce_process_product_meta', 'save_custom_general_fields_values', 20, 1 );
function save_custom_general_fields_values($post_id){
    if ( isset($_POST['_variant_text_field_1']) )
        update_post_meta( $post_id, '_variant_text_field_1', sanitize_text_field($_POST['_variant_text_field_1']) );

    if ( isset($_POST['_variant_text_field_2']) )
        update_post_meta( $post_id, '_variant_text_field_2', sanitize_text_field($_POST['_variant_text_field_2']) );

 }


// Show shipping text field on product page
add_action( 'woocommerce_before_add_to_cart_form', 'display_variant_fields', 15 );
function display_variant_fields() {
    global $product;

    $fields_values = array(); // Initializing

    if( $variant_text_field_1 = $product->get_meta('_variant_text_field_1') )
        $fields_values[] = $variant_text_field_1; // Set the value in the array

    if( $variant_text_field_2 = $product->get_meta('_variant_text_field_2') )
        $fields_values[] = $variant_text_field_2; // Set the value in the array

    // If the array of values is not empty
    if( sizeof( $fields_values ) > 0 ){

        echo '<div class="variant-info-wrapper">';

        // Loop through each existing custom field value
        foreach( $fields_values as $key => $value ) {
            echo '<p class="info-text">' . $value . '</p>';
        }

        echo '</div>';

    }
}
 */
