<?php
require_once __DIR__ . '/../../../vendor/autoload.php';

use VK\Client\VKApiClient;


$access_token = '';
define('PHOTOS_ORDER_ASC', 0);

$vk = new VKApiClient();
$response = $vk->photos()->get($access_token, [
    'rev' => PHOTOS_ORDER_ASC,
    'album_id' => 'profile'
]);
var_dump($response);