<?php
header( 'Content-type: application/json' );
include "connect.php";
$res = array( 'errores' => false );

$id_usuario = $_POST['id_usuario'];
$id_pago_in = $_POST['id_pago_in'];

for ($i=0; $i < sizeof($_POST['id_cuenta']); $i++) { 
	
	$id_cuenta = $_POST['id_cuenta'][$i];
	$monto = $_POST['monto'][$i];

	$sql = "INSERT INTO pagos_out (id_usuario, id_pago_in, id_cuenta, monto, estado, reg_date) VALUES ('$id_usuario', '$id_pago_in', '$id_cuenta', '$monto', 'EN ESPERA', DATE_ADD(NOW(),INTERVAL 3 HOUR))";
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
	$res['mensajes'][] = 'Pago saliente aprobado exitosamente';
	$res['errores'][] = false;
	$res['i'] = $i;
} elseif ($i > 1) {
	$res['mensajes'][] = 'La cantidad de pagos salientes es mayor a 1, no se puede aprobar automaticamente.';
	$res['errores'][] = true;
}

$res['flag'] = $_POST['flag'];

echo json_encode($res);
?>