<?php

use VK\Client\VKApiClient;

require_once __DIR__ .'/../../../vendor/autoload.php';
require_once __DIR__.'/functions.php';

$user_access_token = '';
$album_id = 255007898;

$vk = new \app\VkClient(new VKApiClient(), $user_access_token);

$generator = generateRandomImages(__DIR__.'/img', 1);
foreach ($generator as $i => $image) {
    echo 'Uploading image #'. ($i + 1), PHP_EOL;
    $vk->uploadImage($album_id, $image);
}