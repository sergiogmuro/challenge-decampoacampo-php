<?php

namespace Src\database;

use PDO;
use PDOException;

class Connection implements DBInterface
{
    private static $instance = null;
    private $connection;

    private function __construct()
    {
        if (self::$instance instanceof self) {
            return self::$instance;
        }

        try {

            $host = env('MYSQL_HOST');
            $dbname = env('MYSQL_DATABASE');
            $username = env('MYSQL_USER');
            $password = env('MYSQL_PASSWORD');

            $dsn = "mysql:host={$host};dbname={$dbname};charset=utf8mb4";

            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ];

            $this->connection = new PDO($dsn, $username, $password, $options);
        } catch (PDOException $e) {
            die("Error de conexión: " . $e->getMessage());
        }
    }


    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }


    public function getConnection()
    {
        return $this->connection;
    }

    private function __clone()
    {
    }

    public function __wakeup()
    {
        throw new \Exception("No se puede deserializar una instancia de Database.");
    }
}
