<?php

/**
 * Storefront automatically loads the core CSS even if using a child theme as it is more efficient
 * than @importing it in the child theme style.css file.
 *
 * Uncomment the line below if you'd like to disable the Storefront Core CSS.
 *
 * If you don't plan to dequeue the Storefront Core CSS you can remove the subsequent line and as well
 * as the sf_child_theme_dequeue_style() function declaration.
 */
//add_action( 'wp_enqueue_scripts', 'sf_child_theme_dequeue_style', 999 );

/**
 * Dequeue the Storefront Parent theme core CSS
 */
function sf_child_theme_dequeue_style() {
    wp_dequeue_style( 'storefront-style' );
    wp_dequeue_style( 'storefront-woocommerce-style' );
}

/**
 * Note: DO NOT! alter or remove the code above this text and only add your custom PHP functions below this text.
 */

/* function wp4wp_scripts() {
    wp_enqueue_script('main_js', get_stylesheet_directory_uri() . '/dist/s.js', array(), '1.0', true);
  }
  add_action('wp_enqueue_scripts', 'wp4wp_scripts'); */


  function mytheme_enqueue_style() {
    wp_enqueue_style( 'mytheme-style', get_stylesheet_directory_uri() . '/dist/style.css' );
    wp_enqueue_script( 'myscript', get_stylesheet_directory_uri() . '/dist/app.js'); //mobile menu js
}
add_action( 'wp_enqueue_scripts', 'mytheme_enqueue_style' );




/* WP SHOP */

/* Logo */
add_theme_support( 'custom-logo' );


/* Remove footer credit */
add_action( 'init', 'custom_remove_footer_credit', 10 );

function custom_remove_footer_credit () {
    remove_action( 'storefront_footer', 'storefront_credit', 20 );
    add_action( 'storefront_footer', 'custom_storefront_credit', 20 );
}



/* Add scroll button to short description on product page */
function scroll_btn(){
  echo '<i class="fas fa-arrow-down scroll_btn"></i>';
}
add_action( 'woocommerce_single_product_summary', 'scroll_btn', 20 );

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


/* WooCommerce - single product image size */
add_filter( 'woocommerce_get_image_size_single', function( $size ) {
    return array(
        'width'  => 800,
        'height' => '',
        'crop'   => 0,
    );
} );

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


/* ********* Checkout ********** */

/* Auto update - Cart  */
add_action( 'wp_footer', 'cart_refresh_update_qty' );

function cart_refresh_update_qty() {
    if (is_cart()) {
        ?>
        <script type="text/javascript">
        jQuery('div.woocommerce').on('change', 'input.qty', function(){
            setTimeout(function() {
                jQuery('[name="update_cart"]').trigger('click');
            }, 1 );
        });
        </script>
        <?php
    }
}


/**
 * Simple checkout field addition to CVR.
 *
 */
function woocommerce_add_checkout_fields( $fields ) {
    if (function_exists('is_checkout') && is_checkout()) {

        wp_enqueue_script( 'cvrscript', get_stylesheet_directory_uri() . '/assets/js/cvrautofill.js');

        $fields['billing_vat'] = array(
            'label'        => __( 'CVR nr.' ),
            'type'        => 'text',
            'class'        => array( 'form-row-wide' ),
            'priority'     => 25,
            'required'     => false,
            'custom_attributes' => array('vat-button' => 'Hent oplysninger fra CVR register'),
            'input_class' => array('append-button'),
        );
        return $fields;
    }
}
add_filter( 'woocommerce_billing_fields', 'woocommerce_add_checkout_fields' );




/* PRODUCT CUSTOM FIELD - BADGE */
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

/*
**
Load more
**
*/
function misha_my_load_more_scripts() {

	global $wp_query;

	// In most cases it is already included on the page and this line can be removed
	wp_enqueue_script('jquery');

	// now the most interesting part
	// we have to pass parameters to myloadmore.js script but we can get the parameters values only in PHP
	// you can define variables directly in your HTML but I decided that the most proper way is wp_localize_script()
	wp_localize_script( 'my_loadmore', 'misha_loadmore_params', array(
		'ajaxurl' => site_url() . '/wp-admin/admin-ajax.php', // WordPress AJAX
		'posts' => json_encode( $wp_query->query_vars ), // everything about your loop is here
		'current_page' => get_query_var( 'paged' ) ? get_query_var('paged') : 1,
		'max_page' => $wp_query->max_num_pages
	) );

 	wp_enqueue_script( 'my_loadmore' );
}

