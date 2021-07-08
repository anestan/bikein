<?php

/**
 * Class WC_QuickPay_Views
 */
class WC_QuickPay_Views
{
    /**
     * Fetches and shows a view
     *
     * @param string $path
     * @param array $args
     */
    public static function get_view( $path, $args = [] )
    {
        if (is_array($args) && ! empty($args)) {
            extract($args);
        }

        $file = WCQP_PATH . 'views/' . trim($path);

        if (file_exists($file)) {
            include $file;
        }
    }

	/**
	 * @param $path
	 *
	 * @return string
	 */
	public static function asset_url($path) {
		return WC_QP()->plugin_url('assets/' . $path);
	}
}