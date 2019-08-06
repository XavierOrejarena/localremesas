<?php
header( 'Content-type: application/json' );
include "connect.php";

$id_registro = $_POST['id_registro'];
$banco_id = $_POST['banco_id'];
$monto = $_POST['monto'];
$nota = $_POST['nota'];

$monto_anterior = mysqli_fetch_array(mysqli_query($link, "SELECT monto FROM registros_bancos WHERE id = '$id_registro'"))['monto'];
$res['bancos_1'] = mysqli_query($link, "UPDATE bancos SET saldo = saldo - '$monto_anterior' WHERE id = '$banco_id'");



$res['bancos_2'] = mysqli_query($link, "UPDATE bancos SET saldo = saldo + '$monto' WHERE id = '$banco_id'");
$res['registros_bancos'] = mysqli_query($link, "UPDATE registros_bancos SET monto = '$monto', nota = '$nota' WHERE id = '$id_registro'");

echo json_encode($res);

?>