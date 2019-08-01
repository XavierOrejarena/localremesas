<?php
header( 'Content-type: application/json' );
include "connect.php";


if ($_POST['id']) {
	$id = $_POST['id'];
	$sql = "UPDATE pagos_in SET estado = 'APROBADO' WHERE id = '$id'";
	$res = mysqli_query($link, $sql);
	$sql = "UPDATE pagos_out SET estado = 'PENDIENTE' WHERE id_pago_in = '$id'";
	mysqli_query($link, $sql);
	$monto = mysqli_fetch_assoc(mysqli_query($link, "SELECT monto from pagos_in WHERE id = '$id'"))['monto'];
	$divisa = mysqli_fetch_assoc(mysqli_query($link, "SELECT divisa from pagos_in WHERE id = '$id'"))['divisa'];
	$banco = mysqli_fetch_assoc(mysqli_query($link, "SELECT nombre from bancos WHERE id = (SELECT id_banco from pagos_in WHERE id = '$id')"))['nombre'];

	$token = '716396100:AAFbVh6W950S4goHt30TVUXW3cuKGdWQmKM';
    $chat_id = -1001297263006;
    $text = $monto . " " . $divisa . " --> " . $banco;
    file_get_contents("https://api.telegram.org/bot$token/sendMessage?chat_id=$chat_id&text=$text");
}

echo json_encode($res);
?>