<?php

function get_total_graphics(mixed $db): int
{
    $qs = "SELECT COUNT(id) FROM graphics WHERE isprivate=false AND ispublished=true";
    $total_graphics = $db->queryFirstColumn($qs, 0);
    return $total_graphics;
}

include('../database/connect.php');
$db = getDatabase();
$total_graphics = get_total_graphics($db);

$o = isset($_GET['o']) ? $_GET['o'] : "0";
$offset = 12;

$queryString = '
    SELECT g.*, m.username 
    FROM graphics g
    LEFT JOIN members m ON g.userid = m.userid
    WHERE g.isprivate = false AND g.ispublished = true
    ORDER BY g.id DESC
    LIMIT 12 OFFSET ' . $o;
$result = $db->query($queryString);
$total = $total_graphics;