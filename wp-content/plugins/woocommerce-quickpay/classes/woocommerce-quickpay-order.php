<?php

/**
 * WC_QuickPay_Order class
 *
 * @class          WC_QuickPay_Order
 * @version        1.0.1
 * @package        Woocommerce_QuickPay/Classes
 * @category       Class
 * @author         PerfectSolution
 */

class WC_QuickPay_Order extends WC_Order {

	/** */
	const META_PAYMENT_METHOD_CHANGE_COUNT = '_quickpay_payment_method_change_count';
	/** */
	const META_FAILED_PAYMENT_COUNT = '_quickpay_failed_payment_count';

	/**
	 * get_order_id_from_callback function.
	 *
	 * Returns the order ID based on the ID retrieved from the QuickPay callback.
	 *
	 * @access static public
	 *
	 * @param object - the callback data
	 *
	 * @return int
	 */
	public static function get_order_id_from_callback( $callback_data ) {
		// Check for the post ID reference on the response object.
		// This should be available on all new orders.
		if ( ! empty( $callback_data->variables ) && ! empty( $callback_data->variables->order_post_id ) ) {
			return $callback_data->variables->order_post_id;
		} else if ( isset( $_GET['order_post_id'] ) ) {
			return trim( $_GET['order_post_id'] );
		}

		// Fallback
		preg_match( '/\d{4,}/', $callback_data->order_id, $order_number );
		$order_number = (int) end( $order_number );

		return $order_number;
	}

	/**
	 * get_subscription_id_from_callback function.
	 *
	 * Returns the subscription ID based on the ID retrieved from the QuickPay callback, if present.
	 *
	 * @access static public
	 *
	 * @param object - the callback data
	 *
	 * @return int
	 */
	public static function get_subscription_id_from_callback( $callback_data ) {
		// Check for the post ID reference on the response object.
		// This should be available on all new orders.
		if ( ! empty( $callback_data->variables ) && ! empty( $callback_data->variables->subscription_post_id ) ) {
			return $callback_data->variables->subscription_post_id;
		} else if ( isset( $_GET['subscription_post_id'] ) ) {
			return trim( $_GET['subscription_post_id'] );
		}

		return null;
	}


	/**
	 * get_payment_id function
	 *
	 * If the order has a payment ID, we will return it. If no ID is set we return FALSE.
	 *
	 * @access public
	 * @return string
	 */
	public function get_payment_id() {
		return get_post_meta( $this->get_id(), 'QUICKPAY_PAYMENT_ID', true );
	}

	/**
	 * set_payment_id function
	 *
	 * Set the payment ID on an order
	 *
	 * @access public
	 * @return void
	 */
	public function set_payment_id( $payment_link ) {
		update_post_meta( $this->get_id(), 'QUICKPAY_PAYMENT_ID', $payment_link );
	}

	/**
	 * delete_payment_id function
	 *
	 * Delete the payment ID on an order
	 *
	 * @access public
	 * @return void
	 */
	public function delete_payment_id() {
		delete_post_meta( $this->get_id(), 'QUICKPAY_PAYMENT_ID' );
	}

	/**
	 * get_payment_link function
	 *
	 * If the order has a payment link, we will return it. If no link is set we return FALSE.
	 *
	 * @access public
	 * @return string
	 */
	public function get_payment_link() {
		return get_post_meta( $this->get_id(), 'QUICKPAY_PAYMENT_LINK', true );
	}

	/**
	 * set_payment_link function
	 *
	 * Set the payment link on an order
	 *
	 * @access public
	 * @return void
	 */
	public function set_payment_link( $payment_link ) {
		update_post_meta( $this->get_id(), 'QUICKPAY_PAYMENT_LINK', $payment_link );
	}

	/**
	 * delete_payment_link function
	 *
	 * Delete the payment link on an order
	 *
	 * @access public
	 * @return void
	 */
	public function delete_payment_link() {
		delete_post_meta( $this->get_id(), 'QUICKPAY_PAYMENT_LINK' );
	}

