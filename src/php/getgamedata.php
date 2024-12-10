<?php

//error_reporting(E_ALL);
//ini_set('display_errors', 1);

require_once('../database/connect.php');
$db = connectToDatabase();
function difficulty($wins, $loss)
{
    if ($wins + $loss === 0) {
        return 5;
    }
    $diff = (1 - ($loss / ($wins + $loss))) * 9;
    return round(10 - ($diff));
}
function get_data($g,$db)
{
    $sql = "SELECT author, difficulty FROM games WHERE g_id = :g_id";
    $statement = $db->prepare($sql);
    $statement->execute([
        ':g_id' => $g
    ]);
    $result = $statement->fetchAll()[0];
    $result2['author'] = $result['author'];
    $result2['difficulty'] = $result['difficulty'];
    $sql = "SELECT AVG(score) as avg FROM votes WHERE g_id = :g_id";
    $statement = $db->prepare($sql);
    $statement->execute([
        ':g_id' => $g
    ]);
    $result = $statement->fetchAll()[0];
    if ($result['avg'] === null) {
        $result['avg'] = 0;
    }
    $result2['rating'] = round($result['avg']);
    return $result2;
}
function add_view($g,$db)
{
    $sql = "UPDATE games SET views = views + 1 WHERE g_id = :g_id";
    $statement = $db->prepare($sql);
    $statement->execute([
        ':g_id' => $g
    ]);
}

header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
$separated = explode("_",$_GET['g']);
$db2 = getDatabase();
$qs = "SELECT user_id FROM games WHERE g_id = :id";
$game = $db2->queryFirst($qs,[':id' => $separated[1]]);
if ($separated[0] != $game['user_id']) {
    echo 'Error';
    die();
}
$id = $separated[1];
$data = get_data($id,$db);
$username = $data['author'];
$difficulty = round($data['difficulty']);
add_view($id,$db);
echo "&username={$username}&difficulty=" . $difficulty . "&rating={$data['rating']}";
