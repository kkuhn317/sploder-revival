<?php
function connectToDatabase($table = null) {
    $host = 'localhost';
    $port = '5432';
    $database = 'sploder';
    $username = 'sploder';
    $password = 'sploderwasdabest-database';

    $dsn = "pgsql:host=$host;port=$port;dbname=$database;user=$username;password=$password";

    try {
        $connection = new PDO($dsn);
        return $connection;
    } catch (PDOException $e) {
        echo 'Connection failed: ' . $e->getMessage();
        return null;
    }
}
?>
