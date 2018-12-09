<?php
    
    session_start();
    require_once('functions.php');

    $symbol = isset($_GET['symbol']) ? strtoupper($_GET['symbol']) : '';
    $range = isset($_GET['range']) ? $_GET['range'] : '';
    $date = isset($_GET['date']) ? $_GET['date'] : '';

    if (empty($symbol)) {
        die("Symbol must be provided");
    }

    if (empty($range)) {
        print_r(get_historical_prices($symbol, $range));
    } else if (empty($date)) {
        print_r(get_historical_prices_date($symbol, $date));
    }

?>