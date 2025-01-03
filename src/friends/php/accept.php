<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
include('../../database/connect.php');
include('../../content/logincheck.php');


$db = getDatabase('friends');

$exists = $db->query("SELECT id FROM friends WHERE (user1=:sender_id AND user2=:receiver_id)", [
        ':sender_id' => $_SESSION['username'],
        ':receiver_id' => $_GET['u']
    ]);

if (!isset($exists[0]['id'])) {
    $exists = $db->query("SELECT request_id
        FROM friend_requests
        WHERE (sender_username=:sender_id AND receiver_username = :receiver_username)
        OR (receiver_username=:sender_id AND sender_username = :receiver_username)", [
            ':receiver_username' => $_GET['u'],
            ':sender_id' => $_SESSION['username']
        ]);

    if (isset($exists[0]['request_id'])) {
        $db->execute("DELETE FROM friend_requests
            WHERE (sender_username=:sender_id AND receiver_username = :receiver_username)
            OR (receiver_username=:sender_id AND sender_username = :receiver_username)", [
                ':receiver_username' => $_GET['u'],
                ':sender_id' => $_SESSION['username']
            ]);

        $db->execute("INSERT INTO friends 
            (user1,user2,bested) 
            VALUES (:user1,:user2,false)", [
                ':user1' => $_SESSION['username'],
                ':user2' => $_GET['u']
            ]);

        $user1bp = $db->queryFirstColumn("SELECT boostpoints
            FROM members
            WHERE username=:username", 0, [
            ':username' => $_SESSION['username']
        ]);

        $user2bp = $db->queryFirstColumn("SELECT boostpoints
            FROM members
            WHERE username=:username", 0, [
            ':username' => $_GET['u']
        ]);

        $newuser1bp = $user1bp + 5;
        $newuser2bp = $user2bp + 5;

        $db->execute("UPDATE members
            SET boostpoints=:bp
            WHERE username=:username", [
            ':bp' => $newuser1bp,
            ':username' => $_SESSION['username']
        ]);

        $db->execute("UPDATE members
            SET boostpoints=:bp
            WHERE username=:username", [
            ':bp' => $newuser2bp,
            ':username' => $_GET['u']
        ]);

        include("../../content/webhook.php");
        log_data("Friend Request Accepted", $_SESSION['username'] . " is now friends with " . $_GET['u'], 1);

        header('Location: ../index.php');
    } else {
        header('Location: ../index.php?err=before');
    }
} else {
    header('Location: ../index.php?err=that');
}
