<?php
header( 'Content-type: application/json' );
include "connect.php";


if ($_POST['id']) {
	$id = $_POST['id'];
	$referencia = $_POST['referencia'];
	$id_pago_in = $_POST['id_pago_in'];

	$sql = "UPDATE pagos_out SET estado = 'PAGADO', referencia = '$referencia' WHERE id = '$id'";
	$res = mysqli_query($link, $sql);

	$result = mysqli_query($link, "SELECT estado FROM pagos_out WHERE id_pago_in = '$id_pago_in'");
	$aux = true;
	while($row = mysqli_fetch_array($result)){
		if ($row['estado'] != 'PAGADO') {
			$aux = false;
			break;
		}
	}

	if ($aux) {
		mysqli_query($link, "UPDATE pagos_in SET estado = 'PAGADO' WHERE id = '$id_pago_in'");
	}

	if ($_FILES['comprobante']['name']) {
	    $extension = pathinfo($_FILES["comprobante"]["name"], PATHINFO_EXTENSION);
	    $target_dir = "comprobantes_out/";
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
	        if (move_uploaded_file($_FILES["comprobante"]["tmp_name"], $target_dir . $id . ".".$extension)) {
	            $res['mensajes'][] = 'Archivo cargado existosamente';
	            $res['errores'][] = false;
	        } else {
	            $res['mensajes'][] = 'Hubo un error cargando el archivo.';
	            $res['errores'][] = true;
	        }
	    }
}
}

echo json_encode($res);
?>