<?php

session_start();
$a = $_GET['a'];
require_once('../database/connect.php');
$db = getDatabase();
$creator_name = $_SESSION['username'] ?? null;
function extracted(IDatabase $db): string
{
    require_once(__DIR__ . '/../content/timeelapsed.php');
    $venue = $_GET['v'];
    $page = $_GET['p'];
    $p = $_GET['p'];

    if ($venue == "dashboard") {
        // Store the dashboard subquery in a variable to avoid duplication
        $dashboardSubquery = "
            SELECT c.id AS thread_id, c.id, c.venue, c.creator_name, c.body, c.score, c.timestamp
            FROM comments c
            WHERE (c.venue LIKE '%-' || :username OR c.body LIKE '%@'|| :username || ' %')
            AND c.creator_name != :username

            UNION

            SELECT c.id AS thread_id, c.id, c.venue, c.creator_name, c.body, c.score, c.timestamp
            FROM comments c
            WHERE c.venue LIKE 'review-%'
              AND EXISTS (
                  SELECT 1 FROM reviews r
                  WHERE r.review_id = CAST(SUBSTRING(c.venue FROM 'review-([0-9]+)') AS INTEGER)
                    AND r.userid = :userid
              )
              AND c.creator_name != :username

            UNION

            SELECT c.id AS thread_id, c.id, c.venue, c.creator_name, c.body, c.score, c.timestamp
            FROM comments c
            WHERE c.thread_id IN (
                SELECT id 
                FROM comments 
                WHERE creator_name = :username 
                AND thread_id = id
            ) AND c.creator_name != :username
        ";
        $fulltotal = $db->queryFirstColumn(
            "SELECT COUNT(*) FROM ( $dashboardSubquery ) AS combined_results",
            0,
            [
                ':username' => $_SESSION['username'],
                ':userid' => $_SESSION['userid']
            ]
        );
    } else if ($venue == "allmsgs") {
    $extra = $_GET['o'];
    $filter = explode("-", $extra);

    $params = [];
    $clause = "WHERE venue != 'staff-page' ";

    if ($filter[0] == "creator") {
        $clause .= "AND creator_name = :creator_name";
        $params[':creator_name'] = $filter[1];
    } else if ($filter[0] == "owned") {
        $clause .= "AND venue LIKE '%-' || :owned";
        $params[':owned'] = $filter[1];
    }


    // Only pass necessary params for queryFirstColumn()
    $fulltotal = $db->queryFirstColumn(
        "SELECT COUNT(*)
         FROM comments c
         LEFT JOIN games g ON (
            c.venue LIKE 'game-%' 
            AND c.venue ~ '_[0-9]+-'
            AND CAST(SUBSTRING(c.venue FROM '_([0-9]+)-') AS INTEGER) = g.g_id
         )
         LEFT JOIN reviews r ON (
            c.venue LIKE 'review-%'
            AND CAST(SUBSTRING(c.venue FROM 'review-([0-9]+)') AS INTEGER) = r.review_id
         )
         $clause
         AND (
            (c.venue NOT LIKE 'game-%' AND c.venue NOT LIKE 'review-%')
            OR (g.g_id IS NOT NULL AND g.ispublished = 1 AND g.isprivate = 0 AND g.isdeleted = 0 AND c.venue LIKE 'game-%')
            OR (r.review_id IS NOT NULL AND r.ispublished = true AND r.g_id IS NOT NULL AND c.venue LIKE 'review-%' AND EXISTS (SELECT 1 FROM games g2 WHERE g2.g_id = r.g_id AND g2.ispublished = 1 AND g2.isprivate = 0 AND g2.isdeleted = 0))
         )", 0,
        $params
    );


}

 else {
        $fulltotal = $db->queryFirstColumn("SELECT count(*)
            FROM comments
            WHERE venue=:venue", 0, [
            ':venue' => $venue
            ]);
    }
    $latestp = ceil($fulltotal / 10) - 1;
    if ($p == "-1") {
        $p = max(0, $latestp);
    }
    if ($venue == "dashboard") {
        $result2 = $db->query(
            "SELECT * FROM ( $dashboardSubquery ) AS combined_results
            ORDER BY combined_results.thread_id DESC, combined_results.id ASC
            LIMIT 10 OFFSET :p",
            [
                ':username' => $_SESSION['username'],
                ':userid' => $_SESSION['userid'],
                ':p' => ($p * 10)
            ]
        );
    } else if ($venue == "allmsgs") {
        // Add :p only for the actual query
$perPage = 50;
$latestp = ceil($fulltotal / $perPage) - 1;
$params[':p'] = max(0, ($latestp - $p) * $perPage);
$p = $params[':p'];

    $result2 = $db->query("SELECT c.* 
    FROM comments c
    LEFT JOIN games g ON (
        c.venue LIKE 'game-%' 
        AND c.venue ~ '_[0-9]+-'
        AND CAST(SUBSTRING(c.venue FROM '_([0-9]+)-') AS INTEGER) = g.g_id
    )
    LEFT JOIN reviews r ON (
        c.venue LIKE 'review-%'
        AND CAST(SUBSTRING(c.venue FROM 'review-([0-9]+)') AS INTEGER) = r.review_id
    )
    ".$clause."
    AND (
        (c.venue NOT LIKE 'game-%' AND c.venue NOT LIKE 'review-%')
        OR (g.g_id IS NOT NULL AND g.ispublished = 1 AND g.isprivate = 0 AND g.isdeleted = 0 AND c.venue LIKE 'game-%')
        OR (r.review_id IS NOT NULL AND r.ispublished = true AND r.g_id IS NOT NULL AND c.venue LIKE 'review-%' AND EXISTS (SELECT 1 FROM games g2 WHERE g2.g_id = r.g_id AND g2.ispublished = 1 AND g2.isprivate = 0 AND g2.isdeleted = 0))
    )
    ORDER BY c.thread_id DESC, c.id ASC
    LIMIT $perPage OFFSET :p", $params);
    } else {
        $result2 = $db->query("SELECT *
            FROM comments
            WHERE venue=:venue
            ORDER BY thread_id ASC, id ASC
            LIMIT 10 OFFSET :p", [
            ':venue' => $venue,
            ':p' => ($p * 10)
            ]);
    }

    $data = [];
    foreach ($result2 as $comment) {

        $data[] = [
            'id'            => $comment['id'],
            'thread_id'     => $comment['thread_id'],
            'creator_name'  => $comment['creator_name'],
            'subject'       => '',
            'venue'         => $comment['venue'],
            'body'          => $comment['body'],
            'visible'       => '1',
            'score'         => $comment['score'],
            'date'          => time_elapsed_string('@' . $comment['timestamp']),
            'timestamp'     => $comment['timestamp']
        ];
    }

    $response = [
        [
            'action' => 'read',
            'status' => '1',
            'id'     => $venue,
            'data'   => $data,
            'total'  => $fulltotal,
            'page'   => $p
        ]
    ];

    return json_encode($response);
    }

if ($a == "read") {
    echo extracted($db);
} elseif ($a == "post") {
    $posts = file_get_contents("php://input");
    $formatter = explode("&", $posts);
    require_once('../content/censor.php');
    require_once('../content/keyboardfilter.php');
    $rawMessage = substr($formatter[0], 2);
    $filteredMessage = filterKeyboard(trim($rawMessage));
    // Enforce message length: >8 and <500 characters
    if ($filteredMessage=='' || mb_strlen($filteredMessage) <= 8 || mb_strlen($filteredMessage) >= 500) {
        http_response_code(400);
        die("Message must be greater than 8 and less than 500 characters.");
    }
    $message = htmlspecialchars(censorText($filteredMessage), ENT_QUOTES, "UTF-8", false);
    $reply = substr($formatter[2], 4);

    $venue = $_GET['v'];

    $msgid = ((int)$db->queryFirstColumn("SELECT MAX(id) FROM comments", 0)) + 1;
    if ($reply == 0) {
        $reply = $msgid;
    }
    $t = time();
    $score = 0;
    // Check if the venue is a game
    $venueParts = explode('-', $venue);
    if ($venueParts[0] == 'game') {
        require('../repositories/repositorymanager.php');
        $gameRepository = RepositoryManager::get()->getGameRepository();
        $gameId = explode("_",$venueParts[1])[1];
        $allowComment = $gameRepository->allowComment($gameId);
        if (!$allowComment) {
            http_response_code(403);
            die("Comments are not allowed for this game.");
        }
    }
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
        echo extracted($db);
    }
} elseif ($a == "like") {
    if ($_SESSION['username'] != null) {
        include_once('../content/checkban.php');
        if (checkBan($creator_name)) {
            http_response_code(403);
            die("You are banned and will not be able to send any comments.");
        }
        $posts = file_get_contents("php://input");
        $formatter = explode("&", $posts);
        $id = substr($formatter[0], 3);
        $cuser = $_SESSION['username'] . ',';

        // Has the user already voted down and is changing their vote?
        $result2 = $db->query("SELECT vote FROM comment_votes WHERE id=:id", [
            ':id' => $id
        ]);

        if (isset($result2[0]['vote']) && ($result2[0]['vote'] == -1)) {
            $db->execute("UPDATE comment_votes
                SET vote=:vote
                WHERE id=:id
                AND username=:username", [
                ':id' => $id,
                ':username' => $_SESSION['username'],
                ':vote' => 1
            ]);

            $db->execute("UPDATE comments
                SET score=score+2
                WHERE id=:id", [
                ':id' => $id
            ]);
        } elseif (!isset($result2[0]['vote'])) {
            $db->execute("INSERT INTO comment_votes (id, username, vote) VALUES (:id, :username, :vote)", [
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
                ':vote' => -1
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

    $result2 = $db->queryFirst("SELECT creator_name, venue FROM comments WHERE id=:id", [
        ':id' => $id
    ]);
    if ($_SESSION['username'] != $result2['creator_name']) {
        $queriedAuthor = $db->queryFirstColumn("SELECT author FROM games WHERE g_id=:g_id", 0, [
            ':g_id' => substr($result2['venue'], 5)
        ]);

        if ($_SESSION['username'] != $queriedAuthor) {
            die("Malicious Request Detected");
        }
    }
    if ($_SESSION['username'] == $result2['creator_name'] || $_SESSION['username'] == $queriedAuthor) {
        $db->execute("
            DELETE FROM comments
            WHERE
                (thread_id = :id AND :id = (SELECT thread_id FROM comments WHERE id = :id))
                OR
                (id = :id AND :id != (SELECT thread_id FROM comments WHERE id = :id))
        ", [
            ':id' => $id
        ]);
        echo extracted($db);
    }
}
