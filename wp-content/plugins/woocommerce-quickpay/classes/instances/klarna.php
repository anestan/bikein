<?php

class WC_QuickPay_Klarna extends WC_QuickPay_Instance {

    public $main_settings = NULL;

    public function __construct() {
        parent::__construct();

        // Get gateway variables
        $this->id = 'klarna';

        $this->method_title = 'QuickPay - Klarna';

        $this->setup();

        $this->title = $this->s('title');
        $this->description = $this->s('description');

        add_filter( 'woocommerce_quickpay_cardtypelock_klarna', [ $this, 'filter_cardtypelock' ] );
    }


    /**
     * init_form_fields function.
     *
     * Initiates the plugin settings form fields
     *
     * @access public
     * @return array
     */
    public function init_form_fields()
    {
        $this->form_fields = [
            'enabled' => [
                'title' => __( 'Enable', 'woo-quickpay' ),
                'type' => 'checkbox',
                'label' => __( 'Enable Klarna payment', 'woo-quickpay' ),
                'default' => 'no'
            ],
            '_Shop_setup' => [
                'type' => 'title',
                'title' => __( 'Shop setup', 'woo-quickpay' ),
            ],
            'title' => [
                'title' => __( 'Title', 'woo-quickpay' ),
                'type' => 'text',
                'description' => __( 'This controls the title which the user sees during checkout.', 'woo-quickpay' ),
                'default' => __('Klarna', 'woo-quickpay')
            ],
            'description' => [
                'title' => __( 'Customer Message', 'woo-quickpay' ),
                'type' => 'textarea',
                'description' => __( 'This controls the description which the user sees during checkout.', 'woo-quickpay' ),
                'default' => __('Pay with Klarna', 'woo-quickpay')
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
    public function filter_cardtypelock( )
    {
        return 'klarna-payments';
    }
}
