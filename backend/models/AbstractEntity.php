<?php

namespace Models;

class AbstractEntity
{
    protected $db;
    protected $tableName = '';
    protected $fields = '';

    public function __construct()
    {
        global $container;
        $this->db = $container->get('connection');
    }

    public function findAll()
    {
        $query = 'SELECT * FROM ' . $this->tableName;

        $data = $this->db->query($query)->fetchAll(\PDO::FETCH_ASSOC);
        return $data;
    }
}
