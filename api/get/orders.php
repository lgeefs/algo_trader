<?php

    /***************************
     * accounts::get
     * 
     * Sample Response:
     *{
     *      "orders": [
     *      	{
     *      		"id": 173577870,
     *      		"symbol": "AAPL",
     *      		"symbolId": 8049,
     *      		"totalQuantity": 100,
     *      		"openQuantity": 100,
     *      		"filledQuantity": 0,
     *      		"canceledQuantity": 0,
     *      		"side": "Buy",
     *      		"type": "Limit",
     *      		"limitPrice": 500.95,
     *      		"stopPrice": null,
     *      		"isAllOrNone": false,
     *      		"isAnonymous": false,
     *      		"icebergQty": null,
     *      		"minQuantity": null,
     *      		"avgExecPrice": null,
     *      		"lastExecPrice": null,
     *      		"source": "TradingAPI",
     *      		"timeInForce": "Day",
     *      		"gtdDate": null,
     *      		"state": "Canceled",
     *      		"clientReasonStr": "",
     *      		"chainId": 173577870,
     *      		"creationTime": 2014-10-23T20:03:41.636000-04:00,
     *      		"updateTime": 2014-10-23T20:03:42.890000-04:00,
     *      		"notes": "",
     *      		"primaryRoute": "AUTO",
     *      		"secondaryRoute": "",
     *      		"orderRoute": "LAMP",
     *      		"venueHoldingOrder": "",
     *      		"comissionCharged": 0,
     *      		"exchangeOrderId": "XS173577870",
     *      		"isSignificantShareHolder": false,
     *      		"isInsider": false,
     *      		"isLimitOffsetInDollar": false,
     *      		"userId":  3000124,
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

    $account_id = isset($_GET['account_id']) ? $_GET['account_id'] : '';

    // comma separated list of order ids to query (optional)
    // otherwise all orders will be returned
    $order_ids = isset($_GET['order_ids']) ? $_GET['order_ids'] : '';

    if (empty($account_id)) {
        die("Account id not provided");
    }

    $url = $_SESSION['api_server']."v1/accounts/$account_id/orders?ids=$order_ids";

    require_once('curl_request.php');

?>