<?php
header( 'Content-type: application/json' );
include "connect.php";

if ($_POST['username']) {
	$username = $_POST['username'];

    // if (mysqli_query($link, "SELECT * FROM usuarios WHERE username = '$username'")->num_rows > 0) {
    // 	$res = true;
    // } else {
    // 	$res = false;
    // }
}

echo json_encode(mysqli_query($link, "SELECT * FROM usuarios WHERE username = '$username'")->num_rows > 0);
?>