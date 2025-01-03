<?php

session_start();

require_once('../../database/connect.php');

$db = getDatabase();
$db->execute("UPDATE friends SET bested=true WHERE (user1=:sender_id AND user2=:receiver_id)", [
    ':sender_id' => $_SESSION['username'],
    ':receiver_id' => $_GET['u']
]);

require_once("../../content/webhook.php");
log_data("Friend Bested", $_SESSION['username'] . " has bested " . $_GET['u'], 1);

header('Location: ../index.php');
