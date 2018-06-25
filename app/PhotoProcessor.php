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
     * @param VkImageDownloader $downloader
     */
    public function __construct(VkImageDownloader $downloader)
    {
        $this->downloader = $downloader;
    }

    /**
     * {@inheritdoc}
     */
    public function process(PsrMessage $message, PsrContext $context)
    {
        // TODO: log
        $vkPhoto = json_decode($message->getBody(), true);
        $this->downloader->download(VkImageDownloader::extractImageUrl($vkPhoto), $vkPhoto['owner_id'], $vkPhoto['album_id'], $vkPhoto['id']);

        return self::ACK;
    }
}