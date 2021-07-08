<?php namespace ShipmondoForWooCommerce\Plugin\Controllers;

use ShipmondoForWooCommerce\Lib\Abstracts\Controller;
use ShipmondoForWooCommerce\Plugin\ShippingMethods\Shipmondo;

class BusinessController extends Controller {

	protected function registerActions() {
		parent::registerActions();

		add_action('woocommerce_after_shipping_rate', array(static::class, 'displayBusinessMessage'), 10, 2);

		add_action('woocommerce_checkout_process', array(static::class, 'validateBusinessMethod'));
	}

	/**
	 * Display message below shipping method if chosen.
	 *
	 * @param $rate
	 * @param $index
	 */
	public static function displayBusinessMessage($rate, $index) {
		$chosen_shipping_method = ShippingMethodsController::getChosenShippingMethodForPackage($index);

		if(static::isShippingMethodBusiness($chosen_shipping_method) && $chosen_shipping_method->get_rate_id() == $rate->get_id()) {
			echo '<div class="shipping_company_required">' . __('The company name is required.', 'pakkelabels-for-woocommerce') . '</div>';
		}
	}

	/**
	 * Check if shipping method is shipmondo and is Business
	 *
	 * @param $shipping_method
	 *
	 * @return bool
	 */
	public static function isShippingMethodBusiness($shipping_method) {
		return $shipping_method !== NULL && is_a($shipping_method, Shipmondo::class) && $shipping_method->isBusiness();
	}

	/**
	 * Validate required business name for business shipping methods
	 */
	public static function validateBusinessMethod() {
		if(!WC()->cart->needs_shipping()) {
			return;
		}

		// Fiks problem with WooCommerce Subscriptions
		WC()->cart->calculate_totals();

		foreach(WC()->cart->get_shipping_packages() as $package_key => $package) {
			static::validateBusinessNameForPackage($package_key);
		}

		// WooCommerce Subscriptions carts
		if(isset(WC()->cart->recurring_carts)) {
			foreach(WC()->cart->recurring_carts as $cart_key => $cart) {
				foreach($cart->get_shipping_packages() as $package_key => $package) {
					$key = "{$cart_key}_{$package_key}";

					static::validateBusinessNameForPackage($key);
				}
			}
		}
	}

	public static function validateBusinessNameForPackage($package_key) {
		$chosen_shipping_method = ShippingMethodsController::getChosenShippingMethodForPackage($package_key);

		if(static::isShippingMethodBusiness($chosen_shipping_method)) {
			if(isset($_POST['ship_to_different_address']) && ($_POST['shipping_company'] == '' || !isset($_POST['shipping_company']))) {
				wc_add_notice(__('Please fill out the Shipping company', 'pakkelabels-for-woocommerce'), 'error');
			} else if(!isset($_POST['ship_to_different_address']) && ($_POST['billing_company'] == '' || !isset($_POST['billing_company']))) {
				wc_add_notice(__('Please fill out the billing company', 'pakkelabels-for-woocommerce'), 'error');
			}
		}
	}
}