<?php

    /***************************
     * accounts::get
     * 
     * @param string $symbol_ids
     * 
     * Sample request
     * GET https://api06.iq.questrade.com/v1/symbols/8049
     * Sample request
     * GET https://api06.iq.questrade.com/v1/symbols?ids=8049,...
     * 
     * Sample Response:
     * 
     * {
     *  "symbols": [
     *  	{
     *  		"symbol": "AAPL",
     *  		"symbolId": 8049,
     *  		"prevDayClosePrice": 102.5, 
     *  		"highPrice52": 102.9, 
     *  		"lowPrice52": 63.89, 
     *  		"averageVol3Months": 43769680,
     *  		"averageVol20Days": 12860370,
     *  		"outstandingShares": 5987867000, 
     *  		"eps": 6.2,
     *  		"pe": 16.54,
     *  		"dividend": 0.47,
     *  		"yield": 1.84,
     *  		"exDate": "2014-08-07T00:00:00.000000-04:00", 
     *  		"marketCap": 613756367500, 
     *  		"tradeUnit": 1, 
     *  		"optionType": null,
     *  		"optionDurationType": null, 
     *  		"optionRoot": "",
     *  		"optionContractDeliverables": {
     *  			"underlyings": [], 
     *  			"cashInLieu": 0 
     *  		}, 
     *  		"optionExerciseType": null,
     *  		"listingExchange": "NASDAQ", 
     *  		"description": "APPLE INC",
     *  		"securityType": "Stock", 
     *  		"optionExpiryDate": null,
     *  		"dividendDate": "2014-08-14T00:00:00.000000-04:00",
     *  		"optionStrikePrice": null, 
     *  		"isTradable": true,
     *  		"isQuotable": true,
     *  		"hasOptions": true,
     *  		"minTicks": [
     *  			{
     *  				"pivot": 0, 
     *  				"minTick": 0.0001
     *  			},
     *  			{
     *  				"pivot": 1, 
     *  				"minTick": 0.01
     *  			}
     *  		],
     *          "industrySector": "BasicMaterials", 
     *          "industryGroup":  "Steel", 
     *          "industrySubGroup": "Steel"
     *  	},
     *  	...
     *  ]
     *  }
     * 
     */

    session_start();

    // can be either invidvidual or comma separated id(s)
    $symbol_ids = isset($_GET['symbol_ids']) ? $_GET['symbol_ids'] : '';

    if (empty($symbol_id)) {
        die("No symbol id provided");
    }

    $url = $_SESSION['api_server']."v1/symbols?ids=$symbol_ids";

    require_once('curl_request.php');

?>