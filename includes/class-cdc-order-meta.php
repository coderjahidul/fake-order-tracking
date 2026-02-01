<?php
class CDC_Plugin_Order_Meta
{
    public function __construct()
    {
        add_action('woocommerce_admin_order_data_after_order_details', [$this, 'show_dsr_data']);
    }

    public function show_dsr_data($order)
    {
        echo '<div class="order_data_column dsr-metrics">';
        echo '<h3>Customer Delivery Success Rate</h3>';
        echo '<div class="dsr-grid">';
        echo '<p><strong>Mobile:</strong> <span class="dsr_mobile-number">' . esc_html($order->get_meta('_dsr_mobile_number')) . '</span></p>';
        echo '<p><strong>Total Parcels:</strong> <span class="dsr_total_parcels">' . esc_html($order->get_meta('_dsr_total_parcels')) . '</span></p>';
        echo '<p><strong>Parcels Delivered:</strong> <span class="dsr_total_delivered">' . esc_html($order->get_meta('_dsr_total_delivered')) . '</span></p>';
        echo '<p><strong>Cancelled Parcels:</strong> <span class="dsr_total_cancel">' . esc_html($order->get_meta('_dsr_total_cancel')) . '</span></p>';
        // Check Again button
        echo '<button type="button" class="button check-dsr-again" data-order-id="' . esc_attr($order->get_id()) . '">Check Again</button>';
        echo '</div>';
        // show message
        echo '<div class="dsr_message"><span class="dsr_message_text"></span></div>';
        echo '</div>';
    }
}