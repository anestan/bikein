<?php

/**
 * WC_QuickPay_Settings class
 *
 * @class        WC_QuickPay_Settings
 * @version        1.0.0
 * @package        Woocommerce_QuickPay/Classes
 * @category    Class
 * @author        PerfectSolution
 */
class WC_QuickPay_Settings {

	/**
	 * get_fields function.
	 *
	 * Returns an array of available admin settings fields
	 *
	 * @access public static
	 * @return array
	 */
	public static function get_fields() {
		$fields =
			[
				'enabled' => [
					'title'   => __( 'Enable', 'woo-quickpay' ),
					'type'    => 'checkbox',
					'label'   => __( 'Enable QuickPay Payment', 'woo-quickpay' ),
					'default' => 'yes'
				],

				'_Account_setup'               => [
					'type'  => 'title',
					'title' => __( 'API - Integration', 'woo-quickpay' ),
				],
				'quickpay_apikey'              => [
					'title'       => __( 'Api User key', 'woo-quickpay' ) . self::get_required_symbol(),
					'type'        => 'text',
					'description' => __( 'Your API User\'s key. Create a separate API user in the "Users" tab inside the QuickPay manager.', 'woo-quickpay' ),
					'desc_tip'    => true,
				],
				'quickpay_privatekey'          => [
					'title'       => __( 'Private key', 'woo-quickpay' ) . self::get_required_symbol(),
					'type'        => 'text',
					'description' => __( 'Your agreement private key. Found in the "Integration" tab inside the QuickPay manager.', 'woo-quickpay' ),
					'desc_tip'    => true,
				],
				'_Autocapture'                 => [
					'type'  => 'title',
					'title' => __( 'Autocapture settings', 'woo-quickpay' )
				],
				'quickpay_autocapture'         => [
					'title'       => __( 'Physical products (default)', 'woo-quickpay' ),
					'type'        => 'checkbox',
					'label'       => __( 'Enable', 'woo-quickpay' ),
					'description' => __( 'Automatically capture payments on physical products.', 'woo-quickpay' ),
					'default'     => 'no',
					'desc_tip'    => false,
				],
				'quickpay_autocapture_virtual' => [
					'title'       => __( 'Virtual products', 'woo-quickpay' ),
					'type'        => 'checkbox',
					'label'       => __( 'Enable', 'woo-quickpay' ),
					'description' => __( 'Automatically capture payments on virtual products. If the order contains both physical and virtual products, this setting will be overwritten by the default setting above.', 'woo-quickpay' ),
					'default'     => 'no',
					'desc_tip'    => false,
				],
				'_caching'                     => [
					'type'  => 'title',
					'title' => __( 'Transaction Cache', 'woo-quickpay' )
				],
				'quickpay_caching_enabled'     => [
					'title'       => __( 'Enable Caching', 'woo-quickpay' ),
					'type'        => 'checkbox',
					'description' => __( 'Caches transaction data to improve application and web-server performance. <strong>Recommended.</strong>', 'woo-quickpay' ),
					'default'     => 'yes',
					'desc_tip'    => false,
				],
				'quickpay_caching_expiration'  => [
					'title'       => __( 'Cache Expiration', 'woo-quickpay' ),
					'label'       => __( 'Cache Expiration', 'woo-quickpay' ),
					'type'        => 'number',
					'description' => __( '<strong>Time in seconds</strong> for how long a transaction should be cached. <strong>Default: 604800 (7 days).</strong>', 'woo-quickpay' ),
					'default'     => 7 * DAY_IN_SECONDS,
					'desc_tip'    => false,
				],

				'_Extra_gateway_settings' => [
					'type'  => 'title',
					'title' => __( 'Extra gateway settings', 'woo-quickpay' )
				],
				'quickpay_cardtypelock'   => [
					'title'       => __( 'Payment methods', 'woo-quickpay' ),
					'type'        => 'text',
					'description' => __( 'Default: creditcard. Type in the cards you wish to accept (comma separated). See the valid payment types here: <b>http://tech.quickpay.net/appendixes/payment-methods/</b>', 'woo-quickpay' ),
					'default'     => 'creditcard',
				],
				'quickpay_branding_id'    => [
					'title'       => __( 'Branding ID', 'woo-quickpay' ),
					'type'        => 'text',
					'description' => __( 'Leave empty if you have no custom branding options', 'woo-quickpay' ),
					'default'     => '',
					'desc_tip'    => true,
				],

				'quickpay_autofee'                      => [
					'title'       => __( 'Enable autofee', 'woo-quickpay' ),
					'type'        => 'checkbox',
					'label'       => __( 'Enable', 'woo-quickpay' ),
					'description' => __( 'If enabled, the fee charged by the acquirer will be calculated and added to the transaction amount.', 'woo-quickpay' ),
					'default'     => 'no',
					'desc_tip'    => true,
				],
				'quickpay_captureoncomplete'            => [
					'title'       => __( 'Capture on complete', 'woo-quickpay' ),
					'type'        => 'checkbox',
					'label'       => __( 'Enable', 'woo-quickpay' ),
					'description' => __( 'When enabled quickpay payments will automatically be captured when order state is set to "Complete".', 'woo-quickpay' ),
					'default'     => 'no',
					'desc_tip'    => true,
				],
				'quickpay_complete_on_capture'          => [
					'title'       => __( 'Complete order on capture callbacks', 'woo-quickpay' ),
					'type'        => 'checkbox',
					'label'       => __( 'Enable', 'woo-quickpay' ),
					'description' => __( 'When enabled, an order will be automatically completed when capture callbacks are sent to WooCommerce. Callbacks are sent by QuickPay when the payment is captured from either the shop or the QuickPay manager. Keep disabled to manually complete orders. ', 'woo-quickpay' ),
					'default'     => 'no',
				],
				'quickpay_cancel_transaction_on_cancel' => [
					'title'       => __( 'Cancel payments on order cancellation', 'woo-quickpay' ),
					'type'        => 'checkbox',
					'label'       => __( 'Enable', 'woo-quickpay' ),
					'description' => __( 'Automatically cancel payments via the API when an order\'s status changes to cancelled.', 'woo-quickpay' ),
					'default'     => 'no',
				],
				'quickpay_text_on_statement'            => [
					'title'             => __( 'Text on statement', 'woo-quickpay' ),
					'type'              => 'text',
					'description'       => __( 'Text that will be placed on cardholder’s bank statement (MAX 22 ASCII characters and only supported by Clearhaus currently).', 'woo-quickpay' ),
					'default'           => '',
					'desc_tip'          => false,
					'custom_attributes' => [
						'maxlength' => 22,
					],
				],


				'_Shop_setup'                           => [
					'type'  => 'title',
					'title' => __( 'Shop setup', 'woo-quickpay' ),
				],
				'title'                                 => [
					'title'       => __( 'Title', 'woo-quickpay' ),
					'type'        => 'text',
					'description' => __( 'This controls the title which the user sees during checkout.', 'woo-quickpay' ),
					'default'     => __( 'QuickPay', 'woo-quickpay' ),
					'desc_tip'    => true,
				],
				'description'                           => [
					'title'       => __( 'Customer Message', 'woo-quickpay' ),
					'type'        => 'textarea',
					'description' => __( 'This controls the description which the user sees during checkout.', 'woo-quickpay' ),
					'default'     => __( 'Pay via QuickPay. Allows you to pay with your credit card via QuickPay.', 'woo-quickpay' ),
					'desc_tip'    => true,
				],
				'checkout_button_text'                  => [
					'title'       => __( 'Order button text', 'woo-quickpay' ),
					'type'        => 'text',
					'description' => __( 'Text shown on the submit button when choosing payment method.', 'woo-quickpay' ),
					'default'     => __( 'Go to payment', 'woo-quickpay' ),
					'desc_tip'    => true,
				],
				'instructions'                          => [
					'title'       => __( 'Email instructions', 'woo-quickpay' ),
					'type'        => 'textarea',
					'description' => __( 'Instructions that will be added to emails.', 'woo-quickpay' ),
					'default'     => '',
					'desc_tip'    => true,
				],
				'quickpay_icons'                        => [
					'title'             => __( 'Credit card icons', 'woo-quickpay' ),
					'type'              => 'multiselect',
					'description'       => __( 'Choose the card icons you wish to show next to the QuickPay payment option in your shop.', 'woo-quickpay' ),
					'desc_tip'          => true,
					'class'             => 'wc-enhanced-select',
					'css'               => 'width: 450px;',
					'custom_attributes' => [
						'data-placeholder' => __( 'Select icons', 'woo-quickpay' )
					],
					'default'           => '',
					'options'           => self::get_card_icons(),
				],
				'quickpay_icons_maxheight'              => [
					'title'       => __( 'Credit card icons maximum height', 'woo-quickpay' ),
					'type'        => 'number',
					'description' => __( 'Set the maximum pixel height of the credit card icons shown on the frontend.', 'woo-quickpay' ),
					'default'     => 20,
					'desc_tip'    => true,
				],
				'Google Analytics'                      => [
					'type'  => 'title',
					'title' => __( 'Google Analytics', 'woo-quickpay' ),
				],
				'quickpay_google_analytics_tracking_id' => [
					'title'       => __( 'Tracking ID', 'woo-quickpay' ),
					'type'        => 'text',
					'description' => __( 'Your Google Analytics tracking ID. I.E: UA-XXXXXXXXX-X', 'woo-quickpay' ),
					'default'     => '',
					'desc_tip'    => true,
				],
				'ShopAdminSetup'                        => [
					'type'  => 'title',
					'title' => __( 'Shop Admin Setup', 'woo-quickpay' ),
				],

				'quickpay_orders_transaction_info' => [
					'title'       => __( 'Fetch Transaction Info', 'woo-quickpay' ),
					'type'        => 'checkbox',
					'label'       => __( 'Enable', 'woo-quickpay' ),
					'description' => __( 'Show transaction information in the order overview.', 'woo-quickpay' ),
					'default'     => 'yes',
					'desc_tip'    => false,
				],

				'CustomVariables'           => [
					'type'  => 'title',
					'title' => __( 'Custom Variables', 'woo-quickpay' ),
				],
				'quickpay_custom_variables' => [
					'title'             => __( 'Select Information', 'woo-quickpay' ),
					'type'              => 'multiselect',
					'class'             => 'wc-enhanced-select',
					'css'               => 'width: 450px;',
					'default'           => '',
					'description'       => __( 'Selected options will store the specific data on your transaction inside your QuickPay Manager.', 'woo-quickpay' ),
					'options'           => self::custom_variable_options(),
					'desc_tip'          => true,
					'custom_attributes' => [
						'data-placeholder' => __( 'Select order data', 'woo-quickpay' )
					]
				],
			];

		if ( WC_QuickPay_Subscription::plugin_is_active() ) {
			$fields['woocommerce-subscriptions'] = [
				'type'  => 'title',
				'title' => 'Subscriptions'
			];

			$fields['subscription_autocomplete_renewal_orders'] = [
				'title'       => __( 'Complete renewal orders', 'woo-quickpay' ),
				'type'        => 'checkbox',
				'label'       => __( 'Enable', 'woo-quickpay' ),
				'description' => __( 'Automatically mark a renewal order as complete on successful recurring payments.', 'woo-quickpay' ),
				'default'     => 'no',
				'desc_tip'    => true,
			];
		}

		return $fields;
	}

