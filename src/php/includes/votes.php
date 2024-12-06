<?php

include_once('../database/connect.php');
function get_votes($g_id)
{
    $db = connectToDatabase();
    $sql = "SELECT AVG(score) as avg, COUNT(*) as count FROM votes WHERE g_id = :g_id";
    $statement = $db->prepare($sql);
    $statement->execute([
        ':g_id' => $g_id
    ]);
    $result = $statement->fetchAll()[0];
    return $result;
}
function vote_check($username, $g_id): bool
{
    $sql = "SELECT * FROM votes WHERE username = :username AND g_id = :g_id";
    $db = connectToDatabase();
    $statement = $db->prepare($sql);
    $statement->execute([
        ':username' => $username,
        ':g_id' => $g_id
    ]);
    $result = $statement->fetchAll();
    if (count($result) > 0) {
        return true;
    } else {
        return false;
    }
}
