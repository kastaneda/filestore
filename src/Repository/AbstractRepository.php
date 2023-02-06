<?php

declare(strict_types=1);

namespace App\Repository;

use App\Database;

abstract class AbstractRepository
{
    public function __construct(
        protected Database $db,
    ) {
    }

    abstract protected function getTable(): string;

    protected function getPKey(): string
    {
        return 'id';
    }

    abstract protected function getModel(): string;

    public function findById(int $id)
    {
        $format = 'SELECT * FROM `%s` WHERE `%s`=:id';
        $sql = sprintf($format, $this->getTable(), $this->getPKey());

        return $this->db->fetchOne($this->getModel(), $sql, ['id' => $id]);
    }

    public function findBy(array $param): array
    {
        $where = [];
        foreach ($param as $key => $value) {
            $where[] = sprintf('`%s` = :%s', $key, $key);
        }

        $format = 'SELECT * FROM `%s` WHERE %s';
        $sql = sprintf($format, $this->getTable(), join(' AND ', $where));

        return $this->db->fetch($this->getModel(), $sql, $param);
    }
}