	/**
	 * @return array
	 */
	public static function get_card_icons() {
		return [
			'apple-pay'             => 'Apple Pay',
			'dankort'               => 'Dankort',
			'visa'                  => 'Visa',
			'visaelectron'          => 'Visa Electron',
			'visa-verified'         => 'Verified by Visa',
			'mastercard'            => 'Mastercard',
			'mastercard-securecode' => 'Mastercard SecureCode',
			'mastercard-idcheck'    => 'Mastercard ID Check',
			'maestro'               => 'Maestro',
			'jcb'                   => 'JCB',
			'americanexpress'       => 'American Express',
			'diners'                => 'Diner\'s Club',
			'discovercard'          => 'Discover Card',
			'viabill'               => 'ViaBill',
			'paypal'                => 'Paypal',
			'danskebank'            => 'Danske Bank',
			'nordea'                => 'Nordea',
			'mobilepay'             => 'MobilePay',
			'forbrugsforeningen'    => 'Forbrugsforeningen',
			'ideal'                 => 'iDEAL',
			'unionpay'              => 'UnionPay',
			'sofort'                => 'Sofort',
			'cirrus'                => 'Cirrus',
			'klarna'                => 'Klarna',
			'bankaxess'             => 'BankAxess',
			'vipps'                 => 'Vipps',
			'swish'                 => 'Swish',
			'trustly'               => 'Trustly',
			'paysafecard'           => 'Paysafe Card',
		];
	}


