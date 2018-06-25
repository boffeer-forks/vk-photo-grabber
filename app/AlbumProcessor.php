<?php

namespace app;


use Enqueue\Util\JSON;
use Interop\Queue\PsrContext;
use Interop\Queue\PsrMessage;
use Interop\Queue\PsrProcessor;
use VK\Exceptions\VKApiException;

class AlbumProcessor implements PsrProcessor
{
    /**
     * @var VkClient
     */
    private $client;
    /**
     * @var OutChannel
     */
    private $photoQueue;

    /**
     * AlbumProcessor constructor.
     * @param VkClient $client
     * @param OutChannel $photoQueue
     */
    public function __construct(VkClient $client, OutChannel $photoQueue)
    {
        $this->client     = $client;
        $this->photoQueue = $photoQueue;
    }

    /**
     * {@inheritdoc}
     */
    public function process(PsrMessage $message, PsrContext $context)
    {
        // TODO: log
        $vkAlbum = JSON::decode($message->getBody());
        try {
            $photos = $this->client->iteratePhotos($vkAlbum['owner_id'], $vkAlbum['id'], 20);
            foreach ($photos as $photo) {
                $this->photoQueue->send(JSON::encode($photo));
            }
        } catch (VKApiException $e) {
            return self::REJECT;
        }

        return self::ACK;
    }
}