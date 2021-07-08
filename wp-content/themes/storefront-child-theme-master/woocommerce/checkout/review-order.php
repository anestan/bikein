<?php
/**
 * Review order table
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/review-order.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.8.0
 */

defined( 'ABSPATH' ) || exit;
?>

<div class="shop_table woocommerce-checkout-review-order-table">

	<!-- Shipping -->
	<div class="cart-delivery">
		<?php
		  foreach( WC()->session->get('shipping_for_package_0')['rates'] as $method_id => $rate ){
			  if( WC()->session->get('chosen_shipping_methods')[0] == $method_id ){
				$rate_label = $rate->label; // The shipping method label name
				$rate_cost_excl_tax = floatval($rate->cost); // The cost excluding tax
				// The taxes cost
				$rate_taxes = 0;
				foreach ($rate->taxes as $rate_tax)
				  $rate_taxes += floatval($rate_tax);
				  // The cost including tax
				  $rate_cost_incl_tax = $rate_cost_excl_tax + $rate_taxes;

				  echo '<div class="label">' . $rate_label . ': </div>
						<div class="totals">' . WC()->cart->get_cart_shipping_total() . '</div>';
				  break;
			  }
		   }
		?>
	</div>

	<!-- Coupon -->
	<div class="cart-coupon">
		<?php foreach ( WC()->cart->get_coupons() as $code => $coupon ) : ?>
			<div class="cart-discount coupon-<?php echo esc_attr( sanitize_title( $code ) ); ?>">
				<div><?php wc_cart_totals_coupon_label( $coupon ); ?></div>
				<div><?php wc_cart_totals_coupon_html( $coupon ); ?></div>
			</div>
		<?php endforeach; ?>
	</div>



	<!-- Fee -->
	<div class="cart-fee">
		<?php foreach ( WC()->cart->get_fees() as $fee ) : ?>
				<div class="fee">
					<div><?php echo esc_html( $fee->name ); ?></div>
					<div><?php wc_cart_totals_fee_html( $fee ); ?></div>
				</div>
		<?php endforeach; ?>
	</div>



	<!-- Tax -->
	<div class="cart-tax">
	<?php if ( wc_tax_enabled() && ! WC()->cart->display_prices_including_tax() ) : ?>
			<?php if ( 'itemized' === get_option( 'woocommerce_tax_total_display' ) ) : ?>
				<?php foreach ( WC()->cart->get_tax_totals() as $code => $tax ) : // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited ?>
					<div class="tax-rate tax-rate-<?php echo esc_attr( sanitize_title( $code ) ); ?>">
						<div><?php echo esc_html( $tax->label ); ?></div>
						<div><?php echo wp_kses_post( $tax->formatted_amount ); ?></div>
					</div>
				<?php endforeach; ?>
			<?php else : ?>
				<tr class="tax-total">
					<div><?php echo esc_html( WC()->countries->tax_or_vat() ); ?></div>
					<div><?php wc_cart_totals_taxes_total_html(); ?></div>
				</tr>
			<?php endif; ?>
		<?php endif; ?>
	</div>

	<hr />

	<!-- Subtotal -->
	<div class="cart-subtotal">
		<div><?php esc_html_e( 'Subtotal', 'woocommerce' ); ?></div>
		<div><?php wc_cart_totals_subtotal_html(); ?></div>
	</div>

	<hr />

	<!-- Total -->
	<div class="order-total">
		<div><?php esc_html_e( 'Total', 'woocommerce' ); ?></div>
		<div class="order-total-price"><?php wc_cart_totals_order_total_html(); ?></div>
	</div>

	<!-- Products -->
	<?php
		do_action( 'woocommerce_review_order_before_cart_contents' );

		foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
			$_product = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );

			if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_checkout_cart_item_visible', true, $cart_item, $cart_item_key ) ) {
				?>
				<div class="<?php echo esc_attr( apply_filters( 'woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key ) ); ?>">

					<div class="product-image">
						<?php $thumbnail = $_product->get_image(); echo $thumbnail;
						?>
					</div>

					<div class="name-quantity">
						<div class="product-name">
							<?php echo "<p>" . apply_filters( 'woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key ) . '&nbsp;' . "</p>"; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
						</div>

						<div class="product-quantity">
							<?php echo apply_filters( 'woocommerce_checkout_cart_item_quantity', ' <span>Antal: <strong class="product-quantity">' . sprintf( '%s', $cart_item['quantity'] ) . '</strong></span>', $cart_item, $cart_item_key ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				     </div>
					</div>

					<div clas="product-variable">
						<?php echo wc_get_formatted_cart_item_data( $cart_item ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					</div>

					<div class="price-remove">
						<div class="product-price">
							<?php echo apply_filters( 'woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal( $_product, $cart_item['quantity'] ), $cart_item, $cart_item_key ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
						</div>

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
			</div>
				<?php
			}
		}

		do_action( 'woocommerce_review_order_after_cart_contents' );
		?>




	<!-- Additional fields -->
	<div class="woocommerce-additional-fields">
			<?php do_action( 'woocommerce_before_order_notes', $checkout ); ?>

			<?php if ( apply_filters( 'woocommerce_enable_order_notes_field', 'yes' === get_option( 'woocommerce_enable_order_comments', 'yes' ) ) ) : ?>

				<?php if ( ! WC()->cart->needs_shipping() || wc_ship_to_billing_address_only() ) : ?>

					<h3><?php esc_html_e( 'Additional information', 'woocommerce' ); ?></h3>

				<?php endif; ?>

				<div class="woocommerce-additional-fields__field-wrapper">
					<?php foreach ( $checkout->get_checkout_fields( 'order' ) as $key => $field ) : ?>
						<?php woocommerce_form_field( $key, $field, $checkout->get_value( $key ) ); ?>
					<?php endforeach; ?>
				</div>

			<?php endif; ?>

			<?php do_action( 'woocommerce_after_order_notes', $checkout ); ?>
	</div>


</div>
