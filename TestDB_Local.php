#!/usr/bin/env php
<?php

// session_start();

include "connect.php";

if (mysqli_connect_errno()) {
	echo "Failed to connect to MySQL: " . mysqli_connect_error();
}else {

	$password = password_hash('javier123', PASSWORD_DEFAULT);
	$sql = "UPDATE usuarios SET password = '$password' WHERE id = 1";
	
	if ($link->query($sql)) {
		echo "<br>Usuario creado</br>";
	} else {
		echo "<br>Error creando admin</br>";
	}




	// $monto = 100000;
	// $id_banco = 3;
	// $referencia = '123456';

	// $id = mysqli_fetch_assoc(mysqli_query($link, "SELECT id FROM pagos_in WHERE referencia = '$referencia' AND monto = '$monto' AND id_banco = '$id_banco'"))['id'];
	// echo $id;


	// $divisa = 'USD';
	// $banco = 'INTERBANK';

	// $id_banco = mysqli_fetch_assoc(mysqli_query($link, "SELECT id FROM bancos WHERE nombre = '$banco' AND divisa = '$divisa'"))['id'];


	// $sql = "SELECT * FROM bancos";
	// $result = mysqli_query($link, $sql);

	// while ($row = mysqli_fetch_array($result)) {
	// 	$res[] = $row;
	// 	print_r($row);
	// }

	// while($row = $result->fetch_assoc()) {
    //     print_r($row);
    // }


	// $id_usuario = 1;
	// $monto = 123;
	// $monto = $monto + mysqli_fetch_array(mysqli_query($link, "SELECT monto FROM prestamos WHERE id_usuario = '$id_usuario'"))['monto'];

	// if (mysqli_num_rows(mysqli_query($link, "SELECT * FROM prestamos WHERE id_usuario = '$id_usuario' LIMIT 1"))) {
	// 	$sql = "UPDATE prestamos SET monto = $monto  WHERE id_usuario = '$id_usuario'";
	// } else {
	// 	$sql = "INSERT INTO prestamos (id_usuario, monto) VALUES ('$id_usuario', '$monto')";
	// }

	// echo $sql;

	// $sql = "INSERT INTO bancos (nombre, saldo) VALUES ('Banesco', 100000)";
	// $sql = "INSERT INTO bancos (nombre, saldo) VALUES ('Mercantil', 120000)";
	// $link->query($sql);

	// $sql = "CREATE TABLE bancos (
	// 	id INT(10)  UNSIGNED AUTO_INCREMENT PRIMARY KEY,
	// 	nombre VARCHAR(20),
	// 	saldo INT(10)
	// 	)";

	// if ($link->query($sql) === TRUE) {
	// 	echo "Droped\n";
	// } else {
	// 	echo "Not droped\n" . $conn->error;
	// }
	// $username = 'xavier';


 //    if (password_verify('xavier123', mysqli_fetch_assoc(mysqli_query($link, "SELECT * FROM usuarios WHERE username = '$username'"))['password'])) {
 //      $res = true;
 //      $_SESSION['username'] = $username;
 //      $_SESSION['time'] = time();
 //    } else {
 //      $res = false;
 //    }

 //    print_r($_SESSION);

	// $id_usuario = 2;

	// $sql = "SELECT cuentas.*, usuarios.tipo FROM usuarios_cuentas, cuentas, usuarios WHERE usuarios_cuentas.id_cuenta = cuentas.id AND usuarios_cuentas.id_usuario = '$id_usuario' AND usuarios.id = '$id_usuario'";

	// $sql = "SELECT cuentas.*, usuarios.tipo FROM usuarios_cuentas, cuentas, usuarios WHERE usuarios_cuentas.id_cuenta = cuentas.id AND usuarios_cuentas.id_usuario = '$id_usuario' AND usuarios.id = '$id_usuario'";

	// $result = mysqli_query($link, $sql);

	// $i = 1;
	// while($row = $result->fetch_assoc()) {
	// 	echo $i, " ";
	// 	print_r($row);
	// 	$i++;
 //    }

	// print_r(mysqli_fetch_array(mysqli_query($link, "SELECT tipo FROM usuarios WHERE id = '$id_usuario'"))['tipo']);



	// echo "Good\n";
	// $id_pago_in = 19;
	// $result = mysqli_query($link, "SELECT estado FROM pagos_out WHERE id_pago_in = '$id_pago_in'");
	// $aux = true;
	// while($row = mysqli_fetch_array($result)){
	// 	if ($row['estado'] != 'PAGADO') {
	// 		$aux = false;
	// 		break;
	// 	}
	// }

	// if ($aux) {
	// 	mysqli_query($link, "UPDATE pagos_in SET estado = 'PAGADO' WHERE id = '$id_pago_in'");
	// }
	
	// $sql = "DROP TABLE pagos_out";
	// $link->query("DROP TABLE usuarios_cuentas");
	// $link->query("DROP TABLE pagos_out");
	// $link->query("DROP TABLE pagos_in");
	// $link->query("DROP TABLE cuentas");
	// $link->query("DROP TABLE usuarios");

	    

	// $sql = "INSERT INTO usuarios (tipo) VALUES ('REGULAR')";
	// $sql = "INSERT INTO usuarios_cuentas (id_usuario, id_cuenta) VALUES (1,1)";
	// $id_usuario = 1;
	// $sql = "SELECT * FROM usuarios_cuentas WHERE id_usuario = '$id_usuario'";

	// $result = mysqli_fetch_array(mysqli_query($link, $sql));
	// $sql = "SELECT * FROM usuarios_cuentas WHERE id_usuario = '$id_usuario'";
	// $result = mysqli_query($link, $sql);
	// while($row = mysqli_fetch_array($result)){
	// $id = $row['id_cuenta'];
	// $sql = "SELECT * FROM cuentas WHERE id = '$id'";
	// $result2 = mysqli_query($link, $sql);
	// 	$result2[] = mysqli_fetch_array(mysqli_query($link, $sql));
	// }

	// print_r($result2);

	// mysqli_query($link, "INSERT INTO usuarios (tipo) VALUES ('REGULAR')");
	// print_r(mysqli_fetch_array((mysqli_query($link, "SELECT LAST_INSERT_ID()")))[0]);

	// $sql = "CREATE TABLE usuarios (
	// id INT(10) ZEROFILL UNSIGNED AUTO_INCREMENT PRIMARY KEY,
	// username VARCHAR(255),
	// password VARCHAR(255),
	// tipo VARCHAR(255),
	// reg_date TIMESTAMP
	// )";

	// $sql = "DROP TABLE tasas";

	// if ($link->query($sql) === TRUE) {
	//     echo "Droped\n";
	// } else {
	//     echo "Not droped\n" . $conn->error;
	// }

	// $sql = "CREATE TABLE tasas (
	// divisa VARCHAR(4) PRIMARY KEY NOT NULL,
	// tasa INT(10)
	// )";

	// if ($link->query($sql) === TRUE) {
	//     echo "Droped\n";
	// } else {
	//     echo "Not droped\n" . $conn->error;
	// }

	// $sql = "INSERT INTO tasas (divisa, tasa) VALUES ('PEN', 80)";

	// if ($link->query($sql) === TRUE) {
	//     echo "Droped\n";
	// } else {
	//     echo "Not droped\n" . $conn->error;
	// }

	// $sql = "INSERT INTO tasas (divisa, tasa) VALUES ('USD', 300)";

	// if ($link->query($sql) === TRUE) {
	//     echo "Droped\n";
	// } else {
	//     echo "Not droped\n" . $conn->error;
	// }

	// $sql = "UPDATE tasas SET REGULAR = 80 WHERE id = 1";
	// $sql = "UPDATE tasas SET BUSCADOR = tasas.REGULAR/1.04 WHERE id = 1";

	// mysqli_query($link, $sql);

	// $tipo = 'REGULAR';

	// $sql = "SELECT * FROM tasas";
	// $res = mysqli_fetch_array(mysqli_query($link, $sql));

	// print_r($res[$tipo]);


	// if ($link->query($sql) === TRUE) {
	//     echo "Droped\n";
	// } else {
	//     echo "Not droped\n" . $conn->error;
	// }

	// $sql = "CREATE TABLE usuarios_cuentas (
	// id_usuario INT(10) ZEROFILL UNSIGNED,
	// FOREIGN KEY (id_usuario) REFERENCES usuarios(id),
	// id_cuenta INT(10) ZEROFILL UNSIGNED,
	// FOREIGN KEY (id_cuenta) REFERENCES cuentas(id),
	// CONSTRAINT PK_usuarios_cuentas PRIMARY KEY (id_usuario,id_cuenta)
	// )";

	// if ($link->query($sql) === TRUE) {
	//     echo "Droped\n";
	// } else {
	//     echo "Not droped\n" . $conn->error;
	// }

	// $sql = "CREATE TABLE pagos_in (
	// id INT(10) ZEROFILL UNSIGNED AUTO_INCREMENT PRIMARY KEY,
	// id_usuario INT(10) UNSIGNED,
	// FOREIGN KEY (id_usuario) REFERENCES usuarios(id),
	// divisa VARCHAR(64),
	// banco VARCHAR(64),
	// monto VARCHAR(64),
	// referencia VARCHAR(64),
	// estado VARCHAR(64),
	// reg_date TIMESTAMP
	// )";

	// if ($link->query($sql) === TRUE) {
	//     echo "Droped\n";
	// } else {
	//     echo "Not droped\n" . $conn->error;
	// }

	// $sql = "CREATE TABLE pagos_out (
	// id INT(10) ZEROFILL UNSIGNED AUTO_INCREMENT PRIMARY KEY,
	// id_usuario INT(10) UNSIGNED,
	// FOREIGN KEY (id_usuario) REFERENCES usuarios(id),
	// id_pago_in INT(10) UNSIGNED,
	// FOREIGN KEY (id_pago_in) REFERENCES pagos_in(id),
	// id_cuenta INT(10) UNSIGNED,
	// FOREIGN KEY (id_cuenta) REFERENCES cuentas(id),
	// monto VARCHAR(64),
	// referencia VARCHAR(64),
	// estado VARCHAR(64),
	// reg_date TIMESTAMP
	// )";

	// if ($link->query($sql) === TRUE) {
	//     echo "Droped\n";
	// } else {
	//     echo "Not droped\n" . $conn->error;
	// }

	}

// ssh -f bitnehuc@server237.web-hosting.com -p21098 -L 3306:127.0.0.1:3306 -N
// gu3bXDTFJqrV
?>