<?php

    /***************************
     * markets::get
     * 
     * GET https://api01.iq.questrade.com/v1/markets
     * 
     * Sample Response:
     *{
     *  "markets": [
     *  	{
     *  		"name": "TSX",
     *  		"tradingVenues": [
     *  			"TSX",
     *  			"ALPH",
     *  			"CHIC",
     *  			"OMGA",
     *  			"PURE"
     *  		],
     *  		"defaultTradingVenue": "AUTO",
     *  		"primaryOrderRoutes": [
     *  			"AUTO"
     *  		],
     *  		"secondaryOrderRoutes": [
     *  			"TSX",
     *  			"AUTO"
     *  		],
     *  		"level1Feeds": [
     *  			"ALPH",
     *  			"CHIC",
     *  			"OMGA",
     *  			"PURE",
     *  			"TSX"
     *  		],
     *  		"extendedStartTime": "2014-10-06T07:00:00.000000-04:00",
     *  		"startTime": "2014-10-06T09:30:00.000000-04:00",
     *  		"endTime": "2014-10-06T16:00:00.000000-04:00",
     *  		"currency": "CAD",
     *  		"snapQuotesLimit": 99999
     *  	},
     *  	...
     *  ]
     *  
     * }
     * 
     */

    session_start();

    $url = $_SESSION['api_server']."v1/markets";

    require_once('curl_request.php');

?>