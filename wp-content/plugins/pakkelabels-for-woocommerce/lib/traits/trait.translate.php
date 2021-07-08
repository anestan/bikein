<?php namespace ShipmondoForWooCommerce\Lib\Traits;

trait Translate {

	public static function translate($string, $echo = false, $description = '') {
		$text_domain = static::getTextDomain();

		if($echo) {
			if(!empty($description)) {
				return _ex($string, $description, $text_domain);
			}
			return _e($string, $text_domain);
		}
		if(!empty($description)) {
			return _x($string, $text_domain);
		}
		return __($string, $text_domain);
	}

	public static function translateNumber($single, $plural, $number = 1, $echo = false, $description = '') {
		$text_domain = static::getTextDomain();

		if(!empty($description)) {
			$string = _nx($single, $plural, $number, $description, $text_domain);
		} else {
			$string = _n($single, $plural, $number, $text_domain);
		}

		$string = sprintf($string, $number);

		if($echo) {
			echo $string;
		} else {
			return $string;
		}
	}

	public static function translateReplace($string, $replace = array(), $echo = false, $description = '') {
		$string = static::translate($string, false, $description);

		$string = vsprintf($string, (array) $replace);

		if($echo) {
			echo $string;
		} else {
			return $string;
		}
	}

	public static function translateDate($date, $format = null, $echo = false, $gmt = false) {
		if(is_string($date)) {
			$_date = new \DateTime($date);
			$date = $_date->getTimestamp();
		} else if(is_a($date, 'DateTime')) {
			$date = $date->getTimeStamp();
		}

		$string = date_i18n($format, $date, $gmt);

		if($echo) {
			echo $string;
		} else {
			return $string;
		}
	}
}