	/**
	 * custom_variable_options function.
	 *
	 * Provides a list of custom variable options used in the settings
	 *
	 * @access private
	 * @return array
	 */
	private static function custom_variable_options() {
		$options = [
			'billing_all_data'  => __( 'Billing: Complete Customer Details', 'woo-quickpay' ),
			'browser_useragent' => __( 'Browser: User Agent', 'woo-quickpay' ),
			'customer_email'    => __( 'Customer: Email Address', 'woo-quickpay' ),
			'customer_phone'    => __( 'Customer: Phone Number', 'woo-quickpay' ),
			'shipping_all_data' => __( 'Shipping: Complete Customer Details', 'woo-quickpay' ),
			'shipping_method'   => __( 'Shipping: Shipping Method', 'woo-quickpay' ),
		];

		asort( $options );

		return $options;
	}

	/**
	 * Clears the log file.
	 *
	 * @return string
	 */
	public static function clear_logs_section() {
		$html = sprintf( '<h3 class="wc-settings-sub-title">%s</h3>', __( 'Debug', 'woo-quickpay' ) );
		$html .= sprintf( '<a id="wcqp_wiki" class="wcqp-debug-button button button-primary" href="%s" target="_blank">%s</a>', self::get_wiki_link(), __( 'Got problems? Check out the Wiki.', 'woo-quickpay' ) );
		$html .= sprintf( '<a id="wcqp_logs" class="wcqp-debug-button button" href="%s">%s</a>', WC_QP()->log->get_admin_link(), __( 'View debug logs', 'woo-quickpay' ) );

		if ( woocommerce_quickpay_can_user_empty_logs() ) {
			$html .= sprintf( '<button role="button" id="wcqp_logs_clear" class="wcqp-debug-button button">%s</button>', __( 'Empty debug logs', 'woo-quickpay' ) );
		}

		if ( woocommerce_quickpay_can_user_flush_cache() ) {
			$html .= sprintf( '<button role="button" id="wcqp_flush_cache" class="wcqp-debug-button button">%s</button>', __( 'Empty transaction cache', 'woo-quickpay' ) );
		}

		$html .= sprintf( '<br/>' );
		$html .= sprintf( '<h3 class="wc-settings-sub-title">%s</h3>', __( 'Enable', 'woo-quickpay' ) );

		return $html;
	}

