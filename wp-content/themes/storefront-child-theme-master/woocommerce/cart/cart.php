<?php
/**
 * Cart Page
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/cart/cart.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 4.4.0
 */

defined( 'ABSPATH' ) || exit;

do_action( 'woocommerce_before_cart' ); ?>

<script type="text/javascript">
dataLayer.push({
   "event":"EEaddToCart",
   "ecommerce": {
     "currencyCode": "<?php echo get_woocommerce_currency_symbol(); ?>",
     "add": {
      "actionField": {
        list: "Shopping cart"
      },
      "products": [
                  <?php
                    global $woocommerce;
                    $items = $woocommerce->cart->get_cart();
                    foreach($items as $item => $values):
                      $_product =  wc_get_product( $values['data']->get_id());
                  ?>
                      {
                        "name": "<?php echo $product_title = $_product->get_title(); ?>",
                        "id": "<?php echo $product_id = $_product->get_id(); ?>",
                        "price": "<?php echo $price = get_post_meta($values['product_id'] , '_price', true); ?>",
                        "quantity": <?php echo $cart_quantity = $values['quantity'];?>,
                        "category":"<?php $terms = get_the_terms( $product_id, 'product_cat' );
        foreach ($terms as $term) {
          $product_cat = $term->name;
          }
          echo $product_cat ; ?>"
                      },
                  <?php endforeach; ?>
                ]
            }
          }
});
</script>


