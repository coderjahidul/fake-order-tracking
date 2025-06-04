<?php
class CDC_Loader {
    public function run() {
        require_once CDC_PLUGIN_DIR . '/includes/class-cdc-settings.php';
        require_once CDC_PLUGIN_DIR . '/includes/class-cdc-api.php';
        require_once CDC_PLUGIN_DIR . '/includes/class-cdc-checkout.php';
        require_once CDC_PLUGIN_DIR . '/includes/class-cdc-ajax.php';
        require_once CDC_PLUGIN_DIR . '/includes/class-cdc-order-meta.php';

        new CDC_Settings();
        new CDC_AJAX();
        new CDC_Checkout();
        new CDC_Order_Meta();
    }
}