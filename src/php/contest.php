<?php

function in_array_recursive(mixed $needle, array $haystack, bool $strict): bool
{
    foreach ($haystack as $element) {
        if ($element === $needle) {
            return true;
        }

        $isFound = false;
        if (is_array($element)) {
            $isFound = in_array_recursive($needle, $element, $strict);
        }

        if ($isFound === true) {
            return true;
        }
    }

    return false;
}
function is_winner($id)
{
    $db = getDatabase();
    $result = $db->query("SELECT * FROM contest_winner WHERE g_id = :id", [
        ':id' => $id
    ]);
    if (isset($result[0][0])) {
        return true;
    }
    return false;
}
include('../database/connect.php');
session_start();
$output = "";
$a = $_POST['action'];
$day = date("w");
$lastContest = file_get_contents('../config/currentcontest.txt') - 1;
// Contest status, 0 = results, 1 = nominations, 2 = voting
if (is_winner($_POST['game_id'] ?? -1)) {
    $output .= '&is_winner=1';
    $output .= '&accepting_entries=0';
    die($output);
}

if ($day == 1 || $day == 2) {
    $status = 1;
} elseif ($day == 3 || $day == 4 || $day == 5) {
    $status = 2;
} else {
    $status = 0;
    $output .= '&accepting_entries=0';
    $output .= '&is_winner=0';
    $output .= '&complete=1';
}
if ($a == "status") {
    if ($status == 0) {
        $status = 0;
        $output .= '&accepting_entries=0';
        $output .= '&voting=0';
    } elseif ($status == 1) {
        $output .= '&accepting_entries=1';
        $id = $_POST['game_id'] ?? -1;
        $db = getDatabase();
        $result = $db->query("SELECT *
            FROM contest_nominations
            WHERE g_id = :id
            AND nominator_username = :username", [
            ':id' => $id,
            ':username' => $_SESSION['username'] ?? ''
        ]);
        if (count($result) > 0) {
            $output .= '&already_nominated=1';
        } else {
            $output .= '&can_nominate=1';
        }
    } elseif ($status == 2) {
        $output .= "&voting=1";
        $id = $_POST['game_id'] ?? -1;
        $db = getDatabase();
        $result = $db->query("SELECT * FROM contest_votes WHERE id = :id", [
            ':id' => $id,
        ]);
        if (count($result) > 0) {
            $result = $db->query("SELECT * FROM contest_voter_usernames WHERE voter_username = :username", [
                ':username' => $_SESSION['username'] ?? ''
            ]);
            if (isset($result[$id])) {
                $output .= '&already_voted=1';
            } else {
                if (count($result) >= 3) {
                    $output .= '&max_ballots_cast=1';
                } else {
                    $output .= '&can_vote=1';
                }
            }  
        } else {
            $output .= '&can_vote=0';
        }
    }

    $output .= '&lastContest=' . $lastContest;

    if (isset($_SESSION['username'])) {
        $output .= '&session=1';
    }
    //$output .= '&can_nominate=1';
} elseif ($a == "nominate") {
    if (!isset($_SESSION['username'])) {
        die('&success=false');
    }
    $id = $_POST['game_id'] ?? -1;

    $db = getDatabase();
    $result = $db->query("SELECT * FROM contest_nominations WHERE g_id = :id AND nominator_username = :username", [
        ':id' => $id,
        ':username' => $_SESSION['username'] ?? ''
    ]);

    if (count($result) > 0) {
        die('&success=false');
    } else {
        $db->execute("INSERT INTO contest_nominations (g_id, nominator_username) VALUES (:id, :username)", [
            ':id' => $id,
            ':username' => $_SESSION['username'] ?? ''
        ]);
    }

    $output .= '&success=true';
} elseif ($a == "vote") {
    if (!isset($_SESSION['username'])) {
        die('&success=false');
    }
    $id = $_POST['game_id'] ?? -1;

    $db = getDatabase();
    $result = $db->query("SELECT * FROM contest_votes WHERE id = :id", [
        ':id' => $id,
    ]);

    if (count($result) != 1) {
        die('&success=false');
    }

    $result = $db->query("SELECT * FROM contest_voter_usernames WHERE voter_username = :username", [
        ':username' => $_SESSION['username'] ?? '',
    ]);

    if (count($result) >= 3) {
        die('&success=false');
    }
    if ($result[0][0] == $id || $result[1][0] == $id || $result[2][0] == $id) {
        die("&success=false");
    } else {
        $db->execute("INSERT INTO contest_voter_usernames (id,voter_username) VALUES (:id, :username)", [
            ':id' => $id,
            ':username' => $_SESSION['username'] ?? ''
        ]);
        $db->execute("UPDATE contest_votes SET votes = votes + 1 WHERE id = :id", [
            ':id' => $id
        ]);
    }

    $output .= '&success=true';
}
echo $output;
