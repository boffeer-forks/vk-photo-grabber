<?php

namespace app;


use Enqueue\Util\JSON;
use Interop\Queue\PsrContext;
use Interop\Queue\PsrMessage;
use Interop\Queue\PsrProcessor;

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
        $vkAlbum = JSON::decode($message->getBody());
        $photos = $this->client->iteratePhotos($vkAlbum['owner_id'], $vkAlbum['id'], 20);
        foreach ($photos as $photo) {
            $this->photoQueue->send(JSON::encode($photo));
        }

        return self::ACK;
    }
}