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

    $account_id = isset($_GET['account_id']) ? $_GET['account_id'] : '';

    if (empty($account_id)) {
        die("Account id not provided");
    }

    $url = $_SESSION['api_server']."v1/accounts/$account_id/positions";

    require_once('curl_request.php');

?>