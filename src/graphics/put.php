<?php
session_start();
//error_reporting(E_ALL);
//ini_set('display_errors', 1);
header('Content-Type: text/xml');
$rawdata = file_get_contents("php://input");
$id = $_REQUEST['projid'];
$type = $_GET['objtype'];
$username = $_SESSION["username"];
$userid = $_SESSION["userid"];
require_once('../database/connect.php');
$db = getDatabase();

if ($id == "0") {
    $qs = "INSERT INTO graphics (version, userid, isprivate, ispublished) VALUES (1, :userid, true, false) RETURNING id";
    $id = $db->queryFirstColumn($qs,0,[
        ':userid' => $userid
    ]);
}

// Check whether the user owns the graphic
$qs = "SELECT userid FROM graphics WHERE id=:id";
$result = $db->queryFirstColumn($qs,0,[
    ':id' => $id
]);
if($result == $userid){
    if ($type == "thumbnail") {
        file_put_contents("gif/" . $id . ".gif", $rawdata);
    } elseif ($type == "sprite") {
        $qs = "UPDATE graphics SET ispublished=1 WHERE id=:id";
        $db->execute($qs, [
            ':id' => $id
        ]);
        $result = $statement->fetchAll();

        file_put_contents("png/" . $id . "_6.png", $rawdata);
    } elseif ($type == "project") {
        file_put_contents("prj/" . $id . ".prj", $rawdata);
    }
} else{
    die('<message result="error" message="You do not own this graphic"/>');
}

    

echo '<message result="success" id="' . $id . '" />';
