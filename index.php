<?php

require_once 'config.php';

$content = file_get_contents("php://input");
$update = json_decode($content, true);

//if (!$update) {
//     //receive wrong update, must not happen
//   exit;
//}




//if (isset($update["message"])) {
//    $message = print_r($update, true);
//    file_put_contents('log.txt', $message);
//}

//$send = new src\Telegram(API_KEY, WEBHOOK_URL);

if (isset($update["message"])){
    //$send->sendMessage(158922852, 'i can send message');
    sendMessage(158922852, 'i can send message');
    $message = print_r($update, true);
    file_put_contents('log.txt', $message);
}


$bot = new \src\Bot(new \src\VerifyUser(), new \src\Telegram(API_KEY, WEBHOOK_URL));
$bot->process($update);




//if (isset($update["message"])) {
    //$message = print_r($update,true);
   // file_put_contents('log.txt', $message);
    //$telegram = new Telegram('156771533:AAFtGPT_o3MFuPRBnuYwOZGfNHWt_FivTy4', 'https://wp.12qw.ru/telegram/index.php');
   // $telegram->processMessage($update["message"]);


//    $telegram = new Telegram('156771533:AAFtGPT_o3MFuPRBnuYwOZGfNHWt_FivTy4', 'https://wp.12qw.ru/telegram/index.php');
//    $verify = new VerifyUser();
//    if($verify->checkCode($update["text"] == true)){
//        $telegram->sendMessage('OK', $update["chat"]["id"]);
//    }else{
//        $telegram->sendMessage('FALSE', $update["chat"]["id"]);
//    }


//}



function apiRequest($method, $parameters) {
    $api_url = 'https://api.telegram.org/bot'.API_KEY.'/';
    if (!is_string($method)) {
        $this->logError("Method name must be a string\n");
        return false;
    }

    if (!$parameters) {
        $parameters = array();
    } else if (!is_array($parameters)) {
        $this->logError("Parameters must be an array\n");
        return false;
    }

    foreach ($parameters as $key => &$val) {
        // encoding to JSON array parameters, for example reply_markup
        if (!is_numeric($val) && !is_string($val)) {
            $val = json_encode($val);
        }
    }
    $url = $api_url.$method.'?'.http_build_query($parameters);

    $handle = curl_init($url);
    curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($handle, CURLOPT_CONNECTTIMEOUT, 5);
    curl_setopt($handle, CURLOPT_TIMEOUT, 60);
    curl_setopt($handle, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($handle, CURLOPT_SSL_VERIFYPEER, 0);

    return execCurlRequest($handle);
}

function execCurlRequest($handle) {
    $response = curl_exec($handle);

    if (false === $response) {
        $errno = curl_errno($handle);
        $error = curl_error($handle);
        $this->logError("Curl returned error $errno: $error\n");
        curl_close($handle);
        return false;
    }

    $http_code = intval(curl_getinfo($handle, CURLINFO_HTTP_CODE));
    curl_close($handle);

    if (200 == $http_code){
        $response = json_decode($response, true);
        if (isset($response['description'])) {
            logError("Request was successfull: {$response['description']}\n");
        }
        $response = $response['result'];
    } else if ($http_code >= 500) {
        // do not wat to DDOS server if something goes wrong
        sleep(10);
        return false;
    } else {
        $response = json_decode($response, true);
        $this->logError("Request has failed. Http code = {$http_code}. Error {$response['error_code']}: {$response['description']}\n");
        if ($http_code == 401) {
            throw new \Exception('Invalid access token provided');
        }
        return false;
    }

    return $response;
}

function sendMessage($chat_id, $message)
{
    apiRequest("sendMessage", array('chat_id' => $chat_id, 'text' => $message));
}

function logError($mes)
{
    file_put_contents(DIR_TMP.'telegram.errors.log', "----\n".date('Y-m-d H:i:s')."\n".$mes."\n", FILE_APPEND);
}

