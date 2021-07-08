<div class="shipmondo-modal shipmondo-hidden" id="shipmondo-modal" tabindex="-1" role="dialog" aria-labelledby="<?php print __('packetshop window', 'pakkelabels-for-woocommerce'); ?>">
    <div class="shipmondo-modal-wrapper">
        <div class="shipmondo-loader-wrapper">
            <div class="shipmondo-loader"></div>
        </div>
        <div class="shipmondo-removable-content"></div>
        <div class="shipmondo-error">
            <?php \ShipmondoForWooCommerce\Plugin\Plugin::getTemplate('service-point-selection.modal.partials.close-button'); ?>
            <p><?php _e('Something went wrong, please try again!', 'pakkelabels-for-woocommerce'); ?></p>
            <button class="shipmondo-modal-close-button button alt"><?php _e('Close', 'pakkelabels-for-woocommerce'); ?></button>
        </div>
    </div>
</div>