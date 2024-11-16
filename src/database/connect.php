<?php

// Get the environment variables from the .env file
// It should use the path relative only to connect.php and not the files that include it
$env_filename = __DIR__ . "/../.env";
if(file_exists($env_filename)) {
  $env = file_get_contents($env_filename);
  $lines = explode("\n",$env);
  foreach($lines as $line){
    preg_match("/([^#]+)\=(.*)/",$line,$matches);
    if(isset($matches[2])){
      putenv(trim($line));
    }
  }
}
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
