<?php

session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once('../../database/connect.php');

$db = getDatabase();

$db->execute("DELETE FROM friends
    WHERE (user1=:sender_id AND user2 = :receiver_username) 
    OR (user2=:sender_id AND user1 = :receiver_username)", [
        ':receiver_username' => $_GET['u'],
        ':sender_id' => $_SESSION['username']
    ]);

$db->execute("DELETE FROM friends
    WHERE (user1=:sender_id AND user2 = :receiver_username)
    OR (user2=:sender_id AND user1 = :receiver_username)", [
        ':receiver_username' => $_SESSION['username'],
        ':sender_id' => $_GET['u']
    ]);

include("../../content/webhook.php");

log_data("Friend Unfriended", $_SESSION['username'] . " is no longer friends with " . $_GET['u'], 1);

header('Location: ../index.php');
