<?php
// Define the br2nl function
function br2nl($string)
{
    if ($string === null) {
        return null;
    }
    return preg_replace('/<br\s*\/?>/i', "\n", $string);
}

// Get required data...
session_start();
$s_array = explode("_", $_GET['s']);
$id = end($s_array);
require_once('../database/connect.php');
$db = getDatabase();
$qs = "SELECT author,title,description,g_id,user_id,g_swf FROM games WHERE g_id = :id";
$game = $db->queryFirst($qs, [':id' => $id]);
if ($_SESSION['username'] != $game['author']) {
    header('Location: /?s=' . $_GET['s']);
}
$qs = "SELECT tag FROM game_tags WHERE g_id = :id";
$tags = $db->query($qs, [':id' => $id]);

require('../content/playgame.php');