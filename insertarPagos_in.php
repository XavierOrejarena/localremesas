<?php
header( 'Content-type: application/json' );
include "connect.php";
include "date.php";

$flag = 0;
$id_usuario = $_POST['id_usuario'];
$divisa = $_POST['divisa'];
$banco = $_POST['banco'];
$monto = $_POST['amount'];
$tasa = $_POST['tasa'];
$referencia = $_POST['referencia'];
$id_banco = mysqli_fetch_assoc(mysqli_query($link, "SELECT id FROM bancos WHERE nombre = '$banco' AND divisa = '$divisa'"))['id'];
$mensageTelegram = false;

if ($referencia == 0) {
    
    mysqli_query($link, "INSERT INTO pagos_in (id_usuario, id_banco, monto, referencia, tasa, estado, flag, reg_date) VALUES ('$id_usuario', '$id_banco', '$monto', '$referencia', '$tasa', 'PRESTAMO', 4, '$timeNdate')");
    
    $mensageTelegram = true;
    $res['mensajes'][] = 'Prestamo agregado exitosamente';
    $res['errores'][] = false;
    $flag = 2;
    $id_pago_in = mysqli_fetch_array((mysqli_query($link, "SELECT LAST_INSERT_ID()")))[0];
} else {
    if ($banco == 'BCP') {
        if ($id_pago_in = mysqli_fetch_assoc(mysqli_query($link, "SELECT id FROM pagos_in WHERE RIGHT(referencia, 6) = RIGHT('$referencia', 6) AND monto = '$monto' AND flag = 1 AND id_banco = $id_banco"))['id']) {
            if ($id_pago_in2 = mysqli_fetch_assoc(mysqli_query($link, "SELECT id FROM pagos_in WHERE RIGHT(referencia, 6) = RIGHT('$referencia', 6) AND monto = -7.5 AND flag = 1 AND id_banco = $id_banco"))['id']) {
                $restar = 1;
            }
            
            mysqli_query($link, "UPDATE pagos_in SET id_usuario = '$id_usuario', tasa = '$tasa', estado = 'APROBADO', flag = 2 WHERE id_banco = '$id_banco' AND monto = '$monto' AND RIGHT(referencia, 6) = RIGHT('$referencia', 6) AND flag = 1");
            $mensageTelegram = true;
            $flag = 1;
            $res['mensajes'][] = 'El pago ya existe, pago aprobado.';
            $res['errores'][] = false;
        } elseif ($id_pago_in = mysqli_fetch_assoc(mysqli_query($link, "SELECT id FROM pagos_in WHERE RIGHT(referencia, 6) = RIGHT('$referencia', 6) AND id_banco = '$id_banco' AND (flag IS NULL OR flag = 2)"))['id']) {
            $res['mensajes'][] = 'Este pago ya habia sido agregado anteriormente.';
            $res['errores'][] = true;
            $flag = 3;
        }
    } else {
        if ($id_pago_in = mysqli_fetch_assoc(mysqli_query($link, "SELECT id FROM pagos_in WHERE referencia = '$referencia' AND monto = '$monto' AND flag = 1 AND id_banco = $id_banco"))['id']) {
            if ($id_pago_in2 = mysqli_fetch_assoc(mysqli_query($link, "SELECT id FROM pagos_in WHERE referencia = '$referencia' AND monto = -7.5 AND flag = 1 AND id_banco = $id_banco"))['id']) {
                $restar = 1;
            }
            
            mysqli_query($link, "UPDATE pagos_in SET id_usuario = '$id_usuario', tasa = '$tasa', estado = 'APROBADO', flag = 2 WHERE id_banco = '$id_banco' AND monto = '$monto' AND referencia = '$referencia' AND flag = 1");
            $mensageTelegram = true;
            $flag = 1;
            $res['mensajes'][] = 'El pago ya existe, pago aprobado.';
            $res['errores'][] = false;
        } elseif ($id_pago_in = mysqli_fetch_assoc(mysqli_query($link, "SELECT id FROM pagos_in WHERE referencia = '$referencia' AND id_banco = '$id_banco' AND (flag IS NULL OR flag = 2)"))['id']) {
            $res['mensajes'][] = 'Este pago ya habia sido agregado anteriormente.';
            $res['errores'][] = true;
            $flag = 3;
        }
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

                $sql = "INSERT INTO pagos_in (tasa, id_usuario, id_banco, monto, referencia, estado, reg_date) VALUES ('$tasa', '$id_usuario', '$id_banco', '$monto', '$referencia', 'PENDIENTE', '$timeNdate')";

                if(mysqli_query($link, $sql)) {
                        $res['mensajes'][] = 'Pago agregado existosamente';
                        $res['errores'][] = false;
                        $id_pago_in = mysqli_fetch_array((mysqli_query($link, "SELECT LAST_INSERT_ID()")))[0];
                } else {
                    $res['mensajes'][] = 'Hubo un error agregando el pago';
                    $res['errores'][] = true;
                }
                
            } else {
                $res['mensajes'] = 'Hubo un error cargando el archivo.';
                $res['errores'] = true;
            }
        }
    } elseif ($flag == 0){ // SI NO HAY ARCHIVO
        $sql = "INSERT INTO pagos_in (tasa, id_usuario, id_banco, monto, referencia, estado, reg_date) VALUES ('$tasa', '$id_usuario', '$id_banco', '$monto', '$referencia', 'PENDIENTE', '$timeNdate')";

        if(mysqli_query($link, $sql)) {
                $res['mensajes'][] = 'Pago entrante agregado existosamente';
                $res['errores'][] = false;
                $id_pago_in = mysqli_fetch_array((mysqli_query($link, "SELECT LAST_INSERT_ID()")))[0];
        } else {
            $res['mensajes'][] = 'Hubo un error agregando el pago entrante';
            $res['errores'][] = true;
        }
    }
}

if ($mensageTelegram) {
    $token = '716396100:AAFbVh6W950S4goHt30TVUXW3cuKGdWQmKM';
    $chat_id = -1001297263006;
    $text = number_format((float)$monto, 2, '.', '') . " " . $divisa . " --> " . $banco;
    $ip_server = $_SERVER['SERVER_ADDR'];
    if ($ip_server == "::1" ) {
        // echo "Local Server IP Address is: $ip_server";
        file_get_contents("https://api.telegram.org/bot716396100:AAFbVh6W950S4goHt30TVUXW3cuKGdWQmKM/sendMessage?chat_id=149273661&text=monto: $monto ");
    } else {
        // echo "Server IP Address is: $ip_server";
        file_get_contents("https://api.telegram.org/bot$token/sendMessage?chat_id=$chat_id&text=$text");
    }
}
//---------------------------------------------------------------------------------------------------------
// $res = array( 'errores' => false );
if ($flag == 2) {
	$estado = 'PENDIENTE';
} else {
	$estado = 'EN ESPERA';
}

if ($flag == 3) {
	$res['mensajes'][] = 'Hubo un error agregando el pago saliente';
	$res['errores'][] = true;
}else {
	for ($i=0; $i < sizeof($_POST['id_cuenta']); $i++) {
		
		$id_cuenta = $_POST['id_cuenta'][$i];
		$monto = $_POST['monto'][$i];
	
        $sql = "INSERT INTO pagos_out (id_usuario, id_pago_in, id_cuenta, monto, estado, reg_date) VALUES ('$id_usuario', '$id_pago_in', '$id_cuenta', '$monto', '$estado', '$timeNdate')";
        // file_get_contents("https://api.telegram.org/bot716396100:AAFbVh6W950S4goHt30TVUXW3cuKGdWQmKM/sendMessage?chat_id=149273661&text=$sql");
		if(mysqli_query($link, $sql)) {
			$res['mensajes'][] = 'Pago saliente agregado existosamente';
			$res['errores'][] = false;
			if ($i == 0) {
				$id_pago_out = mysqli_fetch_array((mysqli_query($link, "SELECT LAST_INSERT_ID()")))[0];
			}
		} else {
			$res['mensajes'][] = 'Hubo un error agregando el pago saliente.';
			$res['errores'][] = true;
		}
	}
	if ($flag == 1) {
		$sql = "UPDATE pagos_out SET estado = 'PENDIENTE' WHERE id_pago_in = '$id_pago_in'";
		mysqli_query($link, $sql);
		if ($restar == 1) {
			$sql = "UPDATE pagos_out SET monto = monto - 7.5*(SELECT tasa FROM pagos_in WHERE id = '$id_pago_in') WHERE id = '$id_pago_out'";
			mysqli_query($link, $sql);
			$res['mensajes'][] = 'Se ha descontado la comision de un pago saliente';
			$res['errores'][] = false;
		}
		$res['mensajes'][] = 'Pago saliente aprobado exitosamente';
		$res['errores'][] = false;
	}
}





echo json_encode($res);
?>