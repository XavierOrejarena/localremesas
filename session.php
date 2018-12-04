<?php
header( 'Content-type: application/json' );
session_start();

include 'timeSession.php';

if (!empty($_SESSION)) {
  include 'connect.php';
  
  $username = $_SESSION['username'];
  $res = mysqli_fetch_assoc(mysqli_query($link, "SELECT * FROM usuarios WHERE username = '$username'"))['tipo'];

}

echo json_encode($res);
?>