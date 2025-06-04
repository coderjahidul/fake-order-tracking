<?php
class CDC_AJAX {
    public function __construct() {
        add_action('wp_ajax_check_dsr_score', [$this, 'check_dsr_score']);
        add_action('wp_ajax_nopriv_check_dsr_score', [$this, 'check_dsr_score']);
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

        wp_send_json_success([
            'dsr' => $dsr_percentage
        ]);
    }
}
