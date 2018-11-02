<?php

class IEX {

    public static function get($endpoint) {
    
        session_start();

        $url = "https://api.iextrading.com/1.0/$endpoint";

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        //curl_setopt($ch, CURLOPT_POST, 1);
        //curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($params));
        curl_setopt($ch, CURLOPT_HTTPHEADER, array($auth));

        $result = curl_exec($ch);
        error_log("curl error: ".curl_error($ch));

        curl_close($ch);

        //already json_encoded
        return $result;
    
    }

    public static function post($url, $data = []) {

        session_start();

        $access_token = isset($_GET['access_token']) ? $_GET['access_token'] : isset($_SESSION['access_token']) ? $_SESSION['access_token'] : '';

        if (empty($access_token)) {
            die("No access token provided");
        }

        $auth = "Authorization: Bearer $access_token";

        $headers = [
            $auth,                                                                          
            'Content-Type: application/json'
        ];

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $result = curl_exec($ch);
        error_log("curl error: ".curl_error($ch));

        curl_close($ch);

        //already json_encoded
        return $result;

    }

}


?>