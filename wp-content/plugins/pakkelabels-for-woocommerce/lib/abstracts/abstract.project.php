<?php namespace ShipmondoForWooCommerce\Lib\Abstracts;

use ShipmondoForWooCommerce\Lib\Traits\classLoader;
use ShipmondoForWooCommerce\Lib\Traits\FileHandler;
use ShipmondoForWooCommerce\Lib\Traits\Templating;
use ShipmondoForWooCommerce\Lib\Traits\Translate;

abstract class Project {

	use Translate;
	use classLoader;
	use FileHandler;
	use Templating;

	/**
	 * Project info - Filled automatically
	 *
	 * @var array
	 */
	protected static $info = array();

	/**
	 * Project constructor.
	 *
	 * @param $namespace
	 * @param $root
	 */
	public function __construct($namespace, $root) {
		$this->setInfo('namespace', $namespace);
		$this->setInfo('root', wp_normalize_path($root));

		$this->setInfo('data', $this->readProjectData());

		if(is_admin()) {
			$admin_class = static::getAdminClassName();
			if(class_exists($admin_class)) {
				$this->setInfo('admin', new $admin_class);
			}
		}

		$this->registerFrameworkActions();
		$this->registerFrameworkFilters();

		$this->registerActions();
		$this->registerFilters();

		$this->registerClasses();
	}

	/**
	 * Is this project a plugin
	 * @return bool
	 */
	public static function isPlugin() {
		return static::getInfo('plugin') !== null;
	}

	/**
	 * Is this project a theme
	 * @return bool
	 */
	public static function isTheme() {
		return static::getInfo('theme') !== null;
	}

	/**
	 * Set project info
	 * @param string $name
	 * @param mixed $info
	 */
	protected static function setInfo($name, $info) {
		static::$info[$name] = $info;
	}

	/**
	 * @param string $name
	 * @param mixed $default
	 *
	 * @return mixed|null
	 */
	public static function getInfo($name, $default = null) {
		if(isset(static::$info[$name])) {
			return static::$info[$name];
		}

		return $default;
	}

	/**
	 * Get project data
	 *
	 * @param $name
	 *
	 * @return mixed|string
	 */
	public static function getData($name, $default = null) {
		$data = static::getInfo('data');

		if(is_array($data) && isset($data[$name])) {
			return $data[$name];
		}

		return $default;
	}

	/**
	 * Read project data from plugin file og theme
	 * @return array|\WP_Theme
	 */
	abstract protected function readProjectData();

	/**
	 * Return name of admin class to autoload when on the admin panel.
	 * @return string
	 */
	abstract protected function getAdminClassName();

	/**
	 * Get project root path
	 *
	 * @param string $path Path relative to root
	 *
	 * @return string
	 */
	public static function getRoot($path = '') {
		return trailingslashit(static::getInfo('root'))  . static::decodeFolderStructure($path);
	}

	/**
	 * @param string $path
	 *
	 * @return string
	 */
	public static function getRootURL($path = '') {
		return str_replace(wp_normalize_path(ABSPATH), trailingslashit(site_url()), static::getRoot($path));
	}

	/**
	 * Get project version
	 *
	 * @return mixed|string
	 */
	public static function getVersion() {
		return static::getData('Version');
	}

	/**
	 * Get plugin text domain
	 *
	 * @return mixed|string
	 */
	public static function getTextDomain() {
		return static::getData('TextDomain');
	}



	/**
	 * Register actions for build in functionality
	 */
	protected function registerFrameworkActions() {

	}

	/**
	 * Register filters for build in functionality
	 */
	protected function registerFrameworkFilters() {

	}

	/**
	 * Register Actions
	 */
	protected function registerActions() {

	}

	/**
	 * Register Filters
	 */
	protected function registerFilters() {

	}

	/**
	 * Register classes for auto discovering and loading
	 */
	protected function registerClasses() {

	}
}