	/**
	 * get_transaction_order_id function
	 *
	 * If the order has a transaction order reference, we will return it. If no transaction order reference is set we
	 * return FALSE.
	 *
	 * @access public
	 * @return string
	 */
	public function get_transaction_order_id() {
		return get_post_meta( $this->get_id(), 'TRANSACTION_ORDER_ID', true );
	}

	/**
	 * set_transaction_order_id function
	 *
	 * Set the transaction order ID on an order
	 *
	 * @access public
	 * @return void
	 */
	public function set_transaction_order_id( $transaction_order_id ) {
		update_post_meta( $this->get_id(), 'TRANSACTION_ORDER_ID', $transaction_order_id );
	}

	/**
	 * add_transaction_fee function.
	 *
	 * Adds order transaction fee to the order before sending out the order confirmation
	 *
	 * @access   public
	 *
	 * @param $fee_amount
	 *
	 * @return bool
	 */

	public function add_transaction_fee( $fee_amount ) {
		if ( $fee_amount > 0 ) {

			try {
				$fee = new WC_Order_Item_Fee();

				$fee->set_name( __( 'Payment Fee', 'woo-quickpay' ) );
				$fee->set_total( $fee_amount / 100 );
				$fee->set_tax_status( 'none' );
				$fee->set_total_tax( 0 );
				$fee->set_order_id( $this->get_id() );

				$fee->save();

				$this->add_item( apply_filters( 'woocommerce_quickpay_transaction_fee_data', $fee, $this ) );

				$this->calculate_taxes();
				$this->calculate_totals( false );
				$this->save();

				return true;
			} catch ( WC_Data_Exception $e ) {
				$logger = wc_get_logger();
				$logger->error( $e->getMessage() );
			}

		}

		return false;
	}

	/**
	 * subscription_is_renewal_failure function.
	 *
	 * Checks if the order is currently in a failed renewal
	 *
	 * @access public
	 * @return boolean
	 */
	public function subscription_is_renewal_failure() {
		$renewal_failure = false;

		if ( WC_QuickPay_Subscription::plugin_is_active() ) {
			$renewal_failure = ( WC_QuickPay_Subscription::is_renewal( $this ) and $this->get_status() === 'failed' );
		}

		return $renewal_failure;
	}

	/**
	 * note function.
	 *
	 * Adds a custom order note
	 *
	 * @access public
	 * @return void
	 */
	public function note( $message ) {
		if ( isset( $message ) ) {
			$this->add_order_note( 'QuickPay: ' . $message );
		}
	}

	/**
	 * get_transaction_params function.
	 *
	 * Returns the necessary basic params to send to QuickPay when creating a payment
	 *
	 * @access public
	 * @return array
	 */
	public function get_transaction_params() {
		$is_subscription = $this->contains_subscription() || $this->is_request_to_change_payment() || WC_QuickPay_Subscription::is_subscription( $this->get_id() );

		$params_subscription = [];

		if ( $is_subscription ) {
			$params_subscription = [
				'description' => apply_filters( 'woocommerce_quickpay_transaction_params_description', 'woocommerce-subscription', $this ),
			];
		}

		$params = array_merge( [
			'order_id'         => $this->get_order_number_for_api(),
			'basket'           => $this->get_transaction_basket_params(),
			'shipping_address' => $this->get_transaction_shipping_address_params(),
			'invoice_address'  => $this->get_transaction_invoice_address_params(),
			'shipping'         => $this->get_transaction_shipping_params(),
			'shopsystem'       => $this->get_transaction_shopsystem_params(),
		], $this->get_custom_variables() );

		return apply_filters( 'woocommerce_quickpay_transaction_params', array_merge( $params, $params_subscription ), $this );
	}

	/**
	 * contains_subscription function
	 *
	 * Checks if an order contains a subscription product
	 *
	 * @access public
	 * @return boolean
	 */
	public function contains_subscription() {
		$has_subscription = false;

		if ( WC_QuickPay_Subscription::plugin_is_active() ) {
			$has_subscription = wcs_order_contains_subscription( $this );
		}

		return $has_subscription;
	}

