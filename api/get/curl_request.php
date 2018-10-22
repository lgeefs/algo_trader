<?php

    session_start();

    $access_token = isset($_GET['access_token']) ? $_GET['access_token'] : isset($_SESSION['access_token']) ? $_SESSION['access_token'] : '';
    $function = isset($_GET['function']) ? $_GET['function'] == 1 ? true : false : false;

    error_log($access_token);

    if (empty($access_token)) {
        die("No access token provided");
    }

    $auth = "Authorization: Bearer $access_token";

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
    
?>