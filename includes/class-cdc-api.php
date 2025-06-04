<?php
class CDC_API {
    public function check_dsr($customer_phone) {
        $api_url = get_option('cdc_api_url', 'https://fraudchecker.link/api/v1/qc/'); // Replace with your API URL
        $api_key = get_option('cdc_api_key', ''); // Replace with your API key

        if (!$api_key || !$api_url) {
            return ['error' => 'API key or URL is not set in plugin settings.'];
        }

        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => $api_url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => http_build_query(['phone' => $customer_phone]),
            CURLOPT_HTTPHEADER => [
                'Authorization: Bearer ' . $api_key,
                'Content-Type: application/x-www-form-urlencoded',
            ],
        ]);

        $response = curl_exec($curl);
        curl_close($curl);

        return json_decode($response, true);
    }
}