<?php namespace ShipmondoForWooCommerce\Plugin\ShippingMethods;

use ShipmondoForWooCommerce\Plugin\Controllers\LegacyController;
use ShipmondoForWooCommerce\Plugin\Controllers\SettingsController;
use ShipmondoForWooCommerce\Plugin\Controllers\ShippingMethodsController;
use ShipmondoForWooCommerce\Plugin\Plugin;

class Shipmondo extends \WC_Shipping_Method {

	protected $cart_total = null;

	protected $free_shipping_total = null;

	/**
	 * Shipmondo constructor.
	 *
	 * @param int $instance_id
	 */
	public function __construct($instance_id = 0) {
		parent::__construct($instance_id);

		$this->id = 'shipmondo';
		$this->method_title = __('Shipmondo', 'pakkelabels-for-woocommerce');
		$this->title = $this->method_title;
		$this->method_description = __('Shipmondo shipping method', 'pakkelabels-for-woocommerce');
		$this->supports = array(
			'shipping-zones',
			'instance-settings',
		);

		$this->init();
	}

	/**
	 * Init settings and forms
	 */
	public function init() {
		$this->init_form_fields();
		$this->init_instance_settings();

		$this->title = $this->get_instance_option('title');
		$this->tax_status = $this->get_instance_option('tax_status');
		$this->shipping_price 	            	        = $this->get_instance_option('shipping_price');
		$this->enable_free_shipping                     = $this->get_instance_option('enable_free_shipping');
		$this->free_shipping_total                      = $this->get_instance_option('free_shipping_total');
		$this->enable_free_shipping_with_coupon         = $this->get_instance_option('enable_free_shipping_with_coupon');
		$this->type                                     = !empty(WC()->shipping()->get_shipping_classes()) ? $this->get_instance_option( 'type', 'class' ) : 'class';

		add_action('woocommerce_update_options_shipping_' . $this->id, array( $this, 'process_admin_options'));
	}

