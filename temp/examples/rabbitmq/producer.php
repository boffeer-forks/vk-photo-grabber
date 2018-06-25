<?php
require_once __DIR__ . '/../../../vendor/autoload.php';

use Enqueue\AmqpLib\AmqpConnectionFactory;

//define('AMQP_DEBUG', true);
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
//$destination = $psrContext->createTopic('foo');

$message = $psrContext->createMessage('Hello world!');

$psrContext->createProducer()->send($destination, $message);