<?php
// date_default_timezone_set('America/Nome');
// echo date("Y-m-d h:i:s"), "<br>";
include "connect.php";


$offset=-4*60*60; //converting 5 hours to seconds.  Hora en localhost
$dateFormat="d-m-Y H:i";
$timeNdate=gmdate($dateFormat, time()+$offset);

echo $timeNdate;

// $result = mysqli_query($link, "SELECT CURRENT_TIMESTAMP");
// $result = mysqli_query($link, "SELECT CURRENT_TIMESTAMP + INTERVAL 3 HOUR");
$result = mysqli_query($link, "SELECT CURRENT_TIMESTAMP");

while ($row = mysqli_fetch_array($result)) {
	// echo $row[0], " ";
}

// echo date_default_timezone_get();
// phpinfo();
// echo mysqli_query($link, "SELECT CURDATE()");