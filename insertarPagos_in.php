<?php
header( 'Content-type: application/json' );
include "connect.php";

$res['flag'] = 0;
$id_usuario = $_POST['id_usuario'];
$divisa = $_POST['divisa'];
$banco = $_POST['banco'];
$monto = $_POST['monto'];
$tasa = $_POST['tasa'];
$referencia = $_POST['referencia'];
if ($banco == 'BCP') {
    $referencia = sprintf("%06d", $referencia);
}
$id_banco = mysqli_fetch_assoc(mysqli_query($link, "SELECT id FROM bancos WHERE nombre = '$banco' AND divisa = '$divisa'"))['id'];

if ($referencia == 0) {

    mysqli_query($link, "INSERT INTO pagos_in (id_usuario, id_banco, monto, referencia, tasa, estado, flag, reg_date) VALUES ('$id_usuario', '$id_banco', '$monto', '$referencia', '$tasa', 'PRESTAMO', 4, DATE_ADD(NOW(),INTERVAL 3 HOUR))");
    
    $res['mensajes'][] = 'Prestamo agregado exitosamente';
    $res['errores'][] = false;
    $res['flag'] = 2;
    $res['id_pago_in'] = mysqli_fetch_array((mysqli_query($link, "SELECT LAST_INSERT_ID()")))[0];
} else {
    
    if ($res['id_pago_in'] = mysqli_fetch_assoc(mysqli_query($link, "SELECT id FROM pagos_in WHERE referencia = '$referencia' AND monto = '$monto' AND flag = 1"))['id']) {
        if ($id_pago_in = mysqli_fetch_assoc(mysqli_query($link, "SELECT id FROM pagos_in WHERE referencia = '$referencia' AND monto = -7.5 AND flag = 1"))['id']) {
            $res['restar'] = 1;
        }
        mysqli_query($link, "UPDATE pagos_in SET id_usuario = '$id_usuario', tasa = '$tasa', estado = 'APROBADO', flag = 2 WHERE id_banco = '$id_banco' AND monto = '$monto' AND referencia = '$referencia' AND flag = 1");
        $res['flag'] = 1;
        $res['mensajes'][] = 'El pago ya existe, pago aprobado.';
        $res['errores'][] = false;
    } elseif ($res['id_pago_in'] = mysqli_fetch_assoc(mysqli_query($link, "SELECT id FROM pagos_in WHERE referencia = '$referencia' AND monto = '$monto' AND flag IS NULL"))['id']) {
        $res['mensajes'][] = 'Este pago ya habia sido agregado anteriormente.';
        $res['errores'][] = true;
    }

    if ($_FILES['comprobante']['name']) { // SI HAY ARCHIVO
        $extension = pathinfo($_FILES["comprobante"]["name"], PATHINFO_EXTENSION);
        $target_dir = "comprobantes_in/";
        $target_file = $target_dir . basename($_FILES["comprobante"]["name"]);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
        // Check if image file is a actual image or fake image
        if(isset($_POST["submit"])) {
            $check = getimagesize($_FILES["comprobante"]["tmp_name"]);
            if($check !== false) {
                $uploadOk = 1;
            } else {
                $res['mensajes'][] = 'El archivo no es una imagen.';
                $res['errores'][] = true;
                $uploadOk = 0;
            }
        }
        // Check if file already exists
        if (file_exists($target_file)) {
            $res['mensajes'][] = 'Nombre de archivo ya existe.';
            $res['errores'][] = true;
            $uploadOk = 0;
        }
        // Check file size 2 MEGAS
        if ($_FILES["comprobante"]["size"] > 2000000) { 
            $res['mensajes'][] = 'Tamaño del archivo no permitido.';
            $res['errores'][] = true;
            $uploadOk = 0;
        }
        // Allow certain file formats
        if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "jpe") {
            $res['mensajes'][] = 'Formato de archivo no admitido.';
            $res['errores'][] = true;
            $uploadOk = 0;
        }
        // Check if $uploadOk is set to 0 by an error
        if ($uploadOk == 0) {
            $res['mensajes'][] = 'No se pudo subir el archivo.';
            $res['errores'][] = true;
        // if everything is ok, try to upload file
        } else {
            if (move_uploaded_file($_FILES["comprobante"]["tmp_name"], $target_dir . $banco . '_' . $divisa . '_'  . $referencia . ".jpg")) {
                $res['mensajes'][] = 'Archivo cargado existosamente';
                $res['errores'][] = false;

                $sql = "INSERT INTO pagos_in (tasa, id_usuario, id_banco, monto, referencia, estado, reg_date) VALUES ('$tasa', '$id_usuario', '$id_banco', '$monto', '$referencia', 'PENDIENTE', DATE_ADD(NOW(),INTERVAL 3 HOUR))";

                if(mysqli_query($link, $sql)) {
                        $res['mensajes'][] = 'Pago agregado existosamente';
                        $res['errores'][] = false;
                        $res['id_pago_in'] = mysqli_fetch_array((mysqli_query($link, "SELECT LAST_INSERT_ID()")))[0];
                } else {
                    $res['mensajes'][] = 'Hubo un error agregando el pago';
                    $res['errores'][] = true;
                }
                
            } else {
                $res['mensajes'] = 'Hubo un error cargando el archivo.';
                $res['errores'] = true;
            }
        }
    } elseif ($res['flag'] == 0){ // SI NO HAY ARCHIVO
        $sql = "INSERT INTO pagos_in (tasa, id_usuario, id_banco, monto, referencia, estado, reg_date) VALUES ('$tasa', '$id_usuario', '$id_banco', '$monto', '$referencia', 'PENDIENTE', DATE_ADD(NOW(),INTERVAL 3 HOUR))";

        if(mysqli_query($link, $sql)) {
                $res['mensajes'][] = 'Pago entrante agregado existosamente';
                $res['errores'][] = false;
                $res['id_pago_in'] = mysqli_fetch_array((mysqli_query($link, "SELECT LAST_INSERT_ID()")))[0];
        } else {
            $res['mensajes'][] = 'Hubo un error agregando el pago entrante';
            $res['errores'][] = true;
        }
    }
}



echo json_encode($res);
?>