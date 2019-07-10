<?php
header( 'Content-type: application/json' );
$files = scandir(dirname(__FILE__)."/comprobantes_out");
// print_r($files);
echo json_encode($files);

?>