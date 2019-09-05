<?php
header( 'Content-type: application/json' );
include "connect.php";
$offset = -4*60*60; //converting 5 hours to seconds.
$dateFormat = "Y-m-d H:i:s";
$timeNdate = gmdate($dateFormat, time() + $offset);

$id = $_POST['id'];
$monto = $_POST['monto'];
$nota = $_POST['nota'];

if (mysqli_num_rows(mysqli_query($link, "SELECT * FROM bancos WHERE id = '$id'")) > 0 ) {
    mysqli_query($link, "UPDATE bancos SET saldo = saldo + '$monto' WHERE id = '$id'");
    $sql = "INSERT INTO registros_bancos (id_banco, monto, nota, reg_date) VALUES ('$id', '$monto', '$nota', '$timeNdate')";
    $res['errores'] = !mysqli_query($link, "INSERT INTO registros_bancos (id_banco, monto, nota, reg_date) VALUES ('$id', '$monto', '$nota', '$timeNdate')");
    $res['mensajes'] = "Registro agregado exitosamente.";
    if ($res['errores']) {
        $res['mensajes'] = "Hubo un error agregando el registro.";
    }
} else {
    $res['mensajes'] = "Error, el banco no existe.";
    $res['errores'] = true;
}

echo json_encode($res);

?>