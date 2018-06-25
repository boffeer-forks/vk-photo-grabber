<?php
require_once __DIR__ . '/../../../vendor/autoload.php';

use VK\Client\VKApiClient;
use app\VkClient;

$vkConfig = require_once __DIR__.'/config.php';
$vk = new VkClient(new VKApiClient(), $vkConfig['service_token']);

$vkUserId  = 493757891;
$vkAlbumId = 255007898;
$response = $vk->getPhotos($vkUserId, $vkAlbumId);
var_dump($response);