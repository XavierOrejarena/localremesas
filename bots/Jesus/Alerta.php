#!/usr/bin/env php
<?php
define('BOT_TOKEN', '716396100:AAFbVh6W950S4goHt30TVUXW3cuKGdWQmKM');
define('API_URL', 'https://api.telegram.org/bot'.BOT_TOKEN.'/');

function exec_curl_request($handle) {
    $response = curl_exec($handle);
  
    if ($response === false) {
      $errno = curl_errno($handle);
      $error = curl_error($handle);
      error_log("Curl returned error $errno: $error\n");
      curl_close($handle);
      return false;
    }
  
    $http_code = intval(curl_getinfo($handle, CURLINFO_HTTP_CODE));
    curl_close($handle);
  
    if ($http_code >= 500) {
      // do not wat to DDOS server if something goes wrong
      sleep(10);
      return false;
    } else if ($http_code != 200) {
      $response = json_decode($response, true);
      error_log("Request has failed with error {$response['error_code']}: {$response['description']}\n");
      if ($http_code == 401) {
        throw new Exception('Invalid access token provided');
      }
      return false;
    } else {
      $response = json_decode($response, true);
      if (isset($response['description'])) {
        error_log("Request was successful: {$response['description']}\n");
      }
      $response = $response['result'];
    }
  
    return $response;
  }

function apiRequest($method, $parameters) {
    if (!is_string($method)) {
      error_log("Method name must be a string\n");
      return false;
    }
  
    if (!$parameters) {
      $parameters = array();
    } else if (!is_array($parameters)) {
      error_log("Parameters must be an array\n");
      return false;
    }
  
    foreach ($parameters as $key => &$val) {
      // encoding to JSON array parameters, for example reply_markup
      if (!is_numeric($val) && !is_string($val)) {
        $val = json_encode($val);
      }
    }
    $url = API_URL.$method.'?'.http_build_query($parameters);
  
    $handle = curl_init($url);
    curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($handle, CURLOPT_CONNECTTIMEOUT, 5);
    curl_setopt($handle, CURLOPT_TIMEOUT, 60);
  
    return exec_curl_request($handle);
  }

function getBTCValue() {
    $BINANCE_BTCUSDT = file_get_contents("https://www.bitmex.com/api/v1/trade/bucketed?binSize=1m&partial=true&count=100&reverse=true");
    $BINANCE_BTCUSDT = json_decode($BINANCE_BTCUSDT, true);
  
    foreach ($BINANCE_BTCUSDT as $coin) {
        if ($coin['symbol'] == 'XBTUSD') {
            return round($coin['open'],2);
            break;
        }
    }
    
    return 0;
}

$priceBitmex = getBTCValue();

$URL = file_get_contents("https://localbitcoins.com/buy-bitcoins-online/pe/peru/.json");
$DATA = json_decode($URL, true);
$i = 0;
$pricePeru = 0;

foreach ($DATA['data']['ad_list'] as $oferta) {
    if ($oferta['data']['currency'] == 'PEN') {
        $pricePeru = $pricePeru + round($oferta['data']['temp_price']/3.33);
        $i++;
        if ($i > 0) break;
    }
}

$pricePeru = round($pricePeru,2);
$dif = round($priceBitmex-$pricePeru,2);

$text = "COMPRA\nPerú:\t\t\t".$pricePeru."\nBitmex:\t".$priceBitmex."\nDif:\t\t\t\t".$dif."\n\n";

$URL = file_get_contents("https://localbitcoins.com/sell-bitcoins-online/pe/peru/.json");
$DATA = json_decode($URL, true);
$i = 0;
$pricePeru = 0;

foreach ($DATA['data']['ad_list'] as $oferta) {
    if ($oferta['data']['currency'] == 'PEN') {
        $pricePeru = $pricePeru + round($oferta['data']['temp_price']/3.33);
        $i++;
        if ($i > 0) break;
    }
}

$pricePeru = round($pricePeru,2);
$dif = round($priceBitmex-$pricePeru,2);

$text = $text."VENTA\nPerú:\t\t\t".$pricePeru."\nBitmex:\t".$priceBitmex."\nDif:\t\t\t\t".$dif."\n";

// apiRequest("sendMessage", array('chat_id' => 149273661, "text" => "<pre>".$text."</pre>", 'parse_mode' => 'HTML'));
apiRequest("sendMessage", array('chat_id' => 309646792, "text" => "<pre>".$text."</pre>", 'parse_mode' => 'HTML'));



?>