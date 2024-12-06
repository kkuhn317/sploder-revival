<?php
header('Content-Type: application/xml');
session_start();
$start = $_GET['start']; // Offset from this value
$num = $_GET['num']; // LIMIT to this value

require('../database/connect.php');

$db = getDatabase();
$total = $db->queryFirstColumn('SELECT COUNT(*) FROM graphics',0);

$xml = new SimpleXMLElement('<graphics/>');
$xml->addAttribute('start', $start);
$xml->addAttribute('num', $num);
$xml->addAttribute('total', $total);

if($_GET['userid'] == $_SESSION['userid']){
$graphics_qs = "SELECT id,version FROM graphics WHERE userid=:userid ORDER BY id DESC LIMIT :num OFFSET :start";
$graphicsData = $db->query($graphics_qs, [
    ':num' => $num,
    ':start' => $start,
    ':userid' => $_SESSION['userid']
]);
}
foreach ($graphicsData as $graphic) {
    $g = $xml->addChild('g');
    foreach ($graphic as $key => $value) {
        $g->addAttribute($key, $value);
    }
}


echo $xml->asXML();
