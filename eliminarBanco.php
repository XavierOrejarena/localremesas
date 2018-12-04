<?php
header( 'Content-type: application/json' );
include "connect.php";


$id = $_POST['id'];

$sql = "DELETE FROM bancos WHERE id = '$id'";
$res = mysqli_query($link, $sql);

mysqli_query($res);


echo json_encode($res);
?>