add_action( 'wp_enqueue_scripts', 'misha_my_load_more_scripts' );



function misha_loadmore_ajax_handler(){

	// prepare our arguments for the query
	$args = json_decode( stripslashes( $_POST['query'] ), true );
	$args['paged'] = $_POST['page'] + 1; // we need next page to be loaded
	$args['post_status'] = 'publish';
	$args['post_type'] = 'product';

	// it is always better to use WP_Query but not here
	query_posts( $args );


	echo "<ul class='products columns-4'>";
	if( have_posts() ) :

		// run the loop
		while( have_posts() ): the_post();

			// look into your theme code how the posts are inserted, but you can use your own HTML of course
			// do you remember? - my example is adapted for Twenty Seventeen theme

			wc_get_template_part( 'content', 'product' );

			// get_template_part( 'template-parts/post/content', get_post_format() );
			// for the test purposes comment the line above and uncomment the below one
			// the_title();


		endwhile;

	endif;
	echo "<ul>";
	die; // here we exit the script and even no wp_reset_query() required!
}

add_action('wp_ajax_loadmore', 'misha_loadmore_ajax_handler'); // wp_ajax_{action}
add_action('wp_ajax_nopriv_loadmore', 'misha_loadmore_ajax_handler'); // wp_ajax_nopriv_{action}


/* Remove item from sidebar cart */
function sb_remove_item(){
  foreach ($woocommerce->cart->get_cart() as $cart_item_key => $cart_item) {

        $REMOVE = $woocommerce->cart->remove_cart_item($cart_item_key);

    }
}
add_action('wp_sb_remove_item', 'sb_remove_item');


// Remove sidebar on all Woo Pages
function iconic_remove_sidebar( $is_active_sidebar, $index ) {
    if( $index !== "sidebar-1" ) {
         return $is_active_sidebar;
     }

     if( ! is_product() ) {
        return $is_active_sidebar;
    }

     return false;
 }

add_filter( 'is_active_sidebar', 'iconic_remove_sidebar', 10, 2 );

/* Admin - WooCommerce styling */
function admin_custom_css() {
  echo '<style>
    .stock-heading {
      font-size: 1.3em;
      font-weight: 600;
      color: #23282d;
    }
  </style>';
}

add_action('admin_head', 'admin_custom_css');

