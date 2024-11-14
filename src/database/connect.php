<?php
function connectToDatabase($table = null) {
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
?>
