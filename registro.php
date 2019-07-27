<?php
header( 'Content-type: application/json' );
session_start();

// include 'timeSession.php';

$offset=-4*60*60; //converting 5 hours to seconds.
$dateFormat="Y-m-d H:i:s";
$timeNdate=gmdate($dateFormat, time()+$offset);

if (!empty($_POST)) {
  include 'connect.php';
  $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
  $username = $_POST['username'];

  if (empty($_POST['tipo'])) {
    $tipo = 'REGULAR';
  } else {
    $tipo = $_POST['tipo'];
  }

  $res = $link->query("INSERT INTO usuarios (username, password, tipo, reg_date) VALUES ('$username', '$password', '$tipo', '$timeNdate')");

  $_SESSION['username'] = $username;
  $_SESSION['time'] = time();

}

echo json_encode($res);
?>