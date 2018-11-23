<?php
header( 'Content-type: application/json' );
include "connect.php";
$res = array( 'errores' => false );

if ($_FILES['comprobante']['name']) {
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
    if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg") {
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

        $id_usuario = $_POST['id_usuario'];
        $divisa = $_POST['divisa'];
        $banco = $_POST['banco'];
        $monto = $_POST['monto'];
        $referencia = $_POST['referencia'];

        $sql = "INSERT INTO pagos_in (id_usuario, divisa, banco, monto, referencia, estado) VALUES ('$id_usuario', '$divisa', '$banco', '$monto', '$referencia', 'PENDIENTE')";

        if(mysqli_query($link, $sql)) {
                $res['mensajes'][] = 'Pago agregado existosamente';
                $res['errores'][] = false;
                $res['id_pago_in'] = mysqli_fetch_array((mysqli_query($link, "SELECT LAST_INSERT_ID()")))[0];
            } else {
                $res['mensajes'][] = 'Hubo un error agregando el pago';
                $res['errores'][] = true;
            }

        if (move_uploaded_file($_FILES["comprobante"]["tmp_name"], $target_dir . $referencia . ".jpg")) {
            $res['mensajes'][] = 'Archivo cargado existosamente';
            $res['errores'][] = false;
        } else {
            $res['mensajes'][] = 'Hubo un error cargando el archivo.';
            $res['errores'][] = true;
        }
    }
}


echo json_encode($res);
?>