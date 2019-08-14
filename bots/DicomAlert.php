#!/usr/bin/env php
<?php
// @DicomAlert
$token = '716396100:AAFbVh6W950S4goHt30TVUXW3cuKGdWQmKM';
$chat_id = '@dicomalert';
// $chat_id = 149273661;
// file_get_contents("https://api.telegram.org/bot$token/sendMessage?chat_id=$chat_id&text=Hello");

include "connect.php";
// $host = 'localhost';
// $user = 'xavierorejarena';
// $password = 'NX)[XDCM5~=f';
// $database = 'localremesas';
// $link = mysqli_connect($host, $user, $password, $database);

$data = file_get_contents("http://www.bcv.org.ve");
preg_match_all('/USD/', $data, $matches, PREG_OFFSET_CAPTURE);
$text = substr($data, $matches[0][0][1]+85, 9);
// echo strlen($text), "\n";
// exit;
// echo preg_match('/[a-zA-Z]/', $text), "\n";
// exit;
// if (preg_match('/[a-zA-Z]/', $text)) {
// 	echo "Tiene texto\n";
// }else {
// 	echo "No tiene texto\n";
// }
// preg_match_all('!\d+!', $data, $matches, PREG_OFFSET_CAPTURE);
// $text = $matches[0][2666][0].$matches[0][2667][0].".".$matches[0][2668][0];
// echo is_float($text)."\n";
// print_r($matches);
// echo $text."\n";

$sql = "SELECT tasa FROM DICOM WHERE id = 1";
$result = $link->query($sql);



if ($result->num_rows > 0) {
	$OldText = mysqli_fetch_assoc($result)['tasa'];
	if ($text != $OldText && strlen($text) < 10 && strlen($text) > 8) {
		if (preg_match('/[a-zA-Z]/', $text)) {
			$chat_id = 149273661;
			file_get_contents("https://api.telegram.org/bot$token/sendMessage?chat_id=$chat_id&text=$text");
		} else {
			$sql = "UPDATE DICOM SET tasa = '$text' WHERE id = 1";
			if ($link->query($sql) === TRUE) {
				file_get_contents("https://api.telegram.org/bot$token/sendMessage?chat_id=$chat_id&text=$text");
				// file_get_contents("https://api.telegram.org/bot$token/sendMessage?chat_id=$chat_id&text=OLD: $OldText");
			} else {
				file_get_contents("https://api.telegram.org/bot$token/sendMessage?chat_id=$chat_id&text=error".$conn->error); 
			}
		}

	}
} else {
    file_get_contents("https://api.telegram.org/bot$token/sendMessage?chat_id=$chat_id&text=0 Results");
}
?>