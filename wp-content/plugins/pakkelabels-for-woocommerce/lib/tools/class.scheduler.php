<?php namespace ShipmondoForWooCommerce\Lib\Tools;

use ShipmondoForWooCommerce\Lib\Traits\Scheduling;

/**
 * Class Scheduler
 * A class that implements the Scheduling trait
 * Used for creating wrapper methods in Loader
 * @package MTTWordPressTheme\Lib\Tools
 */
class Scheduler
{
	use Scheduling;

	public static function register()
	{
		static::registerScheduleHooks();
	}
}

Scheduler::register();
