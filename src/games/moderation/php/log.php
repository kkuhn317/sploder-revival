<?php
function logModeration($action, $on, $level) {
    if(!isset($db)){
        include_once(__DIR__.'/../../../database/connect.php');
        $db = connectToDatabase();
    }

    // Time data type is time with time zone
    $sql = "INSERT INTO moderation_logs (moderator, action, \"on\", time, level) VALUES (:moderator, :action, :on, now(), :level)";
    $statement = $db->prepare($sql);
    $statement->execute([
        ':moderator'=>$_SESSION['username'],
        ':action'=>$action,
        ':on'=>$on,
        ':level'=>$level
    ]);
}