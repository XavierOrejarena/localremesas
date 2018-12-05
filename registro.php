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

  $res = $link->query("INSERT INTO usuarios (username, password, tipo, reg_date) VALUES ('$username', '$password', '$tipo', DATE_ADD(NOW(),INTERVAL 3 HOUR));");

  $_SESSION['username'] = $username;
  $_SESSION['time'] = time();

}

echo json_encode($res);
?>