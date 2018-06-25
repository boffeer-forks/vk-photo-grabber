<?php

namespace app;


use PDO;

class Db
{
    /**
     * @var \PDO
     */
    private $pdo;

    /**
     * Db constructor.
     * @param string $dsn
     * @param string $username
     * @param string $password
     */
    public function __construct(string $dsn, string $username, string $password)
    {
        $this->pdo = new PDO($dsn, $username, $password);
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    /**
     * @param string $sql
     * @param array $params
     * @return array
     */
    public function queryAll(string $sql, array $params = [])
    {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);

        return $stmt->fetchAll();
    }

    /**
     * @param string $sql
     * @param array $params
     * @return array
     */
    public function queryOne(string $sql, array $params = [])
    {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        $row = $stmt->fetch();
        $stmt->closeCursor();

        return $row;
    }

    /**
     * @param string $sql
     * @param array $params
     * @return int
     */
    public function execute(string $sql, array $params = [])
    {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);

        return $stmt->rowCount();
    }

    /**
     * @param string $sql
     * @param array $params
     * @return mixed
     */
    public function queryScalar(string $sql, array $params = [])
    {
        $row = $this->queryOne($sql, $params);

        return reset($row);
    }
}