/* Remove Storefront Mobile Menu Links */
add_filter( 'storefront_handheld_footer_bar_links', 'jk_remove_handheld_footer_links' );
function jk_remove_handheld_footer_links( $links ) {
	unset( $links['my-account'] );

	return $links;
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

//Move Variations price above variations to have the same template even if variations prices are the same
// remove_action( 'woocommerce_single_variation', 'woocommerce_single_variation', 10 );
// add_action( 'woocommerce_before_variations_form', 'woocommerce_single_variation', 10 );
//
// add_filter( 'woocommerce_variable_sale_price_html',
// 'lw_variable_product_price', 10, 2 );
// add_filter( 'woocommerce_variable_price_html',
// 'lw_variable_product_price', 10, 2 );
//
// function lw_variable_product_price( $v_price, $v_product ) {
// if(is_product()) {
// return '';
// }
// return $v_price;
// }

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


/* Delivery based on categories */

/**
 * Filter shipping methods in the checkout
 */
add_filter( 'woocommerce_package_rates', 'wooelements_filter_shipping_methods', 10, 2 );
function wooelements_filter_shipping_methods( $rates, $package ) {
	// Find GLS PakkeShop shipping method
	$economy_shipping_method_key = FALSE;
	foreach ( $rates as $rate_key => $rate ) {
		if ( is_object( $rate ) && method_exists( $rate, 'get_label' ) && $rate->get_label() === "GLS PakkeShop" ) {
			$economy_shipping_method_key = $rate_key;
        }
	}

	// Go through all products and check their category
	if ( $economy_shipping_method_key !== FALSE ) {
		$bike_appliances_found = FALSE;
		foreach ( $package['contents'] as $key => $item ) {
			$categories = get_the_terms( $item['product_id'], 'product_cat' );

			if ( $categories && ! is_wp_error( $categories ) && is_array( $categories ) ) {
				foreach ( $categories as $category ) {
					if ( "Cykler" === $category->name || "Elcykler" == $category->name || "Specialcykler" == $category->name  ) {
						$bike_appliances_found = TRUE;
					}
				}
			}
		}

		// Bike appliances has been found, disable GLS shipping
		if ( $bike_appliances_found === TRUE ) {
            unset( $rates[$economy_shipping_method_key] );
		}
	}

	return $rates;
}


/*
* Add extra description for WooCommerce
*
*/

// 1. Display field on "Add new product category" admin page

add_action( 'product_cat_add_form_fields', 'bbloomer_wp_editor_add', 10, 2 );

function bbloomer_wp_editor_add() {
    ?>
    <div class="form-field">
        <label for="seconddesc"><?php echo __( 'Lang beskrivelse', 'woocommerce' ); ?></label>

      <?php
      $settings = array(
         'textarea_name' => 'seconddesc',
         'quicktags' => array( 'buttons' => 'em,strong,link' ),
         'tinymce' => array(
            'theme_advanced_buttons1' => 'bold,italic,strikethrough,separator,bullist,numlist,separator,blockquote,separator,justifyleft,justifycenter,justifyright,separator,link,unlink,separator,undo,redo,separator',
            'theme_advanced_buttons2' => '',
         ),
         'editor_css' => '<style>#wp-excerpt-editor-container .wp-editor-area{height:175px; width:100%;}</style>',
      );

      wp_editor( '', 'seconddesc', $settings );
      ?>

        <p class="description"><?php echo __( 'Denne beskrivelse vises under produkterne i produktkategorien', 'woocommerce' ); ?></p>
    </div>
    <?php
}

// ---------------
// 2. Display field on "Edit product category" admin page

add_action( 'product_cat_edit_form_fields', 'bbloomer_wp_editor_edit', 10, 2 );

function bbloomer_wp_editor_edit( $term ) {
    $second_desc = htmlspecialchars_decode( get_woocommerce_term_meta( $term->term_id, 'seconddesc', true ) );
    ?>
    <tr class="form-field">
        <th scope="row" valign="top"><label for="second-desc"><?php echo __( 'Lang beskrivelse', 'woocommerce' ); ?></label></th>
        <td>
            <?php

         $settings = array(
            'textarea_name' => 'seconddesc',
            'quicktags' => array( 'buttons' => 'em,strong,link' ),
            'tinymce' => array(
               'theme_advanced_buttons1' => 'bold,italic,strikethrough,separator,bullist,numlist,separator,blockquote,separator,justifyleft,justifycenter,justifyright,separator,link,unlink,separator,undo,redo,separator',
               'theme_advanced_buttons2' => '',
            ),
            'editor_css' => '<style>#wp-excerpt-editor-container .wp-editor-area{height:175px; width:100%;}</style>',
         );

         wp_editor( $second_desc, 'seconddesc', $settings );
         ?>

            <p class="description"><?php echo __( 'Denne beskrivelse vises under produkterne i produktkategorien', 'woocommerce' ); ?></p>
        </td>
    </tr>
    <?php
}

// ---------------
// 3. Save field @ admin page

add_action( 'edit_term', 'bbloomer_save_wp_editor', 10, 3 );
add_action( 'created_term', 'bbloomer_save_wp_editor', 10, 3 );

function bbloomer_save_wp_editor( $term_id, $tt_id = '', $taxonomy = '' ) {
   if ( isset( $_POST['seconddesc'] ) && 'product_cat' === $taxonomy ) {
      update_woocommerce_term_meta( $term_id, 'seconddesc', esc_attr( $_POST['seconddesc'] ) );
   }
}
