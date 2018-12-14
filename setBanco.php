<?php
header( 'Content-type: application/json' );
include "connect.php";

$referencia = array_values($_POST)[0];
$monto = array_values($_POST)[1];
$id_banco = explode('r', key($_POST))[1];
$res = false;

if (mysqli_query($link, "UPDATE bancos SET saldo = bancos.saldo + '$monto' WHERE id = '$id_banco'")){
    $res = true;
    if($id = mysqli_fetch_assoc(mysqli_query($link, "SELECT id FROM pagos_in WHERE referencia = '$referencia' AND monto = '$monto' AND id_banco = '$id_banco'"))['id']) {
        $sql = "UPDATE pagos_in SET estado = 'APROBADA' WHERE id = '$id'";
        mysqli_query($link, $sql);
        $sql = "UPDATE pagos_out SET estado = 'PENDIENTE' WHERE id_pago_in = '$id'";
        mysqli_query($link, $sql);
    } 
}

echo json_encode($res);
?>