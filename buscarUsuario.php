<?php
header( 'Content-type: application/json' );
include "connect.php";

$id_usuario = $_POST['id_usuario'];

// $result = mysqli_query($link, "SELECT cuentas.* FROM usuarios_cuentas JOIN cuentas ON cuentas.id = usuarios_cuentas.id_cuenta WHERE usuarios_cuentas.id_usuario = '$id_usuario'");

// while ($row = mysqli_fetch_array($result)) {
// 	$res[cuentas][] = $row;
// }

// $res['tipo'] = mysqli_fetch_array(mysqli_query($link, "SELECT tipo FROM usuarios WHERE id = '$id_usuario'"))['tipo'];

$sql = "SELECT cuentas.*, usuarios.tipo
FROM usuarios_cuentas, cuentas, usuarios
WHERE usuarios_cuentas.id_cuenta = cuentas.id
AND usuarios_cuentas.id_usuario = '$id_usuario'
AND usuarios.id = '$id_usuario'
ORDER BY cuentas.id DESC";

$result = mysqli_query($link, $sql);

if ($result->num_rows > 0) {
	while($row = $result->fetch_assoc()) {
		$res['cuentas'][] = $row;
	}
} else {
	$res['tipo'] = mysqli_fetch_array(mysqli_query($link, "SELECT tipo FROM usuarios WHERE id = '$id_usuario'"))['tipo'];
}

echo json_encode($res);
?>