<?php
header( 'Content-type: application/json' );
session_start();
include "connect.php";

if ($_SESSION['tipo'] == 'ADMIN') {
	$id_cuenta = $_POST['id_cuenta'];
	mysqli_query($link, "DELETE FROM usuarios_cuentas WHERE id_cuenta = '$id_cuenta'");
	mysqli_query($link, "DELETE FROM cuentas WHERE id = '$id_cuenta'");
	$res['mensajes'][] = 'Se ha eliminado la cuenta de este usuario.';
	$res['errores'][] = false;
} else {
	$res['mensajes'][] = 'No tiene permiso.';
	$res['errores'][] = true;
}

echo json_encode($res);
?>