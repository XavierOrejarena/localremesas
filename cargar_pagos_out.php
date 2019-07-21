<?php
header( 'Content-type: application/json' );
include "connect.php";
// $sql = "SELECT pagos_out.*, cuentas.* FROM pagos_out, cuentas WHERE pagos_out.id_cuenta = cuentas.id";
// $sql = "SELECT pagos_out.id AS 'ide', pagos_out.*, cuentas.* FROM pagos_out, cuentas WHERE pagos_out.id_cuenta = cuentas.id AND pagos_out.estado = 'PENDIENTE'";
// $sql = "SELECT * FROM pagos_out WHERE estado = 'PENDIENTE'";
// $sql = "SELECT pagos_out.id AS 'ide', pagos_out.*, cuentas.*
$sql = "SELECT pagos_out.id AS id_pago_out, pagos_out.id_usuario, pagos_out.id_pago_in, pagos_out.monto, cuentas.*
FROM pagos_out
INNER JOIN cuentas ON cuentas.id = pagos_out.id_cuenta
WHERE pagos_out.estado = 'PENDIENTE'";
$result = mysqli_query($link, $sql);

while ($row = mysqli_fetch_assoc($result)) {
	$res[] = $row;
}

echo json_encode($res);
?>