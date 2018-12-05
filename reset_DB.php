#!/usr/bin/env php
<?php

include "connect.php";

if (mysqli_connect_errno()) {
	echo "Failed to connect to MySQL: " . mysqli_connect_error();
}else {
	
	$link->query("DROP TABLE usuarios_cuentas");
	$link->query("DROP TABLE pagos_out");
	$link->query("DROP TABLE pagos_in");
	$link->query("DROP TABLE cuentas");
	$link->query("DROP TABLE usuarios");
	$link->query("DROP TABLE tasas");
	$link->query("DROP TABLE bancos");

	$sql = "CREATE TABLE usuarios (
	id INT(10) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
	username VARCHAR(255) UNIQUE,
	password VARCHAR(255),
	tipo VARCHAR(255),
	reg_date (TIMESTAMP + INTERVAL 3 HOUR)
	)";

	if ($link->query($sql) === TRUE) {
	    echo "Droped\n";
	} else {
	    echo "Not droped\n" . $conn->error;
	}

	$sql = "CREATE TABLE cuentas (
	id INT(10) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
	nombre VARCHAR(20),
	tipo_cedula VARCHAR(1),
	cedula VARCHAR(10),
	tipo_cuenta VARCHAR(9),
	cuenta VARCHAR(20)
	)";

	if ($link->query($sql) === TRUE) {
	    echo "Droped\n";
	} else {
	    echo "Not droped\n" . $conn->error;
	}

	$sql = "CREATE TABLE usuarios_cuentas (
	id_usuario INT(10) UNSIGNED,
	FOREIGN KEY (id_usuario) REFERENCES usuarios(id),
	id_cuenta INT(10) UNSIGNED,
	FOREIGN KEY (id_cuenta) REFERENCES cuentas(id),
	CONSTRAINT PK_usuarios_cuentas PRIMARY KEY (id_usuario,id_cuenta)
	)";

	if ($link->query($sql) === TRUE) {
	    echo "Droped\n";
	} else {
	    echo "Not droped\n" . $conn->error;
	}

	$sql = "CREATE TABLE pagos_in (
	id INT(10) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
	id_usuario INT(10) UNSIGNED,
	FOREIGN KEY (id_usuario) REFERENCES usuarios(id),
	divisa VARCHAR(64),
	banco VARCHAR(64),
	monto VARCHAR(64),
	referencia VARCHAR(64),
	estado VARCHAR(64),
	reg_date (TIMESTAMP + INTERVAL 3 HOUR)
	)";

	if ($link->query($sql) === TRUE) {
	    echo "Droped\n";
	} else {
	    echo "Not droped\n" . $conn->error;
	}

	$sql = "CREATE TABLE pagos_out (
	id INT(10) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
	id_usuario INT(10) UNSIGNED,
	FOREIGN KEY (id_usuario) REFERENCES usuarios(id),
	id_pago_in INT(10) UNSIGNED,
	FOREIGN KEY (id_pago_in) REFERENCES pagos_in(id),
	id_cuenta INT(10) UNSIGNED,
	FOREIGN KEY (id_cuenta) REFERENCES cuentas(id),
	monto VARCHAR(64),
	referencia VARCHAR(64),
	estado VARCHAR(64),
	reg_date (TIMESTAMP + INTERVAL 3 HOUR)
	)";

	if ($link->query($sql) === TRUE) {
	    echo "Droped\n";
	} else {
	    echo "Not droped\n" . $conn->error;
	}

	$sql = "CREATE TABLE tasas (
	divisa VARCHAR(4) PRIMARY KEY NOT NULL,
	tasa DECIMAL(10,2)
	)";

	if ($link->query($sql) === TRUE) {
	    echo "Droped\n";
	} else {
	    echo "Not droped\n" . $conn->error;
	}

	$sql = "INSERT INTO tasas (divisa, tasa) VALUES ('PEN', 80)";

	if ($link->query($sql) === TRUE) {
	    echo "Droped\n";
	} else {
	    echo "Not droped\n" . $conn->error;
	}

	$sql = "INSERT INTO tasas (divisa, tasa) VALUES ('USD', 300)";

	if ($link->query($sql) === TRUE) {
	    echo "Droped\n";
	} else {
	    echo "Not droped\n" . $conn->error;
	}

	$sql = "CREATE TABLE bancos (
		id INT(10)  UNSIGNED AUTO_INCREMENT PRIMARY KEY,
		nombre VARCHAR(20),
		saldo INT(10)
		)";

	if ($link->query($sql) === TRUE) {
		echo "Droped\n";
	} else {
		echo "Not droped\n" . $conn->error;
	}
	$password = password_hash('xavier123', PASSWORD_DEFAULT);
	$sql = "INSERT INTO usuarios (username, password, tipo) VALUES ('XAVIER', '$password', 'ADMIN')";
	
	if ($link->query($sql)) {
		echo "<br>Usuario creado</br>";
	} else {
		echo "<br>Errro creando admin</br>";
	}

	}
?>