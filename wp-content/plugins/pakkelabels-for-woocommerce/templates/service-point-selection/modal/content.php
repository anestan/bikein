<div class="shipmondo-modal-content">
    <?php \ShipmondoForWooCommerce\Plugin\Plugin::getTemplate('service-point-selection.modal.partials.close-button'); ?>
    <div class="shipmondo-modal-header">
        <h4><?php echo __('Choose pickup point', 'pakkelabels-for-woocommerce') ?></h4>
        <p class="shipmondo-pickoup-point-counter" id="shipmondo-service-point-counter"><?php echo sprintf(_n('%s pickup point found', '%s pickup points found', $service_points_number,'pakkelabels-for-woocommerce'), $service_points_number); ?></p>
    </div>
    <div class="shipmondo-modal-body">
        <div id="shipmondo-map-wrapper">
            <div id="shipmondo-map"></div>
            <input type="hidden" name="shipmondo_service_points_json" value='<?php echo htmlentities(json_encode($service_points), ENT_QUOTES, 'UTF-8'); ?>'>
            <script>
                jQuery(document).trigger('shipmondo_service_point_modal_loaded');
            </script>
        </div>
        <div class="shipmondo-list-wrapper">
            <ul class="shipmondo-shoplist-ul">
                <?php
                    foreach($service_points as $service_point) {
                        ?>
                        <li class="shipmondo-shop-list" data-id="<?php echo $service_point->number; ?>">
                            <div class="shipmondo-service-point-info">
	                            <input type="hidden" class="input_shop_id" id="<?php echo 'shop_id_' . $service_point->number; ?>" name="<?php echo 'shop_id_' . $service_point->number; ?>" value="<?php echo 'ID: ' . strtoupper($service_point->agent) . '-' . trim($service_point->number); ?>">
	                            <input type="hidden" class="input_shop_name" id="<?php echo 'shop_name_' . $service_point->number; ?>" name="<?php echo 'shop_name_' . $service_point->number; ?>" value="<?php echo $service_point->company_name; ?>">
	                            <input type="hidden" class="input_shop_address" id="<?php echo 'shop_address_' . $service_point->number; ?>" name="<?php echo 'shop_address_' . $service_point->number; ?>" value="<?php echo $service_point->address; ?>">
	                            <input type="hidden" class="input_shop_zip" id="<?php echo 'shop_zip_' . $service_point->number; ?>" name="<?php echo 'shop_zip_' . $service_point->number; ?>" value="<?php echo $service_point->zipcode; ?>">
	                            <input type="hidden" class="input_shop_city" id="<?php echo 'shop_city_' . $service_point->number; ?>" name="<?php echo 'shop_city_' . $service_point->number; ?>" value="<?php echo $service_point->city; ?>">

	                            <div class="shipmondo-radio-button"></div>
                                <div class="shipmondo-service-point-name"><?php echo $service_point->company_name; ?></div>
                                <div class="shipmondo-service-point-address"><?php echo $service_point->address; ?></div>
                                <div class="shipmondo-service-point-zipcode-city">
                                    <span class="shipmondo-service-point-zipcode"><?php echo $service_point->zipcode; ?></span>, <span class="shipmondo-service-point-city"><?php echo $service_point->city; ?></span>
                                </div>
                            </div>
                        </li>
                        <?php
                    }
                ?>
            </ul>
        </div>
    </div>
    <div class="shipmondo-modal-footer">
        <?php echo __('Powered by Shipmondo', 'pakkelabels-for-woocommerce') ?>
    </div>
</div>
<div class="shipmondo-modal-checkmark">
    <svg class="shipmondo-checkmark" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 52 52"><circle class="shipmondo-checkmark_circle" cx="26" cy="26" r="25" fill="none"/><path class="shipmondo-checkmark_check" fill="none" d="M14.1 27.2l7.1 7.2 16.7-16.8"/></svg>
</div>