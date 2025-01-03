<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);
function difficulty($wins, $loss)
{
    if ($wins + $loss === 0) {
        return 5;
    }
    $diff = (1 - $loss / ($wins + $loss)) * 9;
    return 10 - ($diff);
}
    $hash = $_GET['ax'];
    $gtm = filter_var($_POST['gtm'], FILTER_VALIDATE_INT);
    $w = $_POST['w'];
$   $w = $w == true;
;
    $id = explode("_", $_POST['pubkey']);
    $id[0] = filter_var($id[0], FILTER_VALIDATE_INT);
    $id[1] = filter_var($id[1], FILTER_VALIDATE_INT);
    $md5 = `node md5_edited.js $id[0]_$id[1] $w $gtm`;
if (substr($md5, 0, -1) == $hash) {
    session_start();
    if (!isset($_SESSION['username'])) {
        die("&success=true");
    }
    require_once('../database/connect.php');
    $db = getDatabase();
    $db->execute("INSERT INTO leaderboard
        (username, pubkey, gtm, w)
        VALUES (:username, :pubkey, :gtm, :w)", [
        ':username' => $_SESSION['username'],
        ':pubkey' => $id[1],
        ':gtm' => $gtm,
        ':w' => $w
    ]);
    echo "&success=true";
    // Update difficulty in game table
    $result2 = $db->query("SELECT w, COUNT(*) as count FROM leaderboard WHERE pubkey = :g_id GROUP BY w;", [
        ':g_id' => $id[1]
    ]);
    // If there are no losses, set loss to 0 using ternary operator
    $result2['loss'] = $result2[1]['count'] ?? 0;
    $result2['wins'] = $result2[0]['count'] ?? 0;

    $db->execute("UPDATE games
        SET difficulty = :difficulty
        WHERE g_id = :g_id", [
        ':difficulty' => difficulty($result2['wins'], $result2['loss']),
        ':g_id' => $id[1]
    ]);
} else {
    echo "&success=false";
}
