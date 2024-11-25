<?php

session_start();



header('Content-Type: text/xml');
$id = (int)filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);
include_once('../database/connect.php');
$db = connectToDatabase();
$qs2 = "SELECT author FROM games WHERE g_id=:id";
$statement2 = $db->prepare($qs2);
$statement2->execute([':id' => $id
]);
$result2 = $statement2->fetchAll();
$qs = "UPDATE games SET isdeleted=1 WHERE g_id=:id";
$statement = $db->prepare($qs);
if ($_SESSION["username"] == $result2[0]["author"]) {
    $statement->execute([':id' => $id
    ]);
    header('Location: ../dashboard/my-games.php');
} else {
    echo "There was an error while deleting your game.";
}
