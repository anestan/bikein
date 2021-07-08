<?php namespace ShipmondoForWooCommerce\Plugin\Controllers;

use ShipmondoForWooCommerce\Lib\Abstracts\Controller;
use ShipmondoForWooCommerce\Lib\Tools\Loader;
use ShipmondoForWooCommerce\Plugin\Plugin;

class MigrationsController extends Controller {

	private $backup_id;

	protected function registerActions() {
		Loader::addAction('init', $this, 'maybeMigrate');
	}

	/**
	 * Migrate if migration version is less than the one in WordPress options.
	 */
	public function maybeMigrate() {
		if(is_Admin() && version_compare(Plugin::getVersion(), get_option('shipmondo_migration_version', '0.0.0'), '>')) {
			$this->migrate(get_option('shipmondo_migration_version', '0.0.0'));
		}
	}

	/**
	 * Migrate
	 */
	private function migrate($since_version) {
		// Since v. 3.0.0
		if(version_compare('1.0.7', $since_version, '>')) {
			$this->migrateDeliveryOptions300();
			$this->migrateOrderItemMeta300();
			$this->migratePluginOptions300();
			$this->migrateUserMeta300();

			update_option('shipmondo_migration_version', '1.0.7', true); // autoload for performance
			return; // Return to minimize risk of timeout
		}

		if(version_compare('4.0.0', $since_version, '>')) {
			$this->migrateDeliveryOptions400();

			update_option('shipmondo_migration_version', '4.0.0', true); // autoload for performance
			return; // Return to minimize risk of timeout
		}

		update_option('shipmondo_migration_version', Plugin::getVersion(), true); // if nothing to migrate set version to current version to avoid call to this function
	}

	private function migrateDeliveryOptions400() {
		global $wpdb;

		$legacy_shipping_method_names = array(
			'shipmondo_shipping_gls_business' => array(
				'shipping_agent' => 'gls',
				'shipping_product' => 'business',
				'title' => __('GLS Business', 'pakkelabels-for-woocommerce')
			),
			'shipmondo_shipping_gls_private' => array(
				'shipping_agent' => 'gls',
				'shipping_product' => 'private',
				'title' => __('GLS Private', 'pakkelabels-for-woocommerce')
			),
			'shipmondo_shipping_gls' => array(
				'shipping_agent' => 'gls',
				'shipping_product' => 'service_point',
				'title' => __('GLS Pickup Point', 'pakkelabels-for-woocommerce')
			),
			'shipmondo_shipping_Bring_business' => array(
				'shipping_agent' => 'bring',
				'shipping_product' => 'business',
				'title' => __('Bring Business', 'pakkelabels-for-woocommerce')
			),
			'shipmondo_shipping_bring_private' => array(
				'shipping_agent' => 'bring',
				'shipping_product' => 'private',
				'title' => __('Bring - Evening delivery to private', 'pakkelabels-for-woocommerce')
			),
			'shipmondo_shipping_bring' => array(
				'shipping_agent' => 'bring',
				'shipping_product' => 'service_point',
				'title' => __('Bring - Optional pickup location', 'pakkelabels-for-woocommerce')
			),
			'shipmondo_shipping_dao_direct' => array(
				'shipping_agent' => 'dao',
				'shipping_product' => 'private',
				'title' => __('DAO Direct', 'pakkelabels-for-woocommerce')
			),
			'shipmondo_shipping_dao' => array(
				'shipping_agent' => 'dao',
				'shipping_product' => 'service_point',
				'title' => __('DAO Pickup Point', 'pakkelabels-for-woocommerce')
			),
			'shipmondo_shipping_postnord_business' => array(
				'shipping_agent' => 'pdk',
				'shipping_product' => 'business',
				'title' => __('PostNord Business', 'pakkelabels-for-woocommerce')
			),
			'shipmondo_shipping_postnord_private' => array(
				'shipping_agent' => 'pdk',
				'shipping_product' => 'private',
				'title' => __('PostNord Private', 'pakkelabels-for-woocommerce')
			),
			'shipmondo_shipping_pdk' => array(
				'shipping_agent' => 'pdk',
				'shipping_product' => 'service_point',
				'title' => __('PostNord Pickup Point', 'pakkelabels-for-woocommerce')
			),
			'shipmondo_shipping_custom' => array(
				'shipping_agent' => 'other',
				'shipping_product' => 'private',
				'title' => __('Other', 'pakkelabels-for-woocommerce')
			)
		);

		// Get all shipping options and add agent and product
		foreach($legacy_shipping_method_names as $key => $new_settings) {
			$sql = "SELECT * FROM {$wpdb->prefix}woocommerce_shipping_zone_methods WHERE method_id = '{$key}'";
			$data = $wpdb->get_results($sql);

			if(!empty($data) && !is_wp_error($data)) {
				$this->backupData($data, "{$wpdb->prefix}woocommerce_shipping_zone_methods", $key, 'method');

				foreach($data as $original) {
					$option_name = "woocommerce_{$key}_{$original->instance_id}_settings";
					$new_option_name = "woocommerce_shipmondo_{$original->instance_id}_settings";
					$option_value = get_option($option_name);

					if(!empty($option_value) && !is_wp_error($option_value)) {
						$this->backupData($option_value, $wpdb->options, $option_name, 'option_value');

						$new_option_value = serialize(array_merge($new_settings, (array) $option_value));

						$_sql = $wpdb->prepare("UPDATE {$wpdb->options} SET option_name = %s, option_value = %s WHERE option_name = %s", array($new_option_name, $new_option_value, $option_name));

						$wpdb->query($_sql);
					} else {
						update_option($new_option_name, $new_settings, true);
					}
				}

				$update_sql = $wpdb->prepare("UPDATE {$wpdb->prefix}woocommerce_shipping_zone_methods SET method_id = %s WHERE method_id = %s", array('shipmondo', $key));

				$wpdb->query($update_sql);
			}
		}
	}

