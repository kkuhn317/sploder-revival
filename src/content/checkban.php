<?php
function checkBan($username) {
    if(!isset($db)){
        include_once(__DIR__.'/../database/connect.php');
        $db = connectToDatabase();
    }
    $sql = "SELECT autounbandate FROM banned_members WHERE username=:username ORDER BY autounbandate DESC LIMIT 1";
    $statement = $db->prepare($sql);
    $statement->execute([
        ':username'=>$username
    ]);
    $user = $statement->fetch();
    if(!$user){
        return false;
    } else {
        if(time()>$user['autounbandate']){
            return false;
        } else {
            return true;
        }
    }
    
}