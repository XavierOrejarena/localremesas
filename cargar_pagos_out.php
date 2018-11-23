<?php
header( 'Content-type: application/json' );
include "connect.php";
// $sql = "SELECT pagos_out.*, cuentas.* FROM pagos_out, cuentas WHERE pagos_out.id_cuenta = cuentas.id";
$sql = "SELECT pagos_out.*, cuentas.* FROM pagos_out, cuentas WHERE pagos_out.id_cuenta = cuentas.id AND pagos_out.estado = 'PENDIENTE'";
$result = mysqli_query($link, $sql);

while ($row = mysqli_fetch_array($result)) {
	$res[] = $row;
}

echo json_encode($res);
?>