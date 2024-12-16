<?php

function get_total_graphics(mixed $db, int $userid): int
{
    $qs = "SELECT COUNT(id) FROM graphics WHERE userid=:userid";
    $total_graphics = $db->queryFirstColumn($qs, 0, [':userid' => $userid]);
    return $total_graphics;
}

$username = $_SESSION['username'];
$userid = $_SESSION['userid'];
include('../database/connect.php');
$db = getDatabase();
$total_games = get_total_graphics($db, $userid); // TODO: Pagination refactor
$currentpage = "my-graphics.php";

$o = isset($_GET['o']) ? $_GET['o'] : "0";
$offset = 12;

$queryString = 'SELECT * FROM graphics WHERE userid=:userid ORDER BY id DESC LIMIT 12 OFFSET ' . $o;
$result = $db->query($queryString, [':userid' => $userid]);
$total = $total_games;