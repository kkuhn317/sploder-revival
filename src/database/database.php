<?php

require_once(__DIR__ . "/idatabase.php");
require_once(__DIR__ . "/PaginationData.php");
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

    public function queryPaginated(string $query, int $page, int $itemsPerPage, $parameters = null): PaginationData
    {
        if ($page < 0) {
            throw new InvalidArgumentException("Page cannot be less than 0: " . $page);
        }

        if ($itemsPerPage < 0) {
            throw new InvalidArgumentException("ItemsPerPage cannot be less than 0: " . $itemsPerPage);
        }

        // Calculate the OFFSET based on the current page
        // Assumes index of 0
        $offset = $page * $itemsPerPage;

        $totalCount = $this->queryFirstColumn("SELECT COUNT(*) AS total FROM ($query) AS subquery", 0, $parameters);

        // Modify the original query to include LIMIT and OFFSET for pagination
        $tempParameters = [
            ':limit' => $itemsPerPage,
            ':offset' => $offset,
        ];

        if ($parameters === null) {
            $parameters = $tempParameters;
        } else {
            $parameters = array_merge($parameters, $tempParameters);
        }

        $data = $this->query($query . " LIMIT :limit OFFSET :offset", $parameters);

        // Calculate total pages
        $totalPages = ceil($totalCount / $itemsPerPage);

        // Return data along with total count and total pages
        return new PaginationData($data, $totalCount, $totalPages);
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
