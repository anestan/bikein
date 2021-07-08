<?php

if ( ! function_exists( 'woocommerce_quickpay_get_template' ) ) {
	/**
	 * Convenience wrapper based on the wc_get_template method
	 *
	 * @param        $template_name
	 * @param array  $args
	 * @param string $template_path
	 * @param string $default_path
	 */
	function woocommerce_quickpay_get_template( $template_name, $args = [] ) {
		$template_path = 'woocommerce-quickpay/';
		$default_path = WCQP_PATH . 'templates/';

		wc_get_template( $template_name, $args, $template_path, $default_path );
	}
}