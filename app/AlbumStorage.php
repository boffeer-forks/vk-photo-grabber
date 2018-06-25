<?php

namespace app;


class AlbumStorage
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

    public function save(array $vkAlbum)
    {
        $user = $this->db->queryOne('
            SELECT *
            FROM `album`
            WHERE `vk_album_id` = :vk_album_id
        ', [
            ':vk_album_id' => $vkAlbum['id']
        ]);

        if ($user) {
            $this->db->execute('
                UPDATE `album`
                SET title = :title
                WHERE `vk_album_id` = :vk_album_id
            ', [
                ':title'  => $vkAlbum['title'],
                ':vk_album_id' => $vkAlbum['id']
            ]);
        } else {
            $this->db->execute('
                INSERT INTO `album`(vk_album_id, title)
                VALUES (:vk_album_id, :title)
            ', [
                ':vk_album_id' => $vkAlbum['id'],
                ':title'  => $vkAlbum['title']
            ]);
        }
    }
}