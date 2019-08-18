<?php
header( 'Content-type: application/json' );
include "connect.php";

session_start();

if ($_SESSION['tipo'] == 'ADMIN') {
	$id = $_POST['id'];
	mysqli_query($link, "UPDATE pagos_in SET estado = 'APROBADO' WHERE id = '$id'");
	mysqli_query($link, "UPDATE pagos_out SET estado = 'PENDIENTE' WHERE id_pago_in = '$id'");
	$res['mensajes'] = "Pago aprobado exitosamente.";
	$res['errores'] = false;
	$monto = mysqli_fetch_assoc(mysqli_query($link, "SELECT monto from pagos_in WHERE id = '$id'"))['monto'];
	$divisa= mysqli_fetch_assoc(mysqli_query($link, "SELECT nombre from bancos WHERE id = (SELECT id_banco from pagos_in WHERE id = '$id')"))['divisa'];
	$banco = mysqli_fetch_assoc(mysqli_query($link, "SELECT nombre from bancos WHERE id = (SELECT id_banco from pagos_in WHERE id = '$id')"))['nombre'];
	

	$ip_server = $_SERVER['SERVER_ADDR'];
    if ($ip_server == "::1" ) {

	}else {
		$token = '716396100:AAFbVh6W950S4goHt30TVUXW3cuKGdWQmKM';
		$chat_id = -1001297263006;
		$text = $monto . " " . $divisa . " --> " . $banco;
		file_get_contents("https://api.telegram.org/bot$token/sendMessage?chat_id=$chat_id&text=$text");
	}
} else {
	$res['mensajes'] = "No tiene permiso.";
	$res['errores'] = true;
}

echo json_encode($res);
?>