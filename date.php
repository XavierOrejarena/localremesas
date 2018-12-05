<?php echo date("d<br>h:i:s"), "<br>";

include "connect.php";

// $result = mysqli_query($link, "SELECT CURRENT_TIMESTAMP");
$result = mysqli_query($link, "SELECT CURRENT_TIMESTAMP + INTERVAL 3 HOUR");

while ($row = mysqli_fetch_array($result)) {
	echo $row[0], " ";
}



// echo mysqli_query($link, "SELECT CURDATE()");