<?php

session_start();
require_once('../repositories/repositorymanager.php');

$challengesRepository = RepositoryManager::get()->getChallengesRepository();

$mode = $_POST['choice'];
$mode = $mode == 1 ? true : false;
$challenge = $_POST['challenge'];
$prize = $_POST['prize'];
$winners = $_POST['winners'];

$challengesRepository->addChallenge(1, true, $challenge, $prize, $winners);

header("Location: /games/challenges.php?accept=1_1");