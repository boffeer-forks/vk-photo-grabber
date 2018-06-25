<?php

namespace app;


use Interop\Queue\PsrContext;
use Interop\Queue\PsrMessage;
use Interop\Queue\PsrProcessor;

class PhotoProcessor implements PsrProcessor
{
    /**
     * @var VkImageDownloader
     */
    private $downloader;
    /**
     * @var Storage
     */
    private $storage;

    /**
     * @param VkImageDownloader $downloader
     * @param Storage $storage
     */
    public function __construct(VkImageDownloader $downloader, Storage $storage)
    {
        $this->downloader = $downloader;
        $this->storage = $storage;
    }

    /**
     * {@inheritdoc}
     */
    public function process(PsrMessage $message, PsrContext $context)
    {
        // TODO: log
        $vkPhoto = json_decode($message->getBody(), true);
        if ($this->storage->photoExists($vkPhoto['id'])) {
            return self::REJECT;
        }

        $this->downloader->download(VkImageDownloader::extractImageUrl($vkPhoto), $vkPhoto['owner_id'], $vkPhoto['album_id'], $vkPhoto['id']);
        $this->storage->savePhoto($vkPhoto);

        return self::ACK;
    }
}