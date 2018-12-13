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
	    echo "<br>usuarios<br>";
	} else {
	    echo "<br>no usuarios<br>" . $conn->error;
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
	    echo "<br>cuentas<br>";
	} else {
	    echo "<br>no cuentas<br>" . $conn->error;
	}

	$sql = "CREATE TABLE usuarios_cuentas (
	id_usuario INT(10) UNSIGNED,
	FOREIGN KEY (id_usuario) REFERENCES usuarios(id),
	id_cuenta INT(10) UNSIGNED,
	FOREIGN KEY (id_cuenta) REFERENCES cuentas(id),
	CONSTRAINT PK_usuarios_cuentas PRIMARY KEY (id_usuario,id_cuenta)
	)";

	if ($link->query($sql) === TRUE) {
	    echo "<br>usuarios_cuentas<br>";
	} else {
	    echo "<br>no usuarios_cuentas<br>" . $conn->error;
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
	reg_date TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
	)";

	if ($link->query($sql) === TRUE) {
	    echo "<br>pagos_in<br>";
	} else {
	    echo "<br>no pagos_in<br>" . $conn->error;
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
	reg_date TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
	)";

	if ($link->query($sql) === TRUE) {
	    echo "<br>pagos_out<br>";
	} else {
	    echo "<br>no pagos_out<br>" . $conn->error;
	}

	$sql = "CREATE TABLE prestamos (
	id INT(10) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
	id_usuario INT(10) UNSIGNED,
	FOREIGN KEY (id_usuario) REFERENCES usuarios(id),
	monto DECIMAL(10,2),
	divisa VARCHAR(4)
	)";

	if ($link->query($sql) === TRUE) {
		echo "<br>prestamos<br>";
	} else {
		echo "<br>no prestamos<br>" . $conn->error;
	}

	$sql = "CREATE TABLE tasas (
	divisa VARCHAR(4) PRIMARY KEY NOT NULL,
	tasa DECIMAL(10,2)
	)";

	if ($link->query($sql) === TRUE) {
	    echo "<br>tasas<br>";
	} else {
	    echo "<br>no tasas<br>" . $conn->error;
	}

	$sql = "INSERT INTO tasas (divisa, tasa) VALUES ('PEN', 80)";

	if ($link->query($sql) === TRUE) {
	    echo "<br>PEN<br>";
	} else {
	    echo "<br>no PEN<br>" . $conn->error;
	}

	$sql = "INSERT INTO tasas (divisa, tasa) VALUES ('USD', 300)";

	if ($link->query($sql) === TRUE) {
	    echo "<br>USD<br>";
	} else {
	    echo "<br>no USD<br>" . $conn->error;
	}

	$sql = "CREATE TABLE bancos (
		id INT(10)  UNSIGNED AUTO_INCREMENT PRIMARY KEY,
		nombre VARCHAR(20),
		saldo DECIMAL(10,2),
		divisa VARCHAR(3)
		)";

	if ($link->query($sql) === TRUE) {
		echo "<br>bancos<br>";
	} else {
		echo "<br>no bancos<br>" . $conn->error;
	}
	$password = password_hash('xavier123', PASSWORD_DEFAULT);
	$sql = "INSERT INTO usuarios (username, password, tipo, reg_date) VALUES ('XAVIER', '$password', 'ADMIN', DATE_ADD(NOW(),INTERVAL 3 HOUR))";
	
	if ($link->query($sql)) {
		echo "<br>Usuario creado</br>";
	} else {
		echo "<br>Error creando admin</br>";
	}

	$sql = "INSERT INTO cuentas (nombre, tipo_cedula, cedula, tipo_cuenta, cuenta) VALUES ('XAVIER OREJARENA', 'V', '19398747', 'CORRIENTE', '01340946340001440220')";
	
	if ($link->query($sql)) {
		echo "<br>cuenta creada exitosamente</br>";
	} else {
		echo "<br>Error creando cuenta</br>";
	}

	$link->query("INSERT INTO usuarios_cuentas (id_usuario, id_cuenta) VALUES (1,1)");

}
?>