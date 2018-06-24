<?php
require_once __DIR__ . '/../../vendor/autoload.php';

use app\UserProcessor;
use Enqueue\AmqpLib\AmqpConnectionFactory;
use Enqueue\Consumption\ChainExtension;
use Enqueue\Consumption\Extension\SignalExtension;
use Enqueue\Consumption\QueueConsumer;
use VK\Client\VKApiClient;
use app\VkClient;

$connectionFactory = new AmqpConnectionFactory([
    'host'  => 'localhost',
    'port'  => 5672,
    'vhost' => '/',
    'user'  => 'admin',
    'pass'  => 'qwe123',
]);

/** @var \Enqueue\AmqpLib\AmqpContext $psrContext */
$psrContext = $connectionFactory->createContext();
$queueConsumer = new QueueConsumer($psrContext, new ChainExtension([
    new SignalExtension()
]));

/**
 * Processor
 */
$vkAlbumQueue = $psrContext->createQueue('vk-album-queue');
$psrContext->declareQueue($vkAlbumQueue);
$albumQueue = new \app\OutChannel($psrContext, $vkAlbumQueue);

$vkConfig = require_once __DIR__.'/../../app/examples/vk-sdk/config.php';
$vk = new VkClient(new VKApiClient(), $vkConfig['service_token']);
$processor = new UserProcessor($vk, $albumQueue);


$queueConsumer->bind('vk-user-queue', $processor);
$queueConsumer->consume();
