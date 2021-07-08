<?php namespace ShipmondoForWooCommerce\Lib\Traits;

trait Templating {

	protected static $filter_name_start = '';

	public static function getFilterName($filter_name_end = '') {
		if(empty(static::$filter_name_start)) {
			static::$filter_name_start = 'mtt/' . (empty(static::$slug) ? '' : trailingslashit(static::$slug)) . 'templating/';
		}

		return static::$filter_name_start . $filter_name_end;
	}

	public static function getTemplate($template_names, $args = array(), $echo = TRUE, $folders = array('templates', 'lib.templates')) {
		$template_names = static::singleEnding($template_names, '.php');

		if($file = static::locateFile($template_names, $folders)) {
			$args = apply_filters(
				static::getFilterName('args/'),
				$args,
				$template_names,
				$file
			);

			extract($args);

			ob_start();
			do_action(
				static::getFilterName('html/before/'),
				$template_names,
				$args,
				$file
			);
			include($file);
			do_action(
				static::getFilterName('html/after/'),
				$template_names,
				$args,
				$file
			);
			$html = ob_get_clean();

			if($echo) {
				echo $html;
			}

			return $html;
		}

		return FALSE;
	}

	public static function addStyle($file_name, $deps = array(), $media = 'all', $folders = 'css', $version = 'PROJECT_VERSION') {
		if(static::isFileURL($file_name)) {
			$url = $file_name;
		} else {
			$file_name = static::singleEnding($file_name, '.css');
			$url = static::getFileURL($file_name, $folders);
		}


		if($version === 'PROJECT_VERSION') {
			$version = static::getVersion();
		}

		\wp_enqueue_style(static::generateSlug($file_name), $url, $deps, $version, $media);
	}

	public static function addScript($file_name, $deps = array('jquery'), $in_footer = TRUE, $folders = 'js', $version = 'PROJECT_VERSION') {
		if(static::isFileURL($file_name)) {
			$url = $file_name;
		} else {
			$file_name = static::singleEnding($file_name, '.js');
			$url = static::getFileURL($file_name, $folders);
		}

		if($version === 'PROJECT_VERSION') {
			$version = static::getVersion();
		}

		\wp_enqueue_script(static::generateSlug($file_name), $url, $deps, $version, $in_footer);
	}

	public static function localizeScript($file_name, $object_name, array $data, $folders = 'js') {
		$file_name = static::singleEnding($file_name, '.js');

		\wp_localize_script(static::generateSlug($file_name), $object_name, $data);
	}

	public static function getAssetsURL($file = NULL, $folders = array('assets')) {
		return static::getFileURL($file, $folders);
	}

	public static function getImgURL($file = NULL, $folders = array('assets.img'), $debug = WP_DEBUG) {
		return static::getFileURL($file, $folders, $debug);
	}
}
