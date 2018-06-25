<?php
require_once __DIR__ . '/../../../vendor/autoload.php';

use VK\Client\VKApiClient;

$vkConfig = require_once __DIR__.'/config.php';
$access_token = '';
define('PHOTOS_ORDER_ASC', 0);

$vk = new VKApiClient();
$response = $vk->photos()->get($vkConfig['service_token'], [
    'rev' => PHOTOS_ORDER_ASC,
//    'album_id' => 'profile',
    'owner_id' => 493757891,
    'album_id' => 255007898,
]);
var_dump($response);