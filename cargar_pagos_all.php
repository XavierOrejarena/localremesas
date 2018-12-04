<?php
header( 'Content-type: application/json' );
include "connect.php";

$result = mysqli_query($link, 
"SELECT DISTINCT pagos_in.*, cuentas.nombre 
FROM pagos_in, cuentas, pagos_out 
WHERE pagos_out.id_cuenta = cuentas.id 
AND pagos_in.estado = 'RECHAZADA'
AND pagos_out.id_pago_in = pagos_in.id
AND EXTRACT(DAY FROM pagos_in.reg_date) = EXTRACT(DAY FROM CURDATE())");



while ($row = mysqli_fetch_array($result)) {
	$res[in][] = $row;
}

$result = mysqli_query($link, "SELECT * FROM pagos_in WHERE estado = 'RECHAZADA'");

while ($row = mysqli_fetch_array($result)) {
	$res[out][] = $row;
}

echo json_encode($res);
?>