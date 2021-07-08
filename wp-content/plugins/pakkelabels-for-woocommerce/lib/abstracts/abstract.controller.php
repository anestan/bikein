<?php namespace ShipmondoForWooCommerce\Lib\Abstracts;

abstract class Controller {

	/**
	 * Controller constructor.
	 */
	public function __construct() {
		$this->registerActions();
		$this->registerFilters();
		$this->registerRoutes();

		if(is_admin()) {
			$this->registerActionsAdmin();
			$this->registerFiltersAdmin();
		}
	}

	/**
	 * Register Actions
	 */
	protected function registerActions() {

	}

	/**
	 * Register Filters
	 */
	protected function registerFilters() {

	}

	/**
	 * Register Routes
	 */
	protected function registerRoutes() {

	}

	/**
	 * Register Actions for admin area
	 */
	protected function registerActionsAdmin() {

	}

	/**
	 * Register actions for admin area
	 */
	protected function registerFiltersAdmin() {

	}
}
