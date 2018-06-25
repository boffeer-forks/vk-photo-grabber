<?php
require_once __DIR__ . '/../../../vendor/autoload.php';
require_once __DIR__.'/functions.php';

use VK\Client\VKApiClient;
use app\VkClient;

$vkConfig = require_once __DIR__.'/config.php';
$vk = new VkClient(new VKApiClient(), $vkConfig['service_token']);

$vkUserId  = 493757891;
$vkAlbumId = 255007898;

$dir = __DIR__ . sprintf('/img/user/%d/album/%d', $vkUserId, $vkAlbumId);
@mkdir($dir, 0777, true);

$start = time();
$counter = 0;
foreach ($vk->iteratePhotos($vkUserId, $vkAlbumId, 10) as $photo) {
    $url = extractImageUrl($photo);
    echo 'Downloading image ', $url, PHP_EOL;
    vkDownloadImage($url, $dir . '/' . basename($url));
    ++$counter;
}
$duration = time() - $start;

echo 'Total images downloaded: ', $counter, PHP_EOL;
echo 'Total time: ', $duration, ' sec', PHP_EOL;
