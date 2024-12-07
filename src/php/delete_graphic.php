<?php
session_start();
header('Content-Type: text/xml');
$id = (int)filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);
require_once('../database/connect.php');
$db = getDatabase();
$qs2 = "SELECT userid FROM graphics WHERE id=:id";
$userid = $db->queryFirstColumn($qs2,0,[':id' => $id
]);
$qs = "DELETE FROM graphics WHERE id=:id";
if ($_SESSION["userid"] == $userid) {
    $db->execute($qs,[':id' => $id]);
    // Delete files
    $file1 = '../graphics/gif/' . $id . '.gif';
    $file2 = '../graphics/png/' . $id . '_'; // All files with this prefix
    $files = glob($file2 . '*');
    $file3 = '../graphics/prj/' . $id . '.prj';
    foreach ($files as $file) {
        unlink($file);
    }
    unlink($file1);
    unlink($file3);
    
    header('Location: ../dashboard/my-graphics.php');
} else {
    echo "There was an error while deleting your graphic.";
}