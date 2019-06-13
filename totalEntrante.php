<?php
header( 'Content-type: application/json' );
include "connect.php";

$tipo_usuario = $_POST['tipo_usuario'];

$sql = "SELECT pagos_in.monto, pagos_in.tasa, bancos.divisa 
FROM pagos_in
JOIN bancos ON bancos.id = pagos_in.id_banco
WHERE estado = 'PAGADO'";
$result = mysqli_query($link, $sql);

while ($row = mysqli_fetch_assoc($result)) {
	$res[] = $row;
}

echo json_encode($res);
?>