<?php namespace ShipmondoForWooCommerce\Plugin\Traits;

trait Business {


	public function addActionsBusiness() {
		add_action('woocommerce_after_shipping_rate', array($this, 'displayBusinessMessage'), 10, 2);

		add_action('woocommerce_checkout_process', array($this, 'validateBusinessMethod'));
	}

	/**
	 * Display message below shipping method if chosen.
	 * @param $rate
	 * @param $index
	 */
	public function displayBusinessMessage($rate, $index) {
		if(!$this->isChosenShippingMethod($rate, $index)) {
			return;
		}

		echo '<div class="shipping_company_required">' . __('The company name is required.', 'pakkelabels-for-woocommerce').'</div>';
	}

	/**
	 * Validate required business name for business shipping methods
	 */
	public function validateBusinessMethod() {
		global $woocommerce;

		if(!$woocommerce->cart->needs_shipping()) {
			return;
		}

		foreach($woocommerce->session->chosen_shipping_methods as $index => $rate_id) {
			$shipping_method = $rate_id;

			if($pos = strpos($shipping_method, ':')) {
				$shipping_method = substr($shipping_method, 0, $pos);
			}

			if($shipping_method != $this->id) {
				continue;
			}

			if(isset($_POST['ship_to_different_address']) && ($_POST['shipping_company'] == '' || !isset($_POST['shipping_company']))) {
				wc_add_notice( __('Please fill out the Shipping company', 'pakkelabels-for-woocommerce') , 'error');
			} else if(!isset($_POST['ship_to_different_address']) && ($_POST['billing_company'] == '' || !isset($_POST['billing_company']))) {
				wc_add_notice( __('Please fill out the billing company', 'pakkelabels-for-woocommerce') , 'error');
			}
		}
	}
}