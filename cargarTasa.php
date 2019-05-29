<?php
header( 'Content-type: application/json' );
include "connect.php";

$divisa = $_POST['divisa'];

$sql = "SELECT tasa FROM tasas WHERE divisa = '$divisa'";
$res = mysqli_fetch_array(mysqli_query($link, $sql));

echo json_encode($res['tasa']);
?>