<?php

namespace app;


use Interop\Queue\PsrContext;
use Interop\Queue\PsrMessage;
use Interop\Queue\PsrProcessor;

class PhotoDownloadProcessor implements PsrProcessor
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
        $body = json_decode($message->getBody(), true);
        $this->downloader->download($body['url'], $body['vk_user_id'], $body['vk_album_id'], $body['vk_photo_id']);

        return self::ACK;
    }
}