	/**
	 * Set settings form field
	 */
	public function init_form_fields() {
		$_shipping_agents = $this->getShippingAgents();
		if(empty($_shipping_agents)) {
			$shipping_agents = array();
		} else {
			$shipping_agents = array_map(function($item) {
				return $item->name;
			}, $_shipping_agents);
		}

		$this->instance_form_fields = array(
			'hidden_post_field' => array(
				'type' 			=> 'hidden',
				'class'         => 'hidden_post_field',
			),
			'shipping_agent' => array(
				'title' => __('Carrier', 'pakkelabels-for-woocommerce'),
				'type' => 'select',
				'options' => $shipping_agents,
				'description' => __('Which carrier do you want to configure?', 'pakkelabels-for-woocommerce'),
				'desc_tip' => true,
			),
			'shipping_product' => array(
				'title' => __('Shipping Product', 'pakkelabels-for-woocommerce'),
				'type' => 'select',
				'options' => array(
					'service_point' => __('Service point', 'pakkelabels-for-woocommerce'),
					'private' => __('Home delivery', 'pakkelabels-for-woocommerce'),
					'business' => __('Business Parcel', 'pakkelabels-for-woocommerce'),
				),
				'description' => __('Which shipping product do you want to configure?', 'pakkelabels-for-woocommerce'),
				'desc_tip' => true,
			),
			'_shipping_settings' => array(
				'type' => 'title',
				'title' => __('Shipping Method Settings')
			),
			'title' => array(
				'title' => __('Method name', 'pakkelabels-for-woocommerce'),
				'type' => 'text',
				'description' => __('This controls the title which customer will be presented for during checkout.', 'pakkelabels-for-woocommerce'),
				'default' => $this->title,
				'desc_tip' => true,
			),
			'tax_status' => array(
				'title' 		=> __( 'Tax Status', 'pakkelabels-for-woocommerce' ),
				'type' 			=> 'select',
				'class'         => 'wc-enhanced-select',
				'default' 		=> 'taxable',
				'options'		=> array(
					'taxable' 	=> __( 'Taxable', 'pakkelabels-for-woocommerce' ),
					'none' 		=> _x( 'None', 'Tax status', 'pakkelabels-for-woocommerce' )
				)
			),
			'differentiated_price_type' => array(
				'title' 		=> __( 'Differentiated Price Type', 'pakkelabels-for-woocommerce'),
				'type' 			=> 'select',
				'description' 	=> __( 'Choose what the shipping price is based on', 'pakkelabels-for-woocommerce' ),
				'default' 		=> 'Normal',
				'class'         => 'differentiated_price_type',
				'options'		=> array(
					'Quantity' 		=> __( 'Normal', 'pakkelabels-for-woocommerce'),
					'Weight' 	    => __( 'Weight', 'pakkelabels-for-woocommerce'),
					'Price' 	    => __( 'Price', 'pakkelabels-for-woocommerce'),
				),
			),
			'shipping_price' => array(
				'title' 		=> __( 'Shipping Price', 'pakkelabels-for-woocommerce'),
				'type' 			=> 'text',
				'description' 	=> __( 'Enter a cost (excl. tax) or sum, e.g. <code>10.00 * [qty]</code>.', 'woocommerce' ) . '<br/><br/>' . __( 'Use <code>[qty]</code> for the number of items, <br/><code>[cost]</code> for the total cost of items, and <code>[fee percent="10" min_fee="20" max_fee=""]</code> for percentage based fees.', 'woocommerce' ),
				'class'         => 'shipping_price',
				'default'		=> 0,
				'desc_tip'		=> true
			),
			'hide_shipping_method_if_outside_parameters' => array(
				'title'         => __( 'Hide if outside conditions', 'pakkelabels-for-woocommerce'),
				'type'          => 'checkbox',
				'class'         => 'hide_shipping_method_if_outside_parameters',
				'description'   => __( 'Mark this, to hide this shipping method, if the conditions under Differentiated Price Type not is fulfilled.', 'pakkelabels-for-woocommerce' ),
				'label'			=> ' ',
				'default'       => 0,
				'desc_tip'      => true
			),
			'enable_free_shipping' => array(
				'title'         => __( 'Enable Free Shipping', 'pakkelabels-for-woocommerce'),
				'type'          => 'select',
				'class'         =>  'enable_free_shipping',
				'default'       => 'taxable',
				'options'       => array(
					'No'        => __( 'No', 'pakkelabels-for-woocommerce'),
					'Yes'       => __( 'Yes', 'pakkelabels-for-woocommerce'),
				),
			),
			'free_shipping_total' => array(
				'title'         => __( 'Minimum Purchase For Free Shipping', 'pakkelabels-for-woocommerce'),
				'type'          => 'text',
				'class'         => 'free_shipping_total',
				'description'   => __( 'This control the minimum amount the customer will have to purchase (subtotal) for to get free shipping. <br/><br/><strong>This rule will overrule any differentiated price ranges if the condition is met.</strong>', 'pakkelabels-for-woocommerce' ),
				'default'       => 0,
				'desc_tip'      => true
			),
			'enable_free_shipping_with_coupon' => array(
				'title'         => __( 'Free shipping when a shipping coupon is used', 'pakkelabels-for-woocommerce'),
				'type'          => 'checkbox',
				'class'         => 'enable_free_shipping_with_coupon',
				'description'   => __( 'Check this to enable customers to enabled free shipping for this shipping method, when a shipping coupon is used.', 'pakkelabels-for-woocommerce' ),
				'label'			=> ' ',
				'default'       => 0,
				'desc_tip'      => true
			),
		);


		$shipping_classes = WC()->shipping()->get_shipping_classes();

		if ( !empty( $shipping_classes ) ) {
			$cost_desc = __( 'Enter a cost (excl. tax) or sum, e.g. <code>10.00 * [qty]</code>.', 'woocommerce' ) . '<br/><br/>' . __( 'Use <code>[qty]</code> for the number of items, <br/><code>[cost]</code> for the total cost of items, and <code>[fee percent="10" min_fee="20" max_fee=""]</code> for percentage based fees.', 'woocommerce' );

			$this->instance_form_fields['class_costs'] = array(
				'title'       => __( 'Shipping class costs', 'woocommerce' ),
				'type'        => 'title',
				'description' => sprintf( __( 'These costs can optionally be added based on the <a href="%s">product shipping class</a>.', 'woocommerce' ), admin_url( 'admin.php?page=wc-settings&tab=shipping&section=classes' ) ),
			);

			foreach ( $shipping_classes as $shipping_class ) {
				if ( ! isset( $shipping_class->term_id ) ) {
					continue;
				}
				$this->instance_form_fields[ 'class_cost_' . $shipping_class->term_id ] = array(
					/* translators: %s: shipping class name */
					'title'             => sprintf( __( '"%s" shipping class cost', 'woocommerce' ), esc_html( $shipping_class->name ) ),
					'type'              => 'text',
					'placeholder'       => __( 'N/A', 'woocommerce' ),
					'description'       => $cost_desc,
					'default'           => '',
					'desc_tip'          => true,
					'sanitize_callback' => array( $this, 'sanitize_cost' ),
				);
			}

			$this->instance_form_fields['no_class_cost'] = array(
				'title'             => __( 'No shipping class cost', 'woocommerce' ),
				'type'              => 'text',
				'placeholder'       => __( 'N/A', 'woocommerce' ),
				'description'       => $cost_desc,
				'default'           => '',
				'desc_tip'          => true,
				'sanitize_callback' => array( $this, 'sanitize_cost' ),
			);

			$this->instance_form_fields['type'] = array(
				'title'   => __( 'Calculation type', 'woocommerce' ),
				'type'    => 'select',
				'class'   => 'wc-enhanced-select',
				'default' => 'class',
				'options' => array(
					'class' => __( 'Per class: Charge shipping for each shipping class individually', 'woocommerce' ),
					'order' => __( 'Per order: Charge shipping for the most expensive shipping class', 'woocommerce' ),
				),
			);
		}

	}

