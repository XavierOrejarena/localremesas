<?php
header( 'Content-type: application/json' );
include "connect.php";

$result = mysqli_query($link, 
"SELECT DISTINCT pagos_in.*, cuentas.nombre
FROM pagos_in, pagos_out
JOIN cuentas ON cuentas.id = pagos_out.id_cuenta
WHERE pagos_in.reg_date > NOW() - INTERVAL 21 HOUR
AND pagos_in.estado = 'RECHAZADA'
AND pagos_out.id_pago_in = pagos_in.id");

while ($row = mysqli_fetch_array($result)) {
	$res[in][] = $row;
}

$result = mysqli_query($link, "SELECT * FROM pagos_out WHERE estado = 'RECHAZADO' OR estado = 'PAGADO' AND reg_date > NOW() - INTERVAL 21 HOUR");

while ($row = mysqli_fetch_array($result)) {
	$res[out][] = $row;
}

echo json_encode($res);
?>