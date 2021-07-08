<?php
/**
 * Plugin Name: Shipmondo for WooCommerce
 * Plugin URI: https://shipmondo.com
 * Description: Bring, DAO365, GLS and PostNord Shipping for WooCommerce
 * Version: 4.0.9
 * Text Domain: pakkelabels-for-woocommerce
 * Domain Path: /languages
 * Author: Shipmondo
 * Author URI: https://shipmondo.com
 * Requires at least: 4.5.2
 * Tested up to: 5.7
 * WC requires at least: 3.0.0
 * WC tested up to: 5.4
 */

if(!function_exists('is_plugin_active_for_network')) {
	require_once(ABSPATH . '/wp-admin/includes/plugin.php');
}

if(shipmondo_is_woocommerce_active()) {
	/* Start on MVC and OOP in the plugin - Added by Morning Train */
	require_once(__DIR__ . '/lib/class.plugin-init.php');
	ShipmondoForWooCommerce\PluginInit::registerPlugin(__FILE__);

	function shipmondo_init() {
		load_plugin_textdomain('pakkelabels-for-woocommerce', false, dirname(plugin_basename(__FILE__)) . '/languages');
	}

	shipmondo_init();

}



/**
 * Is WooCommerce active
 * @return bool
 */
function shipmondo_is_woocommerce_active() {
	return (
	        class_exists('WooCommerce')
            || in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) )
	        || is_plugin_active( 'woocommerce/woocommerce.php')
	        || is_plugin_active_for_network( 'woocommerce/woocommerce.php' )
	        || is_plugin_active( '__woocommerce/woocommerce.php')
	        || is_plugin_active_for_network( '__woocommerce/woocommerce.php' )
	);
}