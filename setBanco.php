<?php
header( 'Content-type: application/json' );
include "connect.php";

$referencia = array_values($_POST)[0];
$monto = array_values($_POST)[1];
$id_banco = explode('r', key($_POST))[1];
$res = false;

if (mysqli_query($link, "UPDATE bancos SET saldo = bancos.saldo + '$monto' WHERE id = '$id_banco'")){
    $res = true;

    if (mysqli_fetch_assoc(mysqli_query($link, "SELECT nombre FROM bancos WHERE id = '$id_banco'"))['nombre'] == 'BCP') {
        // SI EL BANCO ES BCP
        if ($monto == -7.5 && mysqli_query($link, "SELECT id FROM pagos_in WHERE referencia = '$referencia' AND id_banco = '$id_banco'")->num_rows == 1) {
            // SI EL MONTO ES -7.5 SOLES Y YA EXISTE UN PAGO CON ESA REFERENCIA
            if (mysqli_query($link, "SELECT id FROM pagos_out WHERE id_pago_in = (SELECT id from pagos_in WHERE referencia = '$referencia' AND id_banco = '$id_banco')")->num_rows == 1) {
                // SI SOLO HAY UN PAGO SALIENTE
                if (mysqli_query($link, "UPDATE pagos_out SET monto = pagos_out.monto + (SELECT tasa FROM pagos_in WHERE referencia = '$referencia' AND id_banco = '$id_banco')*'$monto', estado = 'PENDIENTE' WHERE id_pago_in = (SELECT id from pagos_in WHERE referencia = '$referencia' AND id_banco = '$id_banco')")) {
                    echo "Se descuenta la comision del pago saliente.\n";
                    if (mysqli_query($link, "UPDATE pagos_in SET estado = 'APROBADO' WHERE referencia = '$referencia' AND id_banco = '$id_banco' AND monto <> '$monto'")) {
                        echo "Pago entrante actualizado.\n";
                    }
                }
                if (mysqli_query($link, "INSERT INTO pagos_in (id_usuario, id_banco, monto, referencia, tasa, estado, reg_date) SELECT id_usuario, id_banco, '$monto', referencia, tasa, 'COMISION', reg_date FROM pagos_in WHERE '$referencia' AND id_banco = '$id_banco'")) {
                    echo "Se agrega la comision como pago entrante.\n";
                }
                
            }
        } elseif ($id = mysqli_fetch_assoc(mysqli_query($link, "SELECT id FROM pagos_in WHERE RIGHT(referencia,6) = RIGHT('$referencia',6) AND monto = '$monto' AND id_banco = '$id_banco'"))['id'] && $monto > 0) {
            // SI EXISTE UN PAGO CON ESA REFERENCIA, MONTO Y BANCO
            mysqli_query($link, "UPDATE pagos_in SET estado = 'APROBADO' WHERE id = '$id'");
            mysqli_query($link, "UPDATE pagos_out SET estado = 'PENDIENTE' WHERE id_pago_in = '$id'");
        } else {
            if ($monto > 0) {
                mysqli_query($link, "INSERT INTO pagos_in (id_banco, monto, referencia, reg_date) VALUES ('$id_banco', '$monto', '$referencia', DATE_ADD(NOW(),INTERVAL 3 HOUR))");
            }
        }
    } elseif ($id = mysqli_fetch_assoc(mysqli_query($link, "SELECT id FROM pagos_in WHERE referencia = '$referencia' AND monto = '$monto' AND id_banco = '$id_banco'"))['id']) {
        echo "3.\n";
        // SI ES INTERBANK
        mysqli_query($link, "UPDATE pagos_in SET estado = 'APROBADO' WHERE id = '$id'");
        mysqli_query($link, "UPDATE pagos_out SET estado = 'PENDIENTE' WHERE id_pago_in = '$id'");
    } else {
        mysqli_query($link, "INSERT INTO pagos_in (id_banco, monto, referencia, reg_date) VALUES ('$id_banco', '$monto', '$referencia', DATE_ADD(NOW(),INTERVAL 3 HOUR))");
    }
}

echo json_encode($res);
?>