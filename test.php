<?php
// header( 'Content-type: application/json' );
include "connect.php";

function Unaccent($string)
{
    return preg_replace('~&([a-z]{1,2})(?:acute|cedil|circ|grave|lig|orn|ring|slash|th|tilde|uml|caron);~i', '$1', htmlentities($string, ENT_COMPAT, 'UTF-8'));
}

$sql = "SELECT id, nombre FROM cuentas";
$result = mysqli_query($link, $sql);

while ($row = mysqli_fetch_assoc($result)) {
    
    $nombre = strtoupper(Unaccent($row['nombre']));
    $id = $row['id'];
    // mysqli_query($link, "UPDATE cuentas SET nombre = '$nombre' WHERE id = '$id'");

    echo "<p>$nombre<p>";
}

// echo json_encode($res);
?>