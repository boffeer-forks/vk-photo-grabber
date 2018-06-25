#!/usr/bin/env php
<?php
require __DIR__ . '/../vendor/autoload.php';

use Symfony\Component\Config\FileLocator;
use Symfony\Component\Console\Application;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

$containerBuilder = new ContainerBuilder();
$loader = new YamlFileLoader($containerBuilder, new FileLocator(__DIR__ . '/../config'));
$loader->load('services.yaml');


$application = new Application();
$application->add($containerBuilder->get('command_start_user_worker'));
$application->add($containerBuilder->get('command_start_album_worker'));
$application->add($containerBuilder->get('command_start_photo_worker'));
$application->add($containerBuilder->get('command_user_list'));
$application->add($containerBuilder->get('command_user_album_list'));
$application->add($containerBuilder->get('command_album_photo_list'));
$application->add($containerBuilder->get('command_enqueue_user'));

$application->run();