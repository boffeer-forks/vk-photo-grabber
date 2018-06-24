<?php

namespace app;


class VkImageDownloader
{
    /**
     * @var string
     */
    private $baseDir;

    /**
     * @param string $baseDir
     */
    public function __construct(string $baseDir)
    {
        $this->baseDir = $baseDir;
    }

    /**
     * @param array $vkPhoto
     * @return string
     * @throws \RuntimeException
     */
    public static function extractImageUrl(array $vkPhoto): string
    {
        $fields = array_reverse(['photo_75', 'photo_130', 'photo_604', 'photo_807', 'photo_1280', 'photo_2560']);
        foreach ($fields as $name) {
            if (array_key_exists($name, $vkPhoto)) {
                return $vkPhoto[$name];
            }
        }

        throw new \RuntimeException('Url not found. Possibly wrong data format.');
    }

    /**
     * @param string $imageUrl
     * @param int $vkUserId
     * @param int $vkAlbumId
     * @param int $vkPhotoId
     * @throws \RuntimeException
     */
    public function download(string $imageUrl, int $vkUserId, int $vkAlbumId, int $vkPhotoId)
    {
        $dir = $this->baseDir . sprintf('/user/%d/album/%d', $vkUserId, $vkAlbumId);
        $destination = sprintf('%s/%s.%s', $dir, $vkPhotoId, pathinfo($imageUrl, PATHINFO_EXTENSION));

        @mkdir($dir, 0777, true);

        $fp = fopen($destination, 'w+');
        $curl = curl_init($imageUrl);
        curl_setopt($curl, CURLOPT_FILE, $fp);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($curl, CURLOPT_TIMEOUT, 1000);

        $result = curl_exec($curl);
        if ($result === false) {
            throw new \RuntimeException(sprintf('Error (%d): %s', curl_errno($curl), curl_error($curl)));
        }

        curl_close($curl);
        fclose($fp);
    }
}