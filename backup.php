#!/usr/bin/env php
<?php

$offset = -4*60*60; //converting 5 hours to seconds.
$dateFormat = "Y-m-d_H.i.s";
$timeNdate = gmdate($dateFormat, time()+$offset);
$filename = $timeNdate . '.sql';
$path = dirname(__FILE__) . "/db_backups/";
$ip_server = $_SERVER['SERVER_ADDR'];

if ($ip_server == "::1" ) {
    $result = exec('/usr/local/mysql/bin/mysqldump localremesas --password="NX)[XDCM5~=f" --user=xavierorejarena --single-transaction >'.$path.$filename,$output);
} else {
    $result = exec('mysqldump localremesas --password="NX)[XDCM5~=f" --user=xavierorejarena --single-transaction >'.$path.$filename,$output);
}

?>