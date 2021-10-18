<?php

/**
 * Class WC_QuickPay_Module
 */
abstract class WC_QuickPay_Module {

	protected static $instances;

	/**
	 * WC_QuickPay_Module constructor.
	 */
	protected function __construct() {
		$this->hooks();
	}

	/**
	 * Adds hooks and filters
	 *
	 * @return mixed
	 */
	abstract public function hooks();

	/**
	 * @return mixed
	 */
	public static function get_instance() {
		$class = get_called_class();

		if ( ! isset( self::$instances[ $class ] ) ) {
			self::$instances[ $class ] = new $class;
		}

		return self::$instances[ $class ];
	}

	/**
	 *
	 */
	private function __clone() {
	}

}
