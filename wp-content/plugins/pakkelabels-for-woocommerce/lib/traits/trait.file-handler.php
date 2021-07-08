<?php namespace ShipmondoForWooCommerce\Lib\Traits;

use ShipmondoForWooCommerce\Lib\Tools\Debug;

trait FileHandler {

	/**
	 * Decode folder sturcture when using dot instead of slashes
	 * @param       $path
	 * @param array $type
	 *
	 * @return string|string[]|null
	 */
	protected static function decodeFolderStructure($path, $type = array('php', 'css', 'js', 'png', 'jpg', 'jpeg', 'gif', 'svg', 'json', 'htm', 'html')) {
		$type = implode('$|', (array) $type);

		$path = preg_replace('/\.(?!' . $type . '$)/', '/', $path);

		return $path;
	}

	/**
	 * add an ending to the strings if they dont have it
	 *
	 * @param $subjects
	 * @param $ending
	 *
	 * @return array|string
	 */
	protected static function singleEnding($subjects, $ending) {
		if(is_array($subjects)) {
			$subjects = (array) $subjects;
			foreach($subjects as &$subject) {
				$subject = static::strRightTrim($subject, $ending) . $ending;
			}
		} else {
			$subjects = static::strRightTrim($subjects, $ending) . $ending;
		}

		return $subjects;
	}

	/**
	 * Helper function to strip something from the right
	 * @param $subject
	 * @param $ending
	 *
	 * @return false|string
	 */
	protected static function strRightTrim($subject, $ending) {
		$length = strlen($ending);
		if(substr($subject, -$length) === $ending) {
			return substr($subject, 0, -$length);
		}
		return $subject;
	}

	protected static function getRootLocations() {
		$root_locations = array(
			'child_theme' => get_stylesheet_directory(),
		);

		$theme = get_template_directory();

		if($root_locations['child_theme'] != $theme) {
			$root_locations['theme'] = $theme;
		}

		if(!in_array(static::getRoot(), $root_locations)) {
			$root_locations['root'] = static::getRoot();
		}

		foreach($root_locations as $key => $location) {
			$root_locations[$key] = trailingslashit($location);
		}

		return (array) apply_filters(
			static::getFilterName('root_locations'),
			$root_locations
		);
	}

	/**
	 * Locate a file
	 *
	 * @param       $files
	 * @param array $folders
	 * @param bool  $debug
	 *
	 * @return bool|string|null
	 */
	public static function locateFile($files, $folders = array(''), $debug = WP_DEBUG) {
		$root_locations = static::getRootLocations();

		$folders = (array) apply_filters(
			static::getFilterName('folders'),
			$folders,
			$files,
			$root_locations
		);

		foreach($folders as $key => $folder) {
			$folders[$key] = trailingslashit(static::decodeFolderStructure($folder, null));
		}

		$files = (array) apply_filters(
			static::getFilterName('files'),
			$files,
			$folders,
			$root_locations
		);

		$located_file = null;
		foreach($files as $file) {
			$file_decoded = static::decodeFolderStructure($file);
			foreach($folders as $folder) {
				foreach($root_locations as $location) {
					$abs_path = wp_normalize_path($location . $folder . $file);
					if(is_file($abs_path)) {
						$located_file = $abs_path;
						break 3;
					}

					$abs_path = wp_normalize_path($location . $folder . $file_decoded);
					if(is_file($abs_path)) {
						$located_file = $abs_path;
						break 3;
					}
				}
			}
		}

		return $located_file;
	}

	private static function isFileURL($file_name) {
		if (substr($file_name, 0, 7) === 'http://' || substr($file_name, 0, 8) === 'https://' || substr($file_name, 0, 2) === '//') {
			return true;
		}
		return false;
	}

	private static function generateSlug($file_name) {
		return strtolower(trim(str_replace(array('.js', '.css', 'http://', 'https://', '.', ' ', '/', '_', '\\', '//'), '_', __NAMESPACE__ . '-' . $file_name), '_'));
	}


	public static function getFileURL($files, $folders = array(''), $debug = WP_DEBUG) {
		if($file = static::locateFile($files, $folders, $debug)) {
			$url = str_replace(wp_normalize_path(WP_CONTENT_DIR), trailingslashit(content_url()), $file);

			return $url;
		}

		return false;
	}


}