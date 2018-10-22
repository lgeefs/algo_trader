<?php

    /***************************
     * accounts::get
     * 
     * GET https://api06.iq.questrade.com/v1/accounts/26598145/activities?startTime=2011-02-01T00:00:00-05:00&endTime=2011-02-28T00:00:00-05:00&
     * 
     * @param string $start_time
     * @param string $end_time
     * 
     * Sample Response:
     * {
     *      "activities": [
     *          {
     *              "tradeDate": "2011-02-16T00:00:00.000000-05:00",
     *              "transactionDate": "2011-02-16T00:00:00.000000-05:00",
     *              "settlementDate": "2011-02-16T00:00:00.000000-05:00",
     *              "action": "",
     *              "symbol": "",
     *              "symbolId": 0,
     *              "description": "INT FR 02/04 THRU02/15@ 4 3/4%BAL  205,006   AVBAL  204,966 ",
     *              "currency": "USD",
     *              "quantity": 0,
     *              "price": 0,
     *              "grossAmount": 0,
     *              "commission": 0,
     *              "netAmount": -320.08,
     *              "type": "Interest"
     *          },
     *          ...
     *      ]
     * }
     *
     * 
     */

    session_start();

    $account_id = isset($_GET['account_id']) ? $_GET['account_id'] : '';

    $start_time = isset($_GET['start_time']) ? $_GET['start_time'] : '2011-02-01T00:00:00-05:00';
    $end_time = isset($_GET['end_time']) ? $_GET['end_time'] : '2018-02-26T00:00:00-05:00';

    if (empty($start_time) || empty($end_time)) {
        die("Start time and end time must be provided");
    }

    if (empty($account_id)) {
        die("Account id not provided");
    }

    $url = $_SESSION['api_server']."v1/accounts/$account_id/activities?startTime=$start_time&endTime=$end_time";

    require_once('curl_request.php');

?>