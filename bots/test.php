#!/usr/bin/env php
<?php

$link = mysqli_connect('server237.web-hosting.com', 'bitnehuc_bitnehuc', '58NZPnt4aJsB', 'bitnehuc_Telegram');

if (mysqli_connect_errno()) {
	echo "Failed to connect to MySQL: " . mysqli_connect_error();
}else {
	echo "Good";
	$chat_id = "123";
	$firs_tname = "xavier";
	$last_name = "orejarena";
	$username = "xavierorejarena";
	$sql = "INSERT INTO users (chat_id, firs_tname, last_name, username) VALUES ('$chat_id', '$firs_tname', '$last_name', '$username')";
	if ($link->query($sql) === TRUE) {
	    echo "Usuario agregado correctamente";
	} else {
	    echo "Error agregando usuario: " . $conn->error;
	}

}
mysqli_close($link);

//ssh -f bitnehuc@server237.web-hosting.com -p21098 -L 3306:127.0.0.1:3306 -N
?>
