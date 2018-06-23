<?php
require_once __DIR__ . '/../../../vendor/autoload.php';

use VK\Client\VKApiClient;


$vkConfig = require_once __DIR__.'/config.php';

$vk = new VKApiClient();
$response = $vk->photos()->getAlbums($vkConfig['service_token'], [
    'owner_id' => 493757891
]);
var_dump($response);