<?php namespace ShipmondoForWooCommerce;

class PluginInit {
	protected static $initialized = false;
	protected static $plugin_file = null;

	public static function registerPlugin($plugin_file) {
		static::$plugin_file = $plugin_file;

		add_action('after_setup_theme', array(static::class, 'init'));
	}

	public static function init() {
		if(static::$initialized) {
			return;
		}

		require_once(__DIR__ . '/tools/class.autoloader.php');

		new Lib\Tools\Autoloader(__NAMESPACE__, plugin_dir_path(static::$plugin_file),'plugin');

		$plugin_class = __NAMESPACE__ . '\Plugin\Plugin';

		new $plugin_class(__NAMESPACE__, static::$plugin_file);
	}
}