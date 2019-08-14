<?php
header( 'Content-type: application/json' );
include "connect.php";

$sql = "SELECT prestamos.*, pagos_out.reg_date AS fecha
FROM prestamos
JOIN pagos_out ON pagos_out.id = prestamos.id_pago_out";
$result = mysqli_query($link, $sql);

while ($row = mysqli_fetch_assoc($result)) {
	if ($row['flag'] == 0) {
		$res['total'][] = $row;
	} else {
		$res['detallado'][] = $row;
	}
}

$sql = "SELECT id_usuario, -monto AS monto, reg_date AS fecha, bancos.divisa
FROM pagos_in
JOIN bancos ON bancos.id = pagos_in.id_banco
WHERE flag = 9
ORDER BY pagos_in.id DESC";
$result = mysqli_query($link, $sql);

while ($row = mysqli_fetch_assoc($result)) {
	$res['detallado'][] = $row;
}


echo json_encode($res);
?>