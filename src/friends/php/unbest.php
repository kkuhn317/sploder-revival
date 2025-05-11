<?php

session_start();
require_once('../../database/connect.php');

$db = getDatabase();
$db->execute("UPDATE friends
    SET bested=false
    WHERE (user1=:sender_id AND user2=:receiver_id)", [
        ':sender_id' => $_SESSION['username'],
        ':receiver_id' => $_GET['u']
    ]);

include("../../content/webhook.php");
log_data("Friend Unbested", $_SESSION['username'] . " has unbested " . $_GET['u'], 1);

header('Location: ../index.php');
