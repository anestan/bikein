<?php namespace ShipmondoForWooCommerce\Plugin;

use ShipmondoForWooCommerce\Plugin\Controllers\LegacyController;
use ShipmondoForWooCommerce\Plugin\Controllers\SettingsController;

class ShipmondoAPI {

    private static $service_points_api_url = 'https://service-points.shipmondo.com/service-points.json';

    public static function getServicePoints($agent, $zipcode, $country = '') {
        $data = array(
            'agent' => $agent,
            'zipcode' => $zipcode,
        );

        if(!empty($country)) {
            $data['country'] = $country;
        }

        $pickup_pints = static::callServicePointsAPI($data);

        if(is_wp_error($pickup_pints)) {
            return $pickup_pints;
        }

        $body = json_decode($pickup_pints['body']);

        if(!isset($body->message)) {
            return $body;
        }

        return array();
    }

    private static function callServicePointsAPI($data) {

        $defaults = array(
            'frontend_key' => SettingsController::getFrontendKey(),
            'country' => isset($GLOBALS['woocommerce']->countries) ? $GLOBALS['woocommerce']->countries->get_base_country() : '',
            'number' => 10,
	        'request_url' => get_home_url(),
	        'request_version' => LegacyController::getWooCommerceVersion(),
	        'module_version' => Plugin::getVersion(),
	        'shipping_module_type' => 'woocommerce',
	        'wp_version' => $GLOBALS['wp_version'],
        );

        $args = wp_parse_args($data, $defaults);

        $url = add_query_arg($args, static::$service_points_api_url);

        return wp_remote_get($url);
    }

}