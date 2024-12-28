<?php

require_once(__DIR__ . '/idatabase.php');
require_once(__DIR__ . '/databasemanager.php');

/**
 * @deprecated use "getDatabase" or "DatabaseManager::get()->getPostgresDatabase()" moving forward, as this will be deleted
 *
 * Returns a connection to the Postgres database
 * @return PDO
 */
function connectToDatabase($table = null): PDO
{
    require_once(__DIR__ . '/../config/env.php');
    $host = getenv("POSTGRES_HOST");
    $port = getenv("POSTGRES_PORT");
    $database = getenv("POSTGRES_DB");
    $username = getenv("POSTGRES_USERNAME");
    $password = getenv("POSTGRES_PASSWORD");
    $sslmode = getenv("POSTGRES_SSLMODE");
    $dsn = "pgsql:host=$host;port=$port;dbname=$database;user=$username;password=$password;sslmode=$sslmode";
    try {
        $connection = new PDO($dsn);
        return $connection;
    } catch (PDOException $e) {
        echo 'Connection failed: ' . $e->getMessage();
        return null;
    }
}



/**
 * Retrieves a connection to the Postgres Database
 *
 * @return IDatabase
 */
function getDatabase(): IDatabase
{
    return DatabaseManager::get()->getPostgresDatabase();
}

/**
 * Retrieves a connection to the SQLite Database for the original members
 *
 * @return IDatabase
 */
function getOriginalMembersDatabase(): IDatabase
{
    return DatabaseManager::get()->getOriginalMembersDatabase();
}
