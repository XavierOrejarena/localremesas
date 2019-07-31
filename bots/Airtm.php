#!/usr/bin/env php
<?php

$URL = file_get_contents("https://rates.airtm.io/es");
preg_match_all('/<span class="rate--general">/', $URL, $matches, PREG_OFFSET_CAPTURE);
preg_match_all('!\d+!', substr($URL, $matches[0][0][1], 43), $matches);
$tasa = $matches[0][0] . "." . $matches[0][1];
echo $tasa, "\n";
?>