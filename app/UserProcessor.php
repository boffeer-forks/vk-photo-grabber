<?php

namespace app;


use Enqueue\Util\JSON;
use Interop\Queue\PsrContext;
use Interop\Queue\PsrMessage;
use Interop\Queue\PsrProcessor;

class UserProcessor implements PsrProcessor
{
    /**
     * @var VkClient
     */
    private $client;
    /**
     * @var OutChannel
     */
    private $albumQueue;

    /**
     * @param VkClient $client
     * @param OutChannel $albumQueue
     */
    public function __construct(VkClient $client, OutChannel $albumQueue)
    {
        $this->client     = $client;
        $this->albumQueue = $albumQueue;
    }

    /**
     * {@inheritdoc}
     */
    public function process(PsrMessage $message, PsrContext $context)
    {
        $vkUserId = JSON::decode($message->getBody());
        $vkAlbums = $this->getAlbums($vkUserId);

        array_walk($vkAlbums, function ($album) {
            $this->albumQueue->send(JSON::encode($album));
        });

        return self::ACK;
    }

    /**
     * @param int $vkUserId
     * @return array
     * @throws \VK\Exceptions\VKApiException
     * @throws \VK\Exceptions\VKClientException
     */
    private function getAlbums($vkUserId)
    {
        $response = $this->client->getAlbums($vkUserId);
        $vkAlbums = $response['items'];

        return array_filter($vkAlbums, function (array $album) {
            return $album['size'] > 0;
        });
    }
}