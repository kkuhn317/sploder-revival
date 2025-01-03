<?php

include('../../content/logincheck.php');
include('../../database/connect.php');

error_reporting(E_ALL);
ini_set('display_errors', 1);
$username = $_GET['username'];
$db = getDatabase();
$qs2 = "SELECT id FROM friends WHERE (user1=:sender_id AND user2=:receiver_id)";
$exists = $db->query($qs2, [
    ':sender_id' => $_SESSION['username'],
    ':receiver_id' => $username
]);
if (!isset($exists[0]['id'])) {
    $qs2 = "SELECT userid FROM members WHERE username=:user";
    $receiver = $db->query($qs2, [
        ':user' => $username
    ]);
    if (isset($receiver[0]['userid'])) {
        $receiver = $receiver[0]['userid'];
    } else {
        header('Location: ../index.php?err=no');
        exit;
    }
    $qs2 = "SELECT request_id FROM friend_requests WHERE (sender_username=:sender_id AND receiver_username = :receiver_id) OR ((receiver_username=:sender_id AND sender_username = :receiver_id))";
    $exists = $db->query($qs2, [
        ':receiver_id' => $username,
        ':sender_id' => $_SESSION['username']
    ]);

    if (isset($exists[0]['request_id'])) {
        header('Location: ../index.php?err=sent');
    } elseif ($receiver == $_SESSION['userid']) {
        header('Location: ../index.php?err=you');
    } elseif ($receiver != $_SESSION['userid']) {
        $qs2 = 'INSERT INTO friend_requests 
            (sender_id, receiver_id, sender_username, receiver_username) 
            VALUES (:sender_id, :receiver_id, :sender_username, :receiver_username)';
        $statement2 = $db->execute($qs2, [
            ':sender_id' => $_SESSION['userid'],
            ':receiver_id' => $receiver,
            ':sender_username' => $_SESSION['username'],
            ':receiver_username' => $_GET['username']
        ]);

        include("../../content/webhook.php");
        log_data("Friend Request Attempt", $_SESSION['username'] . " has sent a friend request to " . $_GET['username'], 1);
        header('Location: ../index.php?err=suc');
    }
} else {
    header('Location: ../index.php?err=that');
}