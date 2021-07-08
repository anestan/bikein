<?php

class WC_QuickPay_MobilePay_Subscriptions extends WC_QuickPay_Instance {

	public $main_settings = null;

	const instance_id = 'mobilepay-subscriptions';

	public function __construct() {
		parent::__construct();

		$this->supports = [
			'products',
			'subscriptions',
			'subscription_cancellation',
			'subscription_reactivation',
			'subscription_suspension',
			'subscription_amount_changes',
			'subscription_date_changes',
			'subscription_payment_method_change_admin',
			'subscription_payment_method_change_customer',
			'refunds',
			'multiple_subscriptions',
		];

		// Get gateway variables
		$this->id = self::instance_id;

		$this->method_title = 'QuickPay - MobilePay Subscriptions';

		$this->setup();

		$this->title       = $this->s( 'title' );
		$this->description = $this->s( 'description' );

		add_filter( 'woocommerce_quickpay_cardtypelock_mobilepay-subscriptions', [ $this, 'filter_cardtypelock' ] );
		add_action( 'woocommerce_scheduled_subscription_payment_' . $this->id, [ WC_QP(), 'scheduled_subscription_payment' ], 10, 2 );
		add_filter( 'woocommerce_quickpay_transaction_params_invoice', [ $this, 'maybe_remove_phone_number' ], 10, 2 );
		add_filter( 'woocommerce_available_payment_gateways', [ $this, 'adjust_available_gateways' ] );
		add_filter( "woocommerce_quickpay_create_recurring_payment_data_{$this->id}", [ $this, 'recurring_payment_data' ], 10, 3 );
		add_action( 'woocommerce_quickpay_callback_subscription_authorized', [ $this, 'on_subscription_authorized' ], 10, 3 );
		add_action( 'woocommerce_quickpay_scheduled_subscription_payment_after', [ $this, 'on_after_scheduled_payment_created' ], 10, 2 );
		add_filter( 'woocommerce_quickpay_callback_payment_captured', [ $this, 'maybe_process_order_on_capture' ], 10, 2 );
		add_filter( 'woocommerce_subscription_payment_meta', [ $this, 'woocommerce_subscription_payment_meta' ], 10, 2 );
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
			'enabled'                       => [
				'title'   => __( 'Enable', 'woo-quickpay' ),
				'type'    => 'checkbox',
				'label'   => sprintf( __( 'Enable %s payment', 'woo-quickpay' ), $this->get_sanitized_method_title() ),
				'default' => 'no'
			],
			'_Shop_setup'                   => [
				'type'  => 'title',
				'title' => __( 'Shop setup', 'woo-quickpay' ),
			],
			'title'                         => [
				'title'       => __( 'Title', 'woo-quickpay' ),
				'type'        => 'text',
				'description' => __( 'This controls the title which the user sees during checkout.', 'woo-quickpay' ),
				'default'     => $this->get_sanitized_method_title(),
			],
			'description'                   => [
				'title'       => __( 'Customer Message', 'woo-quickpay' ),
				'type'        => 'textarea',
				'description' => __( 'This controls the description which the user sees during checkout.', 'woo-quickpay' ),
				'default'     => sprintf( __( 'Pay with %s', 'woo-quickpay' ), $this->get_sanitized_method_title() ),
			],
			[
				'type'  => 'title',
				'title' => 'Checkout'
			],
			'checkout_instant_activation'   => [
				'title'       => __( 'Activate subscriptions immediately.', 'woo-quickpay' ),
				'type'        => 'checkbox',
				'label'       => __( 'Enable', 'woo-quickpay' ),
				'default'     => 'no',
				'description' => __( 'Activates the subscription after the customer authorizes an agreement. <strong>Not suitable for membership pages selling virtual products</strong> as the first payment might take up to 48 hours to either succeed or fail. Read more <a href="https://learn.quickpay.net/helpdesk/da/articles/payment-methods/mobilepay-subscriptions/#oprettelse-af-abonnement" target="_blank">here</a>', 'woo-quickpay' ),
			],
			'checkout_prefill_phone_number' => [
				'title'       => __( 'Pre-fill phone number', 'woo-quickpay' ),
				'type'        => 'checkbox',
				'label'       => __( 'Enable', 'woo-quickpay' ),
				'default'     => 'yes',
				'description' => __( 'When enabled the customer\'s phone number will be used on the MobilePay payment page.', 'woo-quickpay' ),
			],
			[
				'type'  => 'title',
				'title' => 'Renewals'
			],
			'renewal_keep_active'           => [
				'title'       => __( 'Keep subscription active', 'woo-quickpay' ),
				'type'        => 'checkbox',
				'label'       => __( 'Enable', 'woo-quickpay' ),
				'default'     => 'no',
				'description' => __( 'When enabled the subscription will automatically be activated after scheduling the renewal payment. If the payment fails the subscription will be put on-hold.', 'woo-quickpay' ),
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
		return 'mobilepay-subscriptions';
	}

	/**
	 * If disabled, the phone number wont be sent to MobilePay which means that customers will have to type in their
	 * phone numbers manually.
	 *
	 * @param array $data
	 * @param WC_QuickPay_Order $order
	 *
	 * @return array
	 */
	public function maybe_remove_phone_number( $data, $order ) {
		if ( $order->get_payment_method() === $this->id ) {
			if ( ! WC_QuickPay_Helper::option_is_enabled( $this->s( 'checkout_prefill_phone_number' ) ) ) {
				if ( isset( $data['phone_number'] ) ) {
					$data['phone_number'] = null;
				}
			}
		}

		return $data;
	}

	/**
	 * Only show the gateway if the cart contains a subscription product
	 *
	 * @param $available_gateways
	 *
	 * @return mixed
	 */
	public function adjust_available_gateways( $available_gateways ) {
		if ( isset( $available_gateways[ $this->id ] )
		     && WC_QuickPay_Subscription::plugin_is_active()
		     && ( is_cart() || is_checkout() ) && ! WC_Subscriptions_Cart::cart_contains_subscription() ) {
			unset( $available_gateways[ $this->id ] );
		}

		return $available_gateways;
	}

	/**
	 * @param array $data
	 * @param \WC_QuickPay_Order $order
	 * @param int $subscription_id
	 *
	 * @return array
	 */
	public function recurring_payment_data( $data, $order, $subscription_id ) {
		if ( empty( $data['due_date'] ) ) {
			$data['due_date']    = gmdate( 'Y-m-d', strtotime( 'today + 2 days' ) );
			$data['description'] = sprintf( __( 'Payment of #%s', 'woo-quickpay' ), $order->get_order_number() );
		}

		return $data;
	}

	/**
	 * If enabled, the module will activate the subscription after an agreement has been authorized, but
	 *
	 * @param WC_QuickPay_Order $subscription
	 * @param WC_QuickPay_Order $parent_order
	 * @param stdClass $transaction
	 */
	public function on_subscription_authorized( $subscription, $parent_order, $transaction ) {
		try {
			if ( $subscription->get_payment_method() === self::instance_id && $subscription = wcs_get_subscription( $subscription->get_id() ) ) {
				$instant_activation    = WC_QuickPay_Helper::option_is_enabled( $this->s( 'checkout_instant_activation' ) );
				$subscription_inactive = ! $subscription->has_status( 'active' );

				if ( $instant_activation && $subscription_inactive ) {
					$subscription->update_status( 'active', __( "'Activate subscriptions immediately.' enabled. Activating subscription due to authorized MobilePay agreement", 'woo-quickpay' ) );
					$subscription->save();
				}
			}
		} catch ( \Exception $e ) {
			( new WC_QuickPay_Log() )->add( 'Unable to activate subscription immediately after payment authorization: ' . $e->getMessage() );
		}
	}

	/**
	 * @param WC_Subscription $subscription
	 * @param WC_Order $renewal_order
	 */
	public function on_after_scheduled_payment_created( $subscription, $renewal_order ) {
		if ( WC_QuickPay_Helper::option_is_enabled( $this->s( 'renewal_keep_active' ) ) ) {
			try {
				$subscription->update_status( 'active' );
			} catch ( \Exception $e ) {
				$subscription->add_order_note( $e->getMessage() );
			}
		}
	}

	/**
	 * @param WC_QuickPay_Order $order
	 * @param stdClass $transaction
	 *
	 * @return bool
	 */
	public function maybe_process_order_on_capture( $order, $transaction ) {
		if ( $order->get_payment_method() === $this->id && $order->needs_payment() ) {
			$order->payment_complete( $transaction->id );
		}
	}

	/**
	 * Declare gateway's meta data requirements in case of manual payment gateway changes performed by admins.
	 *
	 * @param array $payment_meta
	 *
	 * @param WC_Subscription $subscription
	 *
	 * @return array
	 */
	public function woocommerce_subscription_payment_meta( $payment_meta, $subscription ) {
		$order                     = new WC_QuickPay_Order( $subscription->get_id() );
		$payment_meta[ $this->id ] = [
			'post_meta' => [
				'_quickpay_transaction_id' => [
					'value' => $order->get_transaction_id(),
					'label' => __( 'QuickPay Transaction ID', 'woo-quickpay' ),
				],
			],
		];

		return $payment_meta;
	}
}
