<?php

namespace app;


class Storage
{
    /**
     * @var Db
     */
    private $db;

    /**
     * Storage constructor.
     * @param Db $db
     */
    public function __construct(Db $db)
    {
        $this->db = $db;
    }

    public function saveUser(array $vkUser)
    {
        $user = $this->db->queryOne('
            SELECT *
            FROM `user`
            WHERE `vk_user_id` = :vk_user_id
        ', [
            ':vk_user_id' => $vkUser['id']
        ]);

        if ($user) {
            $this->db->execute('
                UPDATE `user`
                SET first_name = :first_name,
                  last_name = :last_name
                WHERE `vk_user_id` = :vk_user_id
            ', [
                ':first_name' => $vkUser['first_name'],
                ':last_name'  => $vkUser['last_name'],
                ':vk_user_id' => $vkUser['id']
            ]);
        } else {
            $this->db->execute('
                INSERT INTO `user`(vk_user_id, first_name, last_name)
                VALUES (:vk_user_id, :first_name, :last_name)
            ', [
                ':vk_user_id' => $vkUser['id'],
                ':first_name' => $vkUser['first_name'],
                ':last_name'  => $vkUser['last_name']
            ]);
        }
    }

    public function saveAlbum(array $vkAlbum)
    {
        $this->db->execute('
                INSERT INTO `album`(vk_album_id, vk_user_id, title)
                VALUES (:vk_album_id, :vk_user_id, :title)
                ON DUPLICATE KEY UPDATE title = :title;
            ', [
            ':vk_album_id' => $vkAlbum['id'],
            ':vk_user_id' => $vkAlbum['owner_id'],
            ':title'  => $vkAlbum['title']
        ]);
    }

    /**
     * @param int $vkPhotoId
     * @return bool
     */
    public function photoExists(int $vkPhotoId)
    {
        $photo = $this->db->queryOne('
            SELECT *
            FROM `photo`
            WHERE vk_photo_id = :vk_photo_id
        ', [
            ':vk_photo_id' => $vkPhotoId
        ]);

        return !!$photo;
    }

    /**
     * @param array $vkPhoto
     */
    public function savePhoto(array $vkPhoto)
    {
        $this->db->execute('
                INSERT IGNORE INTO `photo`(vk_photo_id, vk_album_id, vk_url)
                VALUES (:vk_photo_id, :vk_album_id, :vk_url)
            ', [
            ':vk_photo_id' => $vkPhoto['id'],
            ':vk_album_id' => $vkPhoto['album_id'],
            ':vk_url'      => VkImageDownloader::extractImageUrl($vkPhoto)
        ]);
    }
}