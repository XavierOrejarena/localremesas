<?php
header( 'Content-type: application/json' );
include "connect.php";

$result = mysqli_query($link, "SELECT * FROM tasas");


while($row = $result->fetch_assoc()) {
    $res[] = $row;
}

echo json_encode($res);
?>