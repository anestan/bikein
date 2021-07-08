<div class="shipmondo-dropdown-content">
    <div class="shipmondo-list-wrapper">
        <ul class="shipmondo-shoplist-ul">
            <?php
                foreach($service_points as $service_point) {
                    ?>
                    <li class="shipmondo-shop-list" data-id="<?php echo $service_point->number; ?>">
	                    <input type="hidden" class="input_shop_id" id="<?php echo 'shop_id_' . $service_point->number; ?>" name="<?php echo 'shop_id_' . $service_point->number; ?>" value="<?php echo 'ID: ' . strtoupper($service_point->agent) . '-' . trim($service_point->number); ?>">
	                    <input type="hidden" class="input_shop_name" id="<?php echo 'shop_name_' . $service_point->number; ?>" name="<?php echo 'shop_name_' . $service_point->number; ?>" value="<?php echo $service_point->company_name; ?>">
	                    <input type="hidden" class="input_shop_address" id="<?php echo 'shop_address_' . $service_point->number; ?>" name="<?php echo 'shop_address_' . $service_point->number; ?>" value="<?php echo $service_point->address; ?>">
	                    <input type="hidden" class="input_shop_zip" id="<?php echo 'shop_zip_' . $service_point->number; ?>" name="<?php echo 'shop_zip_' . $service_point->number; ?>" value="<?php echo $service_point->zipcode; ?>">
	                    <input type="hidden" class="input_shop_city" id="<?php echo 'shop_city_' . $service_point->number; ?>" name="<?php echo 'shop_city_' . $service_point->number; ?>" value="<?php echo $service_point->city; ?>">

	                    <img class="agent_icon" src="<?php echo \ShipmondoForWooCommerce\Plugin\Plugin::getImgURL('picker_icon_' . $agent . '.png', array('images'))?>">
                        <div class="shipmondo-service-point-info">
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