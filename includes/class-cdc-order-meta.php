<?php 
class CDC_Order_Meta {
    public function __construct() {
        add_action( 'woocommerce_admin_order_data_after_order_details', [ $this, 'show_dsr_data' ] );
    }

    public function show_dsr_data( $order ) {
        echo '<div class="order_data_column dsr-metrics">';
        echo '<h3>Delivery Success Rate</h3>';
        echo '<div class="dsr-grid">';
        echo '<p><strong>Mobile:</strong> ' . esc_html( $order->get_meta( '_dsr_mobile_number' ) ) . '</p>';
        echo '<p><strong>Total Parcels:</strong> ' . esc_html( $order->get_meta( '_dsr_total_parcels' ) ) . '</p>';
        echo '<p><strong>Parcels Delivered:</strong> ' . esc_html( $order->get_meta( '_dsr_total_delivered' ) ) . '</p>';
        echo '<p><strong>Cancelled Parcels:</strong> ' . esc_html( $order->get_meta( '_dsr_total_cancel' ) ) . '</p>';
        echo '</div></div>';
    }
}