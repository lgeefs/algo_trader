<?php

    /***************************
     * order::post
     * 
     * Sample request
     * POST https://api01.iq.questrade.com/v1/accounts/26598145/orders
     * { 
     *      "accountNumber" : XXXXXXXX,
     *      "symbolId": 8049,
     *      "quantity": 10,
     *      "icebergQuantity": 1,
     *      "limitPrice": 537,
     *      "isAllOrNone": true,
     *      "isAnonymous": false,
     *      "orderType": "Limit",
     *      "timeInForce": "GoodTillCanceled",
     *      "action": "Buy",
     *      "primaryRoute": "AUTO",
     *      "secondaryRoute": "AUTO"
     * }
     * 
     * Sample Response:
     * 
     * {
     *      "orderId": 177106005,
     *      "orders": [
     *      	{
     *      		"id": 177106005,
     *      		"symbol": "AAPL",
     *      		"symbolId": 8049,
     *      		"totalQuantity": 10,
     *      		"openQuantity": 10,
     *      		"filledQuantity": 0,
     *      		"canceledQuantity": 0,
     *      		"side": "Buy",
     *      		"orderType": "Limit",
     *      		"limitPrice": 537,
     *      		"stopPrice": null,
     *      		"isAllOrNone": true,
     *      		"isAnonymous": false,
     *      		"icebergQty": 1,
     *      		"minQuantity": null,
     *      		"avgExecPrice": null,
     *      		"lastExecPrice": null,
     *      		"source": "TradingAPI",
     *      		"timeInForce": "GoodTillCanceled",
     *      		"gtdDate": null,
     *      		"state": "Pending",
     *      		"clientReasonStr": "",
     *      		"chainId": 177106005,
     *      		"creationTime": "2014-10-24T17:48:20.546000-04:00",
     *      		"updateTime": "2014-10-24T17:48:20.876000-04:00",
     *      		"notes": "",
     *      		"primaryRoute": "LAMP",
     *      		"secondaryRoute": "AUTO",
     *      		"orderRoute": "LAMP",
     *      		"venueHoldingOrder": "",
     *      		"comissionCharged": 0,
     *      		"exchangeOrderId": "",
     *      		"isSignificantShareHolder": false,
     *      		"isInsider": false,
     *      		"isLimitOffsetInDollar": false,
     *      		"userId": 3000124,
     *      		"placementCommission": null,
     *      		"legs": [],
     *      		"strategyType": "SingleLeg",
     *      		"triggerStopPrice": null,
     *      		"orderGroupId": 0,
     *      		"orderClass": null,
     *      		"mainChainId": 0
     *      	},
     *      	...
     *      ]
     * }     
     * 
     */

    session_start();

    $account_number = $_GET['account_number'] or die('No account_number provided');
    $symbol_id = $_GET['symbol_id'] or die('No symbol_id provided');
    $quantity = $_GET['quantity'] or die('No quantity provided');
    $iceberg_quantity = $_GET['iceberg_quantity'] or die('No iceberg_quantity provided');
    $limit_price = $_GET['limit_price'] or die('No limit_price provided');
    $all_or_none = $_GET['all_or_none'] or die('No all_or_none provided');
    $is_anonymous = $_GET['is_anonymous'] or die('No is_anonymous provided');
    $order_type = $_GET['order_type'] or die('No order_type provided');
    $time_in_force = $_GET['time_in_force'] or die('No time_in_force provided');
    $action = $_GET['action'] or die('No action provided');
    $primary_route = $_GET['primary_route'] or die('No primary_route provided');
    $secondary_route = $_GET['secondary_route'] or die('No secondary_route provided');

    $params = array(
        "accountNumber" => $account_number,
        "symbolId" => $symbol_id,
        "quantity" => $quantity,
        "icebergQuantity" => $iceberg_quantity,
        "limitPrice" => $limit_price,
        "isAllOrNone" => $all_or_none,
        "isAnonymous" => $is_anonymous,
        "orderType" => $order_type,
        "timeInForce" => $time_in_force,
        "action" => $action,
        "primaryRoute" => $primary_route,
        "secondaryRoute" => $secondary_route
    );

    $url = $_SESSION['api_server']."v1/accounts/$account_number/orders";

    require_once('curl_request.php');

?>