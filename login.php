<?php
  header( 'Content-type: application/json' );
  session_start();
  include 'timeSession.php';

  if (!empty($_POST)) {
    include 'connect.php';
    $username = $_POST['username'];
    $res['username'] = $username;


    if (password_verify($_POST['password'], mysqli_fetch_assoc(mysqli_query($link, "SELECT * FROM usuarios WHERE username = '$username'"))['password'])) {
      $res = true;
      $_SESSION['username'] = $username;
      $_SESSION['time'] = time();
      $_SESSION['tipo'] = mysqli_fetch_assoc(mysqli_query($link, "SELECT * FROM usuarios WHERE username = '$username'"))['tipo'];
    } else {
      $res = false;
    }

    echo json_encode($res);
  }

?>