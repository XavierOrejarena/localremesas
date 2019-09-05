<?php
header( 'Content-type: application/json' );
include "connect.php";

$referencia = array_values($_POST)[0];
$monto = array_values($_POST)[1];
$id_banco = explode('r', key($_POST))[1];

$res['referencia'] = $referencia;
$res['monto'] = $monto;
$res['id_banco'] = $id_banco;
$mensageTelegram = false;

// if ($id_banco == 5) {
//     $referencia = sprintf("%06d", $referencia);
// }
// $res['errores'][] = true;
$res['post'][] = $referencia;


if ($id_banco == 5) {
    if ($monto == -7.5 && ($id_pago_in = mysqli_fetch_assoc(mysqli_query($link, "SELECT id FROM pagos_in WHERE RIGHT(referencia, 6) = RIGHT('$referencia', 6) AND id_banco = '$id_banco' AND estado = 'PENDIENTE' AND flag IS NULL"))['id'])) {
        // if (mysqli_query($link, "UPDATE bancos SET saldo = saldo + '$monto' + (SELECT monto FROM pagos_in WHERE id = '$id_pago_in') WHERE id = '$id_banco'")) {
        if (mysqli_query($link, "UPDATE bancos SET saldo = saldo + '$monto' WHERE id = '$id_banco'")) {
            $res['errores'][] = false;
            $res['mensajes'][] = "Saldo actualizado existosamente.";
            if (mysqli_query($link, "INSERT INTO pagos_in (id_banco, monto, referencia, flag) VALUES ('$id_banco', '$monto', '$referencia', 1)")) {
                $res['errores'][] = false;
                $res['mensajes'][] = "Pago agregado existosamente.";
                mysqli_query($link, "UPDATE pagos_in SET flag = 1 WHERE id = '$id_pago_in'");
            }
        }
        $result = mysqli_query($link, "SELECT id FROM pagos_out WHERE id_pago_in = '$id_pago_in'");
        if ($result->num_rows == 1) { // si solo existe un pago
            $id_pago_out = mysqli_fetch_assoc($result)['id'];
        //     if (mysqli_query($link, "UPDATE pagos_in SET estado = 'APROBADO', flag = 1 WHERE id = '$id_pago_in'")) {
        //         $res['errores'][] = false;
        //         $res['mensajes'][] = 'Se ha aprobado un pago en divisa extranjera.';
                if (mysqli_query($link, "UPDATE pagos_out SET monto = monto - (SELECT tasa FROM pagos_in WHERE id = '$id_pago_in')*7.5 WHERE id = '$id_pago_out'")) {
                    $res['errores'][] = false;
                    $res['mensajes'][] = 'Se ha descontado una comisión en un pago saliente.';
                } 
        // else {
        //             $res['errores'][] = true;
        //             $res['mensajes'][] = 'Hubo un error aprobando un pago en bolívares.';
        //         }
        //     } else {
        //         $res['errores'][] = true;
        //         $res['mensajes'][] = 'Hubo un error aprobando un pago en divisa extranjera.';
        //     }
        } else {
            mysqli_query($link, "UPDATE pagos_in SET flag = 3 WHERE id = '$id_pago_in'");
            $res['errores'][] = true;
            $res['mensajes'][] = 'El numero de pagos salientes es mayor a 1. Debe aprobar de forma manual.';
        }


    }elseif ($id_pago_in = mysqli_fetch_assoc(mysqli_query($link, "SELECT id FROM pagos_in WHERE RIGHT(referencia, 6) = RIGHT('$referencia', 6) AND monto = '$monto' AND id_banco = '$id_banco' AND estado = 'PENDIENTE' AND flag = 1"))['id']) {
        if (mysqli_query($link, "UPDATE bancos SET saldo = saldo + '$monto' WHERE id = '$id_banco'")) {
            $res['errores'][] = false;
            $res['mensajes'][] = "Saldo actualizado existosamente.";
            if (mysqli_query($link, "UPDATE pagos_in SET flag = 2, estado = 'APROBADO' WHERE id = '$id_pago_in'")) {
                $mensageTelegram = true;
                $res['errores'][] = false;
                $res['mensajes'][] = 'Se ha aprobado un pago en divisa extranjera.';
                if (mysqli_query($link, "UPDATE pagos_out SET estado = 'PENDIENTE' WHERE id_pago_in = '$id_pago_in'")) {
                    $res['errores'][] = false;
                    $res['mensajes'][] = "Se ha aprobado un pago en bolívares.";
                }
            }
        }
    }elseif ($id_pago_in = mysqli_fetch_assoc(mysqli_query($link, "SELECT id FROM pagos_in WHERE RIGHT(referencia, 6) = RIGHT('$referencia', 6) AND monto = '$monto' AND id_banco = '$id_banco' AND estado = 'PENDIENTE' AND flag IS NULL"))['id']) {
        if (mysqli_query($link, "UPDATE bancos SET saldo = saldo + '$monto' WHERE id = '$id_banco'")) {
            $res['errores'][] = false;
            $res['mensajes'][] = "Saldo actualizado existosamente.";
            if (mysqli_query($link, "UPDATE pagos_in SET flag = 2, estado = 'APROBADO' WHERE id = '$id_pago_in'")) {
                $mensageTelegram = true;
                $res['errores'][] = false;
                $res['mensajes'][] = 'Se ha aprobado un pago en divisa extranjera.';
                if (mysqli_query($link, "UPDATE pagos_out SET estado = 'PENDIENTE' WHERE id_pago_in = '$id_pago_in'")) {
                    $res['errores'][] = false;
                    $res['mensajes'][] = "Se ha aprobado un pago en bolívares.";
                }
            }
        }
    }elseif ($id_pago_in = mysqli_fetch_assoc(mysqli_query($link, "SELECT id FROM pagos_in WHERE RIGHT(referencia, 6) = RIGHT('$referencia', 6) AND monto = '$monto' AND id_banco = '$id_banco' AND (flag = 2 OR flag = 1)"))['id']) {
        $res['errores'][] = true;
        $res['mensajes'][] = "Ya este pago fue agregado.";
    }elseif (!($id_pago_in = mysqli_fetch_assoc(mysqli_query($link, "SELECT id FROM pagos_in WHERE RIGHT(referencia, 6) = RIGHT('$referencia', 6) AND monto = '$monto' AND id_banco = '$id_banco'"))['id'])) {
        if (mysqli_query($link, "UPDATE bancos SET saldo = saldo + '$monto' WHERE id = '$id_banco'")) {
            $res['errores'][] = false;
            $res['mensajes'][] = "Saldo actualizado existosamente.";
            if (mysqli_query($link, "INSERT INTO pagos_in (id_banco, monto, referencia, flag) VALUES ('$id_banco', '$monto', '$referencia', true)")) {
                $res['errores'][] = false;
                $res['mensajes'][] = "Pago agregado existosamente.";
            }
    }
}
} else {
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
        if ($result->num_rows > 0) { // si solo existe un pago
            // $id_pago_out = mysqli_fetch_assoc($result)['id'];
            if (mysqli_query($link, "UPDATE pagos_in SET estado = 'APROBADO' WHERE id = '$id_pago_in'")) {
                $mensageTelegram = true;
                $res['errores'][] = false;
                $res['mensajes'][] = 'Se ha aprobado un pago en divisa extranjera.';
                if (mysqli_query($link, "UPDATE pagos_out SET estado = 'PENDIENTE' WHERE id_pago_in = '$id_pago_in'")) {
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
    } elseif (mysqli_num_rows(mysqli_query($link, "SELECT id FROM pagos_in WHERE referencia = '$referencia' AND monto = '$monto' AND id_banco = '$id_banco' AND (flag = 2 OR flag = 1)")) > 0) {
            $res['errores'][] = true;
            $res['mensajes'][] = "Ya este pago fue agregado.";
    } else {
        //SI NO EXISTE ESE PAGO
        if (mysqli_query($link, "INSERT INTO pagos_in (id_banco, monto, referencia, flag) VALUES ('$id_banco', '$monto', '$referencia', true)")) {
            mysqli_query($link, "UPDATE bancos SET saldo = saldo + '$monto' WHERE id = '$id_banco'");
            $res['errores'][] = false;
            $res['mensajes'][] = 'Pago agregado existosamente';
        } else {
            $res['errores'][] = true;
            $res['mensajes'][] = 'Hubo un error agregando el pago.';
        }
    }
}

if ($mensageTelegram) {
    $banco = mysqli_fetch_assoc(mysqli_query($link, "SELECT nombre from bancos WHERE id = '$id_banco'"))['nombre'];
    $divisa = mysqli_fetch_assoc(mysqli_query($link, "SELECT divisa from bancos WHERE id = '$id_banco'"))['divisa'];
    $token = '716396100:AAFbVh6W950S4goHt30TVUXW3cuKGdWQmKM';
    $chat_id = -1001297263006;
    $text = number_format((float)$monto, 2, '.', '') . " " . $divisa . " --> " . $banco;
    file_get_contents("https://api.telegram.org/bot$token/sendMessage?chat_id=$chat_id&text=$text");
}

echo json_encode($res);
?>