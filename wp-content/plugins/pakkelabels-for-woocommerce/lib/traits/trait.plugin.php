<?php namespace ShipmondoForWooCommerce\Lib\Traits;

trait plugin {
	/**
	 * plugin constructor.
	 *
	 * @param $namespace
	 * @param $plugin_file
	 */
	public function __construct($namespace, $plugin_file) {
		$this->setInfo('plugin', $this);
		$this->setInfo('plugin_file', $plugin_file);

		parent::__construct($namespace, \plugin_dir_path($plugin_file));
	}

	/**
	 * Returns name of the plugin Admin Class
	 * @return string
	 */
	protected function getAdminClassName() {
		return $this->getInfo('namespace', '') . '\Plugin\PluginAdmin';
	}

	/**
	 * Reads plugin data from the plugin file
	 * @return array
	 */
	protected function readProjectData() {
		require_once(ABSPATH . 'wp-admin/includes/plugin.php');

		return \get_plugin_data($this->getInfo('plugin_file'), false, false);
	}

	/**
	 * Regsiter classes
	 */
	protected function registerClasses() {
		$this->initClassLoader('lib');
		$this->initClassLoader('plugin');
	}
}