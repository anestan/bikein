<?php
	/**
	 * @var $shipping_method \ShipmondoForWooCommerce\Plugin\ShippingMethods\Shipmondo
	 * @var $index string
	 */

	use ShipmondoForWooCommerce\Plugin\Controllers\ServicePointsController;
	use ShipmondoForWooCommerce\Plugin\Plugin;
?>
<div class="shipmondo-shipping-field-wrap" data-shipping_agent="<?php echo $shipping_method->getShippingAgent(); ?>" data-shipping_index="<?php echo $index; ?>">
    <div class="shipmondo-clearfix shipmondo_shipping_button">
        <div class="shipmondo_stores">
            <div>
                <input type="text" id="shipmondo_zipcode_field_<?php echo $index; ?>" name="shipmondo_zipcode[<?php echo $index; ?>]" class="input shipmondo_zipcode" placeholder="<?php echo __('Zip code', 'pakkelabels-for-woocommerce'); ?>" data-current="<?php echo ServicePointsController::getCurrentSelection('zip', $shipping_method->getShippingAgent(), $index); ?>">
                <script>
                    jQuery(document).trigger('shipmondo_zipcode_field_loaded');
                </script>
            </div>
            <div>
	            <div class="shipmondo_dropdown_button">
		            <input disabled type="button" id="shipmondo_find_shop_btn_<?php echo $index; ?>" name="shipmondo_find_shop[<?php echo $index; ?>]" class="button alt shipmondo_select_button" value="<?php echo __('Find nearest pickup point', 'pakkelabels-for-woocommerce' ); ?>" data-selection-type="dropdown">
	            </div>
                <div class="shipmondo_service_point_selector_dropdown_container">
		            <div class="shipmondo_service_point_selector_dropdown shipmondo-hidden">
			            <div class="shipmondo-dropdown-content-section">
				            <div class="shipmondo-loader-wrapper">
					            <div class="shipmondo-loader"></div>
				            </div>
				            <div class="shipmondo-removable-content"></div>
				            <div class="shipmondo-error">
					            <?php Plugin::getTemplate('service-point-selection.modal.partials.close-button'); ?>
					            <p><?php _e('Something went wrong, please try again!', 'pakkelabels-for-woocommerce'); ?></p>
					            <button class="shipmondo-modal-close-button button alt"><?php _e('Close', 'pakkelabels-for-woocommerce'); ?></button>
				            </div>
			            </div>
			            <div class="shipmondo-dropdown-footer">
				            <?php echo __('Powered by Shipmondo', 'pakkelabels-for-woocommerce') ?>
			            </div>
		            </div>
	            </div>
            </div>
        </div>
    </div>
    <div class="hidden_chosen_shop">
        <input type="hidden" name="shipmondo[<?php echo $index; ?>]" value="<?php echo ServicePointsController::getCurrentSelection('id', $shipping_method->getShippingAgent(), $index); ?>">
        <input type="hidden" name="shop_name[<?php echo $index; ?>]" value="<?php echo ServicePointsController::getCurrentSelection('name', $shipping_method->getShippingAgent(), $index); ?>">
        <input type="hidden" name="shop_address[<?php echo $index; ?>]" value="<?php echo ServicePointsController::getCurrentSelection('address', $shipping_method->getShippingAgent(), $index); ?>">
        <input type="hidden" name="shop_zip[<?php echo $index; ?>]" value="<?php echo ServicePointsController::getCurrentSelection('zip', $shipping_method->getShippingAgent(), $index); ?>">
        <input type="hidden" name="shop_city[<?php echo $index; ?>]" value="<?php echo ServicePointsController::getCurrentSelection('city', $shipping_method->getShippingAgent(), $index); ?>">
        <input type="hidden" name="shop_ID[<?php echo $index; ?>]" value="<?php echo ServicePointsController::getCurrentSelection('id_string', $shipping_method->getShippingAgent(), $index); ?>">
    </div>
    <div class="selected_shop_context shipmondo-clearfix<?php echo ServicePointsController::isCurrentSelection($shipping_method->getShippingAgent(), $index) ? ' active' : ''; ?>">
        <div class="shipmondo-shop-header"><?php echo __('Currently chosen pickup point:', 'pakkelabels-for-woocommerce'); ?></div>
        <div class="shipmondo-shop-name"><?php echo ServicePointsController::getCurrentSelection('name', $shipping_method->getShippingAgent(), $index); ?></div>
        <div class="shipmondo-shop-address"><?php echo ServicePointsController::getCurrentSelection('address', $shipping_method->getShippingAgent(), $index); ?></div>
        <div class="shipmondo-shop-zip-and-city"><?php echo ServicePointsController::getCurrentSelection('zip_city', $shipping_method->getShippingAgent(), $index); ?></div>
    </div>
	<div class="shipmondo_zipcode_error_text<?php echo ServicePointsController::isCurrentSelection($shipping_method->getShippingAgent(), $index) ? '' : ' active'; ?>">
        <?php echo __('Please enter a zip code to select a pickup point','pakkelabels-for-woocommerce'); ?>
    </div>
</div>