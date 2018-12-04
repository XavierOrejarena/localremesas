<?php
header( 'Content-type: application/json' );
include "connect.php";
// $sql = "SELECT * FROM pagos_in WHERE estado = 'PENDIENTE'";
$sql = "SELECT * FROM pagos_in WHERE estado = 'PENDIENTE'";
$result = mysqli_query($link, $sql);

while ($row = mysqli_fetch_array($result)) {
	$res[] = $row;
}

echo json_encode($res);
?>