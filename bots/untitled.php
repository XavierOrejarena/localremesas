#!/usr/bin/env php
<?php

$word = "friend";
$URL = (file_get_contents("https://dictionary.cambridge.org/es/diccionario/ingles/$word"));
preg_match_all('/data-src-mp3="/', $URL, $matches, PREG_OFFSET_CAPTURE);
preg_match_all('/.mp3"/', $URL, $matches2, PREG_OFFSET_CAPTURE);
$text = substr($URL, $matches[0][0][1]+14, $matches2[0][0][1]-$matches[0][0][1]-10);

echo $text, "\n";

$ch = curl_init("https://dictionary.cambridge.org$text");
curl_setopt($ch, CURLOPT_HEADER, 0);
curl_setopt($ch, CURLOPT_NOBODY, 0);
curl_setopt($ch, CURLOPT_TIMEOUT, 5);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 1);
$output = curl_exec($ch);
$status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);
if ($status == 200) {
    file_put_contents(dirname(__FILE__) . "/words/". $word . ".mp3", $output);
}

?>