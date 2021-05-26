<?php

/**
 * Simple checkout field addition to CVR.
 *
 */
function woocommerce_add_checkout_fields( $fields ) {
    if (function_exists('is_checkout') && is_checkout()) {

        $fields['billing_vat'] = array(
            'label'        => __( 'CVR nr.' ),
            'type'        => 'text',
            'class'        => array( 'form-row-wide' ),
            'priority'     => 25,
            'required'     => false,
            'custom_attributes' => array('vat-button' => 'Hent oplysninger fra CVR register'),
            'input_class' => array('append-button'),
        );
        return $fields;
    }
}
add_filter( 'woocommerce_billing_fields', 'woocommerce_add_checkout_fields' );

/* WooCommerce - Reorder fields */
function country_reorder( $checkout_fields ) {
    $checkout_fields['billing']['billing_country']['priority'] = 50;
    $checkout_fields['billing']['billing_postcode']['priority'] = 70;
    $checkout_fields['billing']['billing_city']['priority'] = 80;
	return $checkout_fields;
}
add_filter( 'woocommerce_checkout_fields', 'country_reorder' );
