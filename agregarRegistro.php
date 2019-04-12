<?php
header( 'Content-type: application/json' );
include "connect.php";
$id = $_POST['id'];
$monto = $_POST['monto'];
$nota = $_POST['nota'];

mysqli_query($link, "UPDATE bancos SET saldo = saldo + '$monto' WHERE id = '$id'");

echo json_encode(mysqli_query($link, "INSERT INTO registros_bancos (id_banco, monto, nota) VALUES ('$id', '$monto', '$nota')"));

?>