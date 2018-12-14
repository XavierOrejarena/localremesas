<?php
header( 'Content-type: application/json' );
include "connect.php";

// $sql = "SELECT * FROM pagos_in WHERE estado = 'PENDIENTE'";

$sql = "SELECT pagos_in.*, bancos.nombre, bancos.divisa FROM pagos_in JOIN bancos ON pagos_in.id_banco = bancos.id WHERE pagos_in.estado = 'PENDIENTE'";
$result = mysqli_query($link, $sql);

while ($row = mysqli_fetch_assoc($result)) {
	$res[] = $row;
}

echo json_encode($res);
?>