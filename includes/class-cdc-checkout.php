<?php 
class CDC_Checkout {
    public function __construct() {
        // add_action('woocommerce_available_payment_gateways', [$this, 'filter_payment_gateways']);
        add_action('woocommerce_checkout_create_order', [$this, 'attach_dsr_data_to_order'], 20, 2);
    }

    public function filter_payment_gateways($available_gateways) {
        if(!is_checkout() || !is_user_logged_in()) return $available_gateways;

        $user = wp_get_current_user();
        $billing_phone = get_user_meta($user->ID, 'billing_phone', true);

        if (!$billing_phone) return $available_gateways;

        // Call API
        $dsr = new CDC_API();
        $dsr_response = $dsr->check_dsr($billing_phone);
        
        // Get DSR Values
        $total_parcels = isset($dsr_response['total_parcels']) ? (int) $dsr_response['total_parcels'] : 0;
        $total_delivered = isset($dsr_response['total_delivered']) ? (int) $dsr_response['total_delivered'] : 0;
        
        // Get DSR Percentage
        $dsr_percentage = $total_parcels > 0 ? ($total_delivered / $total_parcels) * 100 : 0;
        $dsr_percentage = floatval(number_format($dsr_percentage, 2));

        if (isset($available_gateways['cod'])) {
            if ($dsr_percentage > 50 || $dsr_percentage == 0) {
                $available_gateways['cod']->enabled = true;
            } else {
                unset($available_gateways['cod']);
            }
        }
        
        return $available_gateways;
    }

    public function attach_dsr_data_to_order($order, $data) {
        if(isset( $_POST['billing_phone'] )) {
            $phone = sanitize_text_field($_POST['billing_phone']);

            // Call API
            $dsr = new CDC_API();
            $dsr_response = $dsr->check_dsr($phone);
            
            if (is_array($dsr_response) && isset($dsr_response['mobile_number'])){
                // Update WooCommerce order meta
                $order->update_meta_data( '_dsr_mobile_number', $dsr_response['mobile_number'] );
                $order->update_meta_data( '_dsr_total_parcels', $dsr_response['total_parcels'] );
                $order->update_meta_data( '_dsr_total_delivered', $dsr_response['total_delivered'] );
                $order->update_meta_data( '_dsr_total_cancel', $dsr_response['total_cancel'] );
            }

        }
    }
}