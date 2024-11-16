<?php
session_start();
session_destroy();
error_reporting(E_ALL);
ini_set('display_errors', 1);
$username = $_POST['username'];
$password = $_POST['password'];

include('../database/connect.php');
$db = connectToDatabase('members');
$qs2 = "SELECT password,userid FROM members WHERE username=:user LIMIT 1";
$statement2 = $db->prepare($qs2);
$statement2->execute(
    [
        ':user' => mb_strtolower($username)
    ]
);
$result3 = $statement2->fetchAll();
if($hashed = password_verify($password, $result3[0]['password'])){

session_start();
$_SESSION['loggedin']="true";
$_SESSION['username']=mb_strtolower($username);
$_SESSION['userid']=$result3[0]['userid'];
session_regenerate_id();
$_SESSION['PHPSESSID'] = session_id();
$t=time();
include('getip.php');
$ip = getVisitorIp();
$qs = "UPDATE members SET ip_address=:ip_address WHERE username=:username";
$statement = $db->prepare($qs);
$statement->execute([
    ':ip_address'=>$ip,
    ':username'=>$_SESSION['username']
]);
$qs = "UPDATE members SET lastlogin=:t WHERE username=:username";
$statement = $db->prepare($qs);
$statement->execute([
    ':t'=>$t,
    ':username'=>$_SESSION['username']
]);
$qs = "UPDATE members SET status='online' WHERE username=:username";
$statement = $db->prepare($qs);
$statement->execute([
    ':username'=>$_SESSION['username']
]);
$qs = "UPDATE members SET lastpagechange=:t WHERE username=:username";
$statement = $db->prepare($qs);
$statement->execute([
    ':t'=>$t,
    ':username'=>$_SESSION['username']
]);
header('Location: ../accounts/account.php');
} else {
    header('Location: login.php?err=no');

}
?>