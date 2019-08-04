<?php
header( 'Content-type: application/json' );
include "connect.php";

$files = glob('db_backups/*');
foreach($files as $file){
    $res[] = str_replace('db_backups/', "", $file);
}

echo json_encode($res);
?>