<?php

namespace Utils;

class Connection
{
    public $connection;

    public function __construct()
    {
        $this->connection = new \PDO(
            'mysql:host=db;dbname=' . $_ENV['MYSQL_DATABASE'],
            $_ENV['MYSQL_USER'],
            $_ENV['MYSQL_PASSWORD']
        );
    }
}
