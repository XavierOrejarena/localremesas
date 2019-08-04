<?php
header( 'Content-type: application/json' );
include "connect.php";

$sql = "SELECT id, nombre, apellido, tlf, correo, RUC, DNI, CE, PASAPORTE FROM usuarios";
$result = mysqli_query($link, $sql);

while ($row = mysqli_fetch_assoc($result)) {
	$res[] = $row;
}

echo json_encode($res);
?>