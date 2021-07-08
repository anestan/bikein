<?php

class WC_QuickPay_PayPal extends WC_QuickPay_Instance {

	public $main_settings = null;

	public function __construct() {
		parent::__construct();

		// Get gateway variables
		$this->id = 'quickpay_paypal';

		$this->method_title = 'QuickPay - PayPal';

		$this->setup();

		$this->title       = $this->s( 'title' );
		$this->description = $this->s( 'description' );

		add_filter( 'woocommerce_quickpay_cardtypelock_quickpay_paypal', [ $this, 'filter_cardtypelock' ] );
		add_filter( 'woocommerce_quickpay_transaction_params_basket', [ $this, '_return_empty_array' ], 30, 2 );
		add_filter( 'woocommerce_quickpay_transaction_params_shipping_row', [ $this, '_return_empty_array' ], 30, 2 );
	}


	/**
	 * init_form_fields function.
	 *
	 * Initiates the plugin settings form fields
	 *
	 * @access public
	 * @return array
	 */
	public function init_form_fields() {
		$this->form_fields = [
			'enabled'     => [
				'title'   => __( 'Enable', 'woo-quickpay' ),
				'type'    => 'checkbox',
				'label'   => __( 'Enable PayPal payment', 'woo-quickpay' ),
				'default' => 'no'
			],
			'_Shop_setup' => [
				'type'  => 'title',
				'title' => __( 'Shop setup', 'woo-quickpay' ),
			],
			'title'       => [
				'title'       => __( 'Title', 'woo-quickpay' ),
				'type'        => 'text',
				'description' => __( 'This controls the title which the user sees during checkout.', 'woo-quickpay' ),
				'default'     => __( 'PayPal', 'woo-quickpay' )
			],
			'description' => [
				'title'       => __( 'Customer Message', 'woo-quickpay' ),
				'type'        => 'textarea',
				'description' => __( 'This controls the description which the user sees during checkout.', 'woo-quickpay' ),
				'default'     => __( 'Pay with PayPal', 'woo-quickpay' )
			],
		];
	}


	/**
	 * filter_cardtypelock function.
	 *
	 * Sets the cardtypelock
	 *
	 * @access public
	 * @return string
	 */
	public function filter_cardtypelock() {
		return 'paypal';
	}

	/**
	 * @param array $items
	 * @param WC_QuickPay_Order $order
	 *
	 * @return array
	 */
	public function _return_empty_array( $items, $order ) {
		if ( $order->get_payment_method() === $this->id ) {
			$items = [];
		}

		return $items;
	}

	/**
	 * FILTER: apply_gateway_icons function.
	 *
	 * Sets gateway icons on frontend
	 *
	 * @access public
	 * @return void
	 */
	public function apply_gateway_icons( $icon, $id ) {
		if ( $id == $this->id ) {
			$icon = $this->gateway_icon_create( 'paypal', $this->gateway_icon_size() );
		}

		return $icon;
	}
}
