<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$db1 = new PDO('sqlite:../database/members.db');
$thing = "SELECT username,perms FROM members WHERE perms IS NOT NULL";
$thing2 = $db1->prepare($thing);
$thing2->execute();
$bp = $thing2->fetchAll();
for($i=0;$i<count($bp);$i++){
    echo $bp[$i]['username'];
    if($i!=count($bp)-1){
        echo ",";
    }
}

?>