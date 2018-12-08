<?php
header( 'Content-type: application/json' );
include "connect.php";

$result = mysqli_query($link, 
"SELECT DISTINCT pagos_in.*, cuentas.nombre 
FROM cuentas, pagos_out, pagos_in
WHERE pagos_out.id_cuenta = cuentas.id 
AND pagos_in.estado = 'RECHAZADA'
-- AND pagos_out.id_pago_in = pagos_in.id
AND pagos_in.reg_date > NOW() - INTERVAL 21 HOUR");



while ($row = mysqli_fetch_array($result)) {
	$res[in][] = $row;
}

$result = mysqli_query($link, "SELECT * FROM pagos_out WHERE estado = 'PAGADO' AND reg_date > NOW() - INTERVAL 21 HOUR");

while ($row = mysqli_fetch_array($result)) {
	$res[out][] = $row;
}

echo json_encode($res);
?>