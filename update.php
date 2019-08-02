<?php

include "connect.php";

mysqli_query($link, "ALTER TABLE usuarios ADD nombre VARCHAR(255)");
mysqli_query($link, "ALTER TABLE usuarios ADD apellido VARCHAR(255)");
mysqli_query($link, "ALTER TABLE usuarios ADD dni VARCHAR(255)");
mysqli_query($link, "ALTER TABLE usuarios ADD tlf VARCHAR(255)");
mysqli_query($link, "ALTER TABLE usuarios ADD correo VARCHAR(255)");

?>