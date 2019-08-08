#!/usr/bin/env php
<?php

// $URL = (file_get_contents("https://www.rextie.com/"));
$curl = curl_init();
curl_setopt($curl, CURLOPT_URL, "https://www.rextie.com/");
curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 10);
curl_setopt($curl, CURLOPT_TIMEOUT, 10);

curl_exec($curl);
// echo $URL;
// preg_match_all('/>cambiar/', $URL, $matches, PREG_OFFSET_CAPTURE);
// print_r($matches);
// preg_match_all('!\d+!', substr($URL, $matches[0][0][1], 100), $matches);
// $tasa = $matches[0][31][0] . "." . $matches[0][31][1];
// echo $tasa, "\n";






// $opts = array('http' =>
//   array(
//     'method'  => 'GET',
//     'header'  => "Content-Type: text/html",
//     'content' => '',
//     'timeout' => 1
//   )
// );

// $context  = stream_context_create($opts);
// $url = 'https://rates.airtm.io/es';

// while (1) {
//     $URL = file_get_contents($url, true, $context);
//     if ($URL) {
//         break;
//     }
// }

// $URL = file_get_contents("https://rates.airtm.io/es");
// preg_match_all('/<span class="rate--general">/', $URL, $matches, PREG_OFFSET_CAPTURE);
// preg_match_all('!\d+!', substr($URL, $matches[0][0][1], 43), $matches);
// $tasa = $matches[0][0] . "." . $matches[0][1];
// echo $tasa, "\n";

?>