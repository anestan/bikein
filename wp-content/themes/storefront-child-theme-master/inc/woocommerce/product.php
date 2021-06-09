<?php

/* Add scroll button to short description on product page */
function scroll_btn(){
  echo '<i class="fas fa-arrow-down scroll_btn"></i>';
}
add_action( 'woocommerce_single_product_summary', 'scroll_btn', 20 );


/* WooCommerce - single product image size */
add_filter( 'woocommerce_get_image_size_single', function( $size ) {
    return array(
        'width'  => 800,
        'height' => '',
        'crop'   => 0,
    );
} );

//Rearrange price and short desc
add_action('woocommerce_single_product_summary', 'move_single_product_price', 1);
function move_single_product_price() {
    remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_price', 10);
    add_action('woocommerce_single_product_summary', 'woocommerce_template_single_price', 29);
}

//Remove product meta
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40 );

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



/* Quantity buttons on product page */
add_action( 'wp_footer' , 'custom_quantity_fields_script' );
function custom_quantity_fields_script(){
    ?>
    <script type='text/javascript'>
    jQuery( function( $ ) {
        if ( ! String.prototype.getDecimals ) {
            String.prototype.getDecimals = function() {
                var num = this,
                    match = ('' + num).match(/(?:\.(\d+))?(?:[eE]([+-]?\d+))?$/);
                if ( ! match ) {
                    return 0;
                }
                return Math.max( 0, ( match[1] ? match[1].length : 0 ) - ( match[2] ? +match[2] : 0 ) );
            }
        }
        // Quantity "plus" and "minus" buttons
        $( document.body ).on( 'click', '.plus, .minus', function() {
            var $qty        = $( this ).closest( '.quantity' ).find( '.qty'),
                currentVal  = parseFloat( $qty.val() ),
                max         = parseFloat( $qty.attr( 'max' ) ),
                min         = parseFloat( $qty.attr( 'min' ) ),
                step        = $qty.attr( 'step' );

            // Format values
            if ( ! currentVal || currentVal === '' || currentVal === 'NaN' ) currentVal = 0;
            if ( max === '' || max === 'NaN' ) max = '';
            if ( min === '' || min === 'NaN' ) min = 0;
            if ( step === 'any' || step === '' || step === undefined || parseFloat( step ) === 'NaN' ) step = 1;

            // Change the value
            if ( $( this ).is( '.plus' ) ) {
                if ( max && ( currentVal >= max ) ) {
                    $qty.val( max );
                } else {
                    $qty.val( ( currentVal + parseFloat( step )).toFixed( step.getDecimals() ) );
                }
            } else {
                if ( min && ( currentVal <= min ) ) {
                    $qty.val( min );
                } else if ( currentVal > 0 ) {
                    $qty.val( ( currentVal - parseFloat( step )).toFixed( step.getDecimals() ) );
                }
            }

            // Trigger change event
            $qty.trigger( 'change' );
        });
    });
    </script>
    <?php
}
