#!/usr/bin/env php
<?php
//@AudioWordBot
define('BOT_TOKEN', '814434766:AAEpIZOreTpDpIG_j2xiIW6bYqNTnwm_JOQ');
define('API_URL', 'https://api.telegram.org/bot'.BOT_TOKEN.'/');
define('WEBHOOK_URL', 'https://xavier.mer.web.ve/AudioWordBot.php');

function saveUser($user) {
  include "connect.php";
  $chat_id = $user['id'];
  $first_name = $user['first_name'];
  $last_name = $user['last_name'];
  $username = $user['username'];
  $result = mysqli_query($link, "SELECT chat_id FROM users WHERE chat_id = '$chat_id'");
  if (mysqli_num_rows($result) == 0){
      mysqli_query($link, "INSERT INTO users (chat_id, first_name, last_name, username, AudioWordBot) VALUES ('$chat_id', '$first_name', '$last_name', '$username', 1)");
  }else {
       mysqli_query($link, "UPDATE users SET AudioWordBot = AudioWordBot+1, first_name = '$first_name', last_name = '$last_name', username = '$username' WHERE chat_id = '$chat_id';");
  }
}

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
  $message_id = $message['message_id'];
  $chat_id = $message['chat']['id'];
  apiRequest("sendMessage", array('chat_id' => $chat_id, "text" => 'Procesando...'));
  if (isset($message['text'])) {
    $word = $message['text'];
    $URL = (file_get_contents("https://dictionary.cambridge.org/es/diccionario/ingles/$word"));
    preg_match_all('/data-src-mp3="/', $URL, $matches, PREG_OFFSET_CAPTURE);
    preg_match_all('/.mp3"/', $URL, $matches2, PREG_OFFSET_CAPTURE);
    $text = substr($URL, $matches[0][0][1]+14, $matches2[0][0][1]-$matches[0][0][1]-10);

    $ch = curl_init("https://dictionary.cambridge.org$text");
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_NOBODY, 0);
    curl_setopt($ch, CURLOPT_TIMEOUT, 5);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 1);
    $output = curl_exec($ch);
    $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    if ($status == 200) {
        file_put_contents(dirname(__FILE__) . "/words/". $word . ".mp3", $output);
    }
    
    function sendVoice($word, $chat_id, $uri) {
      $url   = API_URL.'sendVoice';
  
      $post_fields = array(
          "chat_id" => $chat_id,
          "voice"   => new CURLFile(realpath($uri. "/words/". $word . ".mp3")),
          "caption" => $word,
      );
      $ch = curl_init();
      curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type:multipart/form-data"));
      curl_setopt($ch, CURLOPT_URL, $url);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
      curl_setopt($ch, CURLOPT_POSTFIELDS, $post_fields);
      $result = curl_exec($ch);
      curl_close($ch);
      return $result;
  }
  $res = sendVoice($word, $chat_id, dirname(__FILE__));
  } else {
    apiRequest("sendMessage", array('chat_id' => $chat_id, "text" => 'Solo palabras.'));
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
  // saveUser($update['message']['from']);
}

?>