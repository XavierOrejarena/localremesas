<?php

$path = dirname(__FILE__) . "/db_backups/";
$ip_server = $_SERVER['SERVER_ADDR'];
$filename = $_POST['filename'];

if ($ip_server == "::1" ) {
    $result = exec('/usr/local/mysql/bin/mysql localremesas --password="NX)[XDCM5~=f" --user=xavierorejarena <'.$path.$filename,$output);
} else {
    $result = exec('mysql localremesas --password="NX)[XDCM5~=f" --user=xavierorejarena <'.$path.$filename,$output);
}
?>