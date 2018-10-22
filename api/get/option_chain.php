<?php
    
    session_start();
    require_once('functions.php');

    $symbol_id = isset($_GET['symbol_id']) ? $_GET['symbol_id'] : '';
    $symbol = isset($_GET['symbol']) ? strtoupper($_GET['symbol']) : '';

    if (empty($symbol_id) && empty($symbol)) {
        die("Symbol id or symbol must be provided");
    }

    if (!empty($symbol)) {
        $symbols = json_decode(get_symbol_id($symbol), true)['symbols'];
        foreach ($symbols as $sym) {
            if ($sym['symbol'] == $symbol) {
                $symbol_id = $sym['symbolId'];
            }
        }
    }

    write_option_data_to_csv($symbol_id);

?>