<?php

/**
 * Class WC_QuickPay_Extra
 *
 * Used to add an extra gateway with customizable payment methods.
 * want to offer Dankort-betalinger for NETS customers etc.
 */
class WC_QuickPay_Extra extends WC_QuickPay_Instance {

	public $main_settings = null;

	public function __construct() {
		parent::__construct();

		// Get gateway variables
		$this->id = 'quickpay-extra';

		$this->method_title = 'QuickPay - Extra';

		$this->setup();

		$this->title       = $this->s( 'title' );
		$this->description = $this->s( 'description' );

		add_filter( 'woocommerce_quickpay_cardtypelock_' . $this->id, [ $this, 'filter_cardtypelock' ] );
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
			'enabled'        => [
				'title'   => __( 'Enable', 'woo-quickpay' ),
				'type'    => 'checkbox',
				'label'   => __( 'Enable Extra QuickPay gateway', 'woo-quickpay' ),
				'default' => 'no'
			],
			'_Shop_setup'    => [
				'type'  => 'title',
				'title' => __( 'Shop setup', 'woo-quickpay' ),
			],
			'title'          => [
				'title'       => __( 'Title', 'woo-quickpay' ),
				'type'        => 'text',
				'description' => __( 'This controls the title which the user sees during checkout.', 'woo-quickpay' ),
				'default'     => __( 'QuickPay', 'woo-quickpay' )
			],
			'description'    => [
				'title'       => __( 'Customer Message', 'woo-quickpay' ),
				'type'        => 'textarea',
				'description' => __( 'This controls the description which the user sees during checkout.', 'woo-quickpay' ),
				'default'     => __( 'Pay', 'woo-quickpay' )
			],
			'cardtypelock'   => [
				'title'       => __( 'Payment methods', 'woo-quickpay' ),
				'type'        => 'text',
				'description' => __( 'Default: creditcard. Type in the cards you wish to accept (comma separated). See the valid payment types here: <b>http://tech.quickpay.net/appendixes/payment-methods/</b>', 'woo-quickpay' ),
				'default'     => 'creditcard',
			],
			'quickpay_icons' => [
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
				'options'           => WC_QuickPay_Settings::get_card_icons(),
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
		return $this->s( 'cardtypelock' );
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
			$icon = '';

			$icons = $this->s( 'quickpay_icons' );

			if ( ! empty( $icons ) ) {
				$icons_maxheight = $this->gateway_icon_size();

				foreach ( $icons as $key => $item ) {
					$icon .= $this->gateway_icon_create( $item, $icons_maxheight );
				}
			}
		}

		return $icon;
	}
}
