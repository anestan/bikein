<?php namespace ShipmondoForWooCommerce\Plugin\Controllers;

use ShipmondoForWooCommerce\Lib\Abstracts\Controller;
use ShipmondoForWooCommerce\Lib\Tools\Loader;

class DibsEasyCompatibilityController extends Controller {

	protected function registerActions() {
		Loader::addAction('wp_ajax_woocommerce_ajax_on_checkout_error', $this, 'cartCalculateShipping', 1);
		Loader::addAction('wp_ajax_nopriv_woocommerce_ajax_on_checkout_error', $this, 'cartCalculateShipping', 1);
		Loader::addAction('wc_ajax_ajax_on_checkout_error', $this, 'cartCalculateShipping', 1);
	}

	public function cartCalculateShipping() {
		\WC()->cart->calculate_shipping();
	}
}