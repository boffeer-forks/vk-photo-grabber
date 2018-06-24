<?php
require_once __DIR__ . '/../../vendor/autoload.php';

use app\UserProcessor;
use Enqueue\AmqpLib\AmqpConnectionFactory;
use Enqueue\Consumption\ChainExtension;
use Enqueue\Consumption\Extension\SignalExtension;
use Enqueue\Consumption\QueueConsumer;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

$containerBuilder = new ContainerBuilder();
$loader = new YamlFileLoader($containerBuilder, new FileLocator(__DIR__.'/../../config'));
$loader->load('services.yaml');

/** @var AmqpConnectionFactory $connectionFactory */
$connectionFactory = $containerBuilder->get('amqp_connection_factory');
/** @var UserProcessor $processor */
$processor = $containerBuilder->get('user_processor');


/** @var \Enqueue\AmqpLib\AmqpContext $psrContext */
$psrContext = $connectionFactory->createContext();
$queueConsumer = new QueueConsumer($psrContext, new ChainExtension([
    new SignalExtension()
]));
$queueConsumer->bind($containerBuilder->getParameter('queue.user'), $processor);
$queueConsumer->consume();
