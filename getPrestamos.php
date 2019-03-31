<?php
header( 'Content-type: application/json' );
include "connect.php";

// $sql = "SELECT * FROM prestamos WHERE monto > 0";
$sql = "SELECT prestamos.*, bancos.nombre, bancos.divisa FROM prestamos, bancos WHERE prestamos.id_banco = bancos.id AND prestamos.monto > 0";
$result = mysqli_query($link, $sql);

while ($row = mysqli_fetch_assoc($result)) {
	$res[] = $row;
}

echo json_encode($res);
?>