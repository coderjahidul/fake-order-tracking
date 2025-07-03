<?php
class CDC_Plugin_Loader {
    public function fake_order_tracking_run() {
        require_once CDC_PLUGIN_DIR_PATH . '/includes/class-cdc-plugin-settings.php';
        require_once CDC_PLUGIN_DIR_PATH . '/includes/class-cdc-plugin-api.php';
        require_once CDC_PLUGIN_DIR_PATH . '/includes/class-cdc-plugin-checkout.php';
        require_once CDC_PLUGIN_DIR_PATH . '/includes/class-cdc-plugin-ajax.php';
        require_once CDC_PLUGIN_DIR_PATH . '/includes/class-cdc-order-meta.php';

        new CDC_Plugin_Settings();
        new CDC_Plugin_AJAX();
        new CDC_Plugin_Checkout();
        new CDC_Plugin_Order_Meta();
    }
}