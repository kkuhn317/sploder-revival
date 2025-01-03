<?php

error_reporting(E_ERROR);
ini_set('display_errors', 1);
session_start();
$a = $_GET['a'];
require_once('../database/connect.php');
$db = getDatabase();

function extracted(IDatabase $db): void
{
    require_once(__DIR__ . '/../content/timeelapsed.php');
    $venue = $_GET['v'];
    $page = $_GET['p'];
    $p = $_GET['p'];

    $fulltotal = $db->queryFirstColumn("SELECT count(*)
        FROM comments
        WHERE venue=:venue", 0, [
        ':venue' => $venue
        ]);
    $latestp = ceil($fulltotal / 10) - 1;
    if ($p == "-1") {
        $p = max(0, $latestp);
    }

    $result2 = $db->query("SELECT *
        FROM comments
        WHERE venue=:venue
        ORDER BY thread_id ASC
        LIMIT 10 OFFSET :p", [
        ':venue' => $venue,
        ':p' => ($p * 10)
        ]);

    $total = count($result2);
    echo '[{"action":"read","status":"1","id":"' . $venue . '","data":[';
    for ($i = 0; $i <= $total - 1; $i++) {
        $comment = $result2[i];
        echo '{"id":"' . $comment['id']
            . '","thread_id":"' . $comment['thread_id']
            . '","creator_name":"' . $comment['creator_name']
            . '","subject":"","body":"' . $comment['body']
            . '","visible":"1","score":"' . $comment['score']
            . '","date":"' . time_elapsed_string('@' . $comment['timestamp'])
            . '","timestamp":"' . $comment['timestamp'] . '"}';
        if ($i != $total - 1) {
            echo ",";
        }
    }

    echo '],"total":' . $fulltotal . ',"page":' . $p . '}]';
}

if ($a == "read") {
    extracted($db);
} elseif ($a == "post") {
    $posts = file_get_contents("php://input");
    $formatter = explode("&", $posts);
    $message = htmlspecialchars((urldecode(substr($formatter[0], 2))), ENT_QUOTES, "UTF-8", false);
    $reply = substr($formatter[2], 4);

    $venue = $_GET['v'];

    $msgid = ((int)$db->queryFirstColumn("SELECT MAX(id) FROM comments", 0)) + 1;
    if ($reply == 0) {
        $reply = $msgid;
    }
    $t = time();
    $score = 0;
    $creator_name = $_SESSION['username'];
    include_once('../content/checkban.php');
    if (checkBan($creator_name)) {
        // set header to 403 (forbidden) and echo a message
        http_response_code(403);
        die("You are banned and will not be able to send any comments.");
    }
    if ($creator_name != null) {
        $db->execute("INSERT INTO comments
            (venue,thread_id,creator_name,body,score,timestamp)
            VALUES (:venue,:thread_id,:creator_name,:body,:score,:timestamp)", [
            ':venue' => $venue,
            ':thread_id' => $reply,
            ':creator_name' => $creator_name,
            ':body' => $message,
            ':score' => $score,
            ':timestamp' => $t,
        ]);
        extracted($db);
    }
} elseif ($a == "like") {
    if ($_SESSION['username'] != null) {
        include_once('../content/checkban.php');
        if (checkBan($creator_name)) {
            // set header to 403 (forbidden) and echo a message
            http_response_code(403);
            die("You are banned and will not be able to send any comments.");
        }
        $posts = file_get_contents("php://input");
        $formatter = explode("&", $posts);
        $id = substr($formatter[0], 3);
        $cuser = $_SESSION['username'] . ',';

        // Has the user already voted down and is changing their vote?
        $result2->query("SELECT vote
            FROM comment_votes
            WHERE id=:id", [
            ':id' => $id
        ]);

        if (isset($result2[0]['vote']) && ($result2[0]['vote'] == -1)) {
            $statement->execute("UPDATE comment_votes
                SET vote=:vote
                WHERE id=:id
                AND username=:username", [
                ':id' => $id,
                ':username' => $_SESSION['username'],
                ':vote' => 1
                ]);

            $statement->execute("UPDATE comments
                SET score=score+2
                WHERE id=:id", [
                ':id' => $id
                ]);
        } elseif (!isset($result2[0]['vote'])) {
            $statement->execute("INSERT INTO comment_votes (id, username, vote) VALUES (:id, :username, :vote)", [
                ':id' => $id,
                ':username' => $_SESSION['username'],
                ':vote' => 1
            ]);

            $db->execute("UPDATE comments SET score=score+1 WHERE id=:id", [
                ':id' => $id
            ]);
        }
    }
} elseif ($a == "unlike") {
    if ($_SESSION['username'] != null) {
        include_once('../content/checkban.php');
        if (checkBan($creator_name)) {
            // set header to 403 (forbidden) and echo a message
            http_response_code(403);
            die("You are banned and will not be able to send any comments.");
        }

        // This seems dangerous
        $posts = file_get_contents("php://input");
        $formatter = explode("&", $posts);
        $id = substr($formatter[0], 3);
        $cuser = $_SESSION['username'] . ',';

        // Has the user already voted up and is changing their vote?
        $result2 = $db->query("SELECT vote FROM comment_votes WHERE id=:id", [
            ':id' => $id
        ]);

        if (isset($result2[0]['vote']) && ($result2[0]['vote'] == '1')) {
            $db->execute("UPDATE comment_votes SET vote=:vote WHERE id=:id AND username=:username", [
                ':id' => $id,
                ':username' => $_SESSION['username'],
                ':vote' => -1
            ]);

            $db->execute("UPDATE comments SET score=score-2 WHERE id=:id", [
                ':id' => $id
            ]);
        } elseif (!isset($result2[0]['vote'])) {
            $db->execute("INSERT INTO comment_votes
                (id, username, vote)
                VALUES (:id, :username, :vote)", [
                ':id' => $id,
                ':username' => $_SESSION['username'],
                ':vote' => 1
            ]);
            $db->execute("UPDATE comments SET score=score-1 WHERE id=:id", [
                ':id' => $id
            ]);
        }
    }
} elseif ($a == "delete") {
    // TODO: This seems dangerous without sanitization
    $posts = file_get_contents("php://input");
    $formatter = explode("&", $posts);
    $id = substr($formatter[0], 3);

    $result2 = db->execute("SELECT creator_name, venue FROM comments WHERE id=:id", [
        ':id' => $id
    ]);

    if ($_SESSION['username'] != $result2[0]['creator_name']) {
        $queriedAuthor = $db->queryFirstColumn("SELECT author FROM games WHERE g_id=:g_id", 0, [
            ':g_id' => substr($result2[0]['venue'], 5)
        ]);

        if ($_SESSION['username'] != $queriedAuthor) {
            die("Malicious Request Detected");
        }
    }
    if ($_SESSION['username'] == $result2[0]['creator_name'] || $_SESSION['username'] == $queriedAuthor) {
        $db->execute("DELETE FROM comments WHERE id=:id", [
            ':id' => $id
        ]);
        extracted($db);
    }
}
