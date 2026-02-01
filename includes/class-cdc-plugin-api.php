<?php
class CDC_Plugin_API {

    /**
     * Run both Steadfast and Redx checks and calculate total result
     */
    public function check_dsr($customer_phone) {
        // Steadfast result
        $steadfast = $this->steadfas_check_dsr($customer_phone);
        $steadfast_delivered = $steadfast['total_delivered'] ?? 0;
        $steadfast_cancel = $steadfast['total_cancelled'] ?? 0;
        $steadfast_total = $steadfast_delivered + $steadfast_cancel;

        // Redx result
        $redx = $this->redx_check_dsr($customer_phone);
        $redx_data = $redx['data'] ?? [];
        $redx_total = isset($redx_data['totalParcels']) ? (int)$redx_data['totalParcels'] : 0;
        $redx_delivered = isset($redx_data['deliveredParcels']) ? (int)$redx_data['deliveredParcels'] : 0;
        $redx_cancel = $redx_total - $redx_delivered;

        // Total calculation
        $total_parcels = $steadfast_total + $redx_total;
        $total_delivered = $steadfast_delivered + $redx_delivered;
        $total_cancel = $steadfast_cancel + $redx_cancel;

        // Build API-wise data
        $apis = [
            'Pathao' => [
                'courier_name' => 'Pathao',
                'total_parcels' => 0,
                'total_delivered_parcels' => 0,
                'total_cancelled_parcels' => 0,
            ],
            'Steadfast' => [
                'courier_name' => 'Steadfast',
                'total_parcels' => $steadfast_total,
                'total_delivered_parcels' => $steadfast_delivered,
                'total_cancelled_parcels' => $steadfast_cancel,
            ],
            'Paperfly' => [
                'courier_name' => 'PaperFly',
                'total_parcels' => 0,
                'total_delivered_parcels' => 0,
                'total_cancelled_parcels' => 0,
            ],
            'Redex' => [
                'courier_name' => 'Redx',
                'total_parcels' => $redx_total,
                'total_delivered_parcels' => $redx_delivered,
                'total_cancelled_parcels' => $redx_cancel,
            ],
        ];

        return [
            'mobile_number' => $customer_phone,
            'total_parcels' => $total_parcels,
            'total_delivered' => $total_delivered,
            'total_cancel' => $total_cancel,
            'apis' => $apis,
        ];
    }

    /**
     * Steadfast fraud check
     */
    public function steadfas_check_dsr($customer_phone) {
        $email = get_option('cdc_steadfast_email', '');
        $password = get_option('cdc_steadfast_password', '');

        $loginPage = "https://steadfast.com.bd/login";
        $cookieFile = plugin_dir_path(__FILE__) . "steadfast_cookies.txt";

        // Step 1: Get CSRF token
        $ch = curl_init($loginPage);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_COOKIEJAR => $cookieFile,
            CURLOPT_COOKIEFILE => $cookieFile,
            CURLOPT_USERAGENT => "Mozilla/5.0",
            CURLOPT_FOLLOWLOCATION => true,
        ]);
        $loginHtml = curl_exec($ch);
        curl_close($ch);

        preg_match('/<input type="hidden" name="_token" value="([^"]+)"/', $loginHtml, $matches);
        $csrfToken = $matches[1] ?? '';
        if (!$csrfToken) {
            return ['total_parcels'=>0,'total_delivered'=>0,'total_cancelled'=>0];
        }

        // Step 2: Login
        $postFields = http_build_query([
            "_token" => $csrfToken,
            "email" => $email,
            "password" => $password,
        ]);

        $ch = curl_init($loginPage);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $postFields,
            CURLOPT_COOKIEJAR => $cookieFile,
            CURLOPT_COOKIEFILE => $cookieFile,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_USERAGENT => "Mozilla/5.0",
        ]);
        curl_exec($ch);
        curl_close($ch);

        // Step 3: Fraud check
        $fraudUrl = "https://steadfast.com.bd/user/frauds/check/" . urlencode($customer_phone);

        $ch = curl_init($fraudUrl);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_COOKIEFILE => $cookieFile,
            CURLOPT_HTTPHEADER => [
                "Accept: application/json",
                "X-Requested-With: XMLHttpRequest"
            ],
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_USERAGENT => "Mozilla/5.0"
        ]);
        $fraudResponse = curl_exec($ch);
        curl_close($ch);

        $data = json_decode($fraudResponse, true);

        if (!$data) {
            return ['total_parcels'=>0,'total_delivered'=>0,'total_cancelled'=>0];
        }

        return [
            'total_parcels' => $data['total_parcels'] ?? 0,
            'total_delivered' => $data['total_delivered'] ?? 0,
            'total_cancelled' => $data['total_cancel'] ?? 0,
        ];
    }

    /**
     * Redx fraud check
     */
    public function redx_check_dsr($customer_phone) {
        $accessToken = get_option('cdc_redx_access_token', '');
        $url = 'https://redx.com.bd/api';
        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => $url . '/redx_se/admin/parcel/customer-success-return-rate?phoneNumber=' . $customer_phone,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => [
                'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64)',
                'Accept: application/json, text/plain, /',
                'Content-Type: application/json',
                'Authorization: Bearer ' . $accessToken,
            ],
        ]);

        $response = curl_exec($curl);
        curl_close($curl);

        return json_decode($response, true);
    }
}