	/**
	 * is_request_to_change_payment
	 *
	 * Check if the current request is trying to change the payment gateway
	 *
	 * @return bool
	 */
	public function is_request_to_change_payment() {
		$is_request_to_change_payment = false;

		if ( WC_QuickPay_Subscription::plugin_is_active() ) {
			$is_request_to_change_payment = WC_Subscriptions_Change_Payment_Gateway::$is_request_to_change_payment;

			if ( ! $is_request_to_change_payment && ! empty( $_GET['quickpay_change_payment_method'] ) ) {
				$is_request_to_change_payment = true;
			}
		}

		return apply_filters( 'woocommerce_quickpay_is_request_to_change_payment', $is_request_to_change_payment );
	}

	/**
	 * get_order_number_for_api function.
	 *
	 * Prefix the order number if necessary. This is done
	 * because QuickPay requires the order number to contain at least
	 * 4 chars.
	 *
	 * @access public
	 *
	 * @param bool $recurring
	 *
	 * @return string
	 */
	public function get_order_number_for_api( $recurring = false ) {
		$minimum_length = 4;

		$order_id = $this->get_id();

		// When changing payment method on subscriptions
		if ( WC_QuickPay_Subscription::is_subscription( $order_id ) ) {
			$order_number = $order_id;
		} // On initial subscription authorizations
		else if ( ! $this->order_contains_switch() && $this->contains_subscription() && ! $recurring ) {
			// Find all subscriptions
			$subscriptions = WC_QuickPay_Subscription::get_subscriptions_for_order( $order_id );
			// Get the last one and base the transaction on it.
			$subscription = end( $subscriptions );
			// Fetch the ID of the subscription, not the parent order.
			$order_number = $subscription->get_id();

			// If an initial payment on a subscription failed (recurring payment), create a new subscription with appended ID.
			if ( $this->get_failed_quickpay_payment_count() > 0 ) {
				$order_number .= sprintf( '-%d', $this->get_failed_quickpay_payment_count() );
			}
		} // On recurring / payment attempts
		else {
			// Normal orders - get the order number
			$order_number = $this->get_clean_order_number();
			// If an initial payment on a subscription failed (recurring payment), create a new subscription with appended ID.
			if ( $this->get_failed_quickpay_payment_count() > 0 ) {
				$order_number .= sprintf( '-%d', $this->get_failed_quickpay_payment_count() );
			} // If manual payment of renewal, append the order number to avoid duplicate order numbers.
			else if ( WC_QuickPay_Subscription::cart_contains_failed_renewal_order_payment() ) {
				// Get the last one and base the transaction on it.
				$subscription = WC_QuickPay_Subscription::get_subscriptions_for_renewal_order( $this->id, true );
				$order_number .= sprintf( '-%d', $subscription ? $subscription->get_failed_payment_count() : time() );
			}
			// FIXME: This is for backwards compatability only. Before 4.5.6 orders were not set to 'FAILED' when a recurring payment failed.
			// FIXME: To allow customers to pay the outstanding, we must append a value to the order number to avoid errors with duplicate order numbers in the API.
			else if ( WC_QuickPay_Subscription::cart_contains_renewal() ) {
				$order_number .= sprintf( '-%d', time() );
			}
		}

		if ( $this->is_request_to_change_payment() ) {
			$order_number .= sprintf( '-%d', $this->get_payment_method_change_count() );
		}

		$order_number_length = strlen( $order_number );

		if ( $order_number_length < $minimum_length ) {
			preg_match( '/\d+/', $order_number, $digits );

			if ( ! empty( $digits ) ) {
				$missing_digits = $minimum_length - $order_number_length;
				$order_number   = str_replace( $digits[0], str_pad( $digits[0], strlen( $digits[0] ) + $missing_digits, 0, STR_PAD_LEFT ), $order_number );
			}
		}

		return apply_filters( 'woocommerce_quickpay_order_number_for_api', $order_number, $this, $recurring );
	}