<form class="woocommerce-cart-form" action="<?php echo esc_url( wc_get_cart_url() ); ?>" method="post">
	<?php do_action( 'woocommerce_before_cart_table' ); ?>

	<div id="cart-overview">

		<div class="col-cart-one">

			<!-- Cart Header -->
			<div class="cart-header">
				<!-- Product count -->
				<div class="cart-product-count">
					<?php
						global $woocommerce;
						$cart_count = $woocommerce->cart->cart_contents_count;
						if ( $cart_count <= 1 ) {
							echo $woocommerce->cart->cart_contents_count . " vare i kurven";
						}
						else {
						  echo $woocommerce->cart->cart_contents_count . " varer i kurven";
						}
					?>
				</div>

				<h3><?php esc_html_e( 'Cart', 'woocommerce' ); ?></h3>

			</div>
			<!-- Cart Header End -->

			<?php do_action( 'woocommerce_before_cart_contents' ); ?>

			<!-- Cart shop table -->

      <div class="woocommerce-cart-form__cart-item <?php echo esc_attr( apply_filters( 'woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key ) ); ?>">
			<?php
			foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
				$_product   = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
				$product_id = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );

				if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_cart_item_visible', true, $cart_item, $cart_item_key ) ) {
					$product_permalink = apply_filters( 'woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink( $cart_item ) : '', $cart_item, $cart_item_key );
				?>

				<div class="cart-shop-table">

					<!-- Product thumbnail -->
					<div class="product-thumbnail">
							<?php
							$thumbnail = apply_filters( 'woocommerce_cart_item_thumbnail', $_product->get_image(), $cart_item, $cart_item_key );

							if ( ! $product_permalink ) {
								echo $thumbnail; // PHPCS: XSS ok.
							} else {
								printf( '<a href="%s">%s</a>', esc_url( $product_permalink ), $thumbnail ); // PHPCS: XSS ok.
							}
							?>
					</div>


					<!-- Product quantity selection -->
					<div class="product-quantity-selection" data-title="<?php esc_attr_e( 'Quantity', 'woocommerce' ); ?>">
						<?php
							if ( $_product->is_sold_individually() ) {
								$product_quantity = sprintf( '1 <input type="hidden" name="cart[%s][qty]" value="1" />', $cart_item_key );
							} else {
								$product_quantity = woocommerce_quantity_input(
									array(
										'input_name'   => "cart[{$cart_item_key}][qty]",
										'input_value'  => $cart_item['quantity'],
										'max_value'    => $_product->get_max_purchase_quantity(),
										'min_value'    => '0',
										'product_name' => $_product->get_name(),
									),
									$_product,
									false
								);
							}

							echo apply_filters( 'woocommerce_cart_item_quantity', $product_quantity, $cart_item_key, $cart_item ); // PHPCS: XSS ok.
						?>
					</div>


					<!-- Product info -->
					<div class="product-info">

						<!-- Product name -->
						<div class="product-name" data-title="<?php esc_attr_e( 'Product', 'woocommerce' ); ?>">
							<?php
							if ( ! $product_permalink ) {
								echo wp_kses_post( apply_filters( 'woocommerce_cart_item_name', esc_html( $_product->get_name() ), $cart_item, $cart_item_key ) . '&nbsp;' );
							} else {
								echo wp_kses_post( apply_filters( 'woocommerce_cart_item_name', sprintf( '<a href="%s">%s</a>', esc_url( $product_permalink ), esc_html( $_product->get_name() ) ), $cart_item, $cart_item_key ) );
							}

							do_action( 'woocommerce_after_cart_item_name', $cart_item, $cart_item_key );

							// Meta data.
							echo wc_get_formatted_cart_item_data( $cart_item ); // PHPCS: XSS ok.

							// Backorder notification.
							if ( $_product->backorders_require_notification() && $_product->is_on_backorder( $cart_item['quantity'] ) ) {
								echo wp_kses_post( apply_filters( 'woocommerce_cart_item_backorder_notification', '<p class="backorder_notification">' . esc_html__( 'Available on backorder', 'woocommerce' ) . '</p>', $product_id ) );
							}
							?>
						</div>

						<!-- Product price -->
						<div class="product-price" data-title="<?php esc_attr_e( 'Price', 'woocommerce' ); ?>">
								<?php
									echo apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_price( $_product ), $cart_item, $cart_item_key ); // PHPCS: XSS ok.
								?>
						</div>

					</div>


					<!-- Product quantity -->
					<div class="product-quantity" data-title="<?php // esc_attr_e( 'Quantity', 'woocommerce' ); ?>">
						<?php
							echo "<div>Antal</div>";
							echo "<div>" . $cart_item['quantity'] . "</div>";
						?>
					</div>

					<!-- Remove product from cart -->
					<div class="product-remove">
						<?php
							echo apply_filters( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
							'woocommerce_cart_item_remove_link',
								sprintf(
								'<a href="%s" class="remove" aria-label="%s" data-product_id="%s" data-product_sku="%s">&times;</a>',
									esc_url( wc_get_cart_remove_url( $cart_item_key ) ),
									esc_html__( 'Remove this item', 'woocommerce' ),
									esc_attr( $product_id ),
									esc_attr( $_product->get_sku() )
								),
							$cart_item_key
							);
						?>
					</div>
        </div>

					<?php
					}
				}
				?>
      </div>
			<!-- Cart shop table end -->

			<?php do_action( 'woocommerce_cart_contents' ); ?>


			<!-- Coupon -->
			<div class="coupons actions">

				<?php if ( wc_coupons_enabled() ) { ?>
					<div class="coupon">
						<input type="text" name="coupon_code" class="input-text" id="coupon_code" value="" placeholder="<?php esc_attr_e( 'Coupon code', 'woocommerce' ); ?>" /> <button type="submit" class="button" name="apply_coupon" value="<?php esc_attr_e( 'Apply coupon', 'woocommerce' ); ?>"><?php esc_attr_e( 'Apply coupon', 'woocommerce' ); ?></button>
						<?php do_action( 'woocommerce_cart_coupon' ); ?>
					</div>
				<?php } ?>

				<button type="submit" id="update_cart" class="button" name="update_cart" value="<?php esc_attr_e( 'Update cart', 'woocommerce' ); ?>"><?php esc_html_e( 'Update cart', 'woocommerce' ); ?></button>

				<?php do_action( 'woocommerce_cart_actions' ); ?>

				<?php wp_nonce_field( 'woocommerce-cart', 'woocommerce-cart-nonce' ); ?>
			</div>


			<?php do_action( 'woocommerce_after_cart_contents' ); ?>


		</div>
		<!-- Col one end -->

	</form>
		<?php do_action( 'woocommerce_after_cart_table' ); ?>
	

		<!-- Col two -->
		<div class="col-cart-two">
			<?php do_action( 'woocommerce_before_cart_collaterals' ); ?>
			<div class="cart-collaterals">
				<?php
					/**
					 * Cart collaterals hook.
					 *
					 * @hooked woocommerce_cross_sell_display
					 * @hooked woocommerce_cart_totals - 10
					 */
					do_action( 'woocommerce_cart_collaterals' );
				?>
			</div>
		</div>
		<!-- Col two end -->


	</div>
	<!-- Cart overview end -->

<?php do_action( 'woocommerce_after_cart' ); ?>
