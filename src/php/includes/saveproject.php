<?php

function saveProject(int $g_swf): int
{
    ini_set('display_errors', '1');
    ini_set('display_startup_errors', '1');
    error_reporting(E_ALL);
    session_start();
    if (isset($_SESSION['PHPSESSID'])) { // session ID is valid and exists
        $xml = $_POST['xml'] ?? file_get_contents('php://input');
        $xml2 = simplexml_load_string(strval($xml)) or die("INVALID XML FILE!!");
        $author = $_SESSION['username'];
        $ispublished = 0;
        $id = 0;
        $new_game = false;
        include('../database/connect.php');
        $db = getDatabase();
        if (isset($_GET['projid'])) {
            $id = (int)filter_var($_GET['projid'], FILTER_SANITIZE_NUMBER_INT);
        }
        if (!isset($_GET['projid'])) {
            $title = urldecode($xml2->attributes()['title']);
            if($g_swf == 1){
                $title = urldecode($title); // Screw you geoff
                $xml2->attributes()['title'] = $title; // Fix the title in the XML as well... Screw geoff again!!
            }
            $qs = "INSERT INTO games (author, user_id, title, date, description, g_swf, ispublished, isdeleted, isprivate, comments) 
                VALUES (:username, :user_id, :title, :date, :description, :g_swf, :ispublished, :isdeleted, :isprivate, :comments)
                RETURNING g_id;";
            $id = $db->queryFirstColumn($qs, 0, [
                ':username' => $author,
                ':title' => $title,
                ':date' => date("Y-m-d"),
                ':description' => null,
                ':g_swf' => $g_swf,
                ':ispublished' => $ispublished,
                ':isdeleted' => 0,
                ':isprivate' => 1,
                ':comments' => 0,
                ':user_id' => $_SESSION['userid']
            ]);
            $new_game = true;
            // Set xml2 "id" attribute to the new game id
            $xml2->attributes()['id'] = $id;
            // Save XML to $xml
            $xml = $xml2->asXML();
        } else {
            require_once('../repositories/repositorymanager.php');
            $gameRepository = RepositoryManager::get()->getGameRepository();


            if (!$gameRepository->verifyOwnership($id, $_SESSION['username'])) {
                http_response_code(403);
                die('<message result="failed" message="You do not own this game!"/>');
            }
        }


        $project_path = "../users/user" . $_SESSION['userid'] . "/projects/proj" . $id . "/";
        if ($new_game) {
            mkdir($project_path, 0777, true);
            mkdir("../users/user" . $_SESSION['userid'] . "/images/proj" . $id . "/", 0777, true);
        }
        file_put_contents($project_path . "unpublished.xml", $xml);
        echo '<message result="success" id="proj' . $id . '" message="Project file saved."/>';
    } else {
        echo '<message result="failed" message="The session ID is incorrect! Log out and log in again."/>';
    }
    return $id;
}