	/**
	 * Get available shipping agents
	 *
	 * @return mixed|void
	 */
	public function getShippingAgents() {
		return ShippingMethodsController::getShippingAgents();
	}

	/**
	 * Calculate price
	 *
	 * @param       $sum
	 * @param array $args
	 *
	 * @return bool|int|mixed|null
	 */
	protected function evaluate_cost($sum, $args = array()) {
		include_once( WC()->plugin_path() . '/includes/libraries/class-wc-eval-math.php' );

		// Allow 3rd parties to process shipping cost arguments
		$args           = apply_filters('woocommerce_evaluate_shipping_cost_args', $args, $sum, $this);
		$locale         = localeconv();
		$decimals       = array( wc_get_price_decimal_separator(), $locale['decimal_point'], $locale['mon_decimal_point'], ',' );
		$this->fee_cost = $args['cost'];

		// Expand shortcodes
		add_shortcode( 'fee', array( $this, 'fee' ) );

		$sum = do_shortcode(
			str_replace(
				array(
					'[qty]',
					'[cost]',
				),
				array(
					$args['qty'],
					$args['cost'],
				),
				$sum
			)
		);

		remove_shortcode( 'fee', array( $this, 'fee' ) );

		// Remove whitespace from string
		$sum = preg_replace( '/\s+/', '', $sum );

		// Remove locale from string
		$sum = str_replace( $decimals, '.', $sum );

		// Trim invalid start/end characters
		$sum = rtrim( ltrim( $sum, "\t\n\r\0\x0B+*/" ), "\t\n\r\0\x0B+-*/" );

		// Do the math
		return $sum ? \WC_Eval_Math::evaluate( $sum ) : 0;
	}

	/**
	 * Work out fee (shortcode).
	 *
	 * @param  array $atts Attributes.
	 * @return string
	 */
	public function fee( $atts ) {
		$atts = shortcode_atts(
			array(
				'percent' => '',
				'min_fee' => '',
				'max_fee' => '',
			),
			$atts,
			'fee'
		);

		$calculated_fee = 0;

		if ( $atts['percent'] ) {
			$calculated_fee = $this->fee_cost * ( floatval( $atts['percent'] ) / 100 );
		}

		if ( $atts['min_fee'] && $calculated_fee < $atts['min_fee'] ) {
			$calculated_fee = $atts['min_fee'];
		}

		if ( $atts['max_fee'] && $calculated_fee > $atts['max_fee'] ) {
			$calculated_fee = $atts['max_fee'];
		}

		return $calculated_fee;
	}

	/**
	 * Set shipping rates
	 * @param array $package
	 */
	public function calculate_shipping($package = array()) {
		// Register the rate
		if($this->showShippingMethod()) {
			if($this->eligibleFreeShipping($package)) {
				$this->add_rate(array(
					'label' => $this->title . ' ' . apply_filters('shipmondo_free_label', __('(free)', 'pakkelabels-for-woocommerce')),
					'cost' => 0,
					'id' => $this->get_rate_id(),
					'package' => $package
				));
			} else {
				$rate = array(
					'label' => $this->title,
					'cost' => $this->getShippingPrice($package),
					'id' => $this->get_rate_id(),
					'package' => $package
				);
				$rate = $this->add_class_costs($package, $rate);
				$this->add_rate($rate);
			}
		}


	}

