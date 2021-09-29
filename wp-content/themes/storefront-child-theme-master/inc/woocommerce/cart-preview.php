<div id="cart-preview">

    <?php
      global $woocommerce;
      $items = $woocommerce->cart->get_cart();
      echo "<ul class='cart-preview-products'>";
      foreach($items as $item => $values) {
        echo "<li class='cart-preview-product'>";

          $_product =  wc_get_product( $values['data']->get_id() );
          $link = $_product->get_permalink();
          $price = get_post_meta($values['product_id'] , '_price', true);
          $getProductDetail = wc_get_product( $values['product_id'] );
          $cartTotal = $woocommerce->cart->get_cart_total();

          echo '<a href="'. $link .'">';
            echo "<div class='preview-img'>".$getProductDetail->get_image()."</div>";
            echo "<div class='title-quantity'><span class='preview-title'>".$_product->get_title()."</span>";
            echo "<span class='preview-quantity'>Antal: ".$values['quantity']."</span></div>";
            echo "<span class='preview-price'>".$price . ' ' . get_woocommerce_currency_symbol()."</span>";
          echo "</a>";
        echo "</li>";
      }
      echo "</ul>";

      echo "<div class='preview-btm'";
        echo "<div cart-total-wrapper>";
          echo "<span>Pris i alt:</span>";
          echo $cartTotal;
        echo "</div>";
        echo "<a href='".wc_get_cart_url()."' class='preview-cart-btn'>Indkøbskurv</a>";
      echo "</div>";
