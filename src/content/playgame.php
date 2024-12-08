<?php
function get_game_info($game_id)
{
    require_once('../database/connect.php');
    $db = connectToDatabase();
    $qs = "SELECT * FROM games WHERE g_id=:game_id";
    $statement = $db->prepare($qs);
    $statement->execute([':game_id' => $game_id]);
    $result = $statement->fetchAll();
    return $result[0];
}
function get_creator_type($type, $g_swf)
{
    if ($type == 'name') {
        switch ($g_swf) {
            case '1':
                return 'classic';
                break;
            case '2':
                return 'platformer';
                break;
            case '3':
                return '3d adventure';
                break;
            case '5':
                return 'physics';
                break;
            case '7':
                return 'arcade';
                break;
        }
    } else {
        switch ($g_swf) {
            case '1':
                return 'shooter';
                break;
            case '2':
                return 'plat';
                break;
            case '3':
                return 'algo';
                break;
            case '5':
                return 'ppg';
                break;
            case '7':
                return 'arcade';
                break;
        }
    }
}
function get_swf_version($g_Swf)
{
    switch ($g_Swf) {
        case '1':
            return 'idk';
            break;
        case '2':
            return '20.swf?fix=4';
            break;
        case '3':
            return 'idk';
            break;
        case '5':
            return 'idk';
            break;
        case '7':
            return 'idk';
            break;
    }
}
$game = get_game_info($id);
if (!isset($game['title'])) {
    die("Invalid game ID");
}
$status = "playing";