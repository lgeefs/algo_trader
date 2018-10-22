<?php

    /***************************
     * accounts::get
     * 
     * @param string $search_term
     * 
     * Sample request
     * GET https://api01.iq.questrade.com/v1/symbols/search?prefix=BMO
     * 
     * Sample Response:
     * 
     * {
     *  "symbol": [
     *  	{
     *  		"symbol": "BMO",
     *  		"symbolId": 9292,
     *  		"description": "BANK OF MONTREAL",
     *  		"securityType": "Stock",
     *  		"listingExchange": "NYSE",
     *  		"isTradable": true,
     *  		"isQuotable": true,
     *  		"currency": "USD"
     *  	},
     *  	{
     *  		"symbol": "BMO.PRJ.TO",
     *  		"symbolId": 9300,
     *  		"description": "BANK OF MONTREAL CL B SR 13",
     *  		"securityType": "Stock",
     *  		"listingExchange": "TSX",
     *  		"isTradable": true,
     *  		"isQuotable": true,
     *  		"currency": "CAD"
     *  	}
     *  ]
     * } 
     * 
     */

    session_start();

    // can be either invidvidual or comma separated id(s)
    $search_term = isset($_GET['search_term']) ? $_GET['search_term'] : '';

    if (empty($search_term)) {
        die("No search term provided");
    }
    
    require_once('functions.php');
    print_r(json_decode(get_symbol_id($search_term), true)['symbols'][0]['symbol']);

?>