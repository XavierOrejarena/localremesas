<?php

require 'btcinfo.php';
$diff = json_decode(file_get_contents('diff.json'));

foreach($users as $k=>$v){
    $btcPeru = getBTCPeru(true);
    if($btcPeru != false) {
        $params = array(
            "chat_id" => $v,
            'parse_mode' => 'Markdown',
            "text" => "*⚠️La diferencia está sobre lo establecido para Perú⚠️*\n$btcPeru",
        );
        print_r(json_decode(sendMethod("sendMessage", $params)));
    }

    $btcChile = getBTCChile(true);
    if($btcChile != false) {
        $params = array(
            "chat_id" => $v,
            'parse_mode' => 'Markdown',
            "text" => "*⚠️La diferencia está sobre lo establecido para Chile⚠️*\n$btcChile",
        );
        print_r(json_decode(sendMethod("sendMessage", $params)));
    }

    $btcVenezuela = getBTCVenezuela(true);
    if($btcVenezuela != false) {
        $params = array(
            "chat_id" => $v,
            'parse_mode' => 'Markdown',
            "text" => "*⚠️La diferencia está sobre lo establecido para Venezuela⚠️*\n$btcVenezuela",
        );
        print_r(json_decode(sendMethod("sendMessage", $params)));
    }
}
