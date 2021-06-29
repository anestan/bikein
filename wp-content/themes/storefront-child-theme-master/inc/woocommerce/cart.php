<?php

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


/* Remove item from sidebar cart */
function sb_remove_item(){
    foreach ($woocommerce->cart->get_cart() as $cart_item_key => $cart_item) {
  
          $REMOVE = $woocommerce->cart->remove_cart_item($cart_item_key);
  
      }
  }
  add_action('wp_sb_remove_item', 'sb_remove_item');

