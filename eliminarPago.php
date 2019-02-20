<?php
header( 'Content-type: application/json' );
include "connect.php";


$id = $_POST['id'];

$id_banco = mysqli_fetch_assoc(mysqli_query($link, "SELECT id_banco FROM pagos_in WHERE id = '$id'"))['id_banco'];
$monto = mysqli_fetch_assoc(mysqli_query($link, "SELECT monto FROM pagos_in WHERE id = '$id'"))['monto'];


$sql = "DELETE FROM pagos_in WHERE id = '$id'";
$res = mysqli_query($link, $sql);
mysqli_query($link, "UPDATE bancos SET saldo = saldo - '$monto' WHERE id = '$id_banco'");

echo json_encode($id);
?>