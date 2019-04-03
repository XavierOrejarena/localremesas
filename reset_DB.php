<?php

include "connect.php";

if (mysqli_connect_errno()) {
	echo "Failed to connect to MySQL: " . mysqli_connect_error();
}else {
	
	$link->query("DROP TABLE usuarios_cuentas");
	$link->query("DROP TABLE pagos_out");
	$link->query("DROP TABLE pagos_in");
	$link->query("DROP TABLE prestamos");
	$link->query("DROP TABLE cuentas");
	$link->query("DROP TABLE usuarios");
	$link->query("DROP TABLE tasas");
	$link->query("DROP TABLE bancos");

	$sql = "CREATE TABLE usuarios (
	id INT(10) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
	username VARCHAR(255) UNIQUE,
	password VARCHAR(255),
	tipo VARCHAR(255),
	reg_date TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
	)";

	if ($link->query($sql) === TRUE) {
	    echo "<p style='color: green;'>usuarios<p>";
	} else {
	    echo "<p style='color: red;'>no usuarios<p>" . $conn->error;
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
	    echo "<p style='color: green;'>cuentas<p>";
	} else {
	    echo "<p style='color: red;'>no cuentas<p>" . $conn->error;
	}

	$sql = "CREATE TABLE usuarios_cuentas (
	id_usuario INT(10) UNSIGNED,
	FOREIGN KEY (id_usuario) REFERENCES usuarios(id),
	id_cuenta INT(10) UNSIGNED,
	FOREIGN KEY (id_cuenta) REFERENCES cuentas(id),
	CONSTRAINT PK_usuarios_cuentas PRIMARY KEY (id_usuario,id_cuenta)
	)";

	if ($link->query($sql) === TRUE) {
	    echo "<p style='color: green;'>usuarios_cuentas<p>";
	} else {
	    echo "<p style='color: red;'>no usuarios_cuentas<p>" . $conn->error;
	}
	
	$sql = "CREATE TABLE bancos (
		id INT(10)  UNSIGNED AUTO_INCREMENT PRIMARY KEY,
		nombre VARCHAR(20),
		saldo DECIMAL(10,2),
		divisa VARCHAR(3)
		)";
	
	if ($link->query($sql) === TRUE) {
		echo "<p style='color: green;'>bancos<p>";
	} else {
		echo "<p style='color: red;'>no bancos<p>" . $conn->error;
	}

	$sql = "CREATE TABLE pagos_in (
	id INT(10) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
	id_usuario INT(10) UNSIGNED,
	FOREIGN KEY (id_usuario) REFERENCES usuarios(id),
	id_banco INT(10) UNSIGNED,
	FOREIGN KEY (id_banco) REFERENCES bancos(id),
	monto DECIMAL(10,2),
	referencia VARCHAR(64),
	tasa DECIMAL(10,2),
	estado VARCHAR(64),
	flag INT(1) UNSIGNED,
	reg_date TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
	)";

	if ($link->query($sql) === TRUE) {
	    echo "<p style='color: green;'>pagos_in<p>";
	} else {
	    echo "<p style='color: red;'>no pagos_in<p>" . $conn->error;
	}

	$sql = "CREATE TABLE pagos_out (
	id INT(10) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
	id_usuario INT(10) UNSIGNED,
	FOREIGN KEY (id_usuario) REFERENCES usuarios(id),
	id_pago_in INT(10) UNSIGNED,
	FOREIGN KEY (id_pago_in) REFERENCES pagos_in(id),
	id_cuenta INT(10) UNSIGNED,
	FOREIGN KEY (id_cuenta) REFERENCES cuentas(id),
	monto INT(10),
	referencia VARCHAR(64),
	estado VARCHAR(64),
	reg_date TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
	)";

	if ($link->query($sql) === TRUE) {
	    echo "<p style='color: green;'>pagos_out<p>";
	} else {
	    echo "<p style='color: red;'>no pagos_out<p>" . $conn->error;
	}

	$sql = "CREATE TABLE prestamos (
	id INT(10) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
	id_usuario INT(10) UNSIGNED,
	FOREIGN KEY (id_usuario) REFERENCES usuarios(id),
	monto DECIMAL(10,2),
	id_banco INT(10) UNSIGNED,
	FOREIGN KEY (id_banco) REFERENCES bancos(id)
	)";

	if ($link->query($sql) === TRUE) {
		echo "<p style='color: green;'>prestamos<p>";
	} else {
		echo "<p style='color: red;'>no prestamos<p>" . $conn->error;
	}

	$sql = "CREATE TABLE tasas (
	divisa VARCHAR(4) PRIMARY KEY NOT NULL,
	tasa DECIMAL(10,2)
	)";

	if ($link->query($sql) === TRUE) {
	    echo "<p style='color: green;'>tasas<p>";
	} else {
	    echo "<p style='color: red;'>no tasas<p>" . $conn->error;
	}

	$sql = "INSERT INTO tasas (divisa, tasa) VALUES ('PEN', 80)";

	if ($link->query($sql) === TRUE) {
	    echo "<p style='color: green;'>PEN<p>";
	} else {
	    echo "<p style='color: red;'>no PEN<p>" . $conn->error;
	}

	$sql = "INSERT INTO tasas (divisa, tasa) VALUES ('USD', 300)";

	if ($link->query($sql) === TRUE) {
	    echo "<p style='color: green;'>USD<p>";
	} else {
	    echo "<p style='color: red;'>no USD<p>" . $conn->error;
	}

	$password = password_hash('xavier123', PASSWORD_DEFAULT);
	$sql = "INSERT INTO usuarios (username, password, tipo, reg_date) VALUES ('XAVIER', '$password', 'ADMIN', DATE_ADD(NOW(),INTERVAL 3 HOUR))";
	
	if ($link->query($sql)) {
		echo "<p style='color: green;'>Admin creado.</br>";
	} else {
		echo "<p style='color: green;'>Error creando Admin.</br>";
	}

	$sql = "INSERT INTO cuentas (nombre, tipo_cedula, cedula, tipo_cuenta, cuenta) VALUES ('XAVIER OREJARENA', 'V', '19398747', 'CORRIENTE', '01340946340001440220')";
	
	if ($link->query($sql)) {
		echo "<p style='color: green;'>Cuenta creada exitosamente.</br>";
	} else {
		echo "<p style='color: green;'>Error creando cuenta.</br>";
	}

	$sql = "INSERT INTO cuentas (nombre, tipo_cedula, cedula, tipo_cuenta, cuenta) VALUES ('XAVIER OREJARENA', 'V', '19398747', 'CORRIENTE', '01050020651020666722')";
	
	if ($link->query($sql)) {
		echo "<p style='color: green;'>Cuenta creada exitosamente.</br>";
	} else {
		echo "<p style='color: green;'>Error creando cuenta.</br>";
	}

	$link->query("INSERT INTO usuarios_cuentas (id_usuario, id_cuenta) VALUES (1,1)");
	$link->query("INSERT INTO usuarios_cuentas (id_usuario, id_cuenta) VALUES (1,2)");

	$link->query("INSERT INTO bancos (nombre, saldo, divisa) VALUES ('MERCANTIL', 100, 'VES')");
	$link->query("INSERT INTO bancos (nombre, saldo, divisa) VALUES ('BANESCO', 100, 'VES')");
	$link->query("INSERT INTO bancos (nombre, saldo, divisa) VALUES ('INTERBANK', 100, 'USD')");
	$link->query("INSERT INTO bancos (nombre, saldo, divisa) VALUES ('INTERBANK', 100, 'PEN')");
	$link->query("INSERT INTO bancos (nombre, saldo, divisa) VALUES ('BCP', 100, 'PEN')");
	$link->query("INSERT INTO bancos (nombre, saldo, divisa) VALUES ('SCOTIABANK', 100, 'PEN')");
	// $link->query("INSERT INTO pagos_in (tasa, id_usuario, id_banco, monto, referencia, estado, reg_date) VALUES (0, 1, 1, 0, 0, 'APROBADO', DATE_ADD(NOW(),INTERVAL 3 HOUR))");
}
?>