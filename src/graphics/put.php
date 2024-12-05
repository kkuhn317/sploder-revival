<?php
session_start();
header('Content-Type: text/xml');
$rawdata = file_get_contents("php://input");
$id = $_REQUEST['projid'];
$type = $_GET['objtype'];
$username = $_SESSION["username"];
$userid = $_SESSION["userid"];
require_once('../database/connect.php');
$db = getDatabase();

if ($id == "0") {
    $qs = "INSERT INTO graphics (version, userid, isprivate, ispublished) VALUES (0, :userid, true, false) RETURNING id";
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
        // Check image dimensions if it is 80px by 80px to see if they are valid
        $image = imagecreatefromstring($rawdata);
        $width = imagesx($image);
        $height = imagesy($image);
        if ($width != 80 || $height != 80) {
            die('<message result="error" message="Invalid dimensions! Please note that inappropriate graphics and graphics not made by the creator is strictly forbidden."/>');
        }
        file_put_contents("gif/" . $id . ".gif", $rawdata);
    } elseif ($type == "sprite") {
        $isprivate = $_GET['isprivate'] == "1" ? true : false;
        $qs = "UPDATE graphics SET ispublished=true, isprivate=:isprivate, version=version+1 WHERE id=:id RETURNING version";
        $version = $db->queryFirstColumn($qs,0,[
            ':isprivate' => $isprivate,
            ':id' => $id
        ]);
        file_put_contents("png/" . $id . "_". $version .".png", $rawdata);
    } elseif ($type == "project") {
        file_put_contents("prj/" . $id . ".prj", $rawdata);
    }
} else{
    die('<message result="error" message="You do not own this graphic"/>');
}

    

echo '<message result="success" id="' . $id . '" />';
