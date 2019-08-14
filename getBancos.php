<?php
header( 'Content-type: application/json' );
include "connect.php";

$sql = "SELECT * FROM bancos ORDER BY divisa DESC";
$result = mysqli_query($link, $sql);

while($row = mysqli_fetch_assoc($result)) {
	$res[] = $row;
}

echo json_encode($res);
?>