	/**
	 * @param WC_Order|int $order The WC_Order object or ID of a WC_Order order.
	 *
	 * @return bool
	 */
	public function order_contains_switch() {
		if ( function_exists( 'wcs_order_contains_switch' ) ) {
			return wcs_order_contains_switch( $this );
		}

		return false;
	}

	/**
	 * Increase the amount of payment attemtps done through QuickPay
	 *
	 * @return int
	 */
	public function get_failed_quickpay_payment_count() {
		$order_id = $this->get_id();
		$count    = get_post_meta( $order_id, self::META_FAILED_PAYMENT_COUNT, true );
		if ( empty( $count ) ) {
			$count = 0;
		}

		return $count;
	}

	/**
	 * get_clean_order_number function
	 *
	 * Returns the order number without leading #
	 *
	 * @access public
	 * @return integer
	 */
	public function get_clean_order_number() {
		return str_replace( '#', '', $this->get_order_number() );
	}

	/**
	 * Gets the amount of times the customer has updated his card.
	 *
	 * @return int
	 */
	public function get_payment_method_change_count() {
		$order_id = $this->get_id();
		$count    = get_post_meta( $order_id, self::META_PAYMENT_METHOD_CHANGE_COUNT, true );

		if ( ! empty( $count ) ) {
			return $count;
		}

		return 0;
	}

	/**
	 * Creates an array of order items formatted as "QuickPay transaction basket" format.
	 *
	 * @return array
	 */
	public function get_transaction_basket_params() {
		// Contains order items in QuickPay basket format
		$basket = [];

		foreach ( $this->get_items() as $item_line ) {
			$basket[] = $this->get_transaction_basket_params_line_helper( $item_line );
		}

		return apply_filters( 'woocommerce_quickpay_transaction_params_basket', $basket, $this );
	}

	/**
	 * @param $line_item
	 *
	 * @return array
	 */
	private function get_transaction_basket_params_line_helper( $line_item ) {
		// Before WC 3.0
		/**
		 * @var WC_Order_Item_Product $line_item
		 */

		$vat_rate = 0;

		if ( wc_tax_enabled() ) {
			$taxes = WC_Tax::get_rates( $line_item->get_tax_class() );
			//Get rates of the product
			$rates = array_shift( $taxes );
			//Take only the item rate and round it.
			$vat_rate = ! empty( $rates ) && wc_tax_enabled() ? round( array_shift( $rates ) ) : 0;
		}

		$data = [
			'qty'        => $line_item->get_quantity(),
			'item_no'    => $line_item->get_product_id(),
			'item_name'  => $line_item->get_name(),
			'item_price' => (float) ( $line_item->get_total() + $line_item->get_total_tax() ) / $line_item->get_quantity(),
			'vat_rate'   => $vat_rate,
		];

		return [
			'qty'        => $data['qty'],
			'item_no'    => $data['item_no'], //
			'item_name'  => esc_attr( $data['item_name'] ),
			'item_price' => WC_QuickPay_Helper::price_multiply( $data['item_price'], $this->get_currency() ),
			'vat_rate'   => $data['vat_rate'] > 0 ? $data['vat_rate'] / 100 : 0 // Basket item VAT rate (ex. 0.25 for 25%)
		];
	}

	public function get_transaction_shipping_address_params() {
		$shipping_name = trim( $this->get_formatted_shipping_full_name() );
		$company_name  = $this->get_shipping_company();

		if ( empty ( $shipping_name ) && ! empty( $company_name ) ) {
			$shipping_name = $company_name;
		}

		$params = [
			'name'            => $shipping_name,
			'street'          => $this->get_shipping_street_name(),
			'house_number'    => $this->get_shipping_house_number(),
			'house_extension' => $this->get_shipping_house_extension(),
			'city'            => $this->get_shipping_city(),
			'region'          => $this->get_shipping_state(),
			'zip_code'        => $this->get_shipping_postcode(),
			'country_code'    => WC_QuickPay_Countries::getAlpha3FromAlpha2( $this->get_shipping_country() ),
			'phone_number'    => $this->get_billing_phone(),
			'mobile_number'   => $this->get_billing_phone(),
			'email'           => $this->get_billing_email(),
		];

		return apply_filters( 'woocommerce_quickpay_transaction_params_shipping', $params, $this );
	}

