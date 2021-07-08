<?php
/**
 * Update WC_QuickPay to 4.3
 *
 * @author 		PerfectSolution
 * @version     2.0.9
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

$settings = get_option( 'woocommerce_quickpay_settings' );

if ( ! isset( $settings['quickpay_autocapture_virtual'] ) && isset( $settings['quickpay_autocapture'] ) ) {
    $settings['quickpay_autocapture_virtual'] = $settings['quickpay_autocapture'];
}

update_option( 'woocommerce_quickpay_settings', $settings );