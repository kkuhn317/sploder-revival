<?php

/*
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);
*/
$loc = explode("projects/proj", $_GET["loc"]);
$loc = (int)filter_var($loc[1], FILTER_SANITIZE_NUMBER_INT);
include('../database/connect.php');
$db = getDatabase();
$result = $db->query("SELECT l.*
    FROM leaderboard l
    JOIN games g ON l.pubkey = g.g_id
    WHERE l.pubkey = :pubkey
    AND l.w = true
    ORDER BY CASE WHEN g.g_swf = 7 THEN l.gtm END DESC, l.gtm ASC
    LIMIT 16", [
        ':pubkey' => $loc
    ]);
if (!isset($result[0]['username'])) {
    die("<empty />");
}
$string = "";
foreach ($result as $row) {
    $string .= '<score value="' . $row['gtm'] . '" username="' . $row['username'] . "\" />";
}

echo $string;