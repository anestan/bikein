<?php namespace ShipmondoForWooCommerce\Lib\Tools;

/**
 * Class Loader
 *
 * This class wraps add/delete action/filter
 *
 * @package MTTWordPressTheme\Lib\Tools
 */
class Loader {

	/**
	 * Wrapper to \add_action
	 *
	 * @param string   $hook          Hook which to execute on.
	 * @param mixed    $component     Callback context.
	 * @param callable $callback      Callback to be used with context or solo.
	 * @param int      $priority      Optional. Execution priority. Default 10.
	 * @param int      $accepted_args Optional. Number of arguments the function accepts. Default 1.
	 */
	public static function addAction($hook, $component, $callback, $priority = 10, $accepted_args = 1) {
		$callback = $component !== null ? array($component, $callback) : $callback;
		\add_action($hook, $callback , $priority, $accepted_args);
	}

	/**
	 * Wrapper to \remove_action
	 *
	 * @param string   $hook      Hook which to execute on.
	 * @param mixed    $component Callback context.
	 * @param callable $callback  Callback to be used with context or solo.
	 * @param int      $priority  Optional. Execution priority. Default 10.
	 */
	public static function removeAction($hook, $component, $callback, $priority = 10) {
		$callback = ($component !== null ? array($component, $callback) : $callback);
		\remove_action($hook, $callback, $priority);
	}

	/**
	 * Wrapper to \add_filter
	 *
	 * @param string   $hook          Hook which to execute on.
	 * @param mixed    $component     Callback context.
	 * @param callable $callback      Callback to be used with context or solo.
	 * @param int      $priority      Optional. Execution priority. Default 10.
	 * @param int      $accepted_args Optional. Number of arguments the function accepts. Default 1.
	 */
	public static function addFilter($hook, $component, $callback, $priority = 10, $accepted_args = 1) {
		$callback = $component !== null ? array($component, $callback) : $callback;
		\add_filter($hook, $callback , $priority, $accepted_args);
	}

	/**
	 * Wrapper to \remove_filter
	 *
	 * @param string   $hook      Hook which to execute on.
	 * @param mixed    $component Callback context.
	 * @param callable $callback  Callback to be used with context or solo.
	 * @param int      $priority  Optional. Execution priority. Default 10.
	 */
	public static function removeFilter($hook, $component, $callback, $priority = 10) {
		$callback = ($component !== null ? array($component, $callback) : $callback);
		\remove_filter($hook, $callback, $priority);
	}

	/**
	 * Add ajax action
	 * @param      $action
	 * @param      $component
	 * @param      $callback
	 * @param bool $nopriv
	 */
	public static function addAjaxAction($action, $component, $callback, $nopriv = true) {
		static::addAction('wp_ajax_' . $action, $component, $callback);

		if($nopriv) {
			static::addAction('wp_ajax_nopriv_' . $action, $component, $callback);
		}
	}

	/**
	 * Register a new schedule interval.
	 * Wrapper of Scheduler::registerScheduleInterval
	 * @param $interval
	 * @param $slug
	 * @param $display
	 *
	 * @see Scheduler::registerScheduleInterval()
	 */
	public static function registerScheduleInterval($interval, $slug, $display){
		Scheduler::registerScheduleInterval($interval, $slug, $display);
	}

	/**
	 * Register a new scheduled event
	 * Wrapper of Scheduler::registerSchedule
	 * @param $hook
	 * @param $component
	 * @param $callback
	 * @param string $frequency
	 *
	 * @see Scheduler::registerSchedule()
	 */
	public static function registerSchedule($hook, $component, $callback, $frequency = 'daily'){
		Scheduler::registerSchedule($hook, $component, $callback, $frequency);
	}
}
