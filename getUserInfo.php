<?php
header( 'Content-type: application/json' );
include "connect.php";


$id = $_POST['id'];

$sql = "SELECT id, username, tipo, reg_date FROM usuarios WHERE id = '$id'";

$res = mysqli_fetch_assoc(mysqli_query($link, $sql));

echo json_encode($res);
?>