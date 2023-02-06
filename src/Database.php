<?php

declare(strict_types=1);

namespace App;

class Database
{
    protected \PDO $db;

    public function __construct(
        protected array $config,
    ) {
    }

    public function getDatabase(): \PDO
    {
        if (empty($this->db)) {
            $this->db = new \PDO(...$this->config);
        }

        return $this->db;
    }

    public function getStatement(string $sql, array $param = []): \PDOStatement
    {
        $statement = $this->getDatabase()->prepare($sql);

        foreach ($param as $key => $value) {
            $type = match (gettype($value)) {
                'NULL' => \PDO::PARAM_NULL,
                'boolean' => \PDO::PARAM_BOOL,
                'integer' => \PDO::PARAM_INT,
                default => \PDO::PARAM_STR,
            };

            $statement->bindValue($key, $value, $type);
        }

        return $statement;
    }

    public function fetch(string $class, string $sql, array $param = []): array
    {
        $statement = $this->getStatement($sql, $param);
        $statement->execute();

        return $statement->fetchAll(\PDO::FETCH_CLASS, $class);
    }

    public function fetchOne(string $class, string $sql, array $param = []): mixed
    {
        $result = $this->fetch($class, $sql, $param);

        if (empty($result)) {
            return null;
        }

        return $result[0];
    }

    public function execute(string $sql, array $param = []): int
    {
        $statement = $this->getStatement($sql, $param);
        $statement->execute();

        return $statement->rowCount();
    }
}
