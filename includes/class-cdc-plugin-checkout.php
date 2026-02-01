<?php
class CDC_Plugin_Checkout
{
    public function __construct()
    {
        add_action('woocommerce_checkout_create_order', [$this, 'attach_dsr_data_to_order'], 20, 2);
    }

    public function attach_dsr_data_to_order($order, $data)
    {
        if (isset($_POST['billing_phone'])) {
            $phone = sanitize_text_field($_POST['billing_phone']);

            // Call API
            $dsr = new CDC_Plugin_API();
            $dsr_response = $dsr->check_dsr($phone);

            if (is_array($dsr_response) && isset($dsr_response['mobile_number'])) {
                // Update WooCommerce order meta
                $order->update_meta_data('_dsr_mobile_number', $dsr_response['mobile_number']);
                $order->update_meta_data('_dsr_total_parcels', $dsr_response['total_parcels']);
                $order->update_meta_data('_dsr_total_delivered', $dsr_response['total_delivered']);
                $order->update_meta_data('_dsr_total_cancel', $dsr_response['total_cancel']);
            }

        }
    }
}