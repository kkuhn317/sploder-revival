<?php

include('verify.php');
$username = $_POST['username'];
$reason = $_POST['reason'];
$banned_by = $_SESSION['username'];
$bandate = time();
$autounbandate = time() + $_POST['time']*24*60*60;


// Check whether the user exists
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

// Check whether user banned is not a moderator
$sql = "SELECT perms FROM members WHERE username=:username";
$statement = $db->prepare($sql);
$statement->execute([
    ':username'=>$username
]);
$perms = $statement->fetch();
if(str_contains($perms['perms'], 'M')){
    header("Location: ../index.php?err=You cannot ban a moderator");
    die();
}
$sql = "INSERT INTO banned_members (username, banned_by, reason, bandate, autounbandate) VALUES (:username, :banned_by, :reason, :bandate, :autounbandate)";
$statement = $db->prepare($sql);
if($statement->execute([
    ':username'=>$username,
    ':banned_by'=>$banned_by,
    ':reason'=>$reason,
    ':bandate'=>$bandate,
    ':autounbandate'=>$autounbandate
])){
    header("Location: ../index.php?msg=User banned successfully");
} else {
    header("Location: ../index.php?err=There was an error while banning the user");
}