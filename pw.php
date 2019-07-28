<?php

include "connect.php";

$password = '*Ne356#wnTq6#P]"';
$password = password_hash($password, PASSWORD_DEFAULT);
$res = $link->query("UPDATE usuarios set password = '$password' WHERE id = 1");

?>