<?php namespace ShipmondoForWooCommerce\Lib\Tools;

class Debug {

	public static function log($output) {
		$output = static::generateOutput($output, false);
		error_log($output);
	}

	public static function generateOutput($output, $output_pre = true, $get_caller_info = true) {
		if(is_array($output) || is_object($output) || is_bool($output)) {
			ob_start();
			var_dump($output);
			$output = ob_get_clean();
		}
		if($output_pre) {
			$output = static::outputPre($output);
		}
		if($get_caller_info) {
			$backtrace = debug_backtrace(false, 2);
			$caller = $backtrace[1];
			$output = $caller['file'] . ' line ' . $caller['line'] . ': ' . $output;
		}

		return $output;
	}

	public static function stacktrace($die = true) {
		ob_start();
		debug_print_backtrace();
		$stacktrace = ob_get_clean();

		if($die) {
			static::dd($stacktrace, 'Stacktrace', true, false);
		}

		static::dump($stacktrace, true, false);
	}

	public static function outputPre($output) {
		return '<pre>' . $output . '</pre>';
	}

	public static function dd($output, $title = '', $output_pre = true, $get_caller_info = true) {
		$output = static::generateOutput($output, $output_pre, $get_caller_info);

		\wp_die($output, $title);
	}

	public static function dump($output, $outpt_pre = true, $get_caller_info = true) {
		$output = static::generateOutput($output, $outpt_pre, $get_caller_info);
		echo $output;
	}
}