	/**
	 * @return mixed
	 */
	public function get_shipping_street_name() {
		return WC_QuickPay_Address::get_street_name( $this->get_shipping_address_1() );
	}

	/**
	 * @return string
	 */
	public function get_shipping_house_number() {
		return WC_QuickPay_Address::get_house_number( $this->get_shipping_address_1() );
	}

	/**
	 * @return string
	 */
	public function get_shipping_house_extension() {
		return WC_QuickPay_Address::get_house_extension( $this->get_shipping_address_1() );
	}

	public function get_transaction_invoice_address_params() {
		$billing_name = trim( $this->get_formatted_billing_full_name() );
		$company_name = $this->get_billing_company();

		if ( empty ( $billing_name ) && ! empty( $company_name ) ) {
			$billing_name = $company_name;
		}

		$params = [
			'name'            => $billing_name,
			'street'          => $this->get_billing_street_name(),
			'house_number'    => $this->get_billing_house_number(),
			'house_extension' => $this->get_billing_house_extension(),
			'city'            => $this->get_billing_city(),
			'region'          => $this->get_billing_state(),
			'zip_code'        => $this->get_billing_postcode(),
			'country_code'    => WC_QuickPay_Countries::getAlpha3FromAlpha2( $this->get_billing_country() ),
			'phone_number'    => $this->get_billing_phone(),
			'mobile_number'   => $this->get_billing_phone(),
			'email'           => $this->get_billing_email(),
		];

		return apply_filters( 'woocommerce_quickpay_transaction_params_invoice', $params, $this );
	}

	/**
	 * @return mixed
	 */
	public function get_billing_street_name() {
		return WC_QuickPay_Address::get_street_name( $this->get_billing_address_1() );
	}

	/**
	 * @return string
	 */
	public function get_billing_house_number() {

		return WC_QuickPay_Address::get_house_number( $this->get_billing_address_1() );
	}

	/**
	 * @return string
	 */
	public function get_billing_house_extension() {
		return WC_QuickPay_Address::get_house_extension( $this->get_billing_address_1() );
	}

	/**
	 * Creates shipping basket row.
	 *
	 * @return array
	 */
	private function get_transaction_shipping_params() {
		$shipping_tax      = $this->get_shipping_tax();
		$shipping_total    = $this->get_shipping_total();
		$shipping_incl_vat = $shipping_total;
		$shipping_vat_rate = 0;

		if ( $shipping_tax && $shipping_total ) {
			$shipping_incl_vat += $shipping_tax;
			$shipping_vat_rate = $shipping_tax / $shipping_total; // Basket item VAT rate (ex. 0.25 for 25%)
		}

		return apply_filters( 'woocommerce_quickpay_transaction_params_shipping_row', [
			'method'          => 'own_delivery',
			'company'         => $this->get_shipping_method(),
			'amount'          => WC_QuickPay_Helper::price_multiply( $shipping_incl_vat, $this->get_currency() ),
			'vat_rate'        => $shipping_vat_rate,
			'tracking_number' => '',
			'tracking_url'    => '',
		], $this );
	}

	/**
	 * @return array
	 */
	public function get_transaction_shopsystem_params() {
		$params = [
			'name'    => 'WooCommerce',
			'version' => WCQP_VERSION,
		];

		return apply_filters( 'woocommerce_quickpay_transaction_params_shopsystem', $params, $this );
	}

