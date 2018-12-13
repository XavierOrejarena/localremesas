<?php
header( 'Content-type: application/json' );
include "connect.php";


$nombre = $_POST['nombre'];
$saldo = $_POST['saldo'];
$divisa = $_POST['divisa'];

$sql = "INSERT INTO bancos (nombre, saldo, divisa) VALUES ('$nombre', '$saldo', '$divisa')";
$res = mysqli_query($link, $sql);

mysqli_query($res);


echo json_encode($res);
?>