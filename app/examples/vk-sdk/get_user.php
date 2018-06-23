<?php
require_once __DIR__ . '/../../../vendor/autoload.php';

use VK\Client\VKApiClient;


$vkConfig = require_once __DIR__.'/config.php';

$vk = new VKApiClient();
$response = $vk->users()->get($vkConfig['service_token'], [
    'user_ids' => [493757891],
    'fields' => ['city', 'photo'],
]);
var_dump($response);