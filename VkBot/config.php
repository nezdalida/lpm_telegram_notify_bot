<?php

if (!defined('CONFIG_LOADED')) {
    defined('DS') or define('DS', DIRECTORY_SEPARATOR);
    defined('DIR_ROOT') or define('DIR_ROOT', dirname(__FILE__) . DS);
    defined('DIR_TMP') or define('DIR_TMP', DIR_ROOT . 'tmp' . DS);
    defined('DIR_TESTS') or define('DIR_TESTS', DIR_ROOT . 'tests' . DS);
    defined('DIR_CLASSES') or define('DIR_CLASSES', DIR_ROOT . 'classes' . DS);
    defined('DIR_VENDOR') or define('DIR_VENDOR', DIR_ROOT . 'vendor' . DS);
    defined('DIR_IMG') or define('DIR_IMG', DIR_ROOT . 'imgFolder' . DS);

//    define('BASE_URL', 'http://localhost/VKapi/');
    define('BASE_URL', 'http://wp.12qw.ru/telegram/VkBot');
    define('API_KEY', '156771533:AAFtGPT_o3MFuPRBnuYwOZGfNHWt_FivTy4');
    define('WEBHOOK_URL', 'https://wp.12qw.ru/telegram/index.php');

    $user_id = 89370;
    $bot_id = 16309784;

    define('CONFIG_LOADED', true);
}