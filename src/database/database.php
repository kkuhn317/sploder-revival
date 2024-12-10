<?php

require_once(__DIR__ . "/idatabase.php");
require_once(__DIR__ . "/connectionmanager.php");

class Database implements IDatabase
{
    private readonly IConnectionManager $connection_manager;

    public function __construct(IConnectionManager $connection_manager)
    {
        $this->connection_manager = $connection_manager;
    }

    private function getConnection(): PDO
    {
        return $this->connection_manager->getConnection();
    }

    public function query(string $query, array $parameters = []): array
    {
        $statement = $this->getConnection()->prepare($query);
        $statement->execute($parameters);
        return $statement->fetchAll();
    }

    public function queryFirst(string $query, array $parameters = []): mixed
    {
        $statement = $this->getConnection()->prepare($query);
        $statement->execute($parameters);
        return $statement->fetch();
    }

    public function queryFirstColumn(string $query, int $column = 0, array $parameters = []): mixed
    {
        $statement = $this->getConnection()->prepare($query);
        $statement->execute($parameters);
        return $statement->fetchColumn($column);
    }

    public function execute(string $query, array $parameters = []): bool
    {
        $statement = $this->getConnection()->prepare($query);
        return $statement->execute($parameters);
    }
}
