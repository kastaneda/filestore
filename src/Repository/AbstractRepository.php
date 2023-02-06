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

    protected function formatClause(
        string $itemFormat,
        array $elements,
        string $joiner,
    ): string {
        $result = [];
        foreach ($elements as $key => $value) {
            $result[] = sprintf($itemFormat, $key, $value);
        }

        return join($joiner, $result);
    }

    public function findBy(array $where): array
    {
        $sql = sprintf(
            'SELECT * FROM `%s` WHERE %s',
            $this->getTable(),
            $this->formatClause('`%1$s` = :%1$s', $where, ' AND '),
        );

        return $this->db->fetch($this->getModel(), $sql, $where);
    }

    public function findOneBy(array $where): mixed
    {
        return $this->findBy($where)[0] ?? null;
    }

    public function insert(array $data, string $verb = 'INSERT INTO'): int
    {
        $sql = sprintf(
            '%s `%s` (%s) VALUES (%s)',
            $verb,
            $this->getTable(),
            $this->formatClause('`%1$s`', $data, ', '),
            $this->formatClause(':%1$s', $data, ', '),
        );

        return $this->db->execute($sql, $data);
    }

    public function update(array $data, array $where): int
    {
        $sql = sprintf(
            'UPDATE `%s` SET %s WHERE %s ',
            $this->getTable(),
            $this->formatClause('`%1$s` = :%1$s', $data, ', '),
            $this->formatClause('`%1$s` = :%1$s', $where, ' AND '),
        );

        return $this->db->execute($sql, $data + $where);
    }

    public function delete(array $where): int
    {
        $sql = sprintf(
            'DELETE FROM `%s` WHERE %s ',
            $this->getTable(),
            $this->formatClause('`%1$s` = :%1$s', $where, ' AND '),
        );

        return $this->db->execute($sql, $where);
    }

    protected function getWhere(array $data): array
    {
        return [$this->getPKey() => $data[$this->getPKey()]];
    }

    protected function getPayload(array $data): array
    {
        unset($data[$this->getPKey()]);

        return $data;
    }

    public function updateModel(array $data): int
    {
        return $this->update($this->getPayload($data), $this->getWhere($data));
    }

    public function deleteModel(array $data): int
    {
        return $this->delete($this->getWhere($data));
    }
}