	/**
	 * Migrate delivery options
	 *
	 * @since 3.0.0
	 */
	private function migrateDeliveryOptions300() {
		// Shipping Settings
		$this->replaceInDB('options', 'option_name', 'woocommerce_pakkelabels_shipping_', 'woocommerce_shipmondo_shipping_');

		// Shipping zone settings
		$this->replaceInDB('woocommerce_shipping_zone_methods', 'method_id', 'pakkelabels_shipping_', 'shipmondo_shipping_');
	}

	/**
	 * Migrate plugin options
	 *
	 * @since 3.0.0
	 */
	private function migratePluginOptions300() {
		// Pakkelabel_settings
		$this->replaceInDB('options', 'option_value', 'Pakkelabel_', 'shipmondo_', "option_name = 'Pakkelabel_settings'");
		$this->replaceInDB('options', 'option_value', 'pakkelabel_', 'shipmondo_', "option_name = 'Pakkelabel_settings'");
		$this->replaceInDB('options', 'option_name', 'Pakkelabel_', 'shipmondo_', "option_name = 'Pakkelabel_settings'");
	}

	/**
	 * Migrate user options - shipping_method
	 *
	 * @since 3.0.0
	 */
	private function migrateUserMeta300() {
		$this->replaceInDB('usermeta', 'meta_value', 'pakkelabels_', 'shipmondo_', "meta_key = 'shipping_method'");
	}

	/**
	 * Migrate order item meta
	 *
	 * @since 3.0.0
	 */
	private function migrateOrderItemMeta300() {
		$this->replaceInDB('woocommerce_order_itemmeta', 'meta_value', 'pakkelabels_shipping_', 'shipmondo_shipping_', "meta_key = 'method_id'");
	}

	/**
	 * Replace in WP
	 * @param      $table
	 * @param      $field
	 * @param      $search
	 * @param      $replace
	 * @param null $where
	 */
	private function replaceInDB($table, $field, $search, $replace, $where = '') {
		// Get data
		global $wpdb;

		if(!empty($where)) {
			$where .= " AND ";
		}

		$where .= "$field LIKE '%$search%'";

		$data = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}$table WHERE $where");

		$primary_key = $wpdb->get_row("SHOW KEYS FROM {$wpdb->prefix}$table WHERE Key_name = 'PRIMARY'");
		if(isset($primary_key->Column_name)) {
			$primary_key = $primary_key->Column_name;
		}

		// backup
		$this->backupData($data, $table, $field, $search);

		// replace
		foreach((array) $data as $original) {
			if(isset($original->$field) && isset($original->$primary_key)) {
				$new_value = $this->searchReplace($original->$field, $search, $replace);

				$sql = $wpdb->prepare("UPDATE {$wpdb->prefix}$table SET $field = %s WHERE $primary_key = {$original->{$primary_key}}", array($new_value));

				$wpdb->query($sql);
			}
		}

		return;
	}

	private function searchReplace($data, $search, $replace, $serialize = false) {
		try {
			if(is_string($data) && is_serialized($data) && !is_serialized_string($data) && ($unserialized = @unserialize($data)) !== false) {
				$data = $this->searchReplace($unserialized, $search, $replace, true);
			} elseif(is_array($data)) {
				$_tmp = array();
				foreach($data as $key => $value) {
					$_tmp[str_replace($search, $replace, $key)] = $this->searchReplace($value, $search, $replace);
				}
				$data = $_tmp;
				unset($_tmp);
			} elseif(is_object($data)) {
				$_tmp = $data;
				$props = get_object_vars($data);
				foreach($props as $key => $value) {
					$key = str_replace($search, $replace, $key);
					$_tmp->{$key} = $this->searchReplace($value, $search, $replace);
				}
			} elseif(is_serialized_string($data) && is_serialized($data)) {
				if(($data = @unserialize($data)) !== false) {
					$data = str_replace($search, $replace, $data);
					$data = serialize($data);
				}
			} else {
				if(is_string($data)) {
					$data = str_replace($search, $replace, $data);
				}
			}

			if($serialize) {
				return serialize($data);
			}
		} catch(\Exception $error) {
			error_log($error->getMessage());
		}

		return $data;
	}

	/**
	 * Backup data we replace - just in case...
	 * @param $table
	 * @param $field
	 * @param $search
	 * @param $where
	 */
	private function backupData($data, $table, $field, $search) {
		$backup = fopen($this->getBackupFilePath("{$table}_{$field}_{$search}"), 'w');
		fwrite($backup, "<?php exit('No Access');?>" . PHP_EOL); // For security reasons
		fwrite($backup, json_encode($data));
		fclose($backup);
	}

	/**
	 *
	 * @param $filename
	 *
	 * @return string
	 */
	private function getBackupFilePath($filename) {
		$parts = array(
			ABSPATH,
			'wp-content',
			'shipmondo',
			'migrate',
			'backup',
			$this->getBackupID(),
		);

		$path = trailingslashit(join('/', array_filter($parts)));

		if(!is_dir($path)) {
			mkdir($path, 0770, true);
		}

		$filename = $filename . '.php';

		return $path . $filename;

	}

	/**
	 * Get current backup ID or generate new
	 * @return string
	 */
	private function getBackupID() {
		if(!isset($this->backup_id)) {
			$this->backup_id = uniqid();
		}

		return $this->backup_id;
	}
}