<?php
$perpage = 100;
require_once('../database/connect.php');
$db = getDatabase();
// Select all distinct tags
$qs = "SELECT DISTINCT tag FROM game_tags ORDER BY tag LIMIT $perpage OFFSET :offset";
$tags = $db->query($qs, [':offset' => $_GET['offset'] ?? 0]);
$qs = "SELECT COUNT(DISTINCT tag) as total_unique_tags FROM game_tags";
$total = $db->queryFirstColumn($qs, 0);