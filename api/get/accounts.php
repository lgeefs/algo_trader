<?php

    /***************************
     * accounts::get
     * 
     * Sample Response:
     * {
	 *     "accounts": [
     *         {
     *           "type": "Margin",	
     *           "number": "26598145",
     *           "status": "Active",
     *           "isPrimary": true,
     *           "isBilling": true,
     *           "clientAccountType": "Individual"
     *          }
     *     ]
     * }
     * 
     */

    session_start();

    $url = $_SESSION['api_server']."v1/accounts";

    require_once('curl_request.php');

    $accounts = json_decode($result, true)['accounts'];

    foreach ($accounts as $a) {
        if ($a['isPrimary']) {
            foreach ($a as $key => $val) {
                $_SESSION[$key] = $val;
            }
        }
    }

?>