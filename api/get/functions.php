<?php
        
    require('QTrade.php');

    function get_symbol_id($search_term) {
        
        $url = $_SESSION['api_server']."v1/symbols/search?prefix=$search_term";

        return QTrade::get($url);

    }

    function get_option_chain($symbol_id) {

        /***************************
         * option_chain::get
         * 
         * GET https://api01.iq.questrade.com/v1/symbols/9291/options
         * 
         * Sample Response:
         *{
         *  "options": [
         *  	{ 
         *  		"expiryDate": "2015-01-17T00:00:00.000000-05:00", 
         *  		"description": "BANK OF MONTREAL", 
         *  		"listingExchange": "MX", 
         *  		"optionExerciseType": "American", 
         *  		"chainPerRoot": [
         *  			{
         *  				"root": "BMO",
         *  				"chainPerStrikePrice": [
         *  					{
         *  						"strikePrice": 60, 
         *  						"callSymbolId": 6101993, 
         *  						"putSymbolId": 6102009 
         *  					},
         *  					{
         *  						"strikePrice": 62, 
         *  						"callSymbolId": 6101994, 
         *  						"putSymbolId": 6102010 
         *  					},
         *  					{
         *  						"strikePrice": 64, 
         *  						"callSymbolId": 6101995, 
         *  						"putSymbolId": 6102011 
         *  					},
         *  					... 
         *  				],
         *  				"multiplier": 100
         *  			}
         *  		]
         *  	}
         *  ]
         *  
         * }
         * 
         */

        $url = $_SESSION['api_server']."v1/symbols/$symbol_id/options";

        return QTrade::get($url);

    }

    function get_option_data($filters = [], $option_ids = []) {
        
        /*Sample Request:
        * POST https://api01.iq.questrade.com/v1/markets/quotes/options
        *{
        *    "filters": [
        *        {
        *            "optionType": "Call",
        *            "underlyingId": 27426,
        *            "expiryDate": "2017-01-20T00:00:00.000000-05:00",
        *            "minstrikePrice": 70,
        *            "maxstrikePrice": 80
        *        },
        *        ...
        *    ],
        *    "optionIds":
        *        [
        *            9907637,
        *            9907638,
        *            ...
        *        ]
        *}
        */

        if (count($filters) < 1) {
            die("No filters provided.");
        }
        
        if (count($option_ids) < 1) {
            die("No option ids provided.");
        }

        $url = $_SESSION['api_server']."v1/markets/quotes/options";

        $data = [
            'filters' => $filters,
            'optionIds' => $option_ids
        ];

        return QTrade::post($url, $data);

    }

    function write_option_data_to_csv($symbol_id) {

        /*************
        * write all call/put option data to csv
        * calls/{symbol}.csv
        * puts/{symbol}.csv
        */

        $symbol = '';

        if (empty($symbol_id)) {
            die("No symbol id provided.");
        }

        $option_chains = json_decode(get_option_chain($symbol_id), true)['optionChain'];

        foreach ($option_chains as $option_chain) {

            $expiry_date = $option_chain['expiryDate'];
            $description = $option_chain['description'];
            $listing_exchange = $option_chain['listingExchange'];
            $option_exercise_type = $option_chain['optionExerciseType'];
            $chain_per_root = $option_chain['chainPerRoot'];

            foreach ($chain_per_root as $root) {

                $chain_per_strike_price = $root['chainPerStrikePrice'];

                $call_ids = [];
                $put_ids = [];

                foreach ($chain_per_strike_price as $chain) {
                    $call_symbol_id = $chain['callSymbolId'];
                    $put_symbol_id = $chain['putSymbolId'];
                    $strike_price = $chain['strikePrice'];

                    $call_ids[] = $call_symbol_id;
                    $put_ids[] = $put_symbol_id;
                    $strikes[] = $strike_price;

                }
                
                /*
                $expiry_date = new DateTime();
                $expiry_date->modify("+28 day");
                echo $expiry_date->format("Y-m-d");
                */

                //filters are 2d arrays. idk why yet..

                $call_filters = array([
                    "optionType" => "Call",
                    "underlyingId" => $symbol_id,
                    "expiryDate" => $expiry_date
                    //"minstrikePrice" => "",
                    //"maxstrikePrice" => ""
                ]);

                $put_filters = array([
                    "optionType" => "Put",
                    "underlyingId" => $symbol_id,
                    "expiryDate" => $expiry_date
                    //"minstrikePrice" => "",
                    //"maxstrikePrice" => ""
                ]);
            
                $call_data = json_decode(get_option_data($call_filters, $call_ids), true);
                $put_data = json_decode(get_option_data($put_filters, $put_ids), true);

                //print_r($call_data);
                //print_r($put_data);

                $call_quotes = $call_data['optionQuotes'];
                $put_quotes = $put_data['optionQuotes'];

                $dir = "options_data/calls/";
                $filename = "$dir$symbol.csv";

                foreach (array($call_quotes, $put_quotes) as $option_quotes) {

                    if ($option_quotes == $call_quotes) {
                        $dir = str_replace("puts", "calls", $dir);
                        $filename = "$dir$symbol.csv";
                    } else {
                        $dir = str_replace("calls", "puts", $dir);
                        $filename = "$dir$symbol.csv";
                    }

                    if (count($option_quotes) > 0 && $option_quotes[0]['underlying'] != $symbol) {
                        $symbol = $option_quotes[0]['underlying'];
                        $filename = "$dir$symbol.csv";
                    }
                    
                    $keys = null;

                    if (!file_exists($dir)) {
                        mkdir($dir, 0777, true);
                    }

                    $handle = fopen("$filename", "a+");
                    
                    foreach ($option_quotes as $option_quote) {

                        fputcsv($handle, $option_quote);

                        foreach ($option_quote as $key=>$val) {
                            //echo "$key: $val<br /><br />";
                        }
                    }

                }

            }

        }

    }

?>