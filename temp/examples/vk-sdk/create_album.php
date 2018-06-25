<?php
require_once __DIR__ . '/../../../vendor/autoload.php';

use VK\Client\VKApiClient;

$access_token = '';

$vk = new VKApiClient();
$response = $vk->photos()->createAlbum($access_token, [
    'title' => 'Test album #1'
]);
var_dump($response);