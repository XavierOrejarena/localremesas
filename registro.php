<?php
header( 'Content-type: application/json' );
session_start();

// include 'timeSession.php';

if (!empty($_POST)) {
  include 'connect.php';
  $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
  $username = $_POST['username'];

  if (empty($_POST['tipo'])) {
    $tipo = 'REGULAR';
  } else {
    $tipo = $_POST['tipo'];
  }

  $res = $link->query("INSERT INTO usuarios (username, password, tipo) VALUES ('$username', '$password', '$tipo')");

  $_SESSION['username'] = $username;
  $_SESSION['time'] = time();

}

echo json_encode($res);
?>