<?php
header( 'Content-type: application/json' );
include "connect.php";

function Unaccent($string)
{
    return preg_replace('~&([a-z]{1,2})(?:acute|cedil|circ|grave|lig|orn|ring|slash|th|tilde|uml|caron);~i', '$1', htmlentities($string, ENT_COMPAT, 'UTF-8'));
}

$id = $_POST['id'];
$nombre = strtoupper(Unaccent($_POST['nombre']));
$apellido = strtoupper(Unaccent($_POST['apellido']));
$dni = $_POST['dni'];
$tlf = $_POST['tlf'];
$correo = strtoupper($_POST['correo']);

echo json_encode(mysqli_query($link, "UPDATE usuarios SET nombre = '$nombre', apellido = '$apellido', dni = '$dni', tlf = '$tlf', correo = '$correo' WHERE id = '$id'"));

?>