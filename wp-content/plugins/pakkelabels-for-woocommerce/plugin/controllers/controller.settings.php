<?php namespace ShipmondoForWooCommerce\Plugin\Controllers;

use ShipmondoForWooCommerce\Lib\Abstracts\Controller;
use ShipmondoForWooCommerce\Lib\Tools\Loader;
use ShipmondoForWooCommerce\Plugin\Plugin;

class SettingsController extends Controller {

	public function registerActions() {
		parent::registerActions();

		Loader::addAction('admin_notices', static::class, 'noFrontendKeyNotice');
		Loader::addAction('admin_notices', static::class, 'noGoogleMapsAPIKeyNotice');

		Loader::addAction('admin_menu', static::class, 'addAdminMenuPage');
		Loader::addAction('admin_init', static::class, 'initSettings');
	}

	/**
	 * Get Shipmondo fronten key
	 * @return string|null
	 */
	public static function getFrontendKey() {
		$options = get_option('shipmondo_settings');

		if(isset($options['shipmondo_text_field_0'])) {
			return $options['shipmondo_text_field_0'];
		}

		return null;
	}

	/**
	 * Get Google Maps API Key
	 * @return string|null
	 */
	public static function getGoogleMapsAPIKey() {
		$options = get_option('shipmondo_settings');

		if(isset($options['shipmondo_google_api_key'])) {
			return $options['shipmondo_google_api_key'];
		}

		return null;
	}

	/**
	 * Get selection type
	 * @return string modal or dropdown
	 */
	public static function getSelectionType() {
		$options = get_option('shipmondo_settings');

		if(isset($options['shipmondo_service_point_selection_type'])) {
			switch($options['shipmondo_service_point_selection_type']) {
				case 'dropdown':
					return 'dropdown';
					break;
			}
		}

		return 'modal';
	}

	/**
	 * Check if frontend key is set and valid (only validate on strlen)
	 * @return bool
	 */
	public static function isFrontendKeyValid($with_api_call_check = true) {
		$key = static::getFrontendKey();

		if(empty($key) || strlen($key) < 2) {
			return false;
		}

		if($with_api_call_check) {
			$shipping_agents = ShippingMethodsController::getShippingAgents();

			if(empty($shipping_agents) || is_wp_error($shipping_agents)) {
				return false;
			}
		}

		return true;
	}

	/**
	 * Check if Google Maps API key is set and valid (only validate on strlen)
	 * @return bool
	 */
	public static function isGoogleMapsAPIKeyValid() {
		$key = static::getGoogleMapsAPIKey();

		return (!empty($key) && strlen($key) > 5);
	}

	/**
	 * Add notice if no valid Shipmondo frontend key
	 */
	public static function noFrontendKeyNotice() {
		if(!static::isFrontendKeyValid()) {
			$shipmondo_admin_url = Plugin::getTemplate('helpers/a-tag', array(
				'url' => admin_url('admin.php?page=shipmondo'),
				'text' => __('Shipmondo Settings', 'pakkelabels-for-woocommerce')
			), false);

			Plugin::getTemplate('admin/admin-notice', array(
				'text' => sprintf( esc_html__('Please go to the WooCommerce -> %s, and set a valid frontend key', 'pakkelabels-for-woocommerce'), $shipmondo_admin_url)
			));
		}
	}

	/**
	 * Add notice i no valid google maps api key
	 */
	public static function noGoogleMapsAPIKeyNotice() {
		if(!static::isGoogleMapsAPIKeyValid()) {
			$shipmondo_admin_url = Plugin::getTemplate('helpers/a-tag', array(
				'url' => admin_url('admin.php?page=shipmondo'),
				'text' => __('Shipmondo Settings', 'pakkelabels-for-woocommerce')
			), false);

			Plugin::getTemplate('admin/admin-notice', array(
				'text' => sprintf( esc_html__('Please go to the WooCommerce -> %s, and set a valid Google Map API key', 'pakkelabels-for-woocommerce'), $shipmondo_admin_url)
			));
		}
	}

