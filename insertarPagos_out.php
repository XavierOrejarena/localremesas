<?php
header( 'Content-type: application/json' );
include "connect.php";
$res = array( 'errores' => false );

$id_usuario = $_POST['id_usuario'];
$id_pago_in = $_POST['id_pago_in'];
if ($_POST['flag'] == 2) {
	$estado = 'PENDIENTE';
} else {
	$estado = 'EN ESPERA';
}

if ($_POST['flag'] == 3) {
	$res['mensajes'][] = 'Hubo un error agregando el pago saliente';
	$res['errores'][] = true;
}else {
	for ($i=0; $i < sizeof($_POST['id_cuenta']); $i++) {
		
		$id_cuenta = $_POST['id_cuenta'][$i];
		$monto = $_POST['monto'][$i];
	
		$sql = "INSERT INTO pagos_out (id_usuario, id_pago_in, id_cuenta, monto, estado, reg_date) VALUES ('$id_usuario', '$id_pago_in', '$id_cuenta', '$monto', '$estado', DATE_ADD(NOW(),INTERVAL 3 HOUR))";
		if(mysqli_query($link, $sql)) {
			$res['mensajes'][] = 'Pago saliente agregado existosamente';
			$res['errores'][] = false;
			if ($i == 0) {
				$id_pago_out = mysqli_fetch_array((mysqli_query($link, "SELECT LAST_INSERT_ID()")))[0];
			}
		} else {
			$res['mensajes'][] = 'Hubo un error agregando el pago saliente';
			$res['errores'][] = true;
		}
	}
	if ($_POST['flag'] == 1) {
		$sql = "UPDATE pagos_out SET estado = 'PENDIENTE' WHERE id_pago_in = '$id_pago_in'";
		mysqli_query($link, $sql);
		if ($_POST['restar'] == 1) {
			$sql = "UPDATE pagos_out SET monto = monto - 7.5*(SELECT tasa FROM pagos_in WHERE id = '$id_pago_in') WHERE id = '$id_pago_out'";
			mysqli_query($link, $sql);
			$res['mensajes'][] = 'Se ha descontado la comision de un pago saliente';
			$res['errores'][] = false;
		}
		$res['mensajes'][] = 'Pago saliente aprobado exitosamente';
		$res['errores'][] = false;
	}
}
// } elseif ($_POST['flag'] == 1 && $i > 1) {
// 	$res['mensajes'][] = 'La cantidad de pagos salientes es mayor a 1, no se puede aprobar automaticamente.';
// 	$res['errores'][] = true;
// } elseif ($_POST['flag'] == 2) {
// 	$sql = "UPDATE pagos_out SET estado = 'APROBADO' WHERE id_pago_in = '$id_pago_in'";
// }

echo json_encode($res);
?>