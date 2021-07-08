<?php namespace ShipmondoForWooCommerce\Plugin\Controllers;

use ShipmondoForWooCommerce\Lib\Abstracts\Controller;
use ShipmondoForWooCommerce\Lib\Tools\Loader;

class LegacyController extends Controller {

	protected function registerActions() {

	}

	// HELPER FUNCTIONS

	/**
	 * Get version of WooCommerce
	 * @return string|null
	 */
	public static function getWooCommerceVersion() {
		// If get_plugins() isn't available, require it
		if(!function_exists('get_plugins')) {
			require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
		}

		// Create the plugins folder and file variables
		$plugin_folder = get_plugins( '/' . 'woocommerce' );
		$plugin_file = 'woocommerce.php';

		// If the plugin version number is set, return it
		if(isset($plugin_folder[$plugin_file]['Version'])) {
			return $plugin_folder[$plugin_file]['Version'];
		}
		// Otherwise return null
		return NULL;
	}

	/**
	 * Compare WooCommerce version
	 *
	 * @param        $version
	 * @param string $operator
	 *
	 * @return bool|int
	 */
	public static function checkWooCommerceVersion($version, $operator = '>=') {
		return version_compare(static::getWooCommerceVersion(), $version, $operator);
	}
}