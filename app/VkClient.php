<?php

namespace app;


use VK\Client\VKApiClient;

class VkClient
{
    /**
     * @var VKApiClient
     */
    private $apiClient;
    /**
     * @var string
     */
    private $accessToken;

    /**
     * @param VKApiClient $apiClient
     * @param string $accessToken
     */
    public function __construct(VKApiClient $apiClient, string $accessToken)
    {
        $this->apiClient   = $apiClient;
        $this->accessToken = $accessToken;
    }

    /**
     * @param int $albumId
     * @return string
     * @throws \VK\Exceptions\VKApiException
     * @throws \VK\Exceptions\VKClientException
     */
    public function getImageUploadServerUrl(int $albumId): string
    {
        $response = $this->apiClient
            ->photos()
            ->getUploadServer($this->accessToken, ['album_id' => $albumId]);

        return $response['upload_url'];
    }

    /**
     * @param int $albumId
     * @param string $fileName
     * @throws \VK\Exceptions\VKApiException
     * @throws \VK\Exceptions\VKClientException
     */
    public function uploadImage(int $albumId, string $fileName)
    {
        $uploader = new VkImageUploader($this->getImageUploadServerUrl($albumId));
        $data = $uploader->upload($fileName);
        $this->saveUploadedImage($data);
    }

    /**
     * @param array $uploadData
     * @return mixed
     * @throws \VK\Exceptions\VKApiException
     * @throws \VK\Exceptions\VKClientException
     */
    private function saveUploadedImage(array $uploadData)
    {
        return $this->apiClient
            ->photos()
            ->save($this->accessToken, [
                'server'      => $uploadData['server'],
                'photos_list' => stripslashes($uploadData['photos_list']),
                'album_id'    => $uploadData['aid'],
                'hash'        => $uploadData['hash']
            ]);
    }
}