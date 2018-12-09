<?php
    
    session_start();
    require_once('functions.php');

    $symbol = isset($_GET['symbol']) ? strtoupper($_GET['symbol']) : '';

    if (empty($symbol)) {
        die("Symbol must be provided");
    }

    print_r(get_price($symbol));

?>