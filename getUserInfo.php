<?php
header( 'Content-type: application/json' );
include "connect.php";


$id = $_POST['id'];

$sql = "SELECT * FROM usuarios WHERE id = '$id'";

$res = mysqli_fetch_array(mysqli_query($link, $sql));

echo json_encode($res);
?>