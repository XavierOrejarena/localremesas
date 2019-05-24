<?php
header( 'Content-type: application/json' );
include "connect.php";

$amount = $_POST['amount'];
$referencia = $_POST['referencia'];
$id_banco = $_POST['id_banco'];
$id_usuario = $_POST['id_usuario'];

if (!empty($_POST['id_usuario'])) {
    $result = mysqli_query($link, "SELECT id FROM pagos_in WHERE referencia = '$referencia' AND monto = '$amount' AND id_banco = '$id_banco' AND flag = 1");
    if ($result->num_rows == 1) {
        $id_pago_in = mysqli_fetch_assoc($result)['id'];
        mysqli_query($link, "UPDATE pagos_in SET flag = 9, id_usuario = '$id_usuario' WHERE id = '$id_pago_in'");
        mysqli_query($link, "UPDATE prestamos SET monto = monto - '$amount' WHERE id_usuario = '$id_usuario' AND id_banco = '$id_banco'");
        $res[mensaje] = 'Prestamo actualizado exitosamente.';
        $res[error] = false;
    } else {
        $res[mensaje] = 'No se encontró el pago.';
        $res[error] = true;
    }
} else {
    $res[mensaje] = 'Ingrese ID de usuario.';
    $res[error] = true;
}



echo json_encode($res);
?>