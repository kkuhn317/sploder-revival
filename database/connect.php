<?php
function connectToDatabase($table = null) {
    $host = 'ep-blue-band-a2dl3erw.eu-central-1.aws.neon.tech';
    $port = '5432';
    $database = 'sploder';
    //$username = 'sploder';
    //$password = 'sploderwasdabest-database';
    $username = 'sploder_owner';
    $password = 'hVAgHfj0tE6e';
    $sslmode = 'require';
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
