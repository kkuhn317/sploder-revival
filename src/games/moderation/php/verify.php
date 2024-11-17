<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Login check
include(__DIR__.'/../../../content/logincheck.php');

// Check whether the user is actually a moderator and not haxxor
$username = $_SESSION['username'];
// Use dir to include database
include(__DIR__ . '/../../../database/connect.php');
$db = getDatabase();
$qs = "SELECT perms FROM members WHERE username=:username";
$perms = $db->queryFirst($qs, [
    ':username'=>$username
]);

// If perms includes M, then the user is a moderator, else they are haxxor
if(!str_contains($perms['perms'], 'M')){
    die("Haxxor detected");
}
