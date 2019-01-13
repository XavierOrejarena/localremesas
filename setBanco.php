<?php
header( 'Content-type: application/json' );
include "connect.php";

$referencia = array_values($_POST)[0];
$monto = array_values($_POST)[1];
$id_banco = explode('r', key($_POST))[1];

if ($id_banco == 5) {
    if ($id_pago_in = mysqli_fetch_assoc(mysqli_query($link, "SELECT id FROM pagos_in WHERE RIGHT(referencia, 6) = RIGHT('$referencia', 6) AND monto <> -7.5 AND id_banco = '$id_banco' AND estado = 'PENDIENTE'"))['id']) {
        $result = mysqli_query($link, "SELECT id FROM pagos_out WHERE id_pago_in = '$id_pago_in'");
        if ($result->num_rows == 1) { // si solo existe un pago
            if (mysqli_query($link, "UPDATE pagos_in SET flag = 1, monto = monto + '$monto', estado = 'APROBADO' WHERE id = '$id_pago_in' AND flag <=> NULL AND estado <> 'APROBADO'")) {
                if (mysqli_query($link, "UPDATE pagos_out SET estado = 'PENDIENTE', monto = monto + '$monto'*(SELECT tasa FROM pagos_in WHERE id = '$id_pago_in') WHERE id_pago_in = '$id_pago_in'")) {
                    $res['errores'][] = false;
                    $res['mensajes'][] = "Se ha aprobado un pago en bolívares.";
                }
                if (mysqli_query($link, "UPDATE bancos SET monto = monto + '$monto' WHERE id = '$id_banco'")) {
                    $res['errores'][] = false;
                    $res['mensajes'][] = "Pago agregado existosamente.";
                }
            }
        } else {
            mysqli_query($link, "UPDATE pagos_in SET flag = 1 WHERE id = '$id_pago_in' AND flag <=> NULL AND estado <> 'APROBADO'");
            if (mysqli_affected_rows($link)) {
                $res['errores'][] = true;
                $res['mensajes'][] = 'El numero de pagos salientes es mayor a 1. Debe aprobar de forma manual.';
                if (mysqli_query($link, "INSERT INTO pagos_in (id_banco, monto, referencia, reg_date) VALUES ('$id_banco', '$monto', '$referencia', DATE_ADD(NOW(),INTERVAL 3 HOUR))")) {
                    $res['errores'][] = false;
                    $res['mensajes'][] = "Pago agregado existosamente.";
                }
                if (mysqli_query($link, "UPDATE bancos SET monto = monto + '$monto' WHERE id = '$id_banco'")) {
                    $res['errores'][] = false;
                    $res['mensajes'][] = "Pago agregado existosamente.";
                }
            } else {
                $res['errores'][] = true;
                $res['mensajes'][] = "Ya este pago fue agregado.";
            }
        }
    }elseif ($id_pago_in = mysqli_fetch_assoc(mysqli_query($link, "SELECT id FROM pagos_in WHERE RIGHT(referencia, 6) = RIGHT('$referencia', 6) AND monto = '$monto' AND id_banco = '$id_banco' AND estado = 'PENDIENTE'"))['id']) {
    // Si existe uno o mas pagos entrantes pendientes
    if (mysqli_query($link, "UPDATE pagos_in SET flag = 1 WHERE id = '$id_pago_in' AND flag = 0")){
        mysqli_query($link, "UPDATE bancos SET saldo = saldo + '$monto' WHERE id = '$id_banco'");
        $res['errores'][] = false;
        $res['mensajes'][] = 'Pago agregado existosamente.';
    } else {
        $res['errores'][] = true;
        $res['mensajes'][] = 'Hubo un error agregando el pago.';
    }
    $result = mysqli_query($link, "SELECT id FROM pagos_out WHERE id_pago_in = '$id_pago_in'");
    if ($result->num_rows == 1) { // si solo existe un pago
        $id_pago_out = mysqli_fetch_assoc($result)['id'];
        if (mysqli_query($link, "UPDATE pagos_in SET estado = 'APROBADO' WHERE id = '$id_pago_in'")) {
            $res['errores'][] = false;
            $res['mensajes'][] = 'Se ha aprobado un pago en divisa extranjera.';
            if (mysqli_query($link, "UPDATE pagos_out SET estado = 'PENDIENTE' WHERE id = '$id_pago_out'")) {
                $res['errores'][] = false;
                $res['mensajes'][] = 'Se ha aprobado un pago en bolívares.';
            } else {
                $res['errores'][] = true;
                $res['mensajes'][] = 'Hubo un error aprobando un pago en bolívares.';
            }
        } else {
            $res['errores'][] = true;
            $res['mensajes'][] = 'Hubo un error aprobando un pago en divisa extranjera.';
        }
    } else {
        $res['errores'][] = true;
        $res['mensajes'][] = 'El numero de pagos salientes es mayor a 1. Debe aprobar de forma manual.';
    }
    } elseif ($id_pago_in = mysqli_fetch_assoc(mysqli_query($link, "SELECT id FROM pagos_in WHERE RIGHT(referencia, 6) = RIGHT('$referencia', 6) AND monto = '$monto' AND id_banco = '$id_banco' AND estado = 'APROBADO'"))['id']) {
        $res['errores'][] = true;
        $res['mensajes'][] = 'Este pago ya fue aprobado y agregado.';
    } elseif (mysqli_num_rows(mysqli_query($link, "SELECT id FROM pagos_in WHERE RIGHT(referencia, 6) = RIGHT('$referencia', 6) AND monto = '$monto' AND id_banco = '$id_banco' AND flag = 1")) > 0) {
            $res['errores'][] = false;
            $res['mensajes'][] = "Ya este pago fue agregado.";
    } else {
        //SI NO EXISTE ESE PAGO
        if (mysqli_query($link, "INSERT INTO pagos_in (id_banco, monto, referencia, flag) VALUES ('$id_banco', '$monto', '$referencia', true)")) {
            mysqli_query($link, "UPDATE bancos SET saldo = saldo + '$monto' WHERE id = '$id_banco'");
            $res['errores'][] = false;
            $res['mensajes'][] = 'Pago agregado existosamente.';
        } else {
            $res['errores'][] = true;
            $res['mensajes'][] = 'Hubo un error agregando el pago.';
        }
    }
} elseif ($id_banco == 3 || $id_banco == 4) {  // INTERBANK
    if ($id_pago_in = mysqli_fetch_assoc(mysqli_query($link, "SELECT id FROM pagos_in WHERE referencia = '$referencia' AND monto = '$monto' AND id_banco = '$id_banco' AND estado = 'PENDIENTE'"))['id']) {
        // Si existe uno o mas pagos entrantes pendientes
        if (mysqli_query($link, "UPDATE pagos_in SET flag = 1 WHERE id = '$id_pago_in' AND flag = 0")){
            mysqli_query($link, "UPDATE bancos SET saldo = saldo + '$monto' WHERE id = '$id_banco'");
            $res['errores'][] = false;
            $res['mensajes'][] = 'Pago agregado existosamente.';
        } else {
            $res['errores'][] = true;
            $res['mensajes'][] = 'Hubo un error agregando el pago.';
        }
        $result = mysqli_query($link, "SELECT id FROM pagos_out WHERE id_pago_in = '$id_pago_in'");
        if ($result->num_rows == 1) { // si solo existe un pago
            $id_pago_out = mysqli_fetch_assoc($result)['id'];
            if (mysqli_query($link, "UPDATE pagos_in SET estado = 'APROBADO' WHERE id = '$id_pago_in'")) {
                $res['errores'][] = false;
                $res['mensajes'][] = 'Se ha aprobado un pago en divisa extranjera.';
                if (mysqli_query($link, "UPDATE pagos_out SET estado = 'PENDIENTE' WHERE id = '$id_pago_out'")) {
                    $res['errores'][] = false;
                    $res['mensajes'][] = 'Se ha aprobado un pago en bolívares.';
                } else {
                    $res['errores'][] = true;
                    $res['mensajes'][] = 'Hubo un error aprobando un pago en bolívares.';
                }
            } else {
                $res['errores'][] = true;
                $res['mensajes'][] = 'Hubo un error aprobando un pago en divisa extranjera.';
            }
        } else {
            $res['errores'][] = true;
            $res['mensajes'][] = 'El numero de pagos salientes es mayor a 1. Debe aprobar de forma manual.';
        }
        
    } elseif ($id_pago_in = mysqli_fetch_assoc(mysqli_query($link, "SELECT id FROM pagos_in WHERE referencia = '$referencia' AND monto = '$monto' AND id_banco = '$id_banco' AND estado = 'APROBADO'"))['id']) {
        $res['errores'][] = true;
        $res['mensajes'][] = 'Este pago ya fue aprobado y agregado.';
    } elseif (mysqli_num_rows(mysqli_query($link, "SELECT id FROM pagos_in WHERE referencia = '$referencia' AND monto = '$monto' AND id_banco = '$id_banco' AND flag = 1")) > 0) {
            $res['errores'][] = false;
            $res['mensajes'][] = "Ya este pago fue agregado.";
    } else {
        //SI NO EXISTE ESE PAGO
        if (mysqli_query($link, "INSERT INTO pagos_in (id_banco, monto, referencia, flag) VALUES ('$id_banco', '$monto', '$referencia', true)")) {
            mysqli_query($link, "UPDATE bancos SET saldo = saldo + '$monto' WHERE id = '$id_banco'");
            $res['errores'][] = false;
            $res['mensajes'][] = 'Pago agregado existosamente.';
        } else {
            $res['errores'][] = true;
            $res['mensajes'][] = 'Hubo un error agregando el pago.';
        }
    }
}

echo json_encode($res);
?>