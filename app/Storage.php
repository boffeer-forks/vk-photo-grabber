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
            FROM `vk_user`
            WHERE `vk_user_id` = :vk_user_id
        ', [
            ':vk_user_id' => $vkUser['id']
        ]);

        if ($user) {
            $this->db->execute('
                UPDATE `vk_user`
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
                INSERT INTO `vk_user`(vk_user_id, first_name, last_name)
                VALUES (:vk_user_id, :first_name, :last_name)
            ', [
                ':vk_user_id' => $vkUser['id'],
                ':first_name' => $vkUser['first_name'],
                ':last_name'  => $vkUser['last_name']
            ]);
        }
    }
}