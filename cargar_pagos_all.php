<?php
header( 'Content-type: application/json' );
include "connect.php";

$result = mysqli_query($link,
// "SELECT DISTINCT pagos_in.*, cuentas.nombre, bancos.nombre AS name, bancos.divisa
// FROM bancos, pagos_in, pagos_out
// JOIN cuentas ON cuentas.id = pagos_out.id_cuenta
// WHERE pagos_in.reg_date > NOW() - INTERVAL 21 HOUR
// AND bancos.id = pagos_in.id_banco");
"SELECT pagos_in.*, cuentas.nombre, bancos.nombre AS name, bancos.divisa
FROM bancos, pagos_in, pagos_out
JOIN cuentas ON cuentas.id = pagos_out.id_cuenta
WHERE pagos_in.reg_date > NOW() - INTERVAL 21 HOUR
AND pagos_in.estado = 'RECHAZADA'
AND pagos_out.id_pago_in = pagos_in.id
AND bancos.id = pagos_in.id_banco");

while ($row = mysqli_fetch_array($result)) {
	$res[in][] = $row;
}

// $result = mysqli_query($link, 
// "SELECT pagos_out.*, pagos_in.tasa, pagos_in.id AS id_pago_in, pagos_in.monto AS amount, bancos.divisa, bancos.nombre AS banco_in
// FROM bancos, pagos_out
// JOIN pagos_in ON pagos_in.id = pagos_out.id_pago_in
// WHERE pagos_out.estado = 'PAGADO'
// AND bancos.id = pagos_in.id_banco
// AND pagos_in.reg_date > NOW() - INTERVAL 21 HOUR");

$result = mysqli_query($link, 
"SELECT pagos_out.*, pagos_in.tasa, pagos_in.id AS id_pago_in, pagos_in.monto AS amount, bancos.divisa, bancos.nombre AS banco_in, cuentas.cuenta as banco_out
FROM bancos, pagos_out
JOIN pagos_in ON pagos_in.id = pagos_out.id_pago_in
JOIN cuentas ON cuentas.id = pagos_out.id_cuenta
WHERE pagos_out.estado = 'PAGADO'
AND bancos.id = pagos_in.id_banco
AND pagos_in.reg_date > NOW() - INTERVAL 21 HOUR");

while ($row = mysqli_fetch_array($result)) {
	$res[out][] = $row;
}

echo json_encode($res);
?>