	/**
	 * get_custom_variables function.
	 *
	 * Returns custom variables chosen in the gateway settings. This information will
	 * be sent to QuickPay and stored with the transaction.
	 *
	 * @access public
	 * @return array
	 */
	public function get_custom_variables() {
		$custom_vars_settings = (array) WC_QP()->s( 'quickpay_custom_variables' );
		$custom_vars          = [];

		// Single: Order Email
		if ( in_array( 'customer_email', $custom_vars_settings ) ) {
			$custom_vars[ __( 'Customer Email', 'woo-quickpay' ) ] = $this->get_billing_email();
		}

		// Single: Order Phone
		if ( in_array( 'customer_phone', $custom_vars_settings ) ) {
			$custom_vars[ __( 'Customer Phone', 'woo-quickpay' ) ] = $this->get_billing_phone();
		}

		// Single: Browser User Agent
		if ( in_array( 'browser_useragent', $custom_vars_settings ) ) {
			$custom_vars[ __( 'User Agent', 'woo-quickpay' ) ] = $this->get_customer_user_agent();
		}

		// Single: Shipping Method
		if ( in_array( 'shipping_method', $custom_vars_settings ) ) {
			$custom_vars[ __( 'Shipping Method', 'woo-quickpay' ) ] = $this->get_shipping_method();
		}

		// Save a POST ID reference on the transaction
		$custom_vars['order_post_id'] = $this->get_id();

		// Get the correct order_post_id. We want to fetch the ID of the subscription to store data on subscription (if available).
		// But only on the first attempt. In case of failed auto capture on the initial order, we dont want to add the subscription ID.
		// If we are handlong a product switch, we will not need this ID as we are making a regular payment.
		if ( ! $this->order_contains_switch() ) {
			$subscription_id = WC_QuickPay_Subscription::get_subscription_id( $this );
			if ( $subscription_id ) {
				$custom_vars['subscription_post_id'] = $subscription_id;
			}
		}

		if ( $this->is_request_to_change_payment() ) {
			$custom_vars['change_payment'] = true;
		}

		$custom_vars['payment_method'] = $this->get_payment_method();

		$custom_vars = apply_filters( 'woocommerce_quickpay_transaction_params_variables', $custom_vars, $this );

		ksort( $custom_vars );

		return [ 'variables' => $custom_vars ];
	}

	/**
	 * Increase the amount of payment attemtps done through QuickPay
	 *
	 * @return int
	 */
	public function increase_failed_quickpay_payment_count() {
		$order_id = $this->get_id();
		$count    = $this->get_failed_quickpay_payment_count();
		update_post_meta( $order_id, self::META_FAILED_PAYMENT_COUNT, ++ $count );

		return $count;
	}

	/**
	 * Reset the failed payment attempts made through the QuickPay gateway
	 */
	public function reset_failed_quickpay_payment_count() {
		$order_id = $this->get_id();
		delete_post_meta( $order_id, self::META_FAILED_PAYMENT_COUNT );
	}

	/**
	 * get_transaction_link_params function.
	 *
	 * Returns the necessary basic params to send to QuickPay when creating a payment link
	 *
	 * @access public
	 * @return array
	 */
	public function get_transaction_link_params() {
		$is_subscription = $this->contains_subscription() || $this->is_request_to_change_payment();
		$amount          = $this->get_total();

		if ( $is_subscription ) {
			$amount = $this->get_total();
		}

		return [
			'order_id'    => $this->get_order_number_for_api(),
			'continueurl' => $this->get_continue_url(),
			'cancelurl'   => $this->get_cancellation_url(),
			'amount'      => WC_QuickPay_Helper::price_multiply( $amount, $this->get_currency() ),
		];
	}

	/**
	 * get_continue_url function
	 *
	 * Returns the order's continue callback url
	 *
	 * @access public
	 * @return string
	 */
	public function get_continue_url() {
		if ( method_exists( $this, 'get_checkout_order_received_url' ) ) {
			return $this->get_checkout_order_received_url();
		}

		return add_query_arg( 'key', $this->order_key, add_query_arg( 'order', $this->get_id(), get_permalink( get_option( 'woocommerce_thanks_page_id' ) ) ) );
	}

