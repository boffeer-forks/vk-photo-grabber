<?php
require_once __DIR__ . '/../../../vendor/autoload.php';

use VK\Client\VKApiClient;

$user_access_token = '';

$vk = new VKApiClient();
$response = $vk->photos()->getUploadServer($user_access_token, [
    'album_id' => 255007898
]);
var_dump($response['upload_url']);

$post = ['file1' => new \CURLFile(__DIR__.'/3TgFFph.jpg')];
$curl = curl_init();
curl_setopt($curl, CURLOPT_URL, $response['upload_url']);
curl_setopt($curl, CURLOPT_POST, 1);
curl_setopt($curl, CURLOPT_POSTFIELDS, $post);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
$taskData = curl_exec($curl);
$taskInfo = curl_getinfo($curl);

if ($taskData === false) {
    die(sprintf('Error (%d): %s', curl_errno($curl), curl_error($curl)));
}
$uploadData = json_decode($taskData, true);

$saveResponse = $vk->photos()->save($user_access_token, [
    'server'      => $uploadData['server'],
    'photos_list' => stripslashes($uploadData['photos_list']),
    'album_id'    => $uploadData['aid'],
    'hash'        => $uploadData['hash']
]);

var_dump($saveResponse);