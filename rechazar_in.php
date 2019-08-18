<?php
header( 'Content-type: application/json' );
include "connect.php";
session_start();

if ($_SESSION['tipo'] == 'ADMIN') {
	$id = $_POST['id'];
	$sql = "UPDATE pagos_in SET estado = 'RECHAZADA' WHERE id = '$id'";
	mysqli_query($link, $sql);
	$sql = "UPDATE pagos_out SET estado = 'RECHAZADA' WHERE id_pago_in = '$id'";
	mysqli_query($link, $sql);
	$res['mensajes'] = "Pago rechazado exitosamente.";
	$res['errores'] = false;
} else {
	$res['mensajes'] = "No tiene permiso.";
	$res['errores'] = true;
}

echo json_encode($res);
?>