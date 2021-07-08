<?php

class WC_QuickPay_Sofort extends WC_QuickPay_Instance {

	public $main_settings = null;

	public function __construct() {
		parent::__construct();

		// Get gateway variables
		$this->id = 'sofort';

		$this->method_title = 'QuickPay - Sofort';

		$this->setup();

		$this->title       = $this->s( 'title' );
		$this->description = $this->s( 'description' );

		add_filter( 'woocommerce_quickpay_cardtypelock_sofort', [ $this, 'filter_cardtypelock' ] );

		add_action( 'woocommerce_quickpay_accepted_callback_status_capture', [
			$this,
			'additional_callback_handler'
		], 10, 2 );
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
				'label'   => __( 'Enable Sofort payment', 'woo-quickpay' ),
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
				'default'     => __( 'Sofort', 'woo-quickpay' )
			],
			'description' => [
				'title'       => __( 'Customer Message', 'woo-quickpay' ),
				'type'        => 'textarea',
				'description' => __( 'This controls the description which the user sees during checkout.', 'woo-quickpay' ),
				'default'     => __( 'Pay with your mobile phone', 'woo-quickpay' )
			],
		];
	}

	/**
	 * Sofort payments are not sending authorized callbacks. Instead a capture callback is sent. We will perform
	 * gateway specific logic here to handle the payment properly.
	 *
	 * @param \WC_QuickPay_Order $order
	 * @param stdClass $transaction
	 */
	public function additional_callback_handler( $order, $transaction ) {
		if ( $order->get_payment_method() === $this->id ) {
			WC_QuickPay_Callbacks::authorized($order, $transaction);
			WC_QuickPay_Callbacks::payment_authorized($order, $transaction);
		}
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
		return 'sofort';
	}
}
