<?php
    
    session_start();
    require_once('functions.php');

    $symbol = isset($_GET['symbol']) ? strtoupper($_GET['symbol']) : '';
    $range = isset($_GET['range']) ? $_GET['range'] : '';

    if (empty($symbol)) {
        die("Symbol must be provided");
    }

    print_r(json_decode(get_historical_prices($symbol, $range), true));

?>