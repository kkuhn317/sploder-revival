<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);
session_id($_GET['PHPSESSID']);
session_start();
header("Content-type: text/xml");
if (isset($_SESSION['PHPSESSID'])) { // session ID is valid and exists
    $author = $_SESSION["username"];
    $num = $_GET['num'] ?? 10;
    $start = $_GET['start'] ?? 0;
    $version = $_GET['version'] ?? 1;
    if(in_array(
        $version,
        ["5", "3", "7"]
    )){
        $newFormat = true;
    } else {
        $newFormat = false;
    }
    require_once('../database/connect.php');
    $db = getDatabase();
    $queryString = 'SELECT * FROM games WHERE author = :author AND g_swf = :g_swf AND isdeleted = :isdeleted ORDER BY g_id DESC';
    $params = [
        ':g_swf' => $version,
        ':author' => $author,
        ':isdeleted' => '0'
    ];

    if ($newFormat) {
        $queryString .= " LIMIT :num OFFSET :start";
        $params[':start'] = $start;
        $params[':num'] = $num;
    }

    $result = $db->query($queryString, $params);
    //print_r($result);
    $resultTotal = count($result);
    $totalGames = $db->queryFirstColumn("SELECT COUNT(g_id)
        FROM games
        WHERE author= :author
        AND g_swf = :g_swf
        AND isdeleted = :isdeleted", 0, [
        ':g_swf' => $version,
        ':author' => $author,
        ':isdeleted' => '0'
        ]);

    $f = '20';
    if ($newFormat) {
        $string = '<projects total="' . $totalGames . '" start="' . $start . '" num="' . $num . '">';
    } else {
        $num = $resultTotal;
        $string = '<projects total="' . $totalGames . '">';
    }

    foreach ($result as $project) {
        $string .= '<project id="proj' . $project['g_id'] . '" src="proj' . $project['g_id'] . '.xml" title="' . $project['title'] . '" date="' . date("l, F jS, Y", strtotime($f . $project['date'])) . '" time="' . strtotime($f . $project['date']) . '" archived="0" />';
    }
    $string .= '</projects>';
    print($string);
} else {
    if ($version == "7") {
        echo '<projects total="1" start="0" num="10"><project id="notset" src="notset" title="No demo games for you mwahahaha" time="157774694400" archived="0"/></projects>';
    } elseif ($_GET['PHPSESSID'] != "demo") {
        echo '<projects total="1" start="0" num="10"><project id="notset" src="notset" title="The session ID is incorrect!" date="Log out and log in again." archived="0"/></projects>';
    } else {
        echo '<projects total="1" start="0" num="10"><project id="notset" src="notset" title="The creator is in demo mode!" date="Loading is disabled." archived="0"/></projects>';
    }
}
