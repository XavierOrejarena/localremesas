#!/usr/bin/env php
<?php

$opts = array('http' =>
  array(
    'method'  => 'GET',
    'header'  => "Content-Type: text/html",
    'content' => '',
    'timeout' => 1
  )
);

$context  = stream_context_create($opts);
$url = 'https://rates.airtm.io/es';

while (1) {
    $URL = file_get_contents($url, true, $context);
    if ($URL) {
        break;
    }
}

$URL = file_get_contents("https://rates.airtm.io/es");
preg_match_all('/<span class="rate--general">/', $URL, $matches, PREG_OFFSET_CAPTURE);
preg_match_all('!\d+!', substr($URL, $matches[0][0][1], 43), $matches);
$tasa = $matches[0][0] . "." . $matches[0][1];
echo $tasa, "\n";

?>