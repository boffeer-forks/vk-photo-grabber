<?php
require_once __DIR__ . '/../../../vendor/autoload.php';

use Enqueue\AmqpLib\AmqpConnectionFactory;

$connectionFactory = new AmqpConnectionFactory([
    'host'  => 'localhost',
    'port'  => 5672,
    'vhost' => '/',
    'user'  => 'admin',
    'pass'  => 'qwe123',
//    'persisted' => false,
]);

/** @var \Enqueue\AmqpLib\AmqpContext $psrContext */
$psrContext = $connectionFactory->createContext();

$destination = $psrContext->createQueue('foo');
$psrContext->declareQueue($destination);
//$destination = $context->createTopic('foo');

$consumer = $psrContext->createConsumer($destination);

$message = $consumer->receive();

var_dump($message);
// process a message

$consumer->acknowledge($message);
// $consumer->reject($message);