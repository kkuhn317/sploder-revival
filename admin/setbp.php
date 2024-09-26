<?php

$ownerusername = $_SESSION['username'];
if($ownerusername=="saptarshi12345"){
    $type = $_GET['amt'];
    $username = $_GET['username'];
    $db = new PDO('sqlite:../database/members.db');
    $qs = "UPDATE members SET boostpoints=:perms WHERE username=:username";
    $statement = $db->prepare($qs);
    if($statement->execute([
        ':perms'=>$type,
        ':username'=>$username
    ])){
        echo "Boost points changed";
    } else {
        echo "There was an error while changing the boost points";
    }
}
?>