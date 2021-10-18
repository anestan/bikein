<div id="cart-preview">
	<?php 
    $items = WC()->cart->get_cart();
	  global $woocommerce;
  ?>

	<div class="cart-dropdown">
		<div class="cart-dropdown-inner">

        <ul class="cart-preview-products">
				<?php foreach($items as $item => $values) { 
					$_product = $values['data']->post; ?>

          <li class="cart-preview-product">
					
            <?php
              $_product =  wc_get_product( $values['data']->get_id() );
              $link = $_product->get_permalink();
              $price = get_post_meta($values['product_id'] , '_price', true);
              $currency = get_woocommerce_currency_symbol();
              $get_product_detail = wc_get_product( $values['product_id'] );
              $cart_total = $woocommerce->cart->get_cart_total();
              $cart_url = $woocommerce->cart->get_cart_url();
            ?>
					
            <a href="<?php $link ?>">

              <!-- Image -->
              <div class="preview-img">
                <?php echo $get_product_detail->get_image(); ?>
              </div>
			
              <!-- Title + quantity -->
              <div class="title-quantity">
                <span class="preview-title">
                  <?php echo $_product->get_title(); ?>
                </span>

                <span class="preview-quantity">Antal: 
                  <?php echo $values['quantity']; ?>
                </span>
              </div>

              <!-- Price -->
              <span class="preview-price">
                  <?php echo $price . ' ' . $currency; ?>
              </span>					

            </a>

          </li>

				<?php } ?>
        
        </ul>
        <!-- End list -->

        <!-- Cart total -->
        <?php if ( $items ) { ?>
        <div class="preview-btm">
          <div>
            <span>Pris i alt:</span>
            <?php echo $cart_total; ?>
          </div>
          <a href="<?php echo $cart_url ?>" class="preview-cart-btn">Indkøbskurv</a>
        </div>

        <?php } 
        else {
          // Cart empty
          echo "<div class='cart-preview-empty'>Din indkøbskurv er tom</div>";
        }
        ?>
		
		</div>
	</div>

</div>