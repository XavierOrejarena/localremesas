<?php
header( 'Content-type: application/json' );
include "connect.php";
$res = array( 'errores' => false );

$id_usuario = $_POST['id_usuario'];

if ($_POST['flag'] == 2) {
	$id_pago_in = 1;
	$estado = 'PENDIENTE';
} else {
	$id_pago_in = $_POST['id_pago_in'];
	$estado = 'EN ESPERA';
}


for ($i=0; $i < sizeof($_POST['id_cuenta']); $i++) {
	
	$id_cuenta = $_POST['id_cuenta'][$i];
	$monto = $_POST['monto'][$i];

	$sql = "INSERT INTO pagos_out (id_usuario, id_pago_in, id_cuenta, monto, estado, reg_date) VALUES ('$id_usuario', '$id_pago_in', '$id_cuenta', '$monto', '$estado', DATE_ADD(NOW(),INTERVAL 3 HOUR))";
	if(mysqli_query($link, $sql)) {
		$res['mensajes'][] = 'Pagos salientes agregados existosamente';
		$res['errores'][] = false;
	} else {
		$res['mensajes'][] = 'Hubo un error agregando los pagos salientes';
		$res['errores'][] = true;
	}
}

if ($_POST['flag'] == 1 && $i == 1) {
	$sql = "UPDATE pagos_out SET estado = 'PENDIENTE' WHERE id_pago_in = '$id_pago_in'";
	mysqli_query($link, $sql);
	if ($_POST['restar'] == 1) {
		$sql = "UPDATE pagos_out SET monto = monto - 7.5*(SELECT tasa FROM pagos_in WHERE id = '$id_pago_in') WHERE id_pago_in = '$id_pago_in'";
		mysqli_query($link, $sql);
		$res['mensajes'][] = 'Se ha descontado la comision de un pago saliente';
		$res['errores'][] = false;
	}
	$res['mensajes'][] = 'Pago saliente aprobado exitosamente';
	$res['errores'][] = false;
} elseif ($_POST['flag'] == 1 && $i > 1) {
	$res['mensajes'][] = 'La cantidad de pagos salientes es mayor a 1, no se puede aprobar automaticamente.';
	$res['errores'][] = true;
} elseif ($_POST['flag'] == 2) {
	// $sql = "UPDATE pagos_out SET estado = 'APROBADO' WHERE id_pago_in = '$id_pago_in'";
}

echo json_encode($res);
?>