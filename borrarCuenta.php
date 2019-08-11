<?php
header( 'Content-type: application/json' );
include "connect.php";


if (mysqli_connect_errno()) {
	$res['connect'] = false;
}else {
	if ($_POST['tipo_usuario'] == 'ADMIN') {
		$id_cuenta = $_POST['id_cuenta'];
		mysqli_query($link, "DELETE FROM usuarios_cuentas WHERE id_cuenta = '$id_cuenta'");
		mysqli_query($link, "DELETE FROM cuentas WHERE id = '$id_cuenta'");
	} else {
		$res['mensajes'][] = 'No tiene permiso.';
		$res['errores'][] = true;
	}
}
echo json_encode($res);
?>