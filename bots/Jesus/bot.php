#!/usr/bin/env php
<?php
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

function getS() {
  $rdata = array("COMPRA\t\tVENTA");
  $priceBTC = getBTCValue();
  $URL = file_get_contents("https://localbitcoins.com/buy-bitcoins-online/ve/venezuela/.json");
  $DATA = json_decode($URL, true);
  $text = '';
  $i = 0;
  foreach ($DATA['data']['ad_list'] as $oferta) {
    if ($oferta['data']['currency'] == 'VES' && !stripos($oferta['data']['msg'], 'bitmain') && !stripos($oferta['data']['bank_name'], 'bitmain')) {
      $rdata[] = number_format(round($oferta['data']['temp_price']/$priceBTC));
      $i++;
      if ($i > 9) break;
    }
  }
  
  $URL = file_get_contents("https://localbitcoins.com/sell-bitcoins-online/ve/venezuela/.json");
  $DATA = json_decode($URL, true);
  
  $i = 1;
  foreach ($DATA['data']['ad_list'] as $oferta) {
    if ($oferta['data']['currency'] == 'VES'  && !stripos($oferta['data']['msg'], 'bitmain') && !stripos($oferta['data']['bank_name'], 'bitmain')) {
      $rdata[$i] = $rdata[$i]."\t\t\t".number_format(round($oferta['data']['temp_price']/$priceBTC));
      $i++;
      if ($i > 10) break;
    }
  }

  foreach ($rdata as $key) {
    $text = $text.$key."\n";
  }
  return $text;

}

function getVenezuela() {
  include "connect.php";
  $tasa = mysqli_fetch_assoc(mysqli_query($link, "SELECT tasa FROM DICOM WHERE id = 2"))['tasa'];
  $text = "COMPRA\nVES\t\t\t\t\t\t\t\tUSD\t\t\t\t\t\tPEN\t\t\tDIV\n";
  $priceBTC = getBTCValue();
  $URL = file_get_contents("https://localbitcoins.com/buy-bitcoins-online/ve/venezuela/.json");
  $DATA = json_decode($URL, true);
  
  $i = 0;
  foreach ($DATA['data']['ad_list'] as $oferta) {
    if ($oferta['data']['currency'] == 'VES' && !stripos($oferta['data']['msg'], 'bitmain') && !stripos($oferta['data']['bank_name'], 'bitmain')) {
      $aux = $oferta['data']['temp_price']/$priceBTC;
      $text = $text.number_format(round($oferta['data']['temp_price']/1000000,2), 2, ',', ' ')."M\t\t\t".number_format(round($aux))."\t\t\t".number_format(round($oferta['data']['temp_price']/$priceBTC/$tasa))."\t\t\t".round(3050/$aux,3)."\n";
      $i++;
      if ($i > 9) break;
    }
  }
  
  $URL = file_get_contents("https://localbitcoins.com/sell-bitcoins-online/ve/venezuela/.json");
  $DATA = json_decode($URL, true);
  
  $i = 0;
  $text = $text."\nVENTA\nVES\t\t\t\t\t\t\tUSD\t\t\t\t\t\tPEN\t\t\tDIV\n";
  foreach ($DATA['data']['ad_list'] as $oferta) {
    if ($oferta['data']['currency'] == 'VES'  && !stripos($oferta['data']['msg'], 'bitmain') && !stripos($oferta['data']['bank_name'], 'bitmain')) {
      $aux = $oferta['data']['temp_price']/$priceBTC;
      $text = $text.number_format(round($oferta['data']['temp_price']/1000000,2), 2, ',', ' ')."M\t\t\t".number_format(round($aux))."\t\t\t".number_format(round($oferta['data']['temp_price']/$priceBTC/$tasa))."\t\t\t".round(3050/$aux,3)."\n";
      $i++;
      if ($i > 9) break;
    }
  }
  
  return $text."\nBitmex: $priceBTC";

}

function getPeru() {
  include "connect.php";
  $tasa = mysqli_fetch_assoc(mysqli_query($link, "SELECT tasa FROM DICOM WHERE id = 2"))['tasa'];
  $text = "COMPRA\nPEN\t\t\t\t\t\tUSD\n";
  $priceBTC = getBTCValue();
  $URL = file_get_contents("https://localbitcoins.com/buy-bitcoins-online/pe/peru/.json");
  $DATA = json_decode($URL, true);
  $URL = (file_get_contents("https://www.rextie.com/"));
  preg_match_all('/<span _ngcontent-svr-c6="" class="number">/', $URL, $matches, PREG_OFFSET_CAPTURE);
  // <span _ngcontent-svr-c6="" class="number">3.3915</span>
  
  $i = 0;
  foreach ($DATA['data']['ad_list'] as $oferta) {
    if ($oferta['data']['currency'] == 'PEN') {
      $text = $text.number_format(round($oferta['data']['temp_price']))."\t\t\t".round($oferta['data']['temp_price']/$tasa,2)."\n";
      $i++;
      if ($i > 9) break;
    }
  }
  
  $URL = file_get_contents("https://localbitcoins.com/sell-bitcoins-online/pe/peru/.json");
  $DATA = json_decode($URL, true);
  
  $i = 0;
  $text = $text."\nVENTA\nPEN\t\t\t\t\t\tUSD\n";
  foreach ($DATA['data']['ad_list'] as $oferta) {
    if ($oferta['data']['currency'] == 'PEN') {
      $text = $text.number_format(round($oferta['data']['temp_price']))."\t\t\t".round($oferta['data']['temp_price']/$tasa,2)."\n";
      $i++;
      if ($i > 9) break;
    }
  }

  return $text."\nBitmex: $priceBTC";

}

function getColombia() {
  $text = "COMPRA\nCOP\t\t\t\t\t\t\t\tUSD\n";
  $priceBTC = getBTCValue();
  $URL = file_get_contents("https://localbitcoins.com/buy-bitcoins-online/co/colombia/.json");
  $DATA = json_decode($URL, true);
  
  $i = 0;
  foreach ($DATA['data']['ad_list'] as $oferta) {
    if ($oferta['data']['currency'] == 'COP' && !stripos($oferta['data']['msg'], 'bitmain') && !stripos($oferta['data']['bank_name'], 'bitmain')) {
      $text = $text.number_format(round($oferta['data']['temp_price']/1000))."K\t\t\t".number_format(round($oferta['data']['temp_price']/$priceBTC))."\n";
      $i++;
      if ($i > 9) break;
    }
  }
  
  $URL = file_get_contents("https://localbitcoins.com/sell-bitcoins-online/co/colombia/.json");
  $DATA = json_decode($URL, true);
  
  $i = 0;
  $text = $text."\nVENTA\nCOP\t\t\t\t\t\t\tUSD\n";
  foreach ($DATA['data']['ad_list'] as $oferta) {
    if ($oferta['data']['currency'] == 'COP'  && !stripos($oferta['data']['msg'], 'bitmain') && !stripos($oferta['data']['bank_name'], 'bitmain')) {
      $text = $text.number_format(round($oferta['data']['temp_price']/1000))."K\t\t\t".number_format(round($oferta['data']['temp_price']/$priceBTC))."\n";
      $i++;
      if ($i > 9) break;
    }
  }
  
  return $text."\nBitmex: $priceBTC";

}


define('BOT_TOKEN', '716396100:AAFbVh6W950S4goHt30TVUXW3cuKGdWQmKM');
define('API_URL', 'https://api.telegram.org/bot'.BOT_TOKEN.'/');
define('WEBHOOK_URL', 'https://xavier.mer.web.ve/Jesus/bot.php');
echo file_get_contents(API_URL."setWebhook?url=".WEBHOOK_URL);

function apiRequestWebhook($method, $parameters) {
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

  $parameters["method"] = $method;

  header("Content-Type: application/json");
  echo json_encode($parameters);
  return true;
}

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

function apiRequestJson($method, $parameters) {
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

  $parameters["method"] = $method;

  $handle = curl_init(API_URL);
  curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($handle, CURLOPT_CONNECTTIMEOUT, 5);
  curl_setopt($handle, CURLOPT_TIMEOUT, 60);
  curl_setopt($handle, CURLOPT_POST, true);
  curl_setopt($handle, CURLOPT_POSTFIELDS, json_encode($parameters));
  curl_setopt($handle, CURLOPT_HTTPHEADER, array("Content-Type: application/json"));

  return exec_curl_request($handle);
}

function processMessage($message) {
  // process incoming message
  $message_id = $message['message_id'];
  $chat_id = $message['chat']['id'];
  if (isset($message['text'])) {
    // incoming text message
    $text = $message['text'];

    if (strpos($text, "/start") === 0) {
      apiRequestJson("sendMessage", array('chat_id' => $chat_id, "text" => 'Hello', 'reply_markup' => array(
        'keyboard' => array(array('/venezuela', '/peru', '/colombia')),
        'one_time_keyboard' => true,
        'resize_keyboard' => true)));
    } else if (strpos($text,"/venezuela") !== false) {
      apiRequestJson("sendMessage", array('chat_id' => $chat_id, "text" => "<pre>".getVenezuela()."</pre>", 'parse_mode' => 'HTML'));
    } else if (strpos($text,"/peru") !== false) {
      apiRequestJson("sendMessage", array('chat_id' => $chat_id, "text" => "<pre>".getPeru()."</pre>", 'parse_mode' => 'HTML'));
    } else if ($text == "/s") {
      apiRequestJson("sendMessage", array('chat_id' => $chat_id, "text" => "<pre>".getS()."</pre>", 'parse_mode' => 'HTML'));
    } else if (strpos($text,"/colombia") !== false) {
      apiRequestJson("sendMessage", array('chat_id' => $chat_id, "text" => "<pre>".getColombia()."</pre>", 'parse_mode' => 'HTML'));
    } else if (strpos($text,"/tasa") !== false) {
      include "connect.php";
      $tasa = (float)substr($text, 6, strlen($text)-1);
      $res = mysqli_query($link, "UPDATE DICOM SET tasa = $tasa WHERE id = 2");
      if (!$res) $res = mysqli_error();  
      apiRequestJson("sendMessage", array('chat_id' => $chat_id, "text" => $res, 'parse_mode' => 'HTML'));
    }
}
}

if (php_sapi_name() == 'cli') {
  // if run from console, set or delete webhook
  apiRequest('setWebhook', array('url' => isset($argv[1]) && $argv[1] == 'delete' ? '' : WEBHOOK_URL));
  exit;
}


$content = file_get_contents("php://input");
$update = json_decode($content, true);

if (!$update) {
  // receive wrong update, must not happen
  exit;
}

if (isset($update["message"])) {
  processMessage($update["message"]);
}