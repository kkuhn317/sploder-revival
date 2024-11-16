<?php

include('verify.php');
$username = $_REQUEST['username'];

// Check whether user has not been banned
$sql = "SELECT COUNT(*) FROM banned_members WHERE username=:username";
$statement = $db->prepare($sql);
$statement->execute([
    ':username'=>$username
]);
$count = $statement->fetchColumn();
if($count==0){
    header("Location: ../index.php?err=User is not banned");
    die();
}

$sql = "DELETE FROM banned_members WHERE username=:username";
$statement = $db->prepare($sql);
if($statement->execute([
    ':username'=>$username,
])){
    header("Location: ../index.php?msg=User unbanned successfully");
} else {
    header("Location: ../index.php?err=There was an error while unbanning the user");
}