	/**
	 * Add shipping class cost
	 * @param array $package
	 * @param array $rate
	 * @return array $rate
	 */
	private function add_class_costs($package, $rate)
	{
		// Add shipping class costs.
		$shipping_classes = WC()->shipping->get_shipping_classes();

		if ( ! empty( $shipping_classes ) ) {
			$found_shipping_classes = $this->find_shipping_classes( $package );
			$highest_class_cost     = 0;

			foreach ( $found_shipping_classes as $shipping_class => $products ) {
				// Also handles BW compatibility when slugs were used instead of ids.
				$shipping_class_term = get_term_by( 'slug', $shipping_class, 'product_shipping_class' );
				$class_cost_string   = $shipping_class_term && $shipping_class_term->term_id ? $this->get_instance_option( 'class_cost_' . $shipping_class_term->term_id, '') : $this->get_instance_option( 'no_class_cost', '');

				if ( '' === $class_cost_string ) {
					continue;
				}

				$has_costs  = true;
				$class_cost = $this->evaluate_cost(
					$class_cost_string, array(
						'qty'  => array_sum( wp_list_pluck( $products, 'quantity' ) ),
						'cost' => array_sum( wp_list_pluck( $products, 'line_total' ) ),
					)
				);

				if ( 'class' === $this->type ) {
					$rate['cost'] += $class_cost;
				} else {
					$highest_class_cost = $class_cost > $highest_class_cost ? $class_cost : $highest_class_cost;
				}
			}

			if ( 'order' === $this->type && $highest_class_cost ) {
				$rate['cost'] += $highest_class_cost;
			}
		}
		return $rate;
	}

	/**
	 * Finds and returns shipping classes and the products with said class.
	 *
	 * @param mixed $package Package of items from cart.
	 * @return array
	 */
	public function find_shipping_classes( $package ) {
		$found_shipping_classes = array();

		foreach ( $package['contents'] as $item_id => $values ) {
			if ( $values['data']->needs_shipping() ) {
				$found_class = $values['data']->get_shipping_class();

				if ( ! isset( $found_shipping_classes[ $found_class ] ) ) {
					$found_shipping_classes[ $found_class ] = array();
				}

				$found_shipping_classes[ $found_class ][ $item_id ] = $values;
			}
		}

		return $found_shipping_classes;
	}

	/**
	 * Get price from settings
	 * @author Morning Train - Martin Schadegg Brønniche <ms@morningtrain.dk>
	 * @since 1.1.8
	 */
	public function getShippingPrice($package = array()) {
		$price = $this->get_instance_option('shipping_price', 0);

		if($this->get_instance_option('differentiated_price_type') == "Price") {
			$price_classes = get_option('Price_' . $this->instance_id);

			foreach($price_classes as $price_class) {
				if($price_class->minimum < $this->getCartTotal() && $this->getCartTotal() <= $price_class->maximum){
					$price = $price_class->shipping_price;
					break;
				}
				if($price_class->shipping_price > $price) {
					$price = $price_class->shipping_price;
				}
			}
		} else if($this->get_instance_option('differentiated_price_type') == "Weight") {
			$weight_total = $GLOBALS['woocommerce']->cart->cart_contents_weight;
			$weight_classes = get_option('Weight_' . $this->instance_id);
			$price = 0;

			foreach($weight_classes as $weight_class) {
				if($weight_class->minimum < $weight_total && $weight_total <= $weight_class->maximum){
					$price = $weight_class->shipping_price;
					break;
				}
				if($weight_class->shipping_price > $price) {
					$price = $weight_class->shipping_price;
				}
			}
		}

		return $this->evaluate_cost(
			$price,
			array(
				'qty'  => $this->get_package_item_qty($package),
				'cost' => $package['contents_cost'],
			)
		);
	}

	/**
	 * Get item qty of package
	 * @param $package
	 *
	 * @return int
	 */
	public function get_package_item_qty( $package ) {
		$total_quantity = 0;
		foreach ( $package['contents'] as $item_id => $values ) {
			if ( $values['quantity'] > 0 && $values['data']->needs_shipping() ) {
				$total_quantity += $values['quantity'];
			}
		}
		return $total_quantity;
	}

	/**
	 * Should shipping method be visible
	 *
	 * @author Morning Train - Martin Schadegg Brønniche <ms@morningtrain.dk>
	 * @since 1.1.8
	 */
	public function showShippingMethod() {
		if(empty($this->getShippingAgent()) || empty($this->getShippingProduct())) {
			return false;
		}

		if($this->get_instance_option('hide_shipping_method_if_outside_parameters', 'no') !== 'yes') {
			return true;
		}

		if($this->get_instance_option('differentiated_price_type') == 'Price') {
			$price_classes = get_option('Price_' . $this->instance_id);

			foreach($price_classes as $price_class) {
				if($price_class->minimum < $this->getCartTotal() && $this->getCartTotal() <= $price_class->maximum){
					return true;
				}
			}
		} else if($this->get_instance_option('differentiated_price_type') == 'Weight') {
			$weight_total = $GLOBALS['woocommerce']->cart->cart_contents_weight;
			$weight_classes = get_option('Weight_' . $this->instance_id);

			foreach($weight_classes as $weight_class) {
				if($weight_class->minimum < $weight_total && $weight_total <= $weight_class->maximum){
					return true;
				}
			}
		} else {
			return true;
		}

		return false;
	}

