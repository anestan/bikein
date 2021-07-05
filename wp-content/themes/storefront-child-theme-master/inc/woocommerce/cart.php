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