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
$tipo = $_POST['tipo'];
$numero = $_POST['numero'];
$tlf = $_POST['tlf'];
$correo = strtoupper($_POST['correo']);
// $sql = "UPDATE usuarios SET nombre = '$nombre', apellido = '$apellido', $tipo = '$numero', tlf = '$tlf', correo = '$correo' WHERE id = '$id'";

// file_get_contents("https://api.telegram.org/bot716396100:AAFbVh6W950S4goHt30TVUXW3cuKGdWQmKM/sendMessage?chat_id=149273661&text=$sql");

echo json_encode(mysqli_query($link, "UPDATE usuarios SET nombre = '$nombre', apellido = '$apellido', $tipo = '$numero', tlf = '$tlf', correo = '$correo' WHERE id = '$id'"));

?>