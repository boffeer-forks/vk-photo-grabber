<?php

namespace app;


class VkImageUploader
{
    /**
     * @var string
     */
    private $uploadUrl;

    /**
     * @param string $uploadUrl
     */
    public function __construct(string $uploadUrl)
    {
        $this->uploadUrl = $uploadUrl;
    }

    /**
     * @param string $fileName
     * @return array
     */
    public function upload(string $fileName): array
    {
        $post = ['file1' => new \CURLFile($fileName)];
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $this->uploadUrl);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $post);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $taskData = curl_exec($curl);

        if ($taskData === false) {
            throw new \RuntimeException(sprintf('Error (%d): %s', curl_errno($curl), curl_error($curl)));
        }

        return json_decode($taskData, true);
    }
}