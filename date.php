<?php echo date("d/h:i:s");

include "connect.php";

print_r(mysqli_query($link, "GETDATE()"));

echo mysqli_query($link, "GETDATE()");