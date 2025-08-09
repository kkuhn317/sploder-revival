<?php
session_start();

require_once(__DIR__.'/../repositories/repositorymanager.php');
$userRepository = RepositoryManager::get()->getUserRepository();

$data = explode("-", $_GET['c']);
$type = $data[0];
$skins = $data[2];
$mouths = $data[3];
$noses = $data[5];
$eyes = $data[7];
$hairs = $data[9];
$extrasch = $data[11];
$skinc = $data[1];
$eyec = $data[8];
$hairc = $data[10];
$extrasco = $data[12];

if ($type == 'premium') {
    // If the user has a premium avatar for less than 15 minutes, they can edit it for free
    if (isset($_SESSION['premium_avatar']) && time() - $_SESSION['premium_avatar'] < 15*60) {
        // Allow editing for free
    } else {
        $boostPoints = $userRepository->getBoostPoints($_SESSION['userid']);
        if ($boostPoints < 150) {
            die('You do not have enough boost points to create a premium avatar!');
        }
        $userRepository->removeBoostPoints($_SESSION['userid'], 150);
        // Set the premium avatar to be the current time
        $_SESSION['premium_avatar'] = time();
    }
}

$avatarSuffix = $type == 'classic' ? '' : '.1';
$avatarFiles = [
    'skins' => "avatar_01_96.png{$avatarSuffix}",
    'mouths' => "avatar_02_96.png{$avatarSuffix}",
    'noses' => "avatar_03_96.png{$avatarSuffix}",
    'eyes' => "avatar_04_96.png{$avatarSuffix}",
    'hairs' => "avatar_05_96.png{$avatarSuffix}",
    'extras' => "avatar_06_96.png{$avatarSuffix}",
    'default' => "avatar_07_96.png{$avatarSuffix}"
];

$width = 96;
$height = 96;

$allParts = [];

foreach ($avatarFiles as $part => $file) {
    $image = imagecreatefrompng($file);
    $source_width = imagesx($image);
    $source_height = imagesy($image);

    for ($col = 0; $col < $source_width / $width; $col++) {
        for ($row = 0; $row < $source_height / $height; $row++) {
            $allParts[$part][$col][$row] = imagecreatetruecolor($width, $height);
            imagesavealpha($allParts[$part][$col][$row], true);
            $color = imagecolorallocatealpha($allParts[$part][$col][$row], 0, 0, 0, 127);
            imagefill($allParts[$part][$col][$row], 0, 0, $color);
            imagecopyresized(
                $allParts[$part][$col][$row],
                $image,
                0,
                0,
                $col * $width,
                $row * $height,
                $width,
                $height,
                $width,
                $height
            );
        }
    }
}

$avatarParts = ['default', 'mouths', 'noses', 'eyes', 'hairs', 'extras'];
$avatarIndices = [0, $mouths, $noses, $eyes, $hairs, $extrasch];
$avatarColors = [0, 0, 0, $eyec, $hairc, $extrasco];

for ($i = 0; $i < count($avatarParts); $i++) {
    imagecopy($allParts['skins'][$skinc][$skins], $allParts[$avatarParts[$i]][$avatarIndices[$i]][$avatarColors[$i]], 0, 0, 0, 0, 96, 96);
}

$finalimage = imagepng($allParts['skins'][$skinc][$skins], 'a/' . $_SESSION["username"] . '.png');
