<?php require(__DIR__.'/../content/disablemobile.php'); ?>
<?php
require('content/verify.php');
$description = trim($_POST['description']);

// Check whether the description contains characters other than alphabets, numbers, spaces and !@#$%^&*()_+{}|:"<>?`-=[]\;',./
if (!preg_match('/^[a-zA-Z0-9 !@#$%^&*()_+{}|:"<>?`\-=\[\]\\;\',.\/\n\r]*$/', $description)) {
    // Send 400
    http_response_code(400);
}

// Check whether the description is not just spaces and remove starting and trailing spaces
if ($description == '') {
    // Send 400
    $description = null;
} else {
    $description = preg_replace('/[\r\n]{2,}/', "\n", $description);
    $description = nl2br($description);
}

// Update description in database
$qs = "UPDATE games SET description = :description WHERE g_id = :id";
$db->execute($qs, [':description' => $description, ':id' => $id]);