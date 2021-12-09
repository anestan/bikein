<?php

/* WooCommerce - single product image size */
add_filter( 'woocommerce_get_image_size_single', function( $size ) {
    return array(
        'width'  => 600,
        'height' => 600,
        'crop'   => 0,
    );
} );

/* Product gallery - Remove zoom on hover to prevent image stutter */
function remove_zoom_lightbox_gallery_support() {
   remove_theme_support( 'wc-product-gallery-zoom' );
}
add_action( 'wp', 'remove_zoom_lightbox_gallery_support', 99 );

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

//Edit upsell column count

add_action( 'init', 'bbloomer_remove_storefront_theme_upsells');

function bbloomer_remove_storefront_theme_upsells() {
remove_action( 'woocommerce_after_single_product_summary', 'storefront_upsell_display', 15 );
}

add_action( 'woocommerce_after_single_product_summary', 'bbloomer_woocommerce_output_upsells', 15 );

function bbloomer_woocommerce_output_upsells() {
woocommerce_upsell_display( 4,4 );
}

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


add_action('wp_ajax_ql_woocommerce_ajax_add_to_cart', 'ql_woocommerce_ajax_add_to_cart');
add_action('wp_ajax_nopriv_ql_woocommerce_ajax_add_to_cart', 'ql_woocommerce_ajax_add_to_cart');
function ql_woocommerce_ajax_add_to_cart() {
    $product_id = apply_filters('ql_woocommerce_add_to_cart_product_id', absint($_POST['product_id']));
    $quantity = empty($_POST['quantity']) ? 1 : wc_stock_amount($_POST['quantity']);
    $variation_id = absint($_POST['variation_id']);
    $passed_validation = apply_filters('ql_woocommerce_add_to_cart_validation', true, $product_id, $quantity);
    $product_status = get_post_status($product_id);
    if ($passed_validation && WC()->cart->add_to_cart($product_id, $quantity, $variation_id) && 'publish' === $product_status) {
        do_action('ql_woocommerce_ajax_added_to_cart', $product_id);
            if ('yes' === get_option('ql_woocommerce_cart_redirect_after_add')) {
                wc_add_to_cart_message(array($product_id => $quantity), true);
            }
            WC_AJAX :: get_refreshed_fragments();
            } else {
                $data = array(
                    'error' => true,
                    'product_url' => apply_filters('ql_woocommerce_cart_redirect_after_error', get_permalink($product_id), $product_id));
                echo wp_send_json($data);
            }
            wp_die();
        }
