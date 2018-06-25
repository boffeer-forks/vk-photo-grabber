<?php
require_once __DIR__ . '/../../../vendor/autoload.php';

use VK\Client\VKApiClient;
use app\VkClient;

$vkConfig = require_once __DIR__.'/config.php';
$vk = new VkClient(new VKApiClient(), $vkConfig['service_token']);

$vkUserId  = 493757891;
$vkAlbumId = 255007898;
foreach ($vk->iteratePhotos($vkUserId, $vkAlbumId, 10) as $photo) {
    var_dump($photo);
}
