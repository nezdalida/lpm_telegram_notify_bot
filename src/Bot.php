<?php

namespace src;

/**
 * Created by PhpStorm.
 * User: admin
 * Date: 13.01.2016
 * Time: 16:05
 */
class Bot
{
    /**
     * @var VerifyUser
     */
    protected $verify;

    /**
     * @var Telegram
     */
    protected $telegram;

    private $data_file;

    public function __construct($verifier, $telegram){
        $this->verify = $verifier;
        $this->telegram = $telegram;
        $this->data_file = DIR_TMP . 'telegram_notify_chat.php';
    }

    public function process($data)
    {
        if (isset($data['message']) && isset($data['message']['text'])){
            switch ($data['message']['text']){
                case '/start':
                    //выодим сообщение, что  нужно написать ключ

                    break;

                case '/stop':
                    //удаляем по chat_id привязку к пользователю
                    break;

                default:
                    if (preg_match('#^\d+$#', $data['message']['text'])){
                        //число
                        $this->connectUserByCode($data['message']['text'], $data['message']['chat']['id']);
                    } else {
                        $this->telegram->sendMessage('not int',$data['message']['chat']['id']);
                    }
            }
        }
    }

    public function connectUserByCode($code,$chat_id)
    {
        $user_id = $this->verify->checkCode($code);
        if ($user_id) {
            $data = $this->readDataFile();
            $data[$user_id] = $chat_id;
            $this->writeDataFile($data);
            $this->telegram->sendMessage('OK',$chat_id);
        } else {
            $this->telegram->sendMessage('FALSE',$chat_id);
        }
    }

    private function writeDataFile($data)
    {
        return file_put_contents($this->data_file, "<?php return " . var_export($data, true) . ";", EXTR_OVERWRITE);
    }

    public function readDataFile()
    {
        return file_exists($this->data_file) ? include $this->data_file : [];
    }
}