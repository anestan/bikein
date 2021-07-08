<?php namespace ShipmondoForWooCommerce\Lib\Traits;

use ShipmondoForWooCommerce\Lib\Tools\Loader;

/**
 * Implements session save functionality for saving instance state temporary
 *
 * Trait SessionInstance
 * @package MTTWordPressTheme\Lib\Traits
 */
trait SessionInstance {

	/**
	 * Session ref for instance
	 * @var null
	 */
	protected $session_ref = null;

	/**
	 * Properties to include in Session
	 * @var array
	 */
	protected static $session_include_properties = array();

	/**
	 * Initialize session functionality
	 */
	protected static function registerSessionInstanceTrait() {
		Loader::addAction('init', get_called_class(), 'sessionStart');
	}

	/**
	 * Initialize session if not already initialized
	 */
	public static function sessionStart() {
		if(!session_id() && !headers_sent()) {
			session_start();
		}
	}

	/**
	 * Set session
	 *
	 * @param $session_ref string|int Unique session ref
	 * @param $instance
	 */
	public static function setSessionInstance(&$instance, $session_ref = null) {
		if(!is_null($session_ref) || is_null($instance->getSessionRef())) {
			$instance->setSessionRef($session_ref);
		}

		$_SESSION[static::getSlug()][$instance->getSessionRef()] = static::prepareSessionInstance($instance);
	}

	/**
	 * Destroy session
	 *
	 * @param $session_ref
	 */
	public static function destroySessionInstance($session_ref) {
		unset($_SESSION[static::getSlug()][$session_ref]);
	}

	/**
	 * Get Session data
	 *
	 * @param        $session_ref
	 * @param mixed $default
	 *
	 * @return null | static | mixed
	 */
	public static function getSessionInstance($session_ref, $default = 'NEW INSTANCE') {
		if(isset($_SESSION[static::getSlug()][$session_ref])) {
			$instance = new static($_SESSION[static::getSlug()][$session_ref][static::getPrimaryKeyName()]);
			foreach($_SESSION[static::getSlug()][$session_ref] as $var => $value) {
				$instance->{$var} = $value;
			}
			return $instance;
		}

		if($default === 'NEW INSTANCE') {
			return new static(array('session_ref' => $session_ref));
		}

		return $default;
	}

	/**
	 * Get session reference
	 * @return null
	 */
	public function getSessionRef($default = 'GENERATE') {
		if(isset($this->session_ref)) {
			return $this->session_ref;
		}

		if($default === 'GENERATE') {
			$this->setSessionRef();
			return $this->session_ref;
		}

		return $default;
	}

	/**
	 * Set session ref - generate if session_ref is null
	 *
	 * @param null $session_ref
	 */
	public function setSessionRef($session_ref = null) {
		if(is_null($session_ref)) {
			$session_ref = uniqid();
		}

		$this->session_ref = $session_ref;
	}


	/**
	 * Set session
	 */
	public function setSession() {
		static::setSessionInstance($this);
	}

	/**
	 * Prepare Session Instance
	 * @param static $instance
	 * @return array
	 */
	public static function prepareSessionInstance(&$instance) {
		$properties = array(
			$instance->getPrimaryKeyName() => $instance->getPrimaryKey(),
		);
		foreach(get_object_vars($instance) as $var => $value) {
			if(in_array($var, static::$session_include_properties)) {
				$properties[$var] = $value;
			}
		}
		return $properties;
	}

}
