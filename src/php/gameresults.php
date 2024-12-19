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
    /* if ($_GET['PHPSESSID'] == "undefined") {
        die("&success=true");
    }
    session_id($_GET['PHPSESSID']);
    session_start();
    */
    session_start();
    if (!isset($_SESSION['username'])) {
        die("&success=true");
    }
    include('../database/connect.php');
    $db = connectToDatabase();
    $sql = "INSERT INTO leaderboard
        (username, pubkey, gtm, w)
        VALUES (:username, :pubkey, :gtm, :w)";
    $statement = $db->prepare($sql);
    $statement->execute([
        ':username' => $_SESSION['username'],
        ':pubkey' => $id[1],
        ':gtm' => $gtm,
        ':w' => $w
    ]);
    echo "&success=true";
    // update difficulty in game table
    $sql = "SELECT w, COUNT(*) as count FROM leaderboard WHERE pubkey = :g_id GROUP BY w;";
    $statement = $db->prepare($sql);
    $statement->execute([
        ':g_id' => $id[1]
    ]);
    $result2 = $statement->fetchAll();
    $result2['wins'] = $result2[0]['count'] ?? 0;
    //if there are no losses, set loss to 0 using ternary operator
    $result2['loss'] = $result2[1]['count'] ?? 0;

    $sql = "UPDATE games SET difficulty = :difficulty WHERE g_id = :g_id";
    $statement = $db->prepare($sql);
    $statement->execute([
        ':difficulty' => difficulty($result2['wins'], $result2['loss']),
        ':g_id' => $id[1]
    ]);
} else {
    echo "&success=false";
}