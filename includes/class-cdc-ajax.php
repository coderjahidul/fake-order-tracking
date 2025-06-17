<?php
class CDC_AJAX {
    public function __construct() {
        add_action('wp_ajax_check_dsr_score', [$this, 'check_dsr_score']);
        add_action('wp_ajax_nopriv_check_dsr_score', [$this, 'check_dsr_score']);

        add_action('wp_ajax_check_dsr_again', [$this, 'check_dsr_again']);
        add_action('wp_ajax_nopriv_check_dsr_again', [$this, 'check_dsr_again']);
    }

    public function check_dsr_score() {
        if (!isset($_POST['phone'])) {
            wp_send_json_error(['message' => 'Phone number missing']);
        }

        $phone = sanitize_text_field($_POST['phone']);

        $dsr = new CDC_API();
        $dsr_response = $dsr->check_dsr($phone);

        $total_parcels = isset($dsr_response['total_parcels']) ? (int) $dsr_response['total_parcels'] : 0;
        $total_delivered = isset($dsr_response['total_delivered']) ? (int) $dsr_response['total_delivered'] : 0;

        $dsr_percentage = $total_parcels > 0 ? ($total_delivered / $total_parcels) * 100 : 0;
        $dsr_percentage = floatval(number_format($dsr_percentage, 2));

        // Store it in session for checkout use
        WC()->session->set('dsr_percentage', $dsr_percentage);

        wp_send_json_success([
            'dsr' => $dsr_percentage
        ]);
    }

    // Check DSR Again
    public function check_dsr_again() {
        $order_id = intval($_POST['order_id'] ?? 0);
        if(!$order_id) {
            wp_send_json_error(['message' => 'Order ID missing']);
        }

        // get order phone number
        global $wpdb;

        $phone = $wpdb->get_var( $wpdb->prepare(
            "SELECT phone FROM {$wpdb->prefix}wc_order_addresses WHERE order_id = %d AND address_type = %s",
            $order_id,
            'billing'
        ) );

        if ( empty($phone) ) {
            wp_send_json_error(['message' => 'Phone number not found']);
        }

         // Call DSR API
        $dsr = new CDC_API();
        $dsr_response = $dsr->check_dsr($phone);

        if ( is_array($dsr_response) && isset($dsr_response['mobile_number']) ) {
            $order = wc_get_order($order_id);

            if ( ! $order ) {
                wp_send_json_error(['message' => 'Order not found']);
            }

            // Update WooCommerce order meta
            $order->update_meta_data('_dsr_mobile_number', $dsr_response['mobile_number']);
            $order->update_meta_data('_dsr_total_parcels', $dsr_response['total_parcels']);
            $order->update_meta_data('_dsr_total_delivered', $dsr_response['total_delivered']);
            $order->update_meta_data('_dsr_total_cancel', $dsr_response['total_cancel']);
            $order->save(); // Don't forget this!

            wp_send_json_success([
                'message' => 'DSR updated successfully',
                'phone' => $dsr_response['mobile_number'], 
                'total_parcels' => $dsr_response['total_parcels'], 
                'total_delivered' => $dsr_response['total_delivered'], 
                'total_cancel' => $dsr_response['total_cancel']
            ]);
        }elseif (is_array($dsr_response) && isset($dsr_response['status']) && isset($dsr_response['message'])) {
            wp_send_json_error(['message' => $dsr_response['message']]);
        }else {
            wp_send_json_error(['message' => 'Something went wrong. Please try again.']);
        }
    }
}
