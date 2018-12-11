<?php
header( 'Content-type: application/json' );
include "connect.php";

$id = $_POST['id'];

$sql = "SELECT * FROM pagos_in WHERE id_usuario = '$id' AND referencia = 0 AND estado = 'PAGADA'";
$result = mysqli_query($link, $sql);

while ($row = mysqli_fetch_array($result)) {
	$res[] = $row;
}

echo json_encode($res);

?>