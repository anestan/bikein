<?php namespace ShipmondoForWooCommerce\Lib\Traits;

trait classLoader {
	/**
	 * Finds the files in $dir and initializes the defined $call
	 *
	 * @param string $dir
	 * @param string $type
	 * @param string $append
	 * @param string $call
	 * @param array  $call_args
	 */
	public static function loadClasses($dir, $type = 'class', $append = '', $call = '__construct', $call_args = array()) {
		$_dir = static::getRoot($dir);

		if(!is_dir($_dir)) {
			return;
		}

		$dir_iterator = new \RecursiveDirectoryIterator($_dir);

		foreach($dir_iterator as $file) {
			$name = $file->getFileName();
			if($name === '.' || $name === '..' || !empty($type) && substr($name, 0, strlen($type) + 1) != $type . '.') {
				continue;
			}
			$class_name_parts = explode('-', substr(substr($name,strlen($type) + 1),0,-4));

			$class_name = '';
			foreach($class_name_parts as $part) {
				$class_name .= ucfirst($part);
			}

			$folder_namespace = static::getInfo('namespace');

			$dir = static::decodeFolderStructure($dir);

			$dir_parts = explode('/', $dir);

			foreach($dir_parts as $part) {
				$name_parts = explode('-', $part);
				$folder_namespace .= '\\';
				foreach($name_parts as $name_part) {
					$folder_namespace .= ucfirst($name_part);
				}
			}

			$class_name = $folder_namespace . '\\' . $class_name . $append;

			if(class_exists($class_name)) {
				if($call == '__construct') {
					new $class_name(...$call_args);
				} else {
					$class_name::{$call}(...$call_args);
				}
			}
		}
	}

	/**
	 * Registers and inits all controllers in $dir
	 *
	 * @param string $dir relative path in dot notation
	 */
	public static function registerControllers($dir = 'controllers') {
		static::loadClasses($dir, 'controller', 'Controller', '__construct');
	}

	/**
	 * Registers and inits all models in $dir
	 *
	 * @param string $dir relative path in dot notation
	 */
	public static function registerModels($dir = 'models') {
		static::loadClasses($dir, 'class', '', 'register');
	}

	/**
	 * Registers and inits all post types in $dir
	 *
	 * @param string $dir relative path in dot notation
	 *
	 */
	public static function registerCustomPostTypes($dir = 'post-types') {
		static::loadClasses($dir, 'class', '', 'register');
	}

	/**
	 * Registers and inits all loggers in $dir
	 *
	 * @param string $dir relative path in dot notation
	 */
	public static function registerLoggers($dir = 'loggers') {
		static::loadClasses($dir, 'class', '', '__construct');
	}

	/**
	 * Register and inits all commands
	 */
	public static function registerCommands($dir = 'commands') {
		static::loadClasses($dir, 'class', '', 'register');
	}

	/**
	 * Register Modules
	 * Modules must be directories
	 */
	public static function registerModules($dir = 'modules') {
		$_dir = static::getRoot($dir);

		if(!is_dir($_dir)) {
			return;
		}

		$modules_dir = new \RecursiveDirectoryIterator($_dir);

		$dir = static::singleEnding($dir, '.');

		foreach($modules_dir as $module) {
			if(is_dir($module) && !in_array($module->getFilename(), array('.','..')) ) {
				$module_dir = $module->getFilename();
				static::initClassLoader($dir . $module_dir);
			}
		}
	}

	/**
	 * Init class laoder
	 * @param string $dir
	 */
	public static function initClassLoader($dir = '') {
		if(!empty($dir)) {
			$dir = static::singleEnding($dir, '.');
		}
		static::registerControllers($dir . 'controllers');
		static::registerModels($dir . 'models');
		static::registerCustomPostTypes($dir . 'post-types');
		static::registerLoggers($dir . 'loggers');
		static::registerCommands($dir . 'commands');
		static::registerModules($dir . 'modules');
	}

}