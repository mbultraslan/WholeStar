<?php
class OrderHookClient {

    /**
     * The API Key.
     *
     * @var string
     */
    private $apiKey = '';

    /**
     * The API Url.
     *
     * @var string
     */
    private $apiUrl = '';

    function __construct($apiUrl) {
        $this->apiUrl = $apiUrl;
    }



    /**
     * Default options for curl.
     */
    public static $CURL_OPTS = array(
        CURLOPT_CONNECTTIMEOUT => 10,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_SSL_VERIFYPEER => 0,
        CURLOPT_SSL_VERIFYHOST => 0,
        CURLOPT_TIMEOUT        => 10,
        CURLOPT_USERAGENT      => 'order-hook-client-php-v1',
    );

    public function addOrder($orderId, $order) {
        if(!empty($order)) {
            $params = array(
                "order_external_id" => $orderId
            );
            return $this->makeRequest('addOrder', 'POST', $params, $order);
        }
        return false;
    }


    private function makeRequest($action, $method, $params, $body) {
        $ch = curl_init();

        $getParams = '';
        foreach($params as $key=>$value) {
            $getParams .= $key.'='.$value.'&';
        }

        $opts = self::$CURL_OPTS;
        $opts[CURLOPT_URL] = $this->apiUrl . '?action=' . $action . ((!empty($getParams))? '&' . $getParams : '');
        $opts[CURLOPT_CUSTOMREQUEST] = $method;


        if($method == 'POST' || $method == 'PUT') {
            $data_string = json_encode($body);

           // echo json_encode($body, JSON_PRETTY_PRINT); die();

            $opts[CURLOPT_POSTFIELDS] = $data_string;
            $opts[CURLOPT_HTTPHEADER] = array(
                'Content-Type: application/json',
                'Content-Length: ' . strlen($data_string)
            );
        }
        curl_setopt_array($ch, $opts);
        $result = curl_exec($ch);

        $http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);


        // With dual stacked DNS responses, it's possible for a server to
        // have IPv6 enabled but not have IPv6 connectivity.  If this is
        // the case, curl will try IPv4 first and if that fails, then it will
        // fall back to IPv6 and the error EHOSTUNREACH is returned by the
        // operating system.
        if ($result === false && empty($opts[CURLOPT_IPRESOLVE])) {
            $matches = array();
            $regex = '/Failed to connect to ([^:].*): Network is unreachable/';
            if (preg_match($regex, curl_error($ch), $matches)) {
                if (strlen(@inet_pton($matches[1])) === 16) {
                    self::errorLog('Invalid IPv6 configuration on server, '.
                        'Please disable or get native IPv6 on your server.');
                    self::$CURL_OPTS[CURLOPT_IPRESOLVE] = CURL_IPRESOLVE_V4;
                    curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
                    $result = curl_exec($ch);
                }
            }
        }

        if ($result === false) {
//            $e = new OrderHookException(array(
//                'error_code' => curl_errno($ch),
//                'error' => array(
//                    'message' => curl_error($ch),
//                    'type' => 'CurlException',
//                ),
//            ));
            //throw $e;
        }
//echo $result; die();

        if($http_status != 200 || $http_status != 201) {
//            $error = json_decode($result, true);
//            throw new OrderHookException(array(
//                'error_code' => $http_status,
//                'error_msg' => $error['message'],
//                'error' => array(
//                    'message' => $error['message'],
//                    'type' => 'OrderHookException',
//                ),
//            ));
        } else if($http_status == 200) {
            $result = json_decode($result, true);
        }

        curl_close($ch);
        return $result;
    }


}