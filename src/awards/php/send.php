<?php

require_once(__DIR__ . '/../../content/logincheck.php');
require_once(__DIR__ . '/../../database/connect.php');

// Get award data

$data = $_GET['c'];
// Explode - in data
$data = explode("-", $data);
$style = $data[2];
$material = $data[1];
$color = $data[3];
$icon = $data[5];
$category = $_GET['category'];
$membername = $_GET['membername'];
// Vatlidate category
$valid_categories = [
    "", "Challenge", "Fun", "Puzzle", "Action", "Art", "Design",
    "Story", "Craftsman", "Guru", "Player", "Friend", "Respect"
];

if ($membername == $_SESSION['username']) {
    header("Location: ../index.php?err=you");
    die();
}

// Validate category
if (!in_array($category, $valid_categories)) {
    die("Haxxor detected");
}

$message = $_GET['message'];

// If message is over 40 characters, die

if (strlen($message) > 40) {
    die("Haxxor detected");
}
// Validation complete for award date, include main functions
require_once('functions.php');

// Start eligibility validation
require_once('../repositories/repositorymanager.php');

$userRepository = RepositoryManager::get()->getUserRepository();
$level = $userRepository->getLevelByUserId($_SESSION['userid']);
$isEditor = isEditor();
$maxCustomization = getMaxCustomization($level, $isEditor)[1];

if ($level < 10) {
    header("Location: ../index.php");
    die();
}
if (maxAward($level) <= 0) {
    header("Location: ../index.php");
    die();
}

// If style, material, color, and icon are not less than or equal to maxCustomization, die
if ($style > $maxCustomization || $material > $maxCustomization || $color > $maxCustomization || $icon > $maxCustomization) {
    die("Haxxor detected");
}

// Check whether membername is an actual member

$db = getDatabase();
$result = $db->query("SELECT username
    FROM members
    WHERE username = :username", [
        ':username' => $membername
    ]);

if (count($result) == 0) {
    header("Location: ../awards/index.php?err=no");
    die();
}


// Check whether user has already sent an award to membername
$result = $db->query("SELECT username
    FROM award_requests
    WHERE username = :username
    AND membername = :membername", [
        ':username' => $_SESSION['username'],
        ':membername' => $membername]);

if (count($result) > 0) {
    header("Location: ../awards/index.php?err=sent");
    die();
}

reduceAward();

// Send award
$db->execute("INSERT INTO award_requests
    (username, membername, level, category, style, material, icon, color, message)
    VALUES (:username, :membername, :level, :category, :style, :material, :icon, :color, :message)", [
    ':username' => $_SESSION['username'],
    ':membername' => $membername,
    ':level' => $level,
    ':category' => $category,
    ':style' => $style,
    ':material' => $material,
    ':icon' => $icon,
    ':color' => $color,
    ':message' => $message
    ]);

header("Location: ../index.php");
