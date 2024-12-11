<?php

header('Content-Type: application/xml');
session_start();
$start = $_GET['start']; // Offset from this value
$num = $_GET['num']; // LIMIT to this value

require('../database/connect.php');

$db = getDatabase();

$clause = "ispublished=true AND isprivate=false";
$extrainfo = "";
$params = [
    ':num' => $num,
    ':start' => $start
];

if ($_GET['userid'] == $_SESSION['userid']) {
    $clause = isset($_GET['published']) ? "ispublished=true" : "1=1";
    $params[':userid'] = $_SESSION['userid'];
    $clause .= " AND userid=:userid";
} elseif($_GET['userid']==0) {
    if(isset($_GET['searchmode'])){
        $searchmode = $_GET['searchmode'];
        $searchterm = $_GET['searchterm'];
        if($searchmode == "users"){
            // Search by username according to corresponding userid
            $qs = "SELECT userid FROM members WHERE username=:username";
            $userid = $db->queryFirstColumn($qs, 0, [':username' => $searchterm]);
            $clause .= " AND userid=:userid";
            $params[':userid'] = $userid;
        } else {
            // Search by tags
            $qs = "SELECT g_id FROM graphic_tags WHERE SIMILARITY(tag, :tag) > 0.3";
            $g_ids = $db->queryFirst($qs, [':tag' => $searchterm]);
            // Remove duplicate keys in g_ids
            $g_ids = array_unique($g_ids);
            if (!empty($g_ids)) {
                $g_ids = array_values($g_ids); // Extract values from associative array
                $clause .= " AND id IN (".implode(",", array_map('intval', $g_ids)).")";
            } else {
                $clause .= " AND 1=0"; // No results found
            }
        }
    }
    $extrainfo = ""; // Use later to grab likes
}

$graphics_qs = "SELECT id,version$extrainfo FROM graphics WHERE $clause ORDER BY id DESC LIMIT :num OFFSET :start";
$graphicsData = $db->query($graphics_qs, $params);
$graphicsData = array_map(function($graphic) {
    return array_filter($graphic, function($key) {
        return !is_numeric($key);
    }, ARRAY_FILTER_USE_KEY);
}, $graphicsData);
unset($params[':num']);
unset($params[':start']);
$total = $db->queryFirstColumn("SELECT COUNT(*) FROM graphics WHERE $clause", 0,$params);

$xml = new SimpleXMLElement('<graphics/>');
$xml->addAttribute('start', $start);
$xml->addAttribute('num', $num);
$xml->addAttribute('total', $total);

foreach ($graphicsData as $graphic) {
    $g = $xml->addChild('g');
    foreach ($graphic as $key => $value) {
        $g->addAttribute($key, $value);
    }
}


echo $xml->asXML();