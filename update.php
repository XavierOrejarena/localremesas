<?php

include "connect.php";

// mysqli_query($link, "ALTER TABLE usuarios ADD nombre VARCHAR(255)");
// mysqli_query($link, "ALTER TABLE usuarios ADD apellido VARCHAR(255)");
// mysqli_query($link, "ALTER TABLE usuarios ADD dni VARCHAR(255)");
// mysqli_query($link, "ALTER TABLE usuarios ADD tlf VARCHAR(255)");
// mysqli_query($link, "ALTER TABLE usuarios ADD correo VARCHAR(255)");

// mysqli_query($link, "INSERT INTO pagos_out (id_usuario, id_pago_in, monto, estado, reg_date) VALUES (54, 194, 0, 'PRESTAMO', '2019-08-02 20:48:26')");
// $id_pago_out = mysqli_fetch_array((mysqli_query($link, "SELECT LAST_INSERT_ID()")))[0];

$result = mysqli_query($link, "UPDATE prestamos SET monto = 0.24 WHERE id = 8");
// if (!$result)     
// 		die("Adding record failed: " . mysqli_error()); 


?>