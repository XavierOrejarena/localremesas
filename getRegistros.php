<?php
header( 'Content-type: application/json' );
include "connect.php";
$id = $_POST['id'];

$sql = "SELECT * FROM registros_bancos WHERE id_banco = '$id'";
$result = mysqli_query($link, $sql);

while($row = mysqli_fetch_assoc($result)) {
	$res[] = $row;
}

echo json_encode($res);

?>