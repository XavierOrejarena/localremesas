<?php
header( 'Content-type: application/json' );
include "connect.php";


$id = $_POST['id'];
$saldo = $_POST['saldo'];

$sql = "UPDATE bancos SET saldo = '$saldo' WHERE id = '$id'";
$res = mysqli_query($link, $sql);

mysqli_query($res);


echo json_encode($res);
?>