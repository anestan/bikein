<?php

/* Auto update - Cart  */
function cart_refresh_update_qty() {
   if (is_cart()) {
      ?>
      <script type="text/javascript">
         jQuery('div.woocommerce').on('click', 'input.qty', function(){
            jQuery("[name='update_cart']").trigger("click");
         });
      </script>
      <?php
   }
}
add_action( 'wp_footer', 'cart_refresh_update_qty' );

/* Remove item from sidebar cart */
function sb_remove_item(){
    foreach ($woocommerce->cart->get_cart() as $cart_item_key => $cart_item) {

          $REMOVE = $woocommerce->cart->remove_cart_item($cart_item_key);

      }
  }
  add_action('wp_sb_remove_item', 'sb_remove_item');



/* Reorder Cross Sell to woocommerce_after_cart_contents */
remove_action( 'woocommerce_cart_collaterals', 'woocommerce_cross_sell_display' );
add_action( 'woocommerce_after_cart_contents', 'woocommerce_cross_sell_display' );

/* Display Only 2 Cross Sells instead of default 3 */
add_filter( 'woocommerce_cross_sells_total', 'change_cross_sells_product_number' );

function change_cross_sells_product_number( $columns ) {
   return 2;
}



/* Add to cart - Ajax */
function custom_add_to_cart_handler() {
    if( isset($_POST['product_id']) && isset($_POST['form_data']) ) {
        $product_id = $_POST['product_id'];

        $variation = $cart_item_data = $custom_data = array(); // Initializing
        $variation_id = 0; // Initializing

        foreach( $_POST['form_data'] as $values ) {
            if ( strpos( $values['name'], 'attributes_' ) !== false ) {
                $variation[$values['name']] = $values['value'];
            } elseif ( $values['name'] === 'quantity' ) {
                $quantity = $values['value'];
            } elseif ( $values['name'] === 'variation_id' ) {
                $variation_id = $values['value'];
            } elseif ( $values['name'] !== 'add_to_cart' ) {
                $custom_data[$values['name']] = esc_attr($values['value']);
            }
        }

        $product = wc_get_product( $variation_id ? $variation_id : $product_id );

        // Allow product custom fields to be added as custom cart item data from $custom_data additional array variable
        $cart_item_data = (array) apply_filters( 'woocommerce_add_cart_item_data', $cart_item_data, $product_id, $variation_id, $quantity, $custom_data );

        // Add to cart
        $cart_item_key = WC()->cart->add_to_cart( $product_id, $quantity, $variation_id, $variation, $cart_item_data );

        $items = WC()->cart->get_cart();
        global $woocommerce;
        $item_count = $woocommerce->cart->cart_contents_count;

        if ( $cart_item_key ) {
          echo '<span class="cart_total_items">' . $item_count . '</span>';
          $cart_updated = require_once( get_stylesheet_directory() . '/inc/woocommerce/cart-preview-updated.php');
        }
        else {
          $data = array(
            'error'       => true,
            'product_url' => apply_filters( 'woocommerce_cart_redirect_after_error', get_permalink( $product_id ), $product_id )
        );

          wp_send_json( $data );
        }

        wp_die();
    }
}

add_action( 'wc_ajax_custom_add_to_cart', 'custom_add_to_cart_handler' );
add_action( 'wc_ajax_nopriv_custom_add_to_cart', 'custom_add_to_cart_handler' );
