<?php
session_start();
// Get required data...
require_once('../database/connect.php');
$db = getDatabase();
$qs = "SELECT g.author, g.title, g.description, g.g_id, g.user_id, g.g_swf, g.date, g.user_id, g.views, 
ROUND(AVG(r.score), 1) as avg_rating, COUNT(r.score) as total_votes 
FROM games g 
LEFT JOIN votes r ON g.g_id = r.g_id 
WHERE g.ispublished = 1 AND g.isprivate = 0 
GROUP BY g.g_id 
ORDER BY g.g_id DESC 
LIMIT 12 OFFSET :offset";
$games = $db->query($qs, ['offset' => $_GET['o']]);
$qs = "SELECT COUNT(g_id) FROM games WHERE ispublished = 1 AND isprivate = 0";
$total = $db->queryFirstColumn($qs);
$gamesCount = count($games);