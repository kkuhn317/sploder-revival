<?php

session_start();

if (isset($_SESSION['username'])) {
    require_once('../database/connect.php');
    $t = time();
    $db = getDatabase();
    $db->execute("UPDATE members SET lastpagechange=:t WHERE username=:username", [
        ':t' => $t,
        ':username' => $_SESSION['username']
    ]);
}
