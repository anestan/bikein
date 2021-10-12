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

//AJAX add to cart PHP
add_action('wp_ajax_woocommerce_ajax_add_to_cart', 'woocommerce_ajax_add_to_cart');
add_action('wp_ajax_nopriv_woocommerce_ajax_add_to_cart', 'woocommerce_ajax_add_to_cart');

function woocommerce_ajax_add_to_cart() {

    $product_id = apply_filters('woocommerce_add_to_cart_product_id', absint($_POST['product_id']));
    $quantity = empty($_POST['quantity']) ? 1 : wc_stock_amount($_POST['quantity']);
    $variation_id = absint($_POST['variation_id']);
    $passed_validation = apply_filters('woocommerce_add_to_cart_validation', true, $product_id, $quantity);
    $product_status = get_post_status($product_id);

    if ($passed_validation && WC()->cart->add_to_cart($product_id, $quantity, $variation_id) && 'publish' === $product_status) {

        do_action('woocommerce_ajax_added_to_cart', $product_id);

        if ('yes' === get_option('woocommerce_cart_redirect_after_add')) {
            wc_add_to_cart_message(array($product_id => $quantity), true);
        }

        WC_AJAX :: get_refreshed_fragments();
    } else {

        $data = array(
            'error' => true,
            'product_url' => apply_filters('woocommerce_cart_redirect_after_error', get_permalink($product_id), $product_id));

        echo wp_send_json($data);
    }

    wp_die();
}
