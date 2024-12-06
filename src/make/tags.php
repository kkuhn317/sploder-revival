<?php
require('content/verify.php');
$tags = explode(" ", $_POST['tags']);
// Check whether each tag is valid
// For a tag to be valid, it must be less than 30 characters long
// It also must have only letters and numbers
foreach ($tags as $tag) {
    // If a tag is empty, remove it from the array
    if ($tag == '') {
        $tags = array_diff($tags, [$tag]);
        continue;
    }
    if (strlen($tag) > 30) {
        // Send 400
        http_response_code(400);
    }
    if (!preg_match('/^[a-zA-Z0-9]*$/', $tag)) {
        // Send 400
        http_response_code(400);
    }
}
// There must not be more than 25 tags
if (count($tags) > 25) {
    // Send 400
    http_response_code(400);
}

// Update tags in database

$qs = "DELETE FROM game_tags WHERE g_id = :id";
$db->execute($qs, [':id' => $id]);

// Add tags to database in the format of g_id, tag where tag is 1 tag only

if (!empty($tags)) {
    $values = [];
    $params = [':id' => $id];
    foreach ($tags as $index => $tag) {
        $values[] = "(:id, :tag$index)";
        $params[":tag$index"] = $tag;
    }
    $qs = "INSERT INTO game_tags (g_id, tag) VALUES " . implode(", ", $values);
    $db->execute($qs, $params);
}