<?php
header( 'Content-type: application/json' );
include "connect.php";

$amount = $_POST['amount'];
$referencia = $_POST['referencia'];
$id_banco = $_POST['id_banco'];
$id_usuario = $_POST['id_usuario'];
$nombre_banco = mysqli_fetch_assoc(mysqli_query($link, "SELECT nombre FROM bancos WHERE id = '$id_banco'"))['nombre'];

if (!empty($_POST['id_usuario'])) {
    if ($nombre_banco == 'BCP') {
        $result = mysqli_query($link, "SELECT id FROM pagos_in WHERE RIGHT(referencia, 6) = RIGHT('$referencia', 6) AND monto = '$amount' AND id_banco = '$id_banco' AND flag = 1");
    } else {
        $result = mysqli_query($link, "SELECT id FROM pagos_in WHERE referencia = '$referencia' AND monto = '$amount' AND id_banco = '$id_banco' AND flag = 1");
    }
    if ($result->num_rows == 1) {
        $id_pago_in = mysqli_fetch_assoc($result)['id'];
        mysqli_query($link, "UPDATE pagos_in SET flag = 9, id_usuario = '$id_usuario' WHERE id = '$id_pago_in'");
        $divisa = mysqli_fetch_array(mysqli_query($link, "SELECT divisa FROM bancos WHERE id = '$id_banco'"))['divisa'];
        mysqli_query($link, "UPDATE prestamos SET monto = monto - '$amount' WHERE id_usuario = '$id_usuario' AND divisa = '$divisa' AND flag = 0");
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