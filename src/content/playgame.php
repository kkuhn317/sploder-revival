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
enum CreatorTypeName: string
{
    case SHOOTER = 'classic';
    case PLATFORMER = 'platformer';
    case ALGORITHM = '3d adventure';
    case PHYSICS = 'physics';
    case ARCADE = 'arcade';
}

enum CreatorTypeURL: string
{
    case SHOOTER = 'shooter';
    case PLATFORMER = 'plat';
    case ALGORITHM = 'algo';
    case PHYSICS = 'ppg';
    case ARCADE = 'arcade';
}

function get_creator_type($type, $g_swf)
{
    $mapping = [
        '1' => ['name' => CreatorTypeName::SHOOTER, 'url' => CreatorTypeURL::SHOOTER],
        '2' => ['name' => CreatorTypeName::PLATFORMER, 'url' => CreatorTypeURL::PLATFORMER],
        '3' => ['name' => CreatorTypeName::ALGORITHM, 'url' => CreatorTypeURL::ALGORITHM],
        '5' => ['name' => CreatorTypeName::PHYSICS, 'url' => CreatorTypeURL::PHYSICS],
        '7' => ['name' => CreatorTypeName::ARCADE, 'url' => CreatorTypeURL::ARCADE],
    ];

    return $mapping[$g_swf][$type]->value;
}
enum SwfVersion: string
{
    case IDK = 'idk';
    case VERSION_20 = '20';
}
function get_swf_version($g_Swf)
{
    return match ($g_Swf) {
        1, 3, 5, 7 => SwfVersion::IDK->value,
        2 => SwfVersion::VERSION_20->value,
    };
}
$game = get_game_info($id);
if (!isset($game['title'])) {
    die("Invalid game ID");
}
$status = "playing";