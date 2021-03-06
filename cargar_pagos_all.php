<?php
header( 'Content-type: application/json' );
include "connect.php";

$date = $_POST['date'];

if (!$date) {
	$offset = -4*60*60; //converting 5 hours to seconds.
	$dateFormat = "Y-m-d H:i:s";
	$date = gmdate($dateFormat, time()+$offset);
}

if ($_POST['mes'] == 'mes') {
	$result = mysqli_query($link,
"SELECT pagos_in.*, cuentas.nombre, bancos.nombre AS name, bancos.divisa
FROM bancos, pagos_in, pagos_out
JOIN cuentas ON cuentas.id = pagos_out.id_cuenta
WHERE pagos_in.reg_date > NOW() - INTERVAL 21 HOUR
AND pagos_in.estado = 'RECHAZADA'
AND pagos_out.id_pago_in = pagos_in.id
AND bancos.id = pagos_in.id_banco
AND MONTH(DATE(pagos_in.reg_date)) = MONTH('$date')");
} else {
	$result = mysqli_query($link,
"SELECT pagos_in.*, cuentas.nombre, bancos.nombre AS name, bancos.divisa
FROM bancos, pagos_in, pagos_out
JOIN cuentas ON cuentas.id = pagos_out.id_cuenta
WHERE pagos_in.reg_date > NOW() - INTERVAL 21 HOUR
AND pagos_in.estado = 'RECHAZADA'
AND pagos_out.id_pago_in = pagos_in.id
AND bancos.id = pagos_in.id_banco
AND DATE(pagos_in.reg_date) = '$date'");
}

// file_get_contents("https://api.telegram.org/bot716396100:AAFbVh6W950S4goHt30TVUXW3cuKGdWQmKM/sendMessage?chat_id=149273661&text=$date");
while ($row = mysqli_fetch_array($result)) {
	$res[in][] = $row;
}

if ($_POST['mes'] == 'mes') {

$result = mysqli_query($link, "SELECT pagos_out.*, pagos_in.tasa, pagos_in.id AS id_pago_in, pagos_in.monto AS amount, pagos_in.id_banco AS id_banco_in, bancos.divisa, bancos.nombre AS banco_in, b.nombre AS banco_out, usuarios.tipo
FROM pagos_out
JOIN pagos_in ON pagos_in.id = pagos_out.id_pago_in
JOIN bancos ON bancos.id = pagos_in.id_banco
JOIN bancos as b ON b.id = pagos_out.id_banco
JOIN usuarios ON usuarios.id = pagos_in.id_usuario
WHERE bancos.id = pagos_in.id_banco
AND pagos_out.estado = 'PAGADO'
AND MONTH(DATE(pagos_out.reg_date)) = MONTH('$date')
ORDER BY pagos_out.id ASC");

} else {
	$result = mysqli_query($link, 
"SELECT pagos_out.*, pagos_in.tasa, pagos_in.id AS id_pago_in, pagos_in.monto AS amount, pagos_in.id_banco AS id_banco_in, bancos.divisa, bancos.nombre AS banco_in, b.nombre AS banco_out, usuarios.tipo
FROM pagos_out
JOIN pagos_in ON pagos_in.id = pagos_out.id_pago_in
JOIN bancos ON bancos.id = pagos_in.id_banco
JOIN bancos as b ON b.id = pagos_out.id_banco
JOIN usuarios ON usuarios.id = pagos_in.id_usuario
WHERE bancos.id = pagos_in.id_banco
AND pagos_out.estado = 'PAGADO'
AND DATE(pagos_out.reg_date) = '$date'
ORDER BY pagos_out.id ASC");
}

while ($row = mysqli_fetch_assoc($result)) {
	$res[out][] = $row;
}

echo json_encode($res);
?>