	/**
	 * Add Shipmondo admin menu
	 */
	public static function addAdminMenuPage() {
		add_submenu_page('woocommerce', __('Shipmondo', 'woocommerce-shipmondo'), __('Shipmondo', 'woocommerce-shipmondo'), 'manage_options', 'shipmondo', array(static::class, 'displayAdminMenuPage'));
	}

	/**
	 * Display options page
	 */
	public static function displayAdminMenuPage() {
		Plugin::getTemplate('settings.options-page', array(
			'settings_section' => 'ShipmondoPluginPage'
		));
	}

	/**
	 * Initialize settings
	 */
	public static function initSettings() {
		register_setting( 'ShipmondoPluginPage', 'shipmondo_settings' );

		add_settings_section(
			'shipmondo_pluginPage_section',
			__('Shipmondo shipping module settings', 'pakkelabels-for-woocommerce'),
			array(static::class, 'settingsSectionCallback'),
			'ShipmondoPluginPage'
		);

		add_settings_field(
			'shipmondo_text_field_0',
			__('Shipping Module key:', 'pakkelabels-for-woocommerce'),
			array(static::class, 'displayFrontendKeyField'),
			'ShipmondoPluginPage',
			'shipmondo_pluginPage_section'
		);

		add_settings_field(
			'shipmondo_google_api_key',
			__('Google Maps API key:', 'pakkelabels-for-woocommerce'),
			array(static::class, 'displayGoogleMapsAPIKeyField'),
			'ShipmondoPluginPage',
			'shipmondo_pluginPage_section'
		);

		add_settings_field(
			'shipmondo_service_point_selection_type',
			__('Show Pickup Points in:', 'pakkelabels-for-woocommerce'),
			array(static::class, 'displaySelectionTypeSelector'),
			'ShipmondoPluginPage',
			'shipmondo_pluginPage_section'
		);
	}

	/**
	 * Display text in start of settings section
	 */
	public static function settingsSectionCallback() {
		echo __('Generate a shipping module key - click <a target="_blank" href="https://help.shipmondo.com/da/articles/1897540-opret-en-fragtmodul-nogle">here</a> ', 'pakkelabels-for-woocommerce') . '</br>';
		echo __('Generate a personal Google Maps API key - click <a target="_blank" href="https://help.shipmondo.com/da/articles/1889291-opret-en-gratis-google-api-key">here</a>', 'pakkelabels-for-woocommerce');
	}

	/**
	 * Display Frontend Key Field
	 */
	public static function displayFrontendKeyField() {

		Plugin::getTemplate('settings.fields.text', array(
			'name' => 'shipmondo_settings[shipmondo_text_field_0]',
			'value' => (string) static::getFrontendKey(),
			'error_message' => !empty(static::getFrontendKey()) && !static::isFrontendKeyValid() ? __('Could not connect to Shipmondo. Pleace check your key!', 'pakkelabels-for-woocommerce') : ''
		));
	}

	/**
	 * Display Google Maps API Key Field
	 */
	public static function displayGoogleMapsAPIKeyField() {
		Plugin::getTemplate('settings.fields.text', array(
			'name' => 'shipmondo_settings[shipmondo_google_api_key]',
			'value' => (string) static::getGoogleMapsAPIKey()
		));
	}

	/**
	 * Display selction type picker
	 */
	public static function displaySelectionTypeSelector() {
		\ShipmondoForWooCommerce\Plugin\Plugin::getTemplate('settings.fields.select', array(
			'name' => 'shipmondo_settings[shipmondo_service_point_selection_type]',
			'value' => static::getSelectionType(),
			'options' => array(
				array(
					'title' => __('Modal', 'pakkelabels-for-woocommerce'),
					'value' => 'modal',
				),
				array(
					'title' => __('Drop Down', 'pakkelabels-for-woocommerce'),
					'value' => 'dropdown',
				)
			)
		));
	}
}