	/**
	 * get_cancellation_url function
	 *
	 * Returns the order's cancellation callback url
	 *
	 * @access public
	 * @return string
	 */
	public function get_cancellation_url() {
		if ( method_exists( $this, 'get_cancel_order_url' ) ) {
			return str_replace( '&amp;', '&', $this->get_cancel_order_url() );
		}

		return add_query_arg( 'key', $this->get_order_key(), add_query_arg( [
			'order'                => $this->get_id(),
			'payment_cancellation' => 'yes',
		], get_permalink( get_option( 'woocommerce_cart_page_id' ) ) ) );
	}

	/**
	 * Determine if we should enable autocapture on the order. This is based on both the
	 * plugin configuration and the product types. If the order contains both virtual
	 * and non-virtual products,  we will default to the 'quickpay_autocapture'-setting.
	 */
	public function get_autocapture_setting() {
		// Get the autocapture settings
		$autocapture_default = WC_QP()->s( 'quickpay_autocapture' );
		$autocapture_virtual = WC_QP()->s( 'quickpay_autocapture_virtual' );

		$has_virtual_products    = false;
		$has_nonvirtual_products = false;

		// If the two options are the same, return immediately.
		if ( $autocapture_default === $autocapture_virtual ) {
			return $autocapture_default;
		}

		// Check order items type.
		$order_items = $this->get_items( 'line_item' );

		// Loop through the order items
		foreach ( $order_items as $order_item ) {
			// Get the product
			if ( is_callable( array( $order_item, 'get_product' ) ) ) {
				$product = $order_item->get_product();
				// Is this product virtual?
				if ( $product->is_virtual() ) {
					$has_virtual_products = true;
				} // This was a non-virtual product.
				else {
					$has_nonvirtual_products = true;
				}
			}
		}

		// If the order contains both virtual and nonvirtual products,
		// we use the 'quickpay_autopay' as the option of choice.
		if ( $has_virtual_products and $has_nonvirtual_products ) {
			return $autocapture_default;
		} // Or check if the order contains virtual products only
		else if ( $has_virtual_products ) {
			return $autocapture_virtual;
		} // Or default
		else {
			return $autocapture_default;
		}
	}

	/**
	 * has_quickpay_payment function
	 *
	 * Checks if the order is paid with the QuickPay module.
	 *
	 * @return bool
	 * @since  4.5.0
	 * @access public
	 */
	public function has_quickpay_payment() {
		$order_id = $this->get_id();

		return in_array( get_post_meta( $order_id, '_payment_method', true ), [
			'quickpay_anyday',
			'ideal',
			'fbg1886',
			'ideal',
			'klarna',
			'mobilepay',
			'mobilepay_checkout',
			'mobilepay-subscriptions',
			'quickpay_paypal',
			'quickpay',
			'quickpay-extra',
			'resurs',
			'sofort',
			'swish',
			'trustly',
			'viabill',
			'vipps',
		] );
	}

	/**
	 * Increases the amount of times the customer has updated his card.
	 *
	 * @return int
	 */
	public function increase_payment_method_change_count() {
		$count    = $this->get_payment_method_change_count();
		$order_id = $this->get_id();

		update_post_meta( $order_id, self::META_PAYMENT_METHOD_CHANGE_COUNT, ++ $count );

		return $count;
	}

	/**
	 * @param string $context
	 *
	 * @return mixed|string
	 */
	public function get_transaction_id( $context = 'view' ) {
		$order_id = $this->get_id();

		// Search for custom transaction meta added in 4.8 to avoid transaction ID
		// sometimes being empty on subscriptions in WC 3.0.
		$transaction_id = get_post_meta( $order_id, '_quickpay_transaction_id', true );
		if ( empty( $transaction_id ) ) {

			$transaction_id = parent::get_transaction_id( $context );

			if ( empty( $transaction_id ) ) {
				// Search for original transaction ID. The transaction might be temporarily removed by
				// subscriptions. Use this one instead (if available).
				$transaction_id = get_post_meta( $order_id, '_transaction_id_original', true );
				if ( empty( $transaction_id ) ) {
					// Check if the old legacy TRANSACTION ID meta value is available.
					$transaction_id = get_post_meta( $order_id, 'TRANSACTION_ID', true );
				}
			}
		}

		return $transaction_id;
	}
}

