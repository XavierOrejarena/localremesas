<?php
header( 'Content-type: application/json' );
include "connect.php";
session_start();

function generateRandomString($length = 10) {
	$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
	$charactersLength = strlen($characters);
	$randomString = '';
	for ($i = 0; $i < $length; $i++) {
		$randomString .= $characters[rand(0, $charactersLength - 1)];
	}
	return $randomString;
}

if ($_SESSION['tipo'] == 'ADMIN') {
	$id = $_POST['id'];
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
	            $res['mensajes'] = 'El archivo no es una imagen.';
	            $res['errores'] = true;
	            $uploadOk = 0;
	        }
	    }
	    // Check if file already exists
	    if (file_exists($target_file)) {
	        $res['mensajes'] = 'Nombre de archivo ya existe.';
	        $res['errores'] = true;
	        $uploadOk = 0;
	    }
	    // Check file size 2 MEGAS
	    if ($_FILES["comprobante"]["size"] > 2000000) { 
	        $res['mensajes'] = 'Tamaño del archivo no permitido.';
	        $res['errores'] = true;
	        $uploadOk = 0;
	    }
	    // Allow certain file formats
	    if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "jpe") {
	        $res['mensajes'] = 'Formato de archivo no admitido.';
	        $res['errores'] = true;
	        $uploadOk = 0;
	    }
	    // Check if $uploadOk is set to 0 by an error
	    if ($uploadOk == 0) {
	        $res['mensajes'] = 'No se pudo subir el archivo.';
	        $res['errores'] = true;
	    // if everything is ok, try to upload file
	    } else {
			$random = generateRandomString();
			$id_pago_in = $_POST['id_pago_in'];
			$id_usuario = mysqli_fetch_array(mysqli_query($link, "SELECT id_usuario FROM pagos_out WHERE id_pago_in = '$id_pago_in'"))['id_usuario'];
	        if (move_uploaded_file($_FILES["comprobante"]["tmp_name"], $target_dir . $id ."_". $random. "_$id_usuario.jpg")) {
				$referencia = $_POST['referencia'];
				$id_banco = $_POST['id_banco'];


				$sql = "UPDATE pagos_out SET id_banco = '$id_banco', estado = 'PAGADO', referencia = '$referencia' WHERE id = '$id' AND estado = 'PENDIENTE'";
				mysqli_query($link, $sql);

				$result = mysqli_query($link, "SELECT estado FROM pagos_out WHERE id_pago_in = '$id_pago_in'");

				// $chat_id = '@PagosLR';
				// $chat_id = 149273661;
				$token = '716396100:AAFbVh6W950S4goHt30TVUXW3cuKGdWQmKM';
				$chat_id = -1001265304659;
				$text = 'http://localremesas.com/comprobantes_out/'.$id."_".$random."_$id_usuario.jpg";
				$ip_server = $_SERVER['SERVER_ADDR'];
				if ($ip_server == "::1" ) {
					// echo "Local Server IP Address is: $ip_server";
				} else {
					// echo "Server IP Address is: $ip_server";
					file_get_contents("https://api.telegram.org/bot$token/sendMessage?chat_id=$chat_id&text=$text");
				}
				$aux = true;
				while($row = mysqli_fetch_array($result)){
					if ($row['estado'] != 'PAGADO') {
						$aux = false;
						break;
					}
				}

				$monto = mysqli_fetch_array(mysqli_query($link, "SELECT monto FROM pagos_out WHERE id = '$id'"))['monto'];
				mysqli_query($link, "UPDATE bancos SET saldo = bancos.saldo - '$monto' WHERE id = '$id_banco'");

				if ($aux) {
					mysqli_query($link, "UPDATE pagos_in SET estado = 'PAGADO' WHERE id = '$id_pago_in'");
					if (mysqli_fetch_array(mysqli_query($link, "SELECT referencia FROM pagos_in WHERE id = (SELECT id_pago_in FROM pagos_out WHERE id = '$id')"))[referencia] == 0) {

						$monto = mysqli_fetch_array(mysqli_query($link, "SELECT monto FROM pagos_in WHERE id = '$id_pago_in'"))['monto'];
						$id_banco_in = mysqli_fetch_array(mysqli_query($link, "SELECT id_banco FROM pagos_in WHERE id = '$id_pago_in'"))['id_banco'];
						$divisa = mysqli_fetch_array(mysqli_query($link, "SELECT divisa FROM bancos WHERE id = '$id_banco_in'"))['divisa'];
						
						$sql = "INSERT INTO prestamos (id_usuario, id_pago_out, monto, divisa, flag) VALUES ('$id_usuario', '$id', '$monto', '$divisa', 1)";
						mysqli_query($link, $sql);
						if (mysqli_num_rows(mysqli_query($link, "SELECT * FROM prestamos WHERE id_usuario = '$id_usuario' AND divisa = '$divisa' AND flag = 0")) == 1) {
							$sql = "UPDATE prestamos SET monto = monto +$monto WHERE id_usuario = '$id_usuario' AND divisa = '$divisa' AND flag = 0";
						} else {
							$sql = "INSERT INTO prestamos (id_usuario, id_pago_out, monto, divisa, flag) VALUES ('$id_usuario', '$id', '$monto', '$divisa', 0)";
						}
						if(mysqli_query($link, $sql)) {
							$res['mensajes'] = 'Prestamo agregado exitosamente';
							$res['errores'] = false;
						} else {
							$res['mensajes'] = 'Hubo un error agregando el prestamo';
							$res['errores'] = true;
						}
					}

				}
	            $res['mensajes'] = 'Archivo cargado existosamente';
				$res['errores'] = false;
	        } else {
	            $res['mensajes'] = 'Hubo un error cargando el archivo.';
	            $res['errores'] = true;
	        }
	    }
	}
} else {
	$res['mensajes'] = "No tiene permiso.";
	$res['errores'] = true;
}

echo json_encode($res);
?>