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

 
/**
 * 
* Theme Customizer additions.
*/
require_once( get_stylesheet_directory() . '/inc/global.php');
require_once( get_stylesheet_directory() . '/inc/footer.php');
require_once( get_stylesheet_directory() . '/inc/topbar.php');
require_once( get_stylesheet_directory() . '/inc/woocommerce/cart.php');
require_once( get_stylesheet_directory() . '/inc/woocommerce/checkout.php');
require_once( get_stylesheet_directory() . '/inc/woocommerce/delivery.php');
require_once( get_stylesheet_directory() . '/inc/woocommerce/extra-description.php');
require_once( get_stylesheet_directory() . '/inc/woocommerce/loadmore.php');
require_once( get_stylesheet_directory() . '/inc/woocommerce/product-archive.php');
require_once( get_stylesheet_directory() . '/inc/woocommerce/product.php');
require_once( get_stylesheet_directory() . '/inc/woocommerce/ean-field.php');


// Add custom style and javascript
function mytheme_enqueue_style() {
    wp_enqueue_script( 'myscript', get_stylesheet_directory_uri() . '/dist/app.js');

    wp_enqueue_style( 'child-style', get_stylesheet_directory_uri() . '/dist/style.css' );
    //wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css' );
}
add_action( 'wp_enqueue_scripts', 'mytheme_enqueue_style', 999 );


