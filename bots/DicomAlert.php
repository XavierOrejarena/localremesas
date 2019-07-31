#!/usr/bin/env php
<?php
// @DicomAlert
// /usr/local/bin/php /home/xavier/public_html/DicomAlert.php
include "connect.php";

$data = file_get_contents("http://www.bcv.org.ve");
preg_match_all('/USD/', $data, $matches, PREG_OFFSET_CAPTURE);
$text = substr($data, $matches[0][0][1]+85, 9);
$text = (string)$text;
$token = '716396100:AAFbVh6W950S4goHt30TVUXW3cuKGdWQmKM';
$chat_id = '@dicomalert';
// $chat_id = 149273661;

// $link = mysqli_connect('localhost', 'xavierorejarena', 'NX)[XDCM5~=f', 'bitnehuc_Telegram');

$sql = "SELECT tasa FROM DICOM WHERE id = 1";
$result = $link->query($sql);


// echo strlen($text); 

if ($result->num_rows > 0) {
    $OldText = mysqli_fetch_assoc($result)['tasa'];
	if ($text != $OldText && strlen($text) > 7) {

		$sql = "UPDATE DICOM SET tasa = '$text' WHERE id = 1";

		if ($link->query($sql) === TRUE) {
			file_get_contents("https://api.telegram.org/bot$token/sendMessage?chat_id=$chat_id&text=$text");
			// file_get_contents("https://api.telegram.org/bot$token/sendMessage?chat_id=$chat_id&text=old: $OldText");
		} else {
			file_get_contents("https://api.telegram.org/bot$token/sendMessage?chat_id=$chat_id&text=error".$conn->error); 
		}
	}
} else {
    file_get_contents("https://api.telegram.org/bot$token/sendMessage?chat_id=$chat_id&text=0 Results");
}
?>