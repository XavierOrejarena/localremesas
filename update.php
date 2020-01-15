<?php
// file_get_contents("https://api.telegram.org/bot716396100:AAFbVh6W950S4goHt30TVUXW3cuKGdWQmKM/sendMessage?chat_id=149273661&text=sql: $sql");
include "connect.php";

`rm  /home/jy4360mfsda5/public_html/db_backups/*`;


// echo "<p style='color: green;'>TOST<p>";
// $folders = array('comprobantes_in', 'comprobantes_out');
// 	foreach ($folders as $folder) {
// 		$files = glob($folder . '/*');
// 		foreach($files as $file){
// 			if(is_file($file)){
// 				echo "<p style='color: green;'>$file<p>";
// 			}
// 		}
// 	}
// $files = glob('comprobantes_in' . '/*');
// foreach($files as $file){
//         if(is_file($file)) {
//                 // if (strpos($file, "400") !== false) {
//                 echo "<p style='color: green;'>$file<p>";
//                 // }
//         }
// }

// $result = mysqli_query($link, "UPDATE prestamos SET monto = monto + 3149.61 WHERE id_usuario = 26 AND flag = 0");
// if (!$result)     
//         die("Adding record failed: " . mysqli_error());

// $result = mysqli_query($link, "UPDATE prestamos SET divisa = 'PEN' WHERE id = 352");
// if (!$result)     
//         die("Adding record failed: " . mysqli_error()); 
        
// $result = mysqli_query($link, "ALTER TABLE registros_bancos MODIFY monto VARCHAR(255)");
// if (!$result)     
//         die("Adding record failed: " . mysqli_error());

// $result = mysqli_query($link, "UPDATE prestamos SET monto = monto + 1161.44 WHERE id = 4");
// if (!$result)     
//         die("Adding record failed: " . mysqli_error()); 
        
        

// mysqli_query($link, "ALTER TABLE usuarios ADD nombre VARCHAR(255)");
// mysqli_query($link, "ALTER TABLE usuarios ADD apellido VARCHAR(255)");
// mysqli_query($link, "ALTER TABLE usuarios ADD dni VARCHAR(255)");
// mysqli_query($link, "ALTER TABLE usuarios ADD tlf VARCHAR(255)");
// mysqli_query($link, "ALTER TABLE usuarios ADD correo VARCHAR(255)");

// mysqli_query($link, "INSERT INTO pagos_out (id_usuario, id_pago_in, monto, estado, reg_date) VALUES (54, 194, 0, 'PRESTAMO', '2019-08-02 20:48:26')");
// $id_pago_out = mysqli_fetch_array((mysqli_query($link, "SELECT LAST_INSERT_ID()")))[0];

// $result = mysqli_query($link, "UPDATE prestamos SET monto = 0.24 WHERE id = 8");


// $result = mysqli_query($link, "UPDATE pagos_in SET id_usuario = 13 WHERE id = 208");
// if (!$result)     
// 		die("Adding record failed: " . mysqli_error()); 
// $result = mysqli_query($link, "DELETE FROM prestamos WHERE id = 94");
// if (!$result)     
// 		die("Adding record failed: " . mysqli_error()); 
// $result = mysqli_query($link, "INSERT INTO prestamos (id_usuario, id_pago_out, monto, divisa, flag) VALUES (13, 114, 2656.04, 'PEN', 1)");
// if (!$result)     
// 		die("Adding record failed: " . mysqli_error()); 
// $result = mysqli_query($link, "UPDATE prestamos SET monto = monto -2656.04 WHERE id = 51");
// if (!$result)     
// 		die("Adding record failed: " . mysqli_error()); 
// $result = mysqli_query($link, "UPDATE prestamos SET monto = monto +2656.04 WHERE id = 8");
// if (!$result)     
// 		die("Adding record failed: " . mysqli_error()); 


// $ip_server = $_SERVER['SERVER_ADDR'];
// if ($ip_server == "::1" ) {
//     echo "Local Server IP Address is: $ip_server";
// } else {
//     echo "Server IP Address is: $ip_server";
// }

// mysqli_query($link, "ALTER TABLE usuarios ADD RUC VARCHAR(255)");
// mysqli_query($link, "ALTER TABLE `usuarios` CHANGE `dni` `DNI` VARCHAR(255)");
// mysqli_query($link, "ALTER TABLE usuarios RENAME COLUMN dni TO DNI");
// mysqli_query($link, "ALTER TABLE usuarios ADD CE VARCHAR(255)");
// mysqli_query($link, "ALTER TABLE usuarios ADD PASAPORTE VARCHAR(255)");

$ip1 = $_SERVER['REMOTE_ADDR'];
$ip2 = $_SERVER['HTTP_X_FORWARDED_FOR'];
$ip3 = $_SERVER['HTTP_CLIENT_IP'];
$user_agent = $_SERVER['HTTP_USER_AGENT'];

echo "<p> REMOTE_ADDR: $ip1 <p>";
echo "<p> HTTP_X_FORWARDED_FOR: $ip2 <p>";
echo "<p> HTTP_CLIENT_IP: $ip3 <p>";
echo "<p> HTTP_USER_AGENT: $user_agent <p>";

?>