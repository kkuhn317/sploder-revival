<?php
//error_reporting(E_ALL);
//ini_set('display_errors', 1);

function difficulty($wins,$loss){
    if($wins+$loss === 0){
        return 5;
    }
    $diff = (1-($loss/($wins+$loss)))*9;
    return round(10-($diff));
}
function get_data($g){
    include("../database/connect.php");
    $db = connectToDatabase();
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
    if($result['avg'] === null){
        $result['avg'] = 0;
    }
    $result2['rating'] = round($result['avg']);
    return $result2;
}
function add_view($g){
    $db = connectToDatabase();
    $sql = "UPDATE games SET views = views + 1 WHERE g_id = :g_id";
    $statement = $db->prepare($sql);
    $statement->execute([
        ':g_id' => $g
    ]);
}

header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
$data = get_data($_GET['g']);
$username = $data['author'];
$difficulty = round($data['difficulty']);
add_view($_GET['g']);
echo "&username={$username}&difficulty=".$difficulty."&rating={$data['rating']}";