<?php
header( 'Content-type: application/json' );
include "connect.php";
session_start();

$id = $_POST['id'];

if ($_SESSION['tipo'] == 'ADMIN') {
    mysqli_query($link, "DELETE FROM pagos_out WHERE id_pago_in = '$id'");
    mysqli_query($link, "DELETE FROM pagos_in WHERE id = '$id'");
    $res['mensajes'] = "Pago eliminado exitosamente.";
	$res['errores'] = false;
} else {
    $res['mensajes'] = "No tiene permiso.";
	$res['errores'] = true;
}

echo json_encode($res);
?>