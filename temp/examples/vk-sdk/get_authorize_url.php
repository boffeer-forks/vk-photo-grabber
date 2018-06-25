<?php
require_once __DIR__ . '/../../../vendor/autoload.php';

use VK\Client\VKApiClient;
use VK\OAuth\Scopes\VKOAuthUserScope;
use VK\OAuth\VKOAuth;
use VK\OAuth\VKOAuthDisplay;
use VK\OAuth\VKOAuthResponseType;


$vkConfig = require_once __DIR__.'/config.php';

$vk = new VKApiClient('5.73');
$oauth = new VKOAuth();
$client_id = $vkConfig['app_id'];
$redirect_uri = 'https://oauth.vk.com/blank.html';
$display = VKOAuthDisplay::PAGE;
$scope = [VKOAuthUserScope::PHOTOS];
$state = 'secret_state_code';

$browser_url = $oauth->getAuthorizeUrl(VKOAuthResponseType::TOKEN, $client_id, $redirect_uri, $display, $scope, $state);

echo $browser_url, PHP_EOL;