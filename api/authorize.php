<?php

    /***************************
     * access_token::get
     * 
     * @param string grant_type = "refresh_token";
     * @param string refresh_token;
     * 
     * Sample Response:
     * {
     *      "access_token": "C3lTUKuNQrAAmSD/TPjuV/HI7aNrAwDp", // access token to use in authorization bearer header
     *      "token_type": "Bearer", 
     *      "expires_in": 300, 
     *      "refresh_token": "aSBe7wAAdx88QTbwut0tiu3SYic3ox8F",
     *      "api_server": $_SESSION['api_server']."" // the api server to make the rest of our calls to
     * }
     * 
     */

    session_start();

    $refresh_token = isset($_GET['refresh_token']) ? $_GET['refresh_token'] : "";

    if (empty($refresh_token)) {
        require_once('refresh_token.html');
        die(json_encode(array("success" => false, "message" => "No refresh token provided. To get a refresh token, login to your Questrade account, and go to App Hub. Register a new app, and then generate a refresh token. Copy & paste it into the box above and click 'Go'")));
    }

    $url = "https://login.questrade.com/oauth2/token";

    $params = "?";
    $params .= "grant_type=refresh_token";
    $params .= "&refresh_token=$refresh_token";

    $url .= $params;

    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    //curl_setopt($ch, CURLOPT_POST, 1);
    //curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($params));
    //curl_setopt($ch, CURLOPT_HTTPHEADER, array("Authorization: $auth"));

    $result = curl_exec($ch);
    error_log("curl error: ".curl_error($ch));

    curl_close($ch);

    //already json_encoded
    //print_r($result);

    // set connection details in session variable (probs bad idea haha)

    foreach (json_decode($result, true) as $key=>$val) {
        $_SESSION[$key] = $val;
    }

    echo "Congrats, you now have a valid access token.";

?>