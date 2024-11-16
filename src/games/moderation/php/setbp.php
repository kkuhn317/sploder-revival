<?php

include('verify.php');
$username = $_POST['username'];

// Check whether username exists
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

// Set boost points
$sql = "UPDATE members SET boostpoints = :boostpoints WHERE username=:username";
$statement = $db->prepare($sql);
if($statement->execute([
    ':boostpoints'=>$_POST['bp'],
    ':username'=>$username
])){
    header("Location: ../index.php?msg=Boost points set successfully");
} else {
    header("Location: ../index.php?err=There was an error while setting the boost points");
}