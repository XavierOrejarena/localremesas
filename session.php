<?php
header( 'Content-type: application/json' );
session_start();

include 'timeSession.php';

if (!empty($_SESSION)) {
  include 'connect.php';
  
  $res = $_SESSION['username'];

}

echo json_encode($res);
?>