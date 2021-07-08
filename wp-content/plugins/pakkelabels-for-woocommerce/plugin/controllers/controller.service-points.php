<?php namespace ShipmondoForWooCommerce\Plugin\Controllers;

use ShipmondoForWooCommerce\Lib\Abstracts\Controller;
use ShipmondoForWooCommerce\Lib\Tools\Loader;
use ShipmondoForWooCommerce\Plugin\ShipmondoAPI;
use ShipmondoForWooCommerce\Plugin\Plugin;
use ShipmondoForWooCommerce\Plugin\ShippingMethods\Shipmondo;

class ServicePointsController extends Controller {

    /*
	 * @author Morning Train - Martin Schadegg Brønniche <ms@morningtrain.dk>
	 * @since 1.2.0
	 */
    protected function registerActions() {
        Loader::addAction('wp_enqueue_scripts', $this, 'enqueueScripts');
        Loader::addAction('wp_footer', $this, 'includeModalHTML');
        Loader::addAction('wp_ajax_shipmondo_get_service_points', $this, 'getServicePointsSelectionHTML');
        Loader::addAction('wp_ajax_nopriv_shipmondo_get_service_points', $this, 'getServicePointsSelectionHTML');
	    Loader::addAction('wp_ajax_shipmondo_set_selection_session', $this, 'setSelectionSession');
	    Loader::addAction('wp_ajax_nopriv_shipmondo_set_selection_session', $this, 'setSelectionSession');

	    Loader::addAction('woocommerce_after_shipping_rate', static::class, 'displayServicePointFinder', 10, 2);
	    Loader::addAction('woocommerce_checkout_create_order_shipping_item', static::class, 'updateOrderMeta', 10, 4);

		Loader::addAction('woocommerce_checkout_process', static::class, 'validateServicePointSelection');
    }

	/**
	 * Set session with current pickup point selection

	 * @author Morning Train - Martin Schadegg Brønniche <ms@morningtrain.dk>
	 * @since 2.2.0
	 */
    public function setSelectionSession() {
    	$session = WC()->session->get('shipmondo_current_selection');

    	$session[$_POST['shipping_index']] = array(
    		'agent' => $_POST['agent'],
    		'selection' => $_POST['selection']
	    );

    	WC()->session->set('shipmondo_current_selection', $session);

    	exit();
    }

    /*
     * Enqueue Scripts and styling if on checkout page
	 * @author Morning Train - Martin Schadegg Brønniche <ms@morningtrain.dk>
	 * @since 1.2.0
	 */
    public function enqueueScripts() {
        if($this->isCheckout()) {
            Plugin::addScript('https://maps.googleapis.com/maps/api/js?key=' . SettingsController::getGoogleMapsAPIKey(), array(), true, 'js', null);
            Plugin::addScript('shipmondo-service-point');
            Plugin::addStyle('shipmondo-service-point');
            Plugin::localizeScript('shipmondo-service-point', 'shipmondo', array(
                'ajax_url' => admin_url('admin-ajax.php'),
                'gls_icon_url' => Plugin::getFileURL('picker_icon_gls.png', array('images')),
                'bring_icon_url' => Plugin::getFileURL('picker_icon_bring.png', array('images')),
                'dao_icon_url' => Plugin::getFileURL('picker_icon_dao.png', array('images')),
                'pdk_icon_url' => Plugin::getFileURL('picker_icon_pdk.png', array('images')),
                'select_shop_text' => __('Choose pickup point', 'pakkelabels-for-woocommerce')
            ));
        }
    }

    /*
     * Include modal box in footer when on the checkout page
	 * @author Morning Train - Martin Schadegg Brønniche <ms@morningtrain.dk>
	 * @since 1.2.0
	 */
    public function includeModalHTML() {
        if($this->isCheckout()) {
	       $option = SettingsController::getSelectionType();
        	if($option == 'modal') {
		        Plugin::getTemplate('service-point-selection.modal.modal');
	        }
        }
    }

    /*
     * Check if is checkout and not payment page and order recieved page
     */
    private function isCheckout() {
    	return is_checkout() && !is_wc_endpoint_url('order-received') && !is_wc_endpoint_url('order-pay');
    }

    /*
     * Return HTML for ServicePoint selection
     * @author Morning Train - Martin Schadegg Brønniche <ms@morningtrain.dk>
	 * @since 1.2.0
     */
    public function getServicePointsSelectionHTML() {
        $agent = $_POST['agent'];
        $zipcode = $_POST['zipcode'];
        $country = $_POST['country'];
        $selection_type = $_POST['selection_type'];

        $service_points = ShipmondoAPI::getServicePoints($agent, $zipcode, $country);

        if (is_wp_error($service_points)) {
            wp_send_json_error(array(
                'error' => 'shipmondo_api_error',
                'html' => Plugin::getTemplate('service-point-selection.' . $selection_type . '.error', array(
                    'error' => __('Something went wrong, please try again!', 'pakkelabels-for-woocommerce')
                ), false)
            ));
        } elseif (!empty($service_points)) {
            Plugin::getTemplate('service-point-selection.' . $selection_type . '.content', array('service_points' => $service_points, 'service_points_number' => count($service_points), 'agent' => $agent));
        } else {
            Plugin::getTemplate('service-point-selection.' . $selection_type . '.error', array(
                'error' => __('No pickup points found, please try another zip code', 'pakkelabels-for-woocommerce')
            ));
        }

        die();
    }

	/**
	 * Get current selection
	 *
	 * @param      $field_name
	 * @param null $agent
	 *
	 * @return string
	 */
    public static function getCurrentSelection($field_name, $agent, $index = 0, $default = '') {
    	$current_selection = WC()->session->get('shipmondo_current_selection', array());

    	if(!isset($current_selection[$index]) || !static::isCurrentSelection($agent, $index)) {
    		return $default;
	    }

    	if(isset($current_selection[$index]['selection'][$field_name])) {
    		return $current_selection[$index]['selection'][$field_name];
	    }

    	if($field_name == 'zip_city') {
    		$parts = array(
			    static::getCurrentSelection('zip', $agent, $index),
			    static::getCurrentSelection('city', $agent, $index)
		    );
    		return implode(', ', $parts);
	    }

    	return '';
    }

	/**
	 * Is current selection
	 * @return bool
	 */
    public static function isCurrentSelection($agent, $index = 0) {
	    $current_selection = WC()->session->get('shipmondo_current_selection', array());

	    if( !isset($current_selection[$index]['agent']) ||
		    $current_selection[$index]['agent'] !== $agent ||
		    !isset($current_selection[$index]['selection'])) {
		    return false;
	    }

	    $required_fields = array(
	    	'id',
	        'name',
		    'address',
		    'zip',
		    'city',
		    'id_string'
	    );

	    foreach($required_fields as $field) {
	    	if(!isset($current_selection[$index]['selection'][$field])) {
	    		return false;
		    }
	    }

	    return true;
    }

	/**
	 * Display pickup point finder if ServicePoint is chosen
	 * @param $rate
	 * @param $index
	 */
	public static function displayServicePointFinder($rate, $index) {
    	$chosen_shipping_method = ShippingMethodsController::getChosenShippingMethodForPackage($index);

    	if(static::isShippingMethodServicePointDelivery($chosen_shipping_method) && $chosen_shipping_method->get_rate_id() == $rate->get_id()) {
    		$chosen_shipping_method->displayServicePointFinder($index);
	    }
	}

	/**
	 * Check if shipping method is shipmondo and is pickup point
	 * @param $shipping_method
	 *
	 * @return bool
	 */
	public static function isShippingMethodServicePointDelivery($shipping_method) {
		return $shipping_method !== null && is_a($shipping_method, Shipmondo::class) && $shipping_method->isServicePointDelivery();
	}



	/**
	 * Update order meta and shipping address with pickup point
	 * @param $item
	 * @param $package_key
	 * @param $package
	 * @param $order
	 */
	public static function updateOrderMeta($item, $package_key, $package, $order) {
		$shipping_method = ShippingMethodsController::getChosenShippingMethodForPackage($package_key);

		if($shipping_method === null || !is_a($shipping_method, Shipmondo::class) || !$shipping_method->isServicePointDelivery()) {
			return;
		}

		$shipping_info = array(
			'first_name' => (!empty($order->get_shipping_first_name()) ? $order->get_shipping_first_name() : $order->get_billing_first_name()),
			'last_name' => (!empty($order->get_shipping_last_name()) ? $order->get_shipping_last_name() : $order->get_billing_last_name()),
			'company' => (!empty($_POST['shop_name'][$package_key]) ? $_POST['shop_name'][$package_key] : ServicePointsController::getCurrentSelection('name', $shipping_method->getShippingAgent(), $package_key)),
			'address_1' => (!empty($_POST['shop_address'][$package_key]) ? $_POST['shop_address'][$package_key] : ServicePointsController::getCurrentSelection('address', $shipping_method->getShippingAgent(), $package_key)),
			'address_2' => (!empty($_POST['shop_ID'][$package_key]) ? $_POST['shop_ID'][$package_key] : ServicePointsController::getCurrentSelection('id_string', $shipping_method->getShippingAgent(), $package_key)),
			'city' => (!empty($_POST['shop_city'][$package_key]) ? $_POST['shop_city'][$package_key] : ServicePointsController::getCurrentSelection('city', $shipping_method->getShippingAgent(), $package_key)),
			'postcode' => (!empty($_POST['shop_zip'][$package_key]) ? $_POST['shop_zip'][$package_key] : ServicePointsController::getCurrentSelection('zip', $shipping_method->getShippingAgent(), $package_key)),
		);

		// Update shipping info
		$order->set_address($shipping_info, 'shipping');

		$order->update_meta_data(__('Pickup point', 'pakkelabels-for-woocommerce'), (!empty($_POST['shipmondo'][$package_key]) ? $_POST['shipmondo'][$package_key] : ServicePointsController::getCurrentSelection('id', $shipping_method->getShippingAgent(), $package_key)));

	}

	public static function validateServicePointSelection() {
		if(!WC()->cart->needs_shipping()) {
			return;
		}

		// Fix problem with WooCommerce Subscriptions
		WC()->cart->calculate_totals();

		// Get WC Shipping Packages
		foreach(WC()->shipping()->get_packages() as $package_key => $package) {
			static::validateServicePointSelectionForPackage($package_key);
		}

		// WooCommerce get WC Subscriptions cart packages
		if(isset(WC()->cart->recurring_carts)) {
			foreach(WC()->cart->recurring_carts as $cart_key => $cart) {
				foreach($cart->get_shipping_packages() as $package_key => $package) {
					$key = "{$cart_key}_{$package_key}";

					static::validateServicePointSelectionForPackage($key);
				}
			}
		}
	}

	public static function validateServicePointSelectionForPackage($package_key) {
		$chosen_shipping_method = ShippingMethodsController::getChosenShippingMethodForPackage($package_key);

		if(static::isShippingMethodServicePointDelivery($chosen_shipping_method)) {
			if((empty($_POST['shipmondo']) || empty($_POST['shipmondo'][$package_key])) && !ServicePointsController::isCurrentSelection($chosen_shipping_method->getShippingAgent(), $package_key)) {
				wc_add_notice(__('Please select a pickup point before placing your order.', 'pakkelabels-for-woocommerce'), 'error');
			}
		}
	}
}