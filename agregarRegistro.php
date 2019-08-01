<?php
header( 'Content-type: application/json' );
include "connect.php";
$offset = -4*60*60; //converting 5 hours to seconds.
$dateFormat = "Y-m-d H:i:s";
$timeNdate = gmdate($dateFormat, time() + $offset);

$id = $_POST['id'];
$monto = $_POST['monto'];
$nota = $_POST['nota'];

// $res[] = $id;
// $res[] = $monto;
// $res[] = $nota;
// $res[] = $timeNdate;

mysqli_query($link, "UPDATE bancos SET saldo = saldo + '$monto' WHERE id = '$id'");
$res = mysqli_query($link, "INSERT INTO registros_bancos (id_banco, monto, nota, reg_date) VALUES ('$id', '$monto', '$nota', '$timeNdate')");

echo json_encode($res);

?>