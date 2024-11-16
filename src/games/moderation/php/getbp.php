<?php

include('verify.php');
$username = $_POST['username'];

// Check whether user actually exists
$sql = "SELECT COUNT(*) FROM members WHERE username=:username";
$statement = $db->prepare($sql);
$statement->execute([
    ':username'=>$username
]);
$count = $statement->fetchColumn();
if($count==0){
    header("Location: ../index.php?err=User does not exist");
    die();
}

// Get boost points
$sql = "SELECT boostpoints FROM members WHERE username=:username";
$statement = $db->prepare($sql);
$statement->execute([
    ':username'=>$username
]);
$bp = $statement->fetchColumn();

// Send header with boost points
header("Location: ../index.php?bp=".$bp);