<?php
$token = '156771533:AAFtGPT_o3MFuPRBnuYwOZGfNHWt_FivTy4';
$site = 'https://api.telegram.org/bot'.$token;

$update = file_get_contents("php://input");

print_r($update);


?>