<?php
header( 'Content-type: application/json' );
include "connect.php";

$id = $_POST['id'];

$res[] = mysqli_query($link, "DELETE FROM pagos_out WHERE id_pago_in = '$id'");
$res[] = mysqli_query($link, "DELETE FROM pagos_in WHERE id = '$id'");

echo json_encode($res);
?>