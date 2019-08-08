<?php
header( 'Content-type: application/json' );
include "connect.php";


$id = $_POST['id'];

$id_banco =     mysqli_fetch_assoc(mysqli_query($link, "SELECT id_banco     FROM pagos_in WHERE id = '$id'"))['id_banco'];
$monto =        mysqli_fetch_assoc(mysqli_query($link, "SELECT monto        FROM pagos_in WHERE id = '$id'"))['monto'];
$id_usuario =   mysqli_fetch_assoc(mysqli_query($link, "SELECT id_usuario   FROM pagos_in WHERE id = '$id'"))['id_usuario'];
$flag =         mysqli_fetch_assoc(mysqli_query($link, "SELECT flag         FROM pagos_in WHERE id = '$id'"))['flag'];
$divisa =       mysqli_fetch_assoc(mysqli_query($link, "SELECT divisa       FROM bancos WHERE id = '$id_banco'"))['divisa'];

if ($flag == 9) {
    $res['prestamos'] = mysqli_query($link, "UPDATE prestamos SET monto = monto + $monto WHERE id_usuario = '$id_usuario' AND divisa = '$divisa' AND flag = 0");
}

// $id_pago_out = mysqli_fetch_assoc(mysqli_query($link, "SELECT id FROM pagos_out WHERE id_pago_in = '$id'"))['id'];
mysqli_query($link, "UPDATE pagos_out SET id_pago_in = null WHERE id_pago_in = '$id'");
$res['pagos_in'] = mysqli_query($link, "DELETE FROM pagos_in WHERE id = '$id'");
// $res['pago_out'] = mysqli_query($link, "DELETE FROM pagos_out WHERE id = '$id_pago_out'");
mysqli_query($link, "UPDATE bancos SET saldo = saldo - '$monto' WHERE id = '$id_banco'");

echo json_encode($res);
?>