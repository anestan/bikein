<?php
/**
 * Cart totals
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/cart/cart-totals.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 2.3.6
 */

defined( 'ABSPATH' ) || exit;

?>
<div class="cart_totals <?php echo ( WC()->customer->has_calculated_shipping() ) ? 'calculated_shipping' : ''; ?>">

	<?php do_action( 'woocommerce_before_cart_totals' ); ?>

	<h2><?php esc_html_e( 'Ordreoverblik', 'woocommerce' ); ?></h2>
	<p><?php esc_html_e( 'Din bestilling er først bindende, når vi har bekræftet din ordre.', 'woocommerce' ); ?></p>

	<!-- Cart overview -->
	<div class="cart-overview">

		<!-- Subtotal -->
		<div class="cart-subtotal">
				<div><?php esc_html_e( 'Subtotal', 'woocommerce' ); ?></div>
				<div><?php wc_cart_totals_subtotal_html(); ?></div>
		</div>

		<hr />

		<!-- Coupon -->
		<?php foreach ( WC()->cart->get_coupons() as $code => $coupon ) : ?>
			<div class="cart-discount coupon-<?php echo esc_attr( sanitize_title( $code ) ); ?>">
				<div><?php wc_cart_totals_coupon_label( $coupon ); ?></div>
				<div><?php wc_cart_totals_coupon_html( $coupon ); ?></div>
		</div>
		<?php endforeach; ?>

		
		<hr />


		<!-- Shipping -->
		<?php if ( WC()->cart->needs_shipping() && WC()->cart->show_shipping() ) : ?>

			<?php do_action( 'woocommerce_cart_totals_before_shipping' ); ?>

			<?php wc_cart_totals_shipping_html(); ?>

			<?php do_action( 'woocommerce_cart_totals_after_shipping' ); ?>

		<?php elseif ( WC()->cart->needs_shipping() && 'yes' === get_option( 'woocommerce_enable_shipping_calc' ) ) : ?>

			<div class="shipping">
				<div><?php esc_html_e( 'Shipping', 'woocommerce' ); ?></div>
				<div><?php woocommerce_shipping_calculator(); ?></div>
		</div>

		<?php endif; ?>

		<!-- Fee -->
		<?php foreach ( WC()->cart->get_fees() as $fee ) : ?>
			<div class="fee">
				<div><?php echo esc_html( $fee->name ); ?></div>
				<div><?php wc_cart_totals_fee_html( $fee ); ?></div>
		</div>
		<?php endforeach; ?>


		<!-- Tax -->
		<?php
		if ( wc_tax_enabled() && ! WC()->cart->display_prices_including_tax() ) {
			$taxable_address = WC()->customer->get_taxable_address();
			$estimated_text  = '';

			if ( WC()->customer->is_customer_outside_base() && ! WC()->customer->has_calculated_shipping() ) {
				/* translators: %s location. */
				$estimated_text = sprintf( ' <small>' . esc_html__( '(estimated for %s)', 'woocommerce' ) . '</small>', WC()->countries->estimated_for_prefix( $taxable_address[0] ) . WC()->countries->countries[ $taxable_address[0] ] );
			}

			if ( 'itemized' === get_option( 'woocommerce_tax_total_display' ) ) {
				foreach ( WC()->cart->get_tax_totals() as $code => $tax ) { // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
					?>
					<div class="tax-rate tax-rate-<?php echo esc_attr( sanitize_title( $code ) ); ?>">
						<div><?php echo esc_html( $tax->label ) . $estimated_text; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div>
						<div data-title="<?php echo esc_attr( $tax->label ); ?>"><?php echo wp_kses_post( $tax->formatted_amount ); ?></div>
				</div>
					<?php
				}
			} else {
				?>
				<div class="tax-total">
					<div><?php echo esc_html( WC()->countries->tax_or_vat() ) . $estimated_text; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div>
					<div><?php wc_cart_totals_taxes_total_html(); ?></div>
				</div>
					<?php
			}
		}
		?>

		<hr />

		<!-- Total -->
		<?php do_action( 'woocommerce_cart_totals_before_order_total' ); ?>

		<div class="order-total">
			<div><?php esc_html_e( 'Total', 'woocommerce' ); ?></div>
			<div><?php wc_cart_totals_order_total_html(); ?></div>
		</div>

		<?php do_action( 'woocommerce_cart_totals_after_order_total' ); ?>




	</div>
	<!-- Cart overview End -->

	<!-- Cart proceed to checkout -->
	<div class="cart-proceed-to-checkout">
		<div class="wc-proceed-to-checkout">
			<?php do_action( 'woocommerce_proceed_to_checkout' ); ?>
		</div>
	</div>
	<!-- Cart proceed to checkout End -->



	<!-- Selling points -->
	<div class="selling-points-cart">
		<div class="cart-selling-point">
			<i class="<?php echo get_theme_mod( 'header_selling_icon_1'); ?>"></i>
			<p class="selling-point-wrapper">
			<span class="selling-point-title">
			<?php echo get_theme_mod( 'header_selling_block_1'); ?>
			</span>
			</p>
		</div>

		<div class="cart-selling-point">
			<i class="<?php echo get_theme_mod( 'header_selling_icon_2'); ?>"></i>
			<p class="selling-point-wrapper">
			<span class="selling-point-title">
				<?php echo get_theme_mod( 'header_selling_block_2'); ?>
			</span>
			</p>
		</div>
	</div>
	<!-- Selling points end -->


	<hr />


	<!-- Terms -->
	<div class="terms">
		<div class="terms">
			<?php 
				$terms = get_permalink( wc_terms_and_conditions_page_id() );
				$policy = get_permalink( get_option( 'wp_page_for_privacy_policy' )  );
			?>
			<p> <a href="<?php echo $terms ?>">Handelsbetingelser</a> </p>
		</div>
		<div class="policy">
			<p> <a href="<?php echo $policy ?>">Privatlivspolitik</a> </p>
		</div>

	</div>
	<!-- Terms end -->

	<?php do_action( 'woocommerce_after_cart_totals' ); ?>

</div>
