<?php
header( 'Content-type: application/json' );
include "connect.php";
session_start();

$id = $_POST['id'];
$id_pago_out = $_POST['id_pago_out'];

if ($_SESSION['tipo'] == 'ADMIN') {
    
    if ($id == 'null') {
        if (mysqli_query($link, "DELETE FROM pagos_out WHERE id = '$id_pago_out'")) {
            $res['mensajes'] = "Pago eliminado forzadamente.";
            $res['errores'] = true;
        }
    } else {
        if (mysqli_query($link, "DELETE FROM pagos_out WHERE id_pago_in = '$id'")) {
            if (mysqli_query($link, "DELETE FROM pagos_in WHERE id = '$id'")) {
                $res['mensajes'] = "Pago eliminado exitosamente.";
                $res['errores'] = false;
            }
        } else {
            $res['mensajes'] = "No se pudo eliminar el pago.";
            $res['errores'] = true;
        }
    }
} else {
    $res['mensajes'] = "No tiene permiso.";
	$res['errores'] = true;
}

echo json_encode($res);
?>