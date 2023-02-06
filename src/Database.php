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
        // echo $sql . PHP_EOL;
        $statement = $this->getStatement($sql, $param);
        // $statement->debugDumpParams();
        $statement->execute();

        return $statement->fetchAll(\PDO::FETCH_CLASS, $class);
    }

    public function fetchOne(string $class, string $sql, array $param = []): mixed
    {
        $result = $this->fetch($class, $sql, $param);

        return $result[0] ?? null;
    }

    public function execute(string $sql, array $param = []): int
    {
        // echo $sql . PHP_EOL;
        $statement = $this->getStatement($sql, $param);
        // $statement->debugDumpParams();
        $statement->execute();

        return $statement->rowCount();
    }
}
