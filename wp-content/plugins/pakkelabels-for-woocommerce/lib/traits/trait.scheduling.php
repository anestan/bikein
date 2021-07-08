<?php namespace ShipmondoForWooCommerce\Lib\Traits;

/**
 * Trait Scheduling
 * Used for scheduling jobs
 * Creating custom intervals
 * Running schedules jobs
 * @package MTTWordPressTheme\Lib\Traits
 */
trait Scheduling
{
	/**
	 * Used to declare all schedules jobs
	 * ex.
	 * [
	 *  'hook' => 'sync_ean',
	 *  'callback' => ['component' => EanSyncController::class, 'function' => 'syncScheduled'],
	 *  'frequency' => 'daily',
	 * ]
	 *
	 * Make sure the 'function' referes to a static function so it can be called by the scheduler
	 *
	 * @var array
	 */
	protected static $jobs = [];

	protected static $intervals = [];

	/**
	 * Get all intervals created through the scheduler
	 * @return array
	 */
	public static function getIntervals()
	{
		return static::$intervals;
	}

	/**
	 * Get all jobs schedules through the scheduler
	 * @return array
	 */
	public static function getScheduledJobs()
	{
		return static::$jobs;
	}

	/**
	 * Setup the scheduler
	 */
	public static function registerScheduleHooks()
	{
		static::registerScheduleInterval(604800, 'weekly', 'Once a week');
		static::registerScheduleInterval(60, 'every_minute', 'Once a minute');

		add_filter('cron_schedules', [static::class, 'cronSchedules']);
		add_action('wp_loaded', [static::class, 'scheduleJobs']);
	}

	/**
	 * Schedule all jobs created with the Scheduler
	 */
	public static function scheduleJobs()
	{
		foreach(static::$jobs as $job){
			$component = null;
			$callback = $job['callback'];

			if(is_array($callback)){
				$component = $callback[0];
				$callback = $callback[1];
			}

			add_action($job['hook'], [$component, $callback]);
		}

		foreach(static::$jobs as $job){
			if (!wp_next_scheduled($job['hook'])) {
				wp_schedule_event(time(), $job['frequency'], $job['hook']);
			}
		}
	}

	/**
	 * Used to add registered intervals (schedules) to WP filter
	 * @param $schedules
	 * @return array
	 */
	public static function cronSchedules($schedules)
	{
		static::$intervals = array_merge(static::$intervals, $schedules);

		return static::$intervals;
	}

	/**
	 * Register a schedule
	 * @param $hook
	 * @param $component
	 * @param $callback
	 * @param string $frequency
	 * @return bool True if successfully schedules, false if a hook with this name already exists.
	 */
	public static function registerSchedule($hook, $component, $callback, $frequency = 'daily')
	{
		$hook_already_registered = in_array($hook, array_column(static::$jobs, 'hook'), true);
		if($hook_already_registered){
			return false;
		}

		static::$jobs[] = [
			'hook' => $hook,
			'callback' => [$component, $callback],
			'frequency' => $frequency,
		];

		return true;
	}

	/**
	 * Register a new schedule interval,
	 * Returns the newly registered interval
	 * or the existing interval if one already exists with this slug
	 * @param integer $interval
	 * @param string $slug
	 * @param $display
	 * @return array The newly registered schedule interval, or the existing interval with this name
	 *
	 * @see https://codex.wordpress.org/Plugin_API/Filter_Reference/cron_schedules
	 */
	public static function registerScheduleInterval($interval, $slug, $display)
	{
		if(isset(static::$intervals[$slug])){
			return static::$intervals[$slug];
		}

		static::$intervals[$slug] = [
			'interval' => $interval,
			'display' => __($display)
		];

		return static::$intervals[$slug];
	}
}