	/**
	 * Returns the link to the gateway settings page.
	 *
	 * @return mixed
	 */
	public static function get_settings_page_url() {
		return admin_url( 'admin.php?page=wc-settings&tab=checkout&section=wc_quickpay' );
	}

	/**
	 * Shows an admin notice if the setup is not complete.
	 *
	 * @return void
	 */
	public static function show_admin_setup_notices() {
		$error_fields = [];

		$mandatory_fields = [
			'quickpay_privatekey' => __( 'Private key', 'woo-quickpay' ),
			'quickpay_apikey'     => __( 'Api User key', 'woo-quickpay' )
		];

		foreach ( $mandatory_fields as $mandatory_field_setting => $mandatory_field_label ) {
			if ( self::has_empty_mandatory_post_fields( $mandatory_field_setting ) ) {
				$error_fields[] = $mandatory_field_label;
			}
		}

		if ( ! empty( $error_fields ) ) {
			$message = sprintf( '<h2>%s</h2>', __( "WooCommerce QuickPay", 'woo-quickpay' ) );
			$message .= sprintf( '<p>%s</p>', sprintf( __( 'You have missing or incorrect settings. Go to the <a href="%s">settings page</a>.', 'woo-quickpay' ), self::get_settings_page_url() ) );
			$message .= '<ul>';
			foreach ( $error_fields as $error_field ) {
				$message .= "<li>" . sprintf( __( '<strong>%s</strong> is mandatory.', 'woo-quickpay' ), $error_field ) . "</li>";
			}
			$message .= '</ul>';

			printf( '<div class="%s">%s</div>', 'notice notice-error', $message );
		}

	}

	/**
	 * @return string
	 */
	public static function get_wiki_link() {
		return 'http://quickpay.perfect-solution.dk';
	}

	/**
	 * Logic wrapper to check if some of the mandatory fields are empty on post request.
	 *
	 * @return bool
	 */
	private static function has_empty_mandatory_post_fields( $settings_field ) {
		$post_key    = 'woocommerce_quickpay_' . $settings_field;
		$setting_key = WC_QP()->s( $settings_field );

		return empty( $_POST[ $post_key ] ) && empty( $setting_key );

	}

	/**
	 * @return string
	 */
	private static function get_required_symbol() {
		return '<span style="color: red;">*</span>';
	}
}


?>