	/**
	 * get shipping settings
	 * @author Morning Train - Martin Schadegg Brønniche <ms@morningtrain.dk>
	 * @since 1.1.8
	 */
	public function getFreeShippingTotal() {
		if(is_null($this->free_shipping_total)) {
			$this->free_shipping_total = $this->get_instance_option('free_shipping_total');
		}

		return $this->free_shipping_total;
	}

	/**
	 * @author Morning Train - Martin Schadegg Brønniche <ms@morningtrain.dk>
	 * @since 1.1.8
	 */
	public function eligibleFreeShipping($package = array()) {
		if($this->get_instance_option('enable_free_shipping') === 'Yes' && $this->getCartTotal() >= $this->getFreeShippingTotal()) {
			return true;
		}

		if($this->get_instance_option('enable_free_shipping_with_coupon') === 'yes' && !empty($package['applied_coupons'])) {
			$woo_version =  LegacyController::getWooCommerceVersion();

			foreach((array) $package['applied_coupons'] as $coupon) {
				$obj = new \WC_Coupon($coupon);

				if($woo_version < '3.0.0') {
					return $obj->enable_free_shipping();
				} else {
					return $obj->get_free_shipping();
				}
			}
		}

		return false;
	}

	/**
	 * @author Morning Train - Martin Schadegg Brønniche <ms@morningtrain.dk>
	 * @since 1.1.8
	 */
	public function getCartTotal() {
		if(is_null($this->cart_total)) {
			$cart = $GLOBALS['woocommerce']->cart;

			$taxes = LegacyController::getWooCommerceVersion() < '3.2.0' ? $cart->taxes : $cart->get_cart_contents_taxes();

			if(wc_tax_enabled()) {
				$this->cart_total = $cart->cart_contents_total + array_sum($taxes);
			} else {
				if(LegacyController::checkWooCommerceVersion('3.2.0')) {
					$this->cart_total = WC()->cart->get_cart_contents_total();
				} else {
					$cart = $GLOBALS['woocommerce']->cart;
					$this->cart_total = $cart->cart_contents_total;
				}
			}

			// WPML Multicurrency support
			if(isset($GLOBALS['woocommerce_wpml']) && isset($GLOBALS['woocommerce_wpml']->multi_currency) && isset($GLOBALS['woocommerce_wpml']->multi_currency->prices)) {
				$this->cart_total = $GLOBALS['woocommerce_wpml']->multi_currency->prices->unconvert_price_amount($this->cart_total);
			}
		}

		return $this->cart_total;
	}

	/**
	 * Check if current method is chosen in shipping index
	 * @param     $method
	 * @param int $index
	 *
	 * @return bool
	 */
	public function isChosenShippingMethod($index = 0) {
		$chosen_methods = WC()->session->get('chosen_shipping_methods');

		return (isset($chosen_methods[$index]) && $chosen_methods[$index] === $this->get_rate_id());
	}

	/**
	 * Is shipping product service_point
	 * @return bool
	 */
	public function isServicePointDelivery() {
		return in_array($this->getShippingProduct(), array('service_point', 'pickup_point'));
	}

	/**
	 * Is shipping product business
	 * @return bool
	 */
	public function isBusiness() {
		return $this->getShippingProduct() === 'business';
	}

	/**
	 * Get chosen shipping product
	 *
	 * @return mixed
	 */
	public function getShippingProduct() {
		return $this->get_instance_option('shipping_product');
	}

	/**
	 * Get Chosen shipping agent
	 * @return mixed
	 */
	public function getShippingAgent() {
		return $this->get_instance_option('shipping_agent');
	}

	/**
	 * Display pickup point finder input
	 * @param int $index
	 */
	public function displayServicePointFinder($index = 0) {
		if(is_checkout()) {
			$option = SettingsController::getSelectionType();

			Plugin::getTemplate('service-point-selection.' . $option . '.selection-button', array('shipping_method' => $this, 'index' => $index));
		} else {
			echo '<br/><div class="shipping_pickup_cart">' . __('Pickup point is selected during checkout','pakkelabels-for-woocommerce') . '</div>';
		}
	}
}