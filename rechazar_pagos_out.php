<?php
header( 'Content-type: application/json' );
include "connect.php";

$id = $_POST['id'];

$sql = "UPDATE pagos_out SET estado = 'RECHAZADO' WHERE id = '$id'";
$res = mysqli_query($link, $sql);

echo json_encode($res);
?>