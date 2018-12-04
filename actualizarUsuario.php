<?php
header( 'Content-type: application/json' );
include "connect.php";

$id = $_POST['id'];
$tipo = $_POST['tipo'];

echo json_encode(mysqli_query($link, "UPDATE usuarios SET tipo = '$tipo' WHERE id = '$id'"));

?>