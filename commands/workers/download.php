<?php
require_once __DIR__ . '/../../vendor/autoload.php';

use app\PhotoDownloadProcessor;
use app\VkImageDownloader;
use Enqueue\AmqpLib\AmqpConnectionFactory;
use Enqueue\Consumption\ChainExtension;
use Enqueue\Consumption\Extension\SignalExtension;
use Enqueue\Consumption\QueueConsumer;

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
$queueConsumer->bind('vk-photo-queue', new PhotoDownloadProcessor(new VkImageDownloader(__DIR__.'/img_worker')));
$queueConsumer->consume();
