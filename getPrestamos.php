<?php
header( 'Content-type: application/json' );
include "connect.php";

$sql = "SELECT prestamos.*, pagos_out.reg_date AS fecha
FROM prestamos
JOIN pagos_out ON pagos_out.id = prestamos.id_pago_out";
$result = mysqli_query($link, $sql);

while ($row = mysqli_fetch_assoc($result)) {
	if ($row['flag'] == 0) {
		$res['total'][] = $row;
	} else {
		$res['detallado'][] = $row;
	}
}

echo json_encode($res);
?>