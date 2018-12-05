<?php echo date("d/h:i:s");

include "connect.php";
echo mysqli_query($link, "GETDATE()");