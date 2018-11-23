<?php
header( 'Content-type: application/json' );
include "connect.php";


if ($_POST['id']) {
	$id = $_POST['id'];
	$sql = "UPDATE pagos_in SET estado = 'APROBADA' WHERE id = '$id'";
	$res = mysqli_query($link, $sql);
	$sql = "UPDATE pagos_out SET estado = 'PENDIENTE' WHERE id_pago_in = '$id'";
	mysqli_query($link, $sql);
}

echo json_encode($res);
?>