<?php
header( 'Content-type: application/json' );
include "connect.php";

if ($_POST['id_usuario']) {
	$id_usuario = $_POST['id_usuario'];

	// $sql = "SELECT cuentas.* FROM usuarios_cuentas, cuentas WHERE usuarios_cuentas.id_cuenta = cuentas.id AND usuarios_cuentas.id_usuario = '$id_usuario'";

	// $sql = "SELECT cuentas.*, usuarios.tipo FROM usuarios_cuentas, cuentas, usuarios WHERE usuarios_cuentas.id_cuenta = cuentas.id AND usuarios_cuentas.id_usuario = '$id_usuario'";

	$sql = "SELECT cuentas.*, usuarios.tipo FROM usuarios_cuentas, cuentas, usuarios WHERE usuarios_cuentas.id_cuenta = cuentas.id AND usuarios_cuentas.id_usuario = '$id_usuario' AND usuarios.id = '$id_usuario'";
	
	$result = mysqli_query($link, $sql);

    if ($result->num_rows > 0) {
    	while($row = $result->fetch_assoc()) {
	        $res['cuentas'][] = $row;
		}
    } else {
    	$res['tipo'] = mysqli_fetch_array(mysqli_query($link, "SELECT tipo FROM usuarios WHERE id = '$id_usuario'"))['tipo'];
    }


	// $sql = "SELECT * FROM usuarios WHERE id = '$id_usuario'";
	// $result = mysqli_query($link, $sql);
	// if (mysqli_num_rows($result) > 0) {
	// 	$res['mensaje_usuario'] = "Usuario ya existe";
	// 	$sql = "SELECT * FROM usuarios_cuentas WHERE id_usuario = '$id_usuario'";
	// 	$result = mysqli_query($link, $sql);
	// 	while($row = mysqli_fetch_array($result)){
	// 		$id = $row['id_cuenta'];
	// 		$sql = "SELECT * FROM cuentas WHERE id = '$id'";
	// 		$res['cuentas'][] = mysqli_fetch_array(mysqli_query($link, $sql));
	// 		$res['cuentas'][sizeof($res['cuentas'])-1]['monto'] = 0;
	// 	}
	// }else {
	// 	$res['mensaje_usuario'] = "Usuario no existe";
	// }
}

echo json_encode($res);
?>