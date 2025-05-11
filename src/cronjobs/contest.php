<?php

echo "Service  Running!\n";

require_once('../database/connect.php');
$db = getDatabase();

$day = date("w");
// Day of the week
 echo "Day of the week: " . date("w\-l") . "\n";
 // Save current contest...
if ($day == 1) {
    $file = '../config/currentcontest.txt';
// Get current contest
    $current = file_get_contents($file);
    $updated = $current + 1;
    // Save contest data
    file_put_contents($file, $updated);
} elseif ($day == 3) {
    $db->execute("INSERT INTO contest_votes (id)
    SELECT g_id
    FROM contest_nominations
    GROUP BY g_id
    ORDER BY COUNT(*) DESC
    LIMIT 32;");
    $db->execute("DELETE FROM contest_nominations");
} elseif ($day == 6) {
    $db->execute("INSERT INTO contest_winner (g_id, contest_id)
    SELECT id, :contest_id
    FROM contest_votes
    ORDER BY votes DESC
    LIMIT 1;", [
      ':contest_id' => file_get_contents('../config/currentcontest.txt')
    ]);
    $db->execute("DELETE FROM contest_votes");
    $db->execute("DELETE FROM contest_voter_usernames");
}
