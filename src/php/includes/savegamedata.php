<?php

session_id($_GET['PHPSESSID']);
session_start();
if (isset($_SESSION['PHPSESSID'])) { // session ID is valid and exists
    include('../content/checkban.php');
    if (checkBan($_SESSION['username'])) {
        die('<message result="failed" message="You are banned and will not be able to publish games."/>');
    }
    $xml = $_POST['xml'] ?? file_get_contents('php://input');
    $xml2 = simplexml_load_string(strval($xml)) or die("INVALID XML FILE!!");
    if(!isset($_GET['comments']) || !isset($_GET['private'])){
        die('<message result="failed" message="Please save your game again."/>');
    }
    $author = $_SESSION['username'];
    $comments = $_GET['comments'];
    $private = $_GET['private'];
    $ispublished = 1;
    $id = (int)filter_var($_GET['projid'], FILTER_SANITIZE_NUMBER_INT);

    require_once('../database/connect.php');
    $db = getDatabase();

    require_once('../repositories/repositorymanager.php');
    $gameRepository = RepositoryManager::get()->getGameRepository();


    if (!$gameRepository->verifyOwnership($id, $_SESSION['username'])) {
        http_response_code(403);
        die('<message result="failed" message="You do not own this game!"/>');
    }
    
    $currentDate = date("Y-m-d H:i:s");
    
    $db->execute("UPDATE games
        SET ispublished = :ispublished, 
            isprivate = :isprivate, 
            comments = :comments, 
            first_published_date = CASE 
                WHEN ispublished = 0 THEN :current_date 
                ELSE first_published_date 
            END,
            last_published_date = :current_date,
            date = :current_date
        WHERE g_id = :id", [
        ':ispublished' => $ispublished,
        ':isprivate' => $private,
        ':comments' => $comments,
        ':current_date' => $currentDate,
        ':id' => $id
    ]);
    $project_path = "../users/user" . $_SESSION['userid'] . "/projects/proj" . $id . "/";
    file_put_contents($project_path . "game.xml", $xml);
    echo '<message result="success" id="proj' . $id . '" pubkey="' . $_SESSION['userid'] . '_' . $id . '" message="Success"/>';
} else {
    echo '<message result="failed" message="The session ID is incorrect! Log out and log in again."/>';
}