<?php namespace ShipmondoForWooCommerce\Plugin\Controllers;

use ShipmondoForWooCommerce\Plugin\Plugin;
use ShipmondoForWooCommerce\Plugin\ShippingMethods\Shipmondo;
use ShipmondoForWooCommerce\Lib\Abstracts\Controller;
use ShipmondoForWooCommerce\Lib\Tools\Loader;

class ShippingMethodsController extends Controller {

	static protected $shipping_agents = null;

	protected function registerActions() {
		parent::registerActions();

		Loader::addAction('woocommerce_shipping_methods', static::class, 'registerShippingMethods');
		Loader::addAction('woocommerce_sections_shipping', static::class, 'registerAdminScripts');

		Loader::addAjaxAction('shipmondo_get_price_ranges', static::class, 'getPriceRanges', false);

		Loader::addAction('woocommerce_update_options', static::class, 'updatePriceRanges');
	}

	public static function registerShippingMethods($methods) {
		if(SettingsController::isFrontendKeyValid()) {
			$methods['shipmondo'] = Shipmondo::class;
		}

		return $methods;
	}

	public static function registerAdminScripts() {
		if(empty(static::getShippingAgents())) {
			return;
		}

		$aAdminParams = array(
			'ajax_url'                              => admin_url('admin-ajax.php'),
			'sWeightTranslation'                    => __('Weight', 'pakkelabels-for-woocommerce'),
			'sPriceTranslation'                     => __('Price', 'pakkelabels-for-woocommerce'),
			'sQuantityTranslation'                  => __('Quantity', 'pakkelabels-for-woocommerce'),
			'sTitleTranslation'                     => __('Title for Shipmondo', 'pakkelabels-for-woocommerce'),
			'sMinimumTranslation'                   => __('Minimum cart total', 'pakkelabels-for-woocommerce'),
			'sMaximumTranslation'                   => __('Maximum cart total', 'pakkelabels-for-woocommerce'),
			'sShippingPriceTranslation'             => __('Shipping Price', 'pakkelabels-for-woocommerce'),
			'sBtnAddNewPriceRangeRowTranslation'    => __('Add row', 'pakkelabels-for-woocommerce'),
			'sCartTotalTranslation'                 => __('Cart Total', 'pakkelabels-for-woocommerce'),
			'sCurrencySymbol'                       => get_woocommerce_currency_symbol(),
			'sWeightUnit'                           => get_option('woocommerce_weight_unit'),
			'sShippingPriceTranslation'             => __('Shipping Price', 'pakkelabels-for-woocommerce'),
			'sShippingRangeHelperTextTranslation'   => __('In the price table below, you can choose to set up different shipping prices that will be based on the cart’s total of your chosen type.<br/>If the cart total falls outside of any of the chosen ranges, the shipping price will default to the highest shipping price.<br/>Please make sure to follow the woocommerce standard, and use a period (.) as a decimal separator.', 'pakkelabels-for-woocommerce'),
			'shipping_agents' => static::getShippingAgents()
		);

		wp_enqueue_style('shipmondo-admin-shipping-settings.css', Plugin::getRootURL('/css/shipmondo-admin-shipping-settings.css'), array(), filemtime(Plugin::getRoot('/css/shipmondo-admin-shipping-settings.css')));
		wp_enqueue_script('shipmondo-admin-shipping-settings.js', Plugin::getRootURL('/js/shipmondo-admin-shipping-settings.js'), array('jquery'), filemtime(Plugin::getRoot('/css/shipmondo-admin-shipping-settings.css')));
		wp_localize_script('shipmondo-admin-shipping-settings.js', 'ShipmondoAdminParams', $aAdminParams);
	}

	/**
	 * Get shipmondo Shipping Agents from the shipping method class
	 */
	public static function getShippingAgents($cache = true) {
		if(!empty(static::$shipping_agents) && $cache) {
			return static::$shipping_agents;
		}

		if(!SettingsController::isFrontendKeyValid(false)) {
			return null;
		}

		$frontend_key = SettingsController::getFrontendKey();

		$shipping_agents = get_transient("shipmondo_shipping_agents_{$frontend_key}");

		if(!empty($shipping_agents) && $cache) {
			static::$shipping_agents = $shipping_agents;

			return static::$shipping_agents;
		}

		$request = wp_remote_get("https://service-points.shipmondo.com/carriers.json?frontend_key={$frontend_key}");

		if(!is_wp_error($request) && wp_remote_retrieve_response_code($request) == 200) {
			$_shipping_agents = json_decode(wp_remote_retrieve_body($request));

			$shipping_agents = array();

			foreach($_shipping_agents as $agent) {
				$shipping_agents[$agent->code] = $agent;
			}

			static::$shipping_agents = $shipping_agents;

			set_transient("shipmondo_shipping_agents_{$frontend_key}", $shipping_agents, 21600); // Valid for 6 hour

			return static::$shipping_agents;
		}

		return null;
	}

	public static function updatePriceRanges() {
		if(!isset($_POST['woocommerce_shipmondo_hidden_post_field']) || !is_string($_POST['woocommerce_shipmondo_hidden_post_field'])) {
			return;
		}

		$oShippingData = json_decode(stripslashes_deep($_POST['woocommerce_shipmondo_hidden_post_field']));
		if(isset($oShippingData->iInstance_id))
		{

			$iInstance_id = $oShippingData->iInstance_id;
			$sRangeType = $oShippingData->sRangeType;
			$oShippingRangeRow = json_decode($oShippingData->oShippingRows)->oRows;
			update_option($sRangeType . '_' . $iInstance_id, $oShippingRangeRow);
		}
	}


	public static function getPriceRanges() {
		$iInstance_id = (!empty($_POST['iInstance_id']) ? $_POST['iInstance_id'] : '');
		$sRangeType = (!empty($_POST['sRangeType']) ? $_POST['sRangeType'] : '');


		$response['oData'] = get_option($sRangeType . '_' . $iInstance_id);
		$response['status'] = "success";
		echo json_encode($response);
		wp_die();
	}

	/**
	 * Get chosen Shipping method instance for package
	 * @param $package_index
	 *
	 * @return mixed
	 */
	public static function getChosenShippingMethodForPackage($package_index) {
		$chosen_methods = WC()->session->get('chosen_shipping_methods');

		if(isset($chosen_methods[$package_index])) {
			$package = static::getShippingPackage($package_index);
			if($package !== null) {
				$shipping_zone = \WC_Shipping_Zones::get_zone_matching_package($package);
				$shipping_methods = $shipping_zone->get_shipping_methods(true);

				list($method_id, $instance_id) = explode(':', $chosen_methods[$package_index]);

				if(isset($shipping_methods[$instance_id]) && $shipping_methods[$instance_id]->id == $method_id) {
					return $shipping_methods[$instance_id];
				}
			}
		}

		return null;
	}

	/**
	 * Get Package by index
	 *
	 * @param $package_index
	 *
	 * @return mixed|null
	 */
	public static function getShippingPackage($package_index) {
		foreach(WC()->cart->get_shipping_packages() as $package_key => $package) {
			if($package_key == $package_index) {
				return $package;
			}
		}

		// WooCommerce Subscriptions carts
		if(isset(WC()->cart->recurring_carts)) {
			foreach(WC()->cart->recurring_carts as $cart_key => $cart) {
				foreach($cart->get_shipping_packages() as $package_key => $package) {
					$key = "{$cart_key}_{$package_key}";
					if($key == $package_index) {
						return $package;
					}
				}
			}
		}

		return null;
	}
}