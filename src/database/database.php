<?php

require_once(__DIR__ . "/idatabase.php");
require_once(__DIR__ . "/iconnectionmanager.php");

class Database implements IDatabase
{
    private readonly IConnectionManager $connectionManager;

    public function __construct(IConnectionManager $connectionManager)
    {
        $this->connectionManager = $connectionManager;
    }

    private function getConnection(): PDO
    {
        return $this->connectionManager->getConnection();
    }

    public function query(string $query, array $parameters = null, $mode = 0): array
    {
        $connection =  $this->getConnection();
        $statement = $this->getConnection()->prepare($query);
        $statement->execute($parameters);
        return $statement->fetchAll($mode);
    }

    public function queryFirst(string $query, array $parameters = null, $mode = 0): mixed
    {
        $statement = $this->getConnection()->prepare($query);
        $statement->execute($parameters);
        return $statement->fetch($mode);
    }

    public function queryFirstColumn(string $query, int $column = 0, array $parameters = null): mixed
    {
        $statement = $this->getConnection()->prepare($query);
        $statement->execute($parameters);
        return $statement->fetchColumn($column);
    }

    public function execute(string $query, array $parameters = null): bool
    {
        $statement = $this->getConnection()->prepare($query);
        return $statement->execute($parameters);
    }

    public function useTransactionScope(callable $callback): mixed
    {
        $conn = $this->connection;
        if (!$conn->beginTransaction()) {
            throw new Exception("Failed to start a transaction", 1);
        }

        try {
            $result = $callback();
            if (!$conn->commit()) {
                throw new Exception("Failed to commit the transaction", 1);
            }
            return $result;
        } finally {
            $conn->rollBack();
        }
    }
}
