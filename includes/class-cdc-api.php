<?php
class CDC_API {
    public function check_dsr($customer_phone) {
        $api_url = get_option('cdc_api_url', 'https://fraudchecker.link/api/v1/qc/');
        $api_key = get_option('cdc_api_key', '');

        if (!$api_key || !$api_url) {
            return ['error' => 'API key or URL is not set in plugin settings.'];
        }

        $response = wp_remote_post($api_url, [
            'headers' => [
                'Authorization' => 'Bearer ' . $api_key,
                'Content-Type'  => 'application/x-www-form-urlencoded',
            ],
            'body' => [
                'phone' => $customer_phone,
            ],
            'timeout' => 15,
        ]);

        // Check for errors
        if (is_wp_error($response)) {
            return ['error' => $response->get_error_message()];
        }

        // Get the response body and decode
        $body = wp_remote_retrieve_body($response);
        return json_decode($body, true);
    }
}
