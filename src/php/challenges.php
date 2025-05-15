<?php

session_start();
require_once('../repositories/repositorymanager.php');

$challengesRepository = RepositoryManager::get()->getChallengesRepository();

if(isset($_POST['choice'])) {
    $mode = $_POST['choice'];
    $mode = $mode == 0 ? true : false;
    $challenge = $_POST['challenge'];
    $prize = $_POST['prize'];
    $winners = $_POST['winners'];
    $g_id = $_POST['g_id'];

    $challengesRepository->addChallenge($g_id, $mode, $challenge, $prize, $winners);

    header("Location: /games/challenges.php?accept=".$_SESSION['userid']."_".$g_id);
} else if(isset($_POST['accept'])) {
    $s = explode("_",$_POST['s']);
    if($challengesRepository->verifyIfSIsCorrect($s[1], $s[0])) {
        $c_id = $challengesRepository->getChallengeId($s[1]);
        $_SESSION['challenge'] = $c_id;
        header("Location: /games/play.php?s=" . $s[0] . "_" . $s[1] . "&challenge=" . $c_id);
    }
}