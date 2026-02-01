<?php
class CDC_Plugin_AJAX {
    public function __construct() {
        // DSR Score Check
        add_action('wp_ajax_cdc_dsr_get_score', [$this, 'handle_dsr_score_request']);
        add_action('wp_ajax_nopriv_cdc_dsr_get_score', [$this, 'handle_dsr_score_request']);

        // DSR Refresh Check
        add_action('wp_ajax_cdc_dsr_refresh', [$this, 'handle_dsr_refresh_request']);
        add_action('wp_ajax_nopriv_cdc_dsr_refresh', [$this, 'handle_dsr_refresh_request']);
    }

    public function handle_dsr_score_request() {

        // Verify AJAX nonce (dies with 403 if not valid)
        check_ajax_referer('cdc_ajax_nonce', 'nonce');

        if (!isset($_POST['phone'])) {
            wp_send_json_error(['message' => 'Phone number missing']);
        }

        $phone = sanitize_text_field($_POST['phone']);

        $dsr = new CDC_Plugin_API();
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
    public function handle_dsr_refresh_request() {
        error_log('check_dsr_again function called'); // Debug 5

        // Verify AJAX nonce (dies with 403 if not valid)
        check_ajax_referer('cdc_ajax_nonce', 'nonce');

        error_log('Nonce verified'); // Debug 6

        $order_id = intval($_POST['order_id'] ?? 0);
         error_log('Order ID received: ' . $order_id); // Debug 7
         
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

        // Remove non-numeric characters
        $phone = preg_replace('/[^0-9]/', '', $phone);

        // Remove +88 or 880 from beginning
        $phone = str_replace(['+88', '88'], '', $phone);

        // Check if phone number is empty
        if ( empty($phone) ) {
            wp_send_json_error(['message' => 'Phone number not found']);
        }

         // Call DSR API
        $dsr = new CDC_Plugin_API();
        $dsr_response = $dsr->check_dsr($phone);

        // Check if DSR response is valid
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

            // Send success response
            wp_send_json_success([
                'message' => 'DSR updated successfully',
                'phone' => $dsr_response['mobile_number'], 
                'total_parcels' => $dsr_response['total_parcels'], 
                'total_delivered' => $dsr_response['total_delivered'], 
                'total_cancel' => $dsr_response['total_cancel']
            ]);
        }elseif (is_array($dsr_response) && isset($dsr_response['status']) && isset($dsr_response['message'])) {
            // Send error response
            wp_send_json_error(['message' => $dsr_response['message']]);
        }else {
            // Send error response
            wp_send_json_error(['message' => 'Something went wrong. Please try again.']);
        }
    }
}
