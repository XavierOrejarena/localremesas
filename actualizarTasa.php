<?php
header( 'Content-type: application/json' );
include "connect.php";

$divisa = $_POST['divisa'];
$tasa = $_POST['tasa'];

echo json_encode(mysqli_query($link, "UPDATE tasas SET tasa = '$tasa' WHERE divisa = '$divisa'"));

?>