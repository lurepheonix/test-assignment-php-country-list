<?php

namespace Utils;

use PDO;

class QueryBuilder
{
    protected PDO | null $db;
    protected string $tableName;
    protected string $query = '';
    protected array | false $results;

    protected array | string | null $selectFields;
    protected bool $typeIsFrom = true;
    protected string | null $orderByField = null;
    protected string $orderByDirection = 'asc';
    protected int | null $limit = null;

    public static function create($tableName): QueryBuilder
    {
        global $container;
        $qb = new self;
        $qb->tableName = $tableName;
        $qb->db = $container->get('connection');
        return $qb;
    }

    public function select(array $fields = []): QueryBuilder
    {
        if (count($fields) > 0) {
            $this->selectFields = $fields;
        } else {
            $this->selectFields = ' *';
        }
        return $this;
    }

    public function from(): QueryBuilder
    {
        $this->typeIsFrom = true;
        return $this;
    }

    public function orderBy(string $field, string $direction = 'asc'): QueryBuilder
    {
        $this->orderByField = $field;
        $this->orderByDirection = $direction;
        return $this;
    }

    public function limit(int $limit): QueryBuilder
    {
        $this->limit = $limit;
        return $this;
    }

    public function buildQuery(): QueryBuilder
    {
        /**
         * Check whether we've got any fields to select
         * There is a bit of overengineering here to implement other methods, e.g. INSERT later.
         */
        if ($this->selectFields) {
            $query = 'SELECT';
        }

        if (gettype($this->selectFields) === 'array') {
            $query .= ' ' . implode(', ', $this->selectFields);
        } else if (gettype($this->selectFields) === 'string') {
            $query .= ' *';
        }

        if ($this->typeIsFrom) {
            $query .= ' FROM ' . $this->tableName;
        }

        if ($this->orderByField) {
            $query .= ' ORDER BY ' . $this->orderByField . ' ' . $this->orderByDirection;
        }

        if ($this->limit) {
            $query .= ' LIMIT ' . $this->limit;
        }

        $this->query = $query;

        return $this;
    }

    public function execute(): QueryBuilder
    {
        $this->results = $this->db->query($this->query)->fetchAll(\PDO::FETCH_ASSOC);
        return $this;
    }

    public function getArray(): array
    {
        return gettype($this->results === 'array') ? $this->results : [];
    }
}
