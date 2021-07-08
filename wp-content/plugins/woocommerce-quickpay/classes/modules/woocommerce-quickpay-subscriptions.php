<?php

/**
 * Class WC_QuickPay_Orders
 */
class WC_QuickPay_Subscriptions extends WC_QuickPay_Module {

	/**
	 * @return void
	 */
	public function hooks() {
		add_action( 'woocommerce_quickpay_callback_subscription_authorized', [ $this, 'on_subscription_authorized' ], 5, 3 );
	}

	/**
	 * @param WC_QuickPay_Order $subscription
	 * @param WC_QuickPay_Order $parent_order
	 * @param object $transaction
	 */
	public function on_subscription_authorized( $subscription, $parent_order, $transaction ) {
		if ( function_exists( 'wcs_get_subscriptions_for_order' ) && ! WC_QuickPay_Subscription::is_subscription( $parent_order->get_id() ) ) {
			$subscriptions = wcs_get_subscriptions_for_order( $parent_order, [ 'order_type' => 'any' ] );

			if ( ! empty( $subscriptions ) ) {
				foreach ( $subscriptions as $sub ) {
					if ( $subscription && $subscription->get_id() === $sub->get_id() ) {
						continue;
					}

					update_post_meta( $sub->get_id(), '_quickpay_transaction_id', $transaction->id );
				}
			}
		}
	}

}
