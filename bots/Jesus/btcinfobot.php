#!/usr/bin/env php
<?php
require 'btcinfo.php';

$update_id = 0;
while(1) {
    $str = sendMethod("getUpdates", array("offset"=>($update_id + 1)));
    $json = json_decode($str);
    if (count($json->result) > 0) print_r($json->result);
    foreach ($json->result as $result) {
        $update_id = $result->update_id;
        if(isset($result->message)) {
            if (isset($result->message->entities)) {
                $chat_id = $result->message->chat->id;
                $username = $result->message->from->username;
                $message = $result->message->text;
                $length = $result->message->entities[0]->length;
                $command = strtolower(substr($message, 0, $length));
                $param = substr($message, $length + 1, strlen($message));
                /*
                if(!in_array($chat_id, $users)){
                    $params = array(
                        "chat_id" => $chat_id,
                        'parse_mode' => 'Markdown',
                        "text" => 'No tienes permitido usar este bot'
                    );
                    print_r(sendMethod("sendMessage", $params));
                    continue;
                }
                */
                if ($result->message->entities[0]->type == "bot_command") {
                    switch ($command) {
                        case '/start':
                            $params = array(
                                "chat_id" => 149273661,
                                'parse_mode' => 'Markdown',
                                "text" => $chat_id." ".$username
                            );
                            print_r(json_decode(sendMethod("sendMessage", $params)));
                            break;
                        case '/btcperu':
                            $params = array(
                                "chat_id" => $chat_id,
                                'parse_mode' => 'Markdown',
                                "text" => getBTCPeru()
                            );
                            print_r(json_decode(sendMethod("sendMessage", $params)));
                            break;
                        case '/btcchile':
                            $params = array(
                                "chat_id" => $chat_id,
                                'parse_mode' => 'Markdown',
                                "text" => getBTCChile()
                            );
                            print_r(json_decode(sendMethod("sendMessage", $params)));
                            break;
                        case '/btcvenezuela':
                            $params = array(
                                "chat_id" => $chat_id,
                                'parse_mode' => 'Markdown',
                                "text" => getBTCVenezuela()
                            );
                            print_r(json_decode(sendMethod("sendMessage", $params)));
                            break;
                        case '/diffpe':
                            $diff = json_decode(file_get_contents('diff.json'));
                            $num = trim(substr($message, $length, strlen($message)));
                            if (!is_numeric($num)){
                                $message = "El valor que agregó (*$num*) no es númerico";
                            } else {
                                $diff->pe = $num;
                                file_put_contents('diff.json', json_encode($diff));
                                $message = "se ha cambiado el valor de diferencia para Perú a *$num*";
                            }
                            $params = array(
                                "chat_id" => $chat_id,
                                'parse_mode' => 'Markdown',
                                "text" => $message
                            );
                            print_r(json_decode(sendMethod("sendMessage", $params)));
                            break;
                        case '/diffcl':
                            $diff = json_decode(file_get_contents('diff.json'));
                            $num = trim(substr($message, $length, strlen($message)));
                            if (!is_numeric($num)){
                                $message = "El valor que agregó (*$num*) no es númerico";
                            } else {
                                $diff->cl = $num;
                                file_put_contents('diff.json', json_encode($diff));
                                $message = "se ha cambiado el valor de diferencia para Chile a *$num*";
                            }
                            $params = array(
                                "chat_id" => $chat_id,
                                'parse_mode' => 'Markdown',
                                "text" => $message
                            );
                            print_r(json_decode(sendMethod("sendMessage", $params)));
                            break;
                        case '/diffve':
                            $diff = json_decode(file_get_contents('diff.json'));
                            $num = trim(substr($message, $length, strlen($message)));
                            if (!is_numeric($num)){
                                $message = "El valor que agregó (*$num*) no es númerico";
                            } else {
                                $diff->ve = $num;
                                file_put_contents('diff.json', json_encode($diff));
                                $message = "se ha cambiado el valor de diferencia para Venezuela a *$num*";
                            }
                            $params = array(
                                "chat_id" => $chat_id,
                                'parse_mode' => 'Markdown',
                                "text" => $message
                            );
                            print_r(json_decode(sendMethod("sendMessage", $params)));
                            break;
                        case '/notificaciones':
                            $diff = json_decode(file_get_contents('diff.json'));
                            if(in_array($chat_id, $diff->notificaciones)) {
                                $diff->notificaciones = array_diff($diff->notificaciones, [$chat_id]);
                                $message = "Ya no recibirá más notificaciones periódicas de los precios";
                            } else {
                                $diff->notificaciones[] = $chat_id;
                                $message = "A partir de ahora comenzará a recibir notificaciones periódicas de los precios";
                            }
                            file_put_contents('diff.json', json_encode($diff));
                            $params = array(
                                "chat_id" => $chat_id,
                                'parse_mode' => 'Markdown',
                                "text" => $message
                            );
                            print_r(json_decode(sendMethod("sendMessage", $params)));
                            break;
                    }
                }
            }
        }
    }
    sleep(1);
}
