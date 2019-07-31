#!/usr/bin/env php
<?php

require 'btcinfo.php';

foreach($users as $k=>$v){
    $params = array(
        "chat_id" => $v,
        'parse_mode' => 'Markdown',
        "text" => getBTCPeru()
    );
    print_r(json_decode(sendMethod("sendMessage", $params)));

    $params = array(
        "chat_id" => $v,
        'parse_mode' => 'Markdown',
        "text" => getBTCChile()
    );
    print_r(json_decode(sendMethod("sendMessage", $params)));

    $params = array(
        "chat_id" => $v,
        'parse_mode' => 'Markdown',
        "text" => getBTCVenezuela()
    );
    print_r(json_decode(sendMethod("sendMessage", $params)));

}
