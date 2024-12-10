<?php

require_once(__DIR__ . '/idatabasemanager.php');
require_once(__DIR__ . '/database.php');
require_once(__DIR__ . '/connectionmanager.php');

/**
 * Handles all instances of each database with their connection managers
 */
class DatabaseManager implements IDatabaseManager
{
    private IDatabase|null $postgresDatabase;

    private function __construct()
    {
        $this->postgresDatabase = null;
    }

    public function getPostgresDatabase(): IDatabase
    {
        if ($this->postgresDatabase == null) {
            require_once(__DIR__ . '/../config/env.php');
            $host = getenv("POSTGRES_HOST");
            $port = getenv("POSTGRES_PORT");
            $database = getenv("POSTGRES_DB");
            $username = getenv("POSTGRES_USERNAME");
            $password = getenv("POSTGRES_PASSWORD");
            $sslmode = getenv("POSTGRES_SSLMODE");
            $dsn = "pgsql:host=$host;port=$port;dbname=$database;user=$username;password=$password;sslmode=$sslmode";
            $postgresConnection = new ConnectionManager($dsn);
            $this->postgresDatabase = new Database($postgresConnection);
        }
        return $this->postgresDatabase;
    }

    private static IDatabaseManager|null $value = null;

  /**
   * Retrieves the singleton instance of the DatabaseManager
   */
    public static function get(): IDatabaseManager
    {
        if (DatabaseManager::$value == null) {
            DatabaseManager::$value = new DatabaseManager();
        }

        return DatabaseManager::$value;
    }
}
