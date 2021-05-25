<?php

/* Delivery based on categories */

/**
 * Filter shipping methods in the checkout
 */
add_filter( 'woocommerce_package_rates', 'wooelements_filter_shipping_methods', 10, 2 );
function wooelements_filter_shipping_methods( $rates, $package ) {
	// Find GLS PakkeShop shipping method
	$economy_shipping_method_key = FALSE;
	foreach ( $rates as $rate_key => $rate ) {
		if ( is_object( $rate ) && method_exists( $rate, 'get_label' ) && $rate->get_label() === "GLS PakkeShop" ) {
			$economy_shipping_method_key = $rate_key;
        }
	}

	// Go through all products and check their category
	if ( $economy_shipping_method_key !== FALSE ) {
		$bike_appliances_found = FALSE;
		foreach ( $package['contents'] as $key => $item ) {
			$categories = get_the_terms( $item['product_id'], 'product_cat' );

			if ( $categories && ! is_wp_error( $categories ) && is_array( $categories ) ) {
				foreach ( $categories as $category ) {
					if ( "Cykler" === $category->name || "Elcykler" == $category->name || "Specialcykler" == $category->name  ) {
						$bike_appliances_found = TRUE;
					}
				}
			}
		}

		// Bike appliances has been found, disable GLS shipping
		if ( $bike_appliances_found === TRUE ) {
            unset( $rates[$economy_shipping_method_key] );
		}
	}

	return $rates;
}