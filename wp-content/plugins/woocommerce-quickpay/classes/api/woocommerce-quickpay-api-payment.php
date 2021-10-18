<?php
/**
 * WC_QuickPay_API_Payment class
 *
 * @class          WC_QuickPay_API_Payment
 * @since          4.0.0
 * @package        Woocommerce_QuickPay/Classes
 * @category       Class
 * @author         PerfectSolution
 * @docs        http://tech.quickpay.net/api/services/?scope=merchant
 */

class WC_QuickPay_API_Payment extends WC_QuickPay_API_Transaction {

	/**
	 * __construct function.
	 *
	 * @access public
	 * @return void
	 */
	public function __construct( $resource_data = null ) {
		// Run the parent construct
		parent::__construct();

		// Set the resource data to an object passed in on object instantiation.
		// Usually done when we want to perform actions on an object returned from
		// the API sent to the plugin callback handler.
		if ( is_object( $resource_data ) ) {
			$this->resource_data = $resource_data;
		}

		// Append the main API url
		$this->api_url .= 'payments/';
	}


	/**
	 * create function.
	 *
	 * Creates a new payment via the API
	 *
	 * @access public
	 *
	 * @param WC_QuickPay_Order $order
	 *
	 * @return object
	 * @throws QuickPay_API_Exception
	 */
	public function create( WC_QuickPay_Order $order ) {
		return parent::create( $order );
	}


	/**
	 * capture function.
	 *
	 * Sends a 'capture' request to the QuickPay API
	 *
	 * @access public
	 *
	 * @param int $transaction_id
	 * @param \WC_Order|WC_QuickPay_Order $order
	 * @param float $amount
	 *
	 * @return object
	 * @throws QuickPay_API_Exception
	 * @throws QuickPay_Exception
	 * @throws QuickPay_Capture_Exception
	 */
	public function capture( $transaction_id, $order, $amount = null ) {
		// Check if a custom amount ha been set
		if ( $amount === null ) {
			// No custom amount set. Default to the order total
			$amount = $order->get_total();
		}

		$request = $this->post( sprintf( '%d/%s', $transaction_id, "capture" ), [ 'amount' => WC_QuickPay_Helper::price_multiply( $amount, $order->get_currency() ) ], true );

		$this->check_last_operation_of_type_with_location_fallback( 'capture', $order, $request );

		return $this;
	}

	/**
	 * @param string $action action|refund
	 * @param array $request
	 * @param WC_Order $order
	 *
	 * @throws QuickPay_API_Exception
	 * @throws QuickPay_Capture_Exception
	 * @throws QuickPay_Exception
	 */
	public function check_last_operation_of_type_with_location_fallback( $action, $order, $request ) {
		$follow_location = isset( $request[5]['location'] ) && ! empty( $request[5]['location'] );

		try {
			$_action = $this->get_last_operation_of_type( $action );
		} catch ( QuickPay_Exception $e ) {
			$_action = null;
		}

		if ( $follow_location && ! $_action ) {
			$api     = new WC_QuickPay_API( WC_QP()->s( 'quickpay_apikey' ) );
			$_action = $api->get( $request[5]['location'][0] );

			if ( empty( $_action ) ) {
				throw new QuickPay_Exception( sprintf( '%s inconclusive. Response from location header is empty.', ucfirst( $action ) ) );
			}
		}

		if ( ! $follow_location && ! $_action ) {
			throw new QuickPay_Exception( sprintf( 'No %s operation or location found: %s', $action, json_encode( $this->resource_data ) ) );
		}


		if ( $_action->qp_status_code > 20200 ) {
			throw new QuickPay_Capture_Exception( sprintf( '%s payment on order #%s failed. Message: %s', ucfirst( $action ), $order->get_id(), $_action->qp_status_msg ) );
		}
	}


	/**
	 * cancel function.
	 *
	 * Sends a 'cancel' request to the QuickPay API
	 *
	 * @access public
	 *
	 * @param int $transaction_id
	 *
	 * @return void
	 * @throws QuickPay_API_Exception
	 */
	public function cancel( $transaction_id ) {
		$this->post( sprintf( '%d/%s', $transaction_id, "cancel" ) );
	}


	/**
	 * refund function.
	 *
	 * Sends a 'refund' request to the QuickPay API
	 *
	 * @access public
	 *
	 * @param int $transaction_id
	 * @param \WC_Order $order
	 * @param int $amount
	 *
	 * @return void
	 * @throws QuickPay_API_Exception
	 * @throws QuickPay_Exception
	 */
	public function refund( $transaction_id, $order, $amount = null ) {
		// Check if a custom amount ha been set
		if ( $amount === null ) {
			// No custom amount set. Default to the order total
			$amount = $order->get_total();
		}

		if ( ! $order instanceof WC_QuickPay_Order ) {
			$order = new WC_QuickPay_Order( $order->get_id() );
		}

		// Get all basket items
		$basket_items = $order->get_transaction_basket_params();

		// Select the first item as this should be an actual product and not shipping or similar.
		$product = reset( $basket_items );

		$request = $this->post( sprintf( '%d/%s', $transaction_id, "refund" ), [
			'amount'   => WC_QuickPay_Helper::price_multiply( $amount, $order->get_currency() ),
			'vat_rate' => $product['vat_rate'],
		], true );

		$this->check_last_operation_of_type_with_location_fallback( 'refund', $order, $request );
	}


	/**
	 * is_action_allowed function.
	 *
	 * Check if the action we are about to perform is allowed according to the current transaction state.
	 *
	 * @access public
	 * @return boolean
	 * @throws QuickPay_API_Exception
	 */
	public function is_action_allowed( $action ) {
		$state             = $this->get_current_type();
		$remaining_balance = $this->get_remaining_balance();

		$allowed_states = [
			'capture'          => [ 'authorize', 'recurring' ],
			'cancel'           => [ 'authorize', 'recurring' ],
			'refund'           => [ 'capture', 'refund' ],
			'renew'            => [ 'authorize' ],
			'splitcapture'     => [ 'authorize', 'capture' ],
			'recurring'        => [ 'subscribe' ],
			'standard_actions' => [ 'authorize', 'recurring' ],
		];

		// MP Subscription payments cannot be manually captured as they are automatically captured on the due date.
		if ( 'mobilepaysubscriptions' === $this->get_acquirer() && $action === 'capture' ) {
			return false;
		}

		// We want to still allow captures if there is a remaining balance.
		if ( 'capture' === $state && $remaining_balance > 0 && $action !== 'cancel' ) {
			return true;
		}

		return in_array( $state, $allowed_states[ $action ] );
	}
}
