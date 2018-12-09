<?php
        
    require('QTrade.php'); // Questrade cURL requests
    require('IEX.php'); // IEX API cURL requests


    /*********************************************
    * Questrade API METHODS
    *********************************************/

    function get_symbol_id($search_term) {
        
        $url = $_SESSION['api_server']."v1/symbols/search?prefix=$search_term";

        return QTrade::get($url);

    }

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

    function get_option_chain($symbol_id) {

        $url = $_SESSION['api_server']."v1/symbols/$symbol_id/options";

        return QTrade::get($url);

    }
    
    /*Sample Request:
    * POST https://api01.iq.questrade.com/v1/markets/quotes/options
    *{
    *    "filters": [ // Required? We use it regardless
    *        {
    *            "optionType": "Call", //Optional
    *            "underlyingId": 27426, // Required
    *            "expiryDate": "2017-01-20T00:00:00.000000-05:00", // Required
    *            "minstrikePrice": 70, // Optional
    *            "maxstrikePrice": 80 // Optional
    *        },
    *        ...
    *    ],
    *    "optionIds": // Required
    *        [
    *            9907637,
    *            9907638,
    *            ...
    *        ]
    *}
    */

    function get_option_data($filters = [], $option_ids = []) {

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

    /*************
    * write all call/put option data to csv
    * api/get/options_data/calls/{symbol}.csv
    * api/get/options_data/puts/{symbol}.csv
    */

    function write_option_data_to_csv($symbol_id) {

        // just provide the symbol id, the rest will do the work ;)

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

    /*********************************************
    * IEX API METHODS
    *********************************************/

    /*
    +get_company($symbol);
    +get_dividends($symbol, $range);
    +get_financials($symbol);
    +get_historical_prices($symbol, $range);


    */

    function get_company($symbol) {

        /*{
        *   "symbol": "AAPL",
        *   "companyName": "Apple Inc.",
        *   "exchange": "Nasdaq Global Select",
        *   "industry": "Computer Hardware",
        *   "website": "http://www.apple.com",
        *   "description": "Apple Inc is an American multinational technology company. It designs, manufactures, and markets mobile communication and media devices, personal computers, and portable digital music players.",
        *   "CEO": "Timothy D. Cook",
        *   "issueType": "cs",
        *   "sector": "Technology",
        *   "tags": [
        *       "Technology",
        *       "Consumer Electronics",
        *       "Computer Hardware"
        *   ]
        * }
        */
    }

    function get_dividends($symbol, $range) {

        /* GET /stock/{symbol}/dividends/{range}
        *
        * [
        *     {
        *         "exDate": "2017-08-10",
        *         "paymentDate": "2017-08-17",
        *         "recordDate": "2017-08-14",
        *         "declaredDate": "2017-08-01",
        *         "amount": 0.63,
        *         "type": "Dividend income",
        *         "qualified": "Q"
        *     } // , { ... }
        * ]
        *
        */

        /* Possible Endpoints
        *
        * /stock/aapl/dividends/5y
        * /stock/aapl/dividends/2y
        * /stock/aapl/dividends/1y
        * /stock/aapl/dividends/ytd
        * /stock/aapl/dividends/6m
        * /stock/aapl/dividends/3m
        * /stock/aapl/dividends/1m
        */

        $endpoint = "stock/$symbol/dividends/$range";

        return IEX::get($endpoint);

    }

    function get_financials($symbol) {

        /*GET /stock/{symbol}/financials
        * 
        * {
        *    "symbol": "AAPL",
        *    "financials": [
        *        {
        *        "reportDate": "2017-03-31",
        *        "grossProfit": 20591000000,
        *        "costOfRevenue": 32305000000,
        *        "operatingRevenue": 52896000000,
        *        "totalRevenue": 52896000000,
        *        "operatingIncome": 14097000000,
        *        "netIncome": 11029000000,
        *        "researchAndDevelopment": 2776000000,
        *        "operatingExpense": 6494000000,
        *        "currentAssets": 101990000000,
        *        "totalAssets": 334532000000,
        *        "totalLiabilities": 200450000000,
        *        "currentCash": 15157000000,
        *        "currentDebt": 13991000000,
        *        "totalCash": 67101000000,
        *        "totalDebt": 98522000000,
        *        "shareholderEquity": 134082000000,
        *        "cashChange": -1214000000,
        *        "cashFlow": 12523000000,
        *        "operatingGainsLosses": null
        *        } // , { ... }
        *    ]
        * }
        */

        $endpoint = "stock/$symbol/financials";

        return IEX::get($endpoint);

    }

    function get_historical_prices($symbol, $range) {

        /* https://iextrading.com/developer/docs/#chart
        *
        * Range	       |Description	       |Source
        *--------------+-------------------+--------------------------------------
        * 5y	       |Five years	       |Historically adjusted market-wide data
        * 2y	       |Two years	       |Historically adjusted market-wide data
        * 1y	       |One year	       |Historically adjusted market-wide data
        * ytd	       |Year-to-date	   |Historically adjusted market-wide data
        * 6m	       |Six months	       |Historically adjusted market-wide data
        * 3m	       |Three months	   |Historically adjusted market-wide data
        * 1m	       |One month (default)|Historically adjusted market-wide data
        * 1d	       |One day	           |IEX-only data by minute
        * date	       |Specific date	   |IEX-only data by minute for a specified
        *              |                   |date in the format YYYYMMDD if available.
        *              |                   |Currently supporting trailing 30 calendar days.
        * dynamic	   |One day	           |Will return 1d or 1m data depending on the day
        *              |                   |or week and time of day. Intraday per minute data
        *              |                   |is only returned during market hours.
        *
        * Possible Endpoints:
        * /stock/aapl/chart
        * /stock/aapl/chart/5y
        * /stock/aapl/chart/2y
        * /stock/aapl/chart/1y
        * /stock/aapl/chart/ytd
        * /stock/aapl/chart/6m
        * /stock/aapl/chart/3m
        * /stock/aapl/chart/1m
        * /stock/aapl/chart/1d
        * /stock/aapl/chart/date/20180129
        * /stock/aapl/chart/dynamic
        */

        $endpoint = "/stock/$symbol/chart/$range";

        return IEX::get($endpoint);

    }

    function get_historical_prices_date($symbol, $date) {

        $endpoint = "/stock/$symbol/chart/date/$date";

        return IEX::get($endpoint);

    }

    function get_logo($symbol) {

        //Returns company logo lol

        /*GET /stock/{symbol}/logo   
        *
        *{
        *  "url": "https://storage.googleapis.com/iex/api/logos/AAPL.png"
        *}
        */

        $endpoint = "/stock/$symbol/logo";

        return IEX::get($endpoint);

    }

    function get_news($symbol = '', $range = '') {

        /*GET /stock/{symbol}/news/last/{last}
        * 
        * [
        *   {
        *     "datetime": "2017-06-29T13:14:22-04:00",
        *     "headline": "Voice Search Technology Creates A New Paradigm For Marketers",
        *     "source": "Benzinga via QuoteMedia",
        *     "url": "https://api.iextrading.com/1.0/stock/aapl/article/8348646549980454",
        *     "summary": "<p>Voice search is likely to grow by leap and bounds, with technological advancements leading to better adoption and fueling the growth cycle, according to Lindsay Boyajian, <a href=\"http://loupventures.com/how-the-future-of-voice-search-affects-marketers-today/\">a guest contributor at Loup Ventu...",
        *     "related": "AAPL,AMZN,GOOG,GOOGL,MSFT",
        *     "image": "https://api.iextrading.com/1.0/stock/aapl/news-image/7594023985414148"
        *   }
        * ]
        */

        $endpoint;

        // if symbol is empty we get stock-specific news
        if (!empty($symbol)) {
            $endpoint = "/stock/$symbol/news/last/$range";
        } else {
            // otherwise, get general market-wide news
            $endpoint = "/stock/market/news/last/$range";
        }

        return IEX::get($endpoint);

    }

    function get_ohlc($symbol = '') {

        /*GET /stock/{symbol}/ohlc
        *
        *{
        *  "open": {
        *    "price": 154,
        *    "time": 1506605400394
        *  },
        *  "close": {
        *    "price": 153.28,
        *    "time": 1506605400394
        *  },
        *  "high": 154.80,
        *  "low": 153.25
        *}
        */

        $endpoint;

        // if symbol is not empty, get ohlc for it
        if (!empty($symbol)) {
            $endpoint = "/stock/$symbol/ohlc";
        } else {
            // otherwise, get EVERY stock's ohlc!
            $endpoint = "/stock/market/ohlc";
        }

        return IEX::get($endpoint);

    }

    function get_previous_day_price($symbol) {

        /*GET /stock/{symbol}/previous
        * The above example will return JSON with the following keys
        * 
        * {
        *   "symbol": "AAPL",
        *   "date": "2017-09-19",
        *   "open": 159.51,
        *   "high": 159.77,
        *   "low": 158.44,
        *   "close": 158.73,
        *   "volume": 20810632,
        *   "unadjustedVolume": 20810632,
        *   "change": 0.06,
        *   "changePercent": 0.038,
        *   "vwap": 158.9944
        * }
        */

        $endpoint = "/stock/$symbol/previous";

        return IEX::get($endpoint);

    }

    function get_price($symbol) {

        /*GET /stock/{symbol}/price
        *The above example will return a number
        *
        *143.28
        *
        */

        $endpoint = "/stock/$symbol/price";

        return IEX::get($endpoint);

    }

    function get_quote($symbol) {

        /*GET /stock/{symbol}/quote
        * The above example will return JSON with the following keys
        * 
        * {
        *   "symbol": "AAPL",
        *   "companyName": "Apple Inc.",
        *   "primaryExchange": "Nasdaq Global Select",
        *   "sector": "Technology",
        *   "calculationPrice": "tops",
        *   "open": 154,
        *   "openTime": 1506605400394,
        *   "close": 153.28,
        *   "closeTime": 1506605400394,
        *   "high": 154.80,
        *   "low": 153.25,
        *   "latestPrice": 158.73,
        *   "latestSource": "Previous close",
        *   "latestTime": "September 19, 2017",
        *   "latestUpdate": 1505779200000,
        *   "latestVolume": 20567140,
        *   "iexRealtimePrice": 158.71,
        *   "iexRealtimeSize": 100,
        *   "iexLastUpdated": 1505851198059,
        *   "delayedPrice": 158.71,
        *   "delayedPriceTime": 1505854782437,
        *   "extendedPrice": 159.21,
        *   "extendedChange": -1.68,
        *   "extendedChangePercent": -0.0125,
        *   "extendedPriceTime": 1527082200361,
        *   "previousClose": 158.73,
        *   "change": -1.67,
        *   "changePercent": -0.01158,
        *   "iexMarketPercent": 0.00948,
        *   "iexVolume": 82451,
        *   "avgTotalVolume": 29623234,
        *   "iexBidPrice": 153.01,
        *   "iexBidSize": 100,
        *   "iexAskPrice": 158.66,
        *   "iexAskSize": 100,
        *   "marketCap": 751627174400,
        *   "peRatio": 16.86,
        *   "week52High": 159.65,
        *   "week52Low": 93.63,
        *   "ytdChange": 0.3665,
        * }
        */

        $endpoint = "/stock/$symbol/quote";

        return IEX::get($endpoint);

    }

    function get_short_interest($symbol) {

        /* GET /stock/{symbol}/short-interest
        * 
        * [
        *   {
        *     "SettlementDate": "20171013",
        *     "SecurityName": "ZIEXT Common Stock",
        *     "CurrentShortInterest": 5363,
        *     "PreviousShortInterest": 5730,
        *     "PercentChange": -0.064049,
        *     "AverageDailyVolume": 2113,
        *     "DaystoCover": 2.54,
        *     "StockAdjustmentFlag": "N",
        *     "RevisionFlag": "N",
        *     "SymbolinINETSymbology": "ZIEXT",
        *     "SymbolinCQSSymbology": "ZIEXT",
        *     "SymbolinCMSSymbology": "ZIEXT",
        *     "NewIssueFlag": "N",
        *     "CompanyName": "ZIEXT Test Company"
        *   },
        *   {...}
        * ]
        */

    }

    function get_stats($symbol) {

        /* GET /stock/{symbol}/stats
        * 
        * {
        *    "companyName": "Apple Inc.",
        *    "marketcap": 760334287200,
        *    "beta": 1.295227,
        *    "week52high": 156.65,
        *    "week52low": 93.63,
        *    "week52change": 58.801903,
        *    "shortInterest": 55544287,
        *    "shortDate": "2017-06-15",
        *    "dividendRate": 2.52,
        *    "dividendYield": 1.7280395,
        *    "exDividendDate": "2017-05-11 00:00:00.0",
        *    "latestEPS": 8.29,
        *    "latestEPSDate": "2016-09-30",
        *    "sharesOutstanding": 5213840000,
        *    "float": 5203997571,
        *    "returnOnEquity": 0.08772939519857577,
        *    "consensusEPS": 3.22,
        *    "numberOfEstimates": 15,
        *    "symbol": "AAPL",
        *    "EBITDA": 73828000000,
        *    "revenue": 220457000000,
        *    "grossProfit": 84686000000,
        *    "cash": 256464000000,
        *    "debt": 358038000000,
        *    "ttmEPS": 8.55,
        *    "revenuePerShare": 42.2830389885382,
        *    "revenuePerEmployee": 1900491.3793103448,
        *    "peRatioHigh": 25.5,
        *    "peRatioLow": 8.7,
        *    "EPSSurpriseDollar": null,
        *    "EPSSurprisePercent": 3.9604,
        *    "returnOnAssets": 14.15,
        *    "returnOnCapital": null,
        *    "profitMargin": 20.73,
        *    "priceToSales": 3.6668503,
        *    "priceToBook": 6.19,
        *    "day200MovingAvg": 140.60541,
        *    "day50MovingAvg": 156.49678,
        *    "institutionPercent": 32.1,
        *    "insiderPercent": null,
        *    "shortRatio": 1.6915414,
        *    "year5ChangePercent": 0.5902546932200027,
        *    "year2ChangePercent": 0.3777449874142869,
        *    "year1ChangePercent": 0.39751716851558366,
        *    "ytdChangePercent": 0.36659492036160124,
        *    "month6ChangePercent": 0.12208398133748043,
        *    "month3ChangePercent": 0.08466584665846649,
        *    "month1ChangePercent": 0.009668596145283263,
        *    "day5ChangePercent": -0.005762605699968781
        * }
        */

        $endpoint = "stock/$symbol/stats";

        return IEX::get($endpoint);

    }



?>