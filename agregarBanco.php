<?php
header( 'Content-type: application/json' );
include "connect.php";


$nombre = $_POST['nombre'];
$saldo = $_POST['saldo'];

$sql = "INSERT INTO bancos (nombre, saldo) VALUES ('$nombre', '$saldo')";
$res = mysqli_query($link, $sql);

mysqli_query($res);


echo json_encode($res);
?>