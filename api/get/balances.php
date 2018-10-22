<?php

    /***************************
     * accounts::get
     * 
     * Sample Response:
     * {
	 *		"perCurrencyBalances": [
	 *			{
	 *				"currency": "CAD", 
	 *				"cash": 243971.7,
	 *				"marketValue": 6017,
	 *				"totalEquity": 249988.7, 
	 *				"buyingPower": 496367.2,
	 *				"maintenanceExcess": 248183.6,
	 *				"isRealTime": false
	 *			},
	 *			{
	 *				"currency": "USD", 
	 *				"cash": 198259.05, 
	 *				"marketValue": 53745, 
	 *				"totalEquity": 252004.05, 
	 *				"buyingPower": 461013.3, 
	 *				"maintenanceExcess": 230506.65,
	 *				"isRealTime": false
	 *			}
	 *		],
	 *		"combinedBalances": [
	 *			...
	 *		],
	 *		"sodPerCurrencyBalances": [
	 *			...
	 *		],
	 *		"sodCombinedBalances": [
	 *			...
	 *		]
 	 * }
     * 
     */

    session_start();

    $account_id = isset($_GET['account_id']) ? $_GET['account_id'] : '';

    if (empty($account_id)) {
        die("Account id not provided");
    }

    $url = $_SESSION['api_server']."v1/accounts/$account_id/balances";

    require_once('curl_request.php');

?>