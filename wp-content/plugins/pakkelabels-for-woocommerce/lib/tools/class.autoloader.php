<?php namespace ShipmondoForWooCommerce\Lib\Tools;

class Autoloader {

	/**
	 * Namespace for project
	 *
	 * @var string
	 */
	private $namespace = '';

	/**
	 * Root path for project
	 * @var string
	 */
	private $root_path = '';

	/**
	 * Project Type can be theme og plugin
	 * @var string
	 */
	private $project_type = 'theme';

	/**
	 * Autoloader constructor.
	 *
	 * @param string $namespace
	 * @param string $root_path leave empty if theme
	 * @param string $project_type
	 *
	 * @throws \Exception
	 */
	public function __construct($namespace = '', $root_path = '', $project_type = 'theme') {
		$this->namespace = $namespace;
		$this->root_path = $root_path;
		$this->project_type = $project_type;

		$this->register();
	}

	/**
	 * Register autoloader
	 *
	 * @param bool $prepend
	 *
	 * @throws \Exception
	 */
	public function register($prepend = TRUE) {
		spl_autoload_register(array($this, 'autoloader'), TRUE, $prepend);
	}

	/**
	 * Unregister autoloader
	 */
	public function unregister() {
		spl_autoload_unregister(array($this, 'autoloader'));
	}

	/**
	 * Autoload every class
	 *
	 * @param $class
	 */
	public function autoloader($class) {
		// If not the right namespace, bail early
		if(substr($class, 0, strlen($this->namespace)) !== $this->namespace) {
			return;
		}

		// remove root namespace
		$relative_class = substr($class, strlen($this->namespace . '\\'));

		// Converte to path parts
		$path_parts = $this->getPathParts($relative_class);
		$end_part = array_pop($path_parts);

		// Set locations
		$relative_location = implode('/', $path_parts) . '/';
		$locations = $this->getLocations();

		// Prepare file name
		preg_match_all('!([A-Z][A-Z0-9]*(?=$|[A-Z][a-z0-9])|[A-Za-z][a-z0-9]+)!', $end_part, $matches);
		$end = implode('-', $matches[0]);

		$file_name_start_strings = array(
			'',
			'class.',
			'abstract.',
			'trait.',
			'model.',
		);

		// add special file name cases like Controllers
		if(count($matches[0]) > 1) {
			$special_file_name_start_string = strtolower(end($matches[0])) . '.';
			array_pop($matches[0]);
			$special_end = implode('-', $matches[0]);
		}


		// Check each locaiton
		foreach($locations as $location) {
			$location = trailingslashit($location) . $relative_location;
			foreach($file_name_start_strings as $file_name_start_string) {
				$file_ending = $file_name_start_string . strtolower($end) . '.php';

				if(is_file($location . $file_ending)) {
					include_once($location . $file_ending);

					return;
				}
			}

			if(isset($special_file_name_start_string)) {
				$file_ending = $special_file_name_start_string . strtolower($special_end) . '.php';

				if(is_file($location . $file_ending)) {
					include_once($location . $file_ending);

					return;
				}
			}
		}
	}

	/**
	 * Converte class to path parts
	 * @param $class
	 *
	 * @return array
	 */
	public function getPathParts($class) {
		// Get the class parts
		$class_parts = explode('\\', $class);

		// Converte to path parts
		$path_parts = array();
		foreach($class_parts as $part) {
			preg_match_all('!([A-Z][A-Z0-9]*(?=$|[A-Z][a-z0-9])|[A-Za-z][a-z0-9]+)!', $part, $matches);
			$parts = $matches[0];
			$part = implode('-', $parts);

			$path_parts[] = strtolower($part);
		}

		return $path_parts;
	}


	public function getLocations() {
		switch($this->project_type) {
			case 'plugin';
				$path_parts = $this->getPathParts($this->namespace);
				$relative_theme_location = '/' . implode('/', $path_parts) . '/';
				$locations = array(get_stylesheet_directory() . $relative_theme_location, get_template_directory() . $relative_theme_location);
				if(!empty($this->root_path)) {
					$locations[] = $this->root_path;
				}
				break;
			case 'theme':
			default:
				$locations = array(get_stylesheet_directory(), get_template_directory());
		}

		return $locations; // TODO: add apply_filters
	}
}
