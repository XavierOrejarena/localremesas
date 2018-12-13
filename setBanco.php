<?php
header( 'Content-type: application/json' );
include "connect.php";

$referencia = array_values($_POST)[0];
$monto = array_values($_POST)[1];
$id = explode('r', key($_POST))[1];

$res = mysqli_query($link, "UPDATE bancos SET saldo = bancos.saldo + '$monto' WHERE id = '$id'");

echo json_encode($res);
?>