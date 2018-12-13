<?php
header( 'Content-type: application/json' );
include "connect.php";

$referencia = array_values($_POST)[0];
$monto = array_values($_POST)[1];
$id = explode('r', key($_POST))[1];
// $res = false;

if (mysqli_query($link, "UPDATE bancos SET saldo = bancos.saldo + '$monto' WHERE id = '$id'")){
    if(mysqli_query($link, "INSERT INTO depositos (id_banco, monto, referencia) VALUES ('$id', '$monto', '$referencia')")) {
        $res = true;
    } else {
        $res['monto'] = $monto;
        $res['referencia'] = $referencia;
        $res['id'] = $id;
    }
}

echo json_encode($res);
?>