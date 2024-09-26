<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
 echo "Service  Running!\n";

$day = date("w");

// Day of the week
 echo "Day of the week: ".date("w\-l")."\n";
 // Save current contest...
if($day == 1) {
    $file = '../config/currentcontest.txt';
// Get current contest
    $current = file_get_contents($file);
    $updated = $current + 1;
    // Save contest data
    file_put_contents($file, $updated);
} elseif($day == 3) {
    include('../database/connect.php');
    $db = connectToDatabase();
    $qs = "
    INSERT INTO contest_votes (id)
    SELECT g_id
    FROM contest_nominations
    GROUP BY g_id
    ORDER BY COUNT(*) DESC
    LIMIT 32;
    ";
    $statement = $db->prepare($qs);
    $statement->execute();
    $qs = "DELETE FROM contest_nominations";
    $statement = $db->prepare($qs);
    $statement->execute();


} elseif($day == 6){
    include('../database/connect.php');
    $db =  connectToDatabase();
    $qs = "
    INSERT INTO contest_winner (g_id, contest_id)
    SELECT id, :contest_id
    FROM contest_votes
    ORDER BY votes DESC
    LIMIT 1;
    ";
    $statement = $db->prepare($qs);
    $statement->execute(
        [
            ':contest_id' => file_get_contents('../config/currentcontest.txt')
        ]
    );
    $qs = "DELETE FROM contest_votes";
    $statement = $db->prepare($qs);
    $statement->execute();
    $qs = "DELETE FROM contest_voter_usernames";
    $statement = $db->prepare($qs);
    $statement->execute();

}