<?php
header( 'Content-type: application/json' );
include "connect.php";
$res = array( 'errores' => false );

$id_usuario = $_POST['id_usuario'];
$id_pago_in = $_POST['id_pago_in'];

for ($i=0; $i < sizeof($_POST['id_cuenta']); $i++) { 
	
	$id_cuenta = $_POST['id_cuenta'][$i];
	$monto = $_POST['monto'][$i];

	$sql = "INSERT INTO pagos_out (id_usuario, id_pago_in, id_cuenta, monto, estado, reg_date) VALUES ('$id_usuario', '$id_pago_in', '$id_cuenta', '$monto', 'EN ESPERA', DATE_ADD(NOW(),INTERVAL 3 HOUR));";
	if(mysqli_query($link, $sql)) {
		$res['mensajes'] = 'Pagos agregados existosamente';
		$res['errores'] = false;
	} else {
		$res['mensajes'] = 'Hubo un error agregando los pagos';
		$res['errores'] = true;
	}
}
echo json_encode($res);
?>