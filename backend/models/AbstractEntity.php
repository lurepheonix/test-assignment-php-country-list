<?php

namespace Models;

use Utils\QueryBuilder;

class AbstractEntity
{
    protected $tableName = '';
    protected $fields = '';

    /** 
     * @param array $options 
     * 
     * Options: array
     * 'select'? => array ['field1', 'field2', 'field3 as value'],
     * 'orderBy'? => array ['field': 'field1', 'order': 'asc' | 'desc']
     * 'limit'? => int
     */

    public function findAll($options = []): array | false
    {
        $qb = QueryBuilder::create($this->tableName);

        if (isset($options['select'])) {
            $qb->select($options['select']);
        } else {
            $qb->select();
        }

        $qb->from();

        if (isset($options['orderBy'])) {
            $qb->orderBy(
                $options['orderBy']['field'],
                $options['orderBy']['order']
            );
        }

        if (isset($options['limit'])) {
            $qb->limit($options['limit']);
        }

        $data = $qb->buildQuery()->execute()->getArray();
        return $data;
    }
}
