<?php
header( 'Content-type: application/json' );
include "connect.php";
$res = array( 'errores' => false );

if ($_POST['id_usuario'] == 0) {
	mysqli_query($link, "INSERT INTO usuarios (tipo) VALUES ('REGULAR')");
	$id_usuario = mysqli_fetch_array((mysqli_query($link, "SELECT LAST_INSERT_ID()")))[0];
} else {
	$id_usuario = $_POST['id_usuario'];
}
$res['id_usuario'] = $id_usuario;

if (isset($_POST['cuenta'])) {
	for ($i=0; $i < sizeof($_POST['nombre']); $i++) { 
		$nombre = strtoupper($_POST['nombre'][$i]);
		$tipo_cedula = $_POST['tipo_cedula'][$i];
		$cedula = $_POST['cedula'][$i];
		$tipo_cuenta = $_POST['tipo_cuenta'][$i];
		$cuenta = $_POST['cuenta'][$i];

		$sql = "SELECT * FROM cuentas WHERE cuenta = '$cuenta'";
		$result = mysqli_query($link, $sql);

		if (mysqli_num_rows($result) > 0) {
			$res['mensajes'][$i] = "Cuenta ya existe '$cuenta'";
			$res['errores'][$i] = true;
			$id_cuenta = mysqli_fetch_array($result)['id']; //ID DE LA CUENTA
			mysqli_query($link, "INSERT INTO usuarios_cuentas (id_usuario, id_cuenta) VALUES ('$id_usuario', '$id_cuenta')");
		}else {
			$sql = "INSERT INTO cuentas (nombre, tipo_cedula, cedula, tipo_cuenta, cuenta) VALUES ('$nombre', '$tipo_cedula', '$cedula', '$tipo_cuenta', '$cuenta')";

			if ($link->query($sql) === TRUE) {
			    $res['mensajes'][$i] = "Cuenta agregada exitosamente '$cuenta'";
				$id_cuenta = mysqli_fetch_array(mysqli_query($link, "SELECT * FROM cuentas WHERE cuenta = '$cuenta'"))['id'];
				mysqli_query($link, "INSERT INTO usuarios_cuentas (id_usuario, id_cuenta) VALUES ('$id_usuario', '$id_cuenta')");
			} else {
			    $res['mensajes'][$i] = "Error agregando cuenta. '$cuenta'";
			    $res['errores'][$i] = true;
			}
		}
		}
}
echo json_encode($res);
?>