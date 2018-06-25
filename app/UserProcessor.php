<?php

namespace app;


use Enqueue\Util\JSON;
use Interop\Queue\PsrContext;
use Interop\Queue\PsrMessage;
use Interop\Queue\PsrProcessor;
use VK\Exceptions\VKApiException;

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
     * @var Storage
     */
    private $storage;

    /**
     * @param VkClient $client
     * @param OutChannel $albumQueue
     * @param Storage $storage
     */
    public function __construct(VkClient $client, OutChannel $albumQueue, Storage $storage)
    {
        $this->client     = $client;
        $this->albumQueue = $albumQueue;
        $this->storage = $storage;
    }

    /**
     * {@inheritdoc}
     */
    public function process(PsrMessage $message, PsrContext $context)
    {
        $vkUserId = JSON::decode($message->getBody());
        try {
            $vkUser = $this->getVkUser($vkUserId);
        } catch (VKApiException $e) {
            return self::REJECT;
        }
        $this->storage->saveUser($vkUser);
        $this->enqueueAlbums($vkUserId);

        return self::ACK;
    }

    /**
     * @param int $vkUserId
     * @throws VKApiException
     * @throws \VK\Exceptions\VKClientException
     */
    private function enqueueAlbums($vkUserId)
    {
        $vkAlbums = $this->getAlbums($vkUserId);
        array_walk($vkAlbums, function ($album) {
            $this->albumQueue->send(JSON::encode($album));
        });
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

    /**
     * @param int $vkUserId
     * @return mixed
     * @throws \VK\Exceptions\VKApiException
     * @throws \VK\Exceptions\VKClientException
     */
    private function getVkUser($vkUserId)
    {
        return $this->client->getUser($vkUserId);
    }
}