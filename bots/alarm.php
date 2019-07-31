#!/usr/bin/env php
<?php
	$api = "411509742:AAF0EfG_R-0mp3nL_KRTanuwDF79MiY2FRs";
	function sendMessage($chat_id, $text)
	{
	    global $api;
	    $get = file_get_contents("https://api.telegram.org/bot$api/sendMessage?chat_id=$chat_id&text=".urlencode($text));
	}

	include "connect.php";
	
	// while (true) {
		$result = mysqli_query($link, "SELECT * FROM alarms_binance");
		while($row = mysqli_fetch_array($result)){
			$chat_id = $row['chat_id'];
			$coin = $row['coin'];
			$seted_price = $row['seted_price'];
			$type = $row['type'];
			$row_num = $row['row_num'];
			$price = json_decode(file_get_contents("https://api.binance.com/api/v1/ticker/price?symbol=$coin"), true)['price'];
			$seted_price = floatval($seted_price);
			$price = floatval($price);
			if ($price >= $seted_price and $type == "high") {
				sendMessage($chat_id, $coin." just reached the price of ".$seted_price);
				mysqli_query($link, "DELETE FROM alarms_binance WHERE row_num ='$row_num'");
			}
			elseif ($price <= $seted_price and $type == "low") {
				sendMessage($chat_id, "/".$coin." just reached the price of ".$seted_price);
				mysqli_query($link, "DELETE FROM alarms_binance WHERE row_num ='$row_num'");
			}

	 	}
	 	//sleep(60);
	// }
?>
