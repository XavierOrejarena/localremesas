<?php echo date("d h:i:s"), "<br>";

include "connect.php";

// $result = mysqli_query($link, "SELECT CURRENT_TIMESTAMP");
// $result = mysqli_query($link, "SELECT CURRENT_TIMESTAMP + INTERVAL 3 HOUR");
$result = mysqli_query($link, "SELECT CURRENT_TIMESTAMP");

while ($row = mysqli_fetch_array($result)) {
	echo $row[0], " ";
}



// echo mysqli_query($link, "SELECT CURDATE()");