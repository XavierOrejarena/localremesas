<?php
header( 'Content-type: application/json' );
include "connect.php";

$sql = "SELECT bancos.nombre, bancos.divisa, pagos_in.id, pagos_in.referencia, pagos_in.monto, pagos_in.id_usuario, pagos_in.reg_date
FROM bancos
INNER JOIN pagos_in ON bancos.id = pagos_in.id_banco
-- WHERE estado IS NULL OR flag = 2
WHERE estado = 'APROBADO'
OR estado IS NULL
OR estado = 'PAGADO'
AND referencia != 0
ORDER BY pagos_in.id DESC";

$result = mysqli_query($link, $sql);

while ($row = mysqli_fetch_assoc($result)) {
	$res[] = $row;
}
 
echo json_encode($res);

?>