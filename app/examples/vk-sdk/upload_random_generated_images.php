<?php

use VK\Client\VKApiClient;

require_once __DIR__ .'/../../../vendor/autoload.php';
require_once __DIR__.'/functions.php';

$user_access_token = '';
$album_id = 255007898;

$vk = new VKApiClient();
$saveImage = function ($uploadData) use ($vk, $user_access_token) {
    return $vk->photos()->save($user_access_token, [
        'server'      => $uploadData['server'],
        'photos_list' => stripslashes($uploadData['photos_list']),
        'album_id'    => $uploadData['aid'],
        'hash'        => $uploadData['hash']
    ]);
};

$response = $vk->photos()->getUploadServer($user_access_token, ['album_id' => $album_id]);
echo 'Upload URL: ', $response['upload_url'], PHP_EOL;

$generator = generateRandomImages(__DIR__.'/img', 1);
foreach ($generator as $i => $image) {
    echo 'Uploading image #'. ($i + 1), PHP_EOL;
    $uploadData = vkUploadImage($image, $response['upload_url']);
    $saveImage($uploadData);
}