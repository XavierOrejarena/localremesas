#!/usr/bin/env php
<?php
//@the_binance_bot
define('BOT_TOKEN', '411509742:AAF0EfG_R-0mp3nL_KRTanuwDF79MiY2FRs');
define('API_URL', 'https://api.telegram.org/bot'.BOT_TOKEN.'/');
define('WEBHOOK_URL', 'https://xavier.mer.web.ve/the_binance_bot.php');

function gen_uuid() {
    return sprintf( '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
        mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ),
        mt_rand( 0, 0xffff ),
        mt_rand( 0, 0x0fff ) | 0x4000,
        mt_rand( 0, 0x3fff ) | 0x8000,
        mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff )
    );
}

function sendMessage($chat_id, $text) {
    apiRequest('sendMessage', ['chat_id' => $chat_id, 'text' => $text]);
}

function saveUser($user) {
    include "connect.php";
    $chat_id = $user['id'];
    $first_name = $user['first_name'];
    $last_name = $user['last_name'];
    $username = $user['username'];
    $result = mysqli_query($link, "SELECT chat_id FROM users WHERE chat_id = '$chat_id'");
    if (mysqli_num_rows($result) == 0){
        mysqli_query($link, "INSERT INTO users (chat_id, first_name, last_name, username, the_binance_bot) VALUES ('$chat_id', '$first_name', '$last_name', '$username', 1)");
    }else {
         mysqli_query($link, "UPDATE users SET the_binance_bot = the_binance_bot+1, first_name = '$first_name', last_name = '$last_name', username = '$username' WHERE chat_id = '$chat_id';");
    }
}

function apiRequestWebhook($method, $parameters)
{
    if (!is_string($method)) {
        error_log("El nombre del método debe ser una cadena de texto\n");
        return false;
    }
    if (!$parameters) {
        $parameters = [];
    } elseif (!is_array($parameters)) {
        error_log("Los parámetros deben ser un arreglo/matriz\n");
        return false;
    }
    $parameters['method'] = $method;
    header('Content-Type: application/json');
    echo json_encode($parameters);
    return true;
}

function exec_curl_request($handle)
{
    $response = curl_exec($handle);
    if ($response === false) {
        $errno = curl_errno($handle);
        $error = curl_error($handle);
        error_log("Curl retornó un error $errno: $error\n");
        curl_close($handle);
        return false;
    }
    $http_code = intval(curl_getinfo($handle, CURLINFO_HTTP_CODE));
    curl_close($handle);
    if ($http_code >= 500) {
        // do not wat to DDOS server if something goes wrong
    sleep(10);
        return false;
    } elseif ($http_code != 200) {
        $response = json_decode($response, true);
        error_log("La solicitud fallo con el error {$response['error_code']}: {$response['description']}\n");
        if ($http_code == 401) {
            throw new Exception('El token provisto es inválido');
        }
        return false;
    } else {
        $response = json_decode($response, true);
        if (isset($response['description'])) {
            error_log("La solicitud fue exitosa: {$response['description']}\n");
        }
        $response = $response['result'];
    }
    return $response;
}

function apiRequest($method, $parameters)
{
    if (!is_string($method)) {
        error_log("El nombre del método debe ser una cadena de texto\n");
        return false;
    }
    if (!$parameters) {
        $parameters = [];
    } elseif (!is_array($parameters)) {
        error_log("Los parámetros deben ser un arreglo/matriz\n");
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
    curl_setopt($handle, CURLOPT_CONNECTTIMEOUT, 0);
    curl_setopt($handle, CURLOPT_TIMEOUT, 0);
    return exec_curl_request($handle);
}

function apiRequestJson($method, $parameters)
{
    if (!is_string($method)) {
        error_log("El nombre del método debe ser una cadena de texto\n");
        return false;
    }
    if (!$parameters) {
        $parameters = [];
    } elseif (!is_array($parameters)) {
        error_log("Los parámetros deben ser un arreglo/matriz\n");
        return false;
    }
    $parameters['method'] = $method;
    $handle = curl_init(API_URL);
    curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($handle, CURLOPT_CONNECTTIMEOUT, 5);
    curl_setopt($handle, CURLOPT_TIMEOUT, 60);
    curl_setopt($handle, CURLOPT_POSTFIELDS, json_encode($parameters));
    curl_setopt($handle, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    return exec_curl_request($handle);
}

function processQuery($inline_query)
{
    $results = [];
    $ThePrice = json_decode(file_get_contents("https://api.binance.com/api/v1/ticker/price?symbol=BTCUSDT"), true)['price'];
    $TheSymbol = json_decode(file_get_contents("https://api.binance.com/api/v1/ticker/price?symbol=BTCUSDT"), true)['symbol'];
    if (empty($inline_query['query'])) {
        $results[] = [
            'type'         => 'article',
            'id'           => gen_uuid(),
            'title'        => $TheSymbol,
            'message_text' => "/$TheSymbol ".round($ThePrice),
            'description'  => $ThePrice,
        ];
        // $ThePrice = json_decode(file_get_contents("https://api.binance.com/api/v1/ticker/price?symbol=ETHUSDT"), true)['price'];
        // $TheSymbol = json_decode(file_get_contents("https://api.binance.com/api/v1/ticker/price?symbol=ETHUSDT"), true)['symbol'];
        // $results[] =[
        //     'type'         => 'article',
        //     'id'           => gen_uuid(),
        //     'title'        => $TheSymbol,
        //     'message_text' => "$TheSymbol ".$ThePrice,
        //     'description'  => $ThePrice,
        // ];
        // $ThePrice = json_decode(file_get_contents("https://api.binance.com/api/v1/ticker/price?symbol=LTCUSDT"), true)['price'];
        // $TheSymbol = json_decode(file_get_contents("https://api.binance.com/api/v1/ticker/price?symbol=LTCUSDT"), true)['symbol'];
        // $results[] =[
        //     'type'         => 'article',
        //     'id'           => gen_uuid(),
        //     'title'        => $TheSymbol,
        //     'message_text' => "$TheSymbol ".$ThePrice,
        //     'description'  => $ThePrice,
        // ];
        // $ThePrice = json_decode(file_get_contents("https://api.binance.com/api/v1/ticker/price?symbol=BCCUSDT"), true)['price'];
        // $TheSymbol = json_decode(file_get_contents("https://api.binance.com/api/v1/ticker/price?symbol=BCCUSDT"), true)['symbol'];
        // $results[] =[
        //     'type'         => 'article',
        //     'id'           => gen_uuid(),
        //     'title'        => $TheSymbol,
        //     'message_text' => "$TheSymbol ".$ThePrice,
        //     'description'  => $ThePrice,
        // ];
    }
    else{
        $k = 0;
        $data = json_decode(file_get_contents("https://api.binance.com/api/v3/ticker/price", true));
        for ($i = 0; $i < sizeof($data); $i++) { 
            $symbol = $data[$i]->symbol;
            $price = $data[$i]->price;
            if (stripos($symbol, $inline_query['query']) !== false) {
                $k++;
                $results[] = [
                'type'         => 'article',
                'id'           => gen_uuid(),
                'title'        => $symbol,
                'message_text' => "/".$symbol." ".$price,
                'description'  => $price,
            ];
            }
            if ($k == 50) {
                break;
            }
        }
    }
    apiRequest('answerInlineQuery', array('inline_query_id' => $inline_query['id'], 'results' => $results, 'cache_time' => 0));
}

function processMessage($message) {
    $message_id = $message['message_id'];
    $chat_id = $message['chat']['id'];
    $text = $message['text'];

    if(strtolower($text) == "/start") {
    sendMessage($chat_id, "Hello ".$message['from']['first_name'].", to use the bot just type the token you want to know the price, for example: /BTCUSDT

If you want to know all token listed in Binance.com just type /coins");

    }
    elseif (strtolower($text) == "/help") {
        sendMessage($chat_id, "You are retarded, please don't use this Bot");
    }
    elseif (strtolower($text) == "/coins") {
        $Binance = json_decode(file_get_contents("https://api.binance.com//api/v1/exchangeInfo"), true);
        $coins = "";
        // for ($i= 0; $i < sizeof($Binance['symbols']) ; $i++) { 
        for ($i= 0; $i < 50 ; $i++) { 
            $coin = $Binance['symbols'][$i]['symbol'];
            $coins = $coins."
/".$coin;
        }
        sendMessage($chat_id, $coins);
    }
    elseif (strtolower(substr($text, 0, 6)) == '/alarm') {
        $text = str_word_count($text, 1, "0123456789.");
        if (sizeof($text) == 3 ) {
            $coin = strtoupper($text[1]);
            $seted_price = floatval($text[2]);
            $price = floatval(json_decode(file_get_contents("https://api.binance.com/api/v1/ticker/price?symbol=$coin"), true)['price']);
            sendMessage($chat_id, "You will receive a notification when /$coin reaches $seted_price");
            if ($seted_price > $price) {
                $type = "high";
            }
            else{
                $type = "low";
            }
            include "connect.php";
            mysqli_query($link, "INSERT INTO alarms_binance (chat_id, coin, seted_price, type) VALUES ('$chat_id', '$coin', '$seted_price', '$type')");
        }
        else{
            sendMessage($chat_id, "Error. Follow the example: /alarm BTCUSDT 9150"); 
        }

    }
    elseif (strtolower($text) == "/myalarms") {
        include "connect.php";
        $result = mysqli_query($link, "SELECT * FROM alarms_binance WHERE chat_id = $chat_id ORDER BY seted_price ASC");
        if (mysqli_fetch_array($result)) {
            $array = [];
            foreach ($result as $key => $value) {
                $array[] =  [['text' => $value['coin'], 'callback_data' => $value['row_num']],
                            ['text' => $value['seted_price'], 'callback_data' => $value['row_num']],
                            ['text' => "\xE2\x9D\x8C", 'callback_data' => $value['row_num']]];
            }
            apiRequestJson('sendMessage', ['chat_id' => $chat_id, 'text' => 'Select which you want to delete:', 'reply_markup' => [
            'inline_keyboard' => $array]]);
        }
        else{
            sendMessage($chat_id, 'There are no alarms.');
        }
    } else if (stripos($text, "/BTCUSDT") !== false) {
        $price = json_decode(file_get_contents("https://api.binance.com/api/v1/ticker/price?symbol=BTCUSDT"), true)['price'];
        $text = "/BTCUSDT ".$price;
        if ($price > 0) {
            sendMessage($chat_id, $text);
        }

    } else if (strlen($text) > 6){
    		$coin = strtoupper($text);
	        $coin = ltrim($coin, '/');
	        $price = json_decode(file_get_contents("https://api.binance.com/api/v1/ticker/price?symbol=$coin"), true)['price'];
            $text = "/".$coin." ".$price;
            if ($price > 0) {
                sendMessage($chat_id, $text);
            }
    }
}

if (php_sapi_name() == 'cli') {
    // if run from console, set or delete webhook
  apiRequest('setWebhook', ['url' => isset($argv[1]) && $argv[1] == 'delete' ? '' : WEBHOOK_URL]);
    exit;
}
$content = file_get_contents('php://input');
$update = json_decode($content, true);
if (!$update) {
    // receive wrong update, must not happen
  exit;
}
if (isset($update['message'])) {
    if (stripos($update['message']['text'], "/BTCUSDT ") === false) {
        // $token = '411509742:AAF0EfG_R-0mp3nL_KRTanuwDF79MiY2FRs';
        // $chat_id = 149273661;
        // $text = $update['message']['text'];
        // file_get_contents("https://api.telegram.org/bot$token/sendMessage?chat_id=$chat_id&text=$text");
        processMessage($update['message']);
        saveUser($update['message']['from']);
    }
}
if (isset($update['inline_query'])) {
    processQuery($update['inline_query']);
    saveUser($update['inline_query']['from']);
}
if (isset($update['callback_query'])) {
    $chat_id = $update['callback_query']['from']['id'];
    $row_num = $update['callback_query']['data'];
    include "connect.php";
    mysqli_query($link, "DELETE FROM alarms_binance WHERE row_num = $row_num");
    $result = mysqli_query($link, "SELECT * FROM alarms_binance WHERE chat_id = $chat_id ORDER BY seted_price ASC");
    if (mysqli_fetch_array($result)) {
        $array = [];
        foreach ($result as $key => $value) {
            $array[] =  [['text' => $value['coin'], 'callback_data' => $value['row_num']],
                        ['text' => $value['seted_price'], 'callback_data' => $value['row_num']],
                        ['text' => "\xE2\x9D\x8C", 'callback_data' => $value['row_num']]];
        }
        apiRequestJson('sendMessage', ['chat_id' => $chat_id, 'text' => 'Alarm deleted successfully:', 'reply_markup' => [
        'inline_keyboard' => $array]]);
        }
    else{
        sendMessage($chat_id, 'There are no alarms.');
    }
}

?>