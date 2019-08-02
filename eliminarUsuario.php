<?php
header( 'Content-type: application/json' );
include "connect.php";


$id = $_POST['id'];

if (mysqli_num_rows(mysqli_query($link, "SELECT * FROM pagos_in WHERE id_usuario = '$id'")) == 0 ) {
    $res = mysqli_query($link, "DELETE FROM usuarios WHERE id = '$id'");
}


echo json_encode($res);
?>