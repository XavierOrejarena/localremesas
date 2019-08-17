<?php
header( 'Content-type: application/json' );
include "connect.php";

$offset = -4*60*60; //converting 5 hours to seconds.
$dateFormat = "Y-m-d H:i:s";
$timeNdate = gmdate($dateFormat, time()+$offset);

$flag = true;
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
        if (mysqli_query($link, "SELECT id FROM prestamos WHERE id_usuario = '$id_usuario' AND divisa = '$divisa' AND flag = 0")->num_rows == 1) {
            mysqli_query($link, "UPDATE prestamos SET monto = monto - '$amount' WHERE id_usuario = '$id_usuario' AND divisa = '$divisa' AND flag = 0");
        } else {
            mysqli_query($link, "INSERT INTO pagos_out (id_usuario, id_pago_in, monto, estado, reg_date) VALUES ('$id_usuario', '$id_pago_in', 0, 'PRESTAMO', '$timeNdate')");
            $id_pago_out = mysqli_fetch_array((mysqli_query($link, "SELECT LAST_INSERT_ID()")))[0];
            $result = mysqli_query($link, "SELECT id FROM prestamos WHERE id_pago_out = '$id_pago_out'");
            if ($result->num_rows > 0) {
                mysqli_query($link, "UPDATE prestamos SET monto = monto + '$amount' WHERE id_usuario = '$id_usuario' AND divisa = '$divisa' AND flag = 0");
                $res[mensaje] = 'Error. Este prestamo ya se encuentra registrado.';
                $res[error] = true;
                $flag = false;
            } else {
                mysqli_query($link, "INSERT INTO prestamos (id_usuario, id_pago_out, monto, divisa, flag) VALUES ('$id_usuario', '$id_pago_out', '-$amount', '$divisa', 0)");
            }
        }
        if ($flag) {
            $res[mensaje] = 'Prestamo actualizado exitosamente.';
            $res[error] = false;
        }

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