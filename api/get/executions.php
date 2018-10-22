<?php

    /***************************
     * accounts::get
     * 
     * Sample Response:
     * {
	 *      "executions": [
	 *      	{
	 *      		"symbol": "AAPL",
	 *      		"symbolId": 8049, 
	 *      		"quantity": 10, 
	 *      		"side": "Buy",
	 *      		"price": 536.87, 
	 *      		"id": 53817310, 
	 *      		"orderId": 177106005,
	 *      		"orderChainId": 177106005,
	 *      		"exchangeExecId": "XS1771060050147",
	 *      		"timestamp": 2014-03-31T13:38:29.000000-04:00,
	 *      		"notes": "",
	 *      		"venue": "LAMP", 
	 *      		"totalCost": 5368.7,
	 *      		"orderPlacementCommission": 0,
	 *      		"commission": 4.95,
	 *      		"executionFee": 0,
	 *      		"secFee": 0, 
	 *      		"canadianExecutionFee": 0, 
	 *      		"parentId": 0
	 *      	}
	 *      ]
     * }
     * 
     */

    session_start();

    $account_id = isset($_GET['account_id']) ? $_GET['account_id'] : '';

    if (empty($account_id)) {
        die("Account id not provided");
    }

    $url = $_SESSION['api_server']."v1/accounts/$account_id/executions";

    require_once('curl_request.php');

?>