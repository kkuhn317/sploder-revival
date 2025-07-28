<?php

require_once('../repositories/repositorymanager.php');

$challengesRepository = RepositoryManager::get()->getChallengesRepository();
$userRepository = RepositoryManager::get()->getUserRepository();

function difficulty($wins, $loss)
{
    if ($wins + $loss === 0) {
        return 5;
    }
    $diff = (1 - $loss / ($wins + $loss)) * 9;
    return 10 - ($diff);
}
    $hash = $_GET['ax'];
    $gtm = filter_var($_POST['gtm'], FILTER_VALIDATE_INT);
    $w = filter_var($_POST['w'], FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
    $w = $w ? 'true' : 'false';

    $id = explode("_", $_POST['pubkey']);
    $id[0] = filter_var($id[0], FILTER_VALIDATE_INT);
    $id[1] = filter_var($id[1], FILTER_VALIDATE_INT);
    $md5 = `node md5_edited.js $id[0]_$id[1] $w $gtm`;
if (substr($md5, 0, -1) == $hash) {
    session_start();
    if (!isset($_SESSION['username'])) {
        die("&success=true");
    }
    require_once('../database/connect.php');
    $db = getDatabase();
    $db->execute("INSERT INTO leaderboard
        (username, pubkey, gtm, w)
        VALUES (:username, :pubkey, :gtm, :w)", [
        ':username' => $_SESSION['username'],
        ':pubkey' => $id[1],
        ':gtm' => $gtm,
        ':w' => $w
    ]);

    if(isset($_SESSION['challenge'])){
        $challengeId = $challengesRepository->getChallengeId($id[1]);
        if($challengeId == $_SESSION['challenge']) {
            $challengeId = $_SESSION['challenge'];
        } else {
            $challengeId = null;
        }
    } else {
        $challengeId = null;
    }
    
    if($challengeId != null) {
        // Get challenge info to confirm if the game is a challenge and check the requirements
        $challengeInfo = $challengesRepository->getChallengeInfo($id[1]);
        $challenge = $challengeInfo['challenge'];
        $prize = $challengeInfo['prize'];
        $mode = $challengeInfo['mode'];

        // Verify challenge ID
        $isValidChallenge = $challengesRepository->verifyChallengeId($id[1], $challengeId, $_SESSION['challenge'] ?? -1);
        // Verify if the challenge requirements are met
        if ($mode) {
            if($gtm > $challenge) {
                $isValidChallenge = false;
            }
        } else {
            $score = filter_var($_POST['score'], FILTER_VALIDATE_INT);
            if($score < $challenge) {
                $isValidChallenge = false;
            }
        }

        if($challengesRepository->hasWonChallenge($id[1], $_SESSION['userid'])) {
            $isValidChallenge = false;
        }
        
        // If the game is a challenge, insert the result into the challenges table
        if ($isValidChallenge) {
            $challengesRepository->addChallengeWinner($id[1], $_SESSION['userid']);
            
            // Check if the user is the owner of the game
            $isOwner = $challengesRepository->verifyIfOwner($id[1], $_SESSION['userid']);
            // If the user is the owner, update the challenge as verified
            if ($isOwner) {
                $challengesRepository->verifyChallenge($challengeId);
            } else {
                $userRepository->addBoostPoints($_SESSION['userid'], $prize);
            }
        } else {
            unset($_SESSION['challenge']);
        }
    }
    echo "&success=true";
    // Update difficulty in game table
    $result2 = $db->query("SELECT w, COUNT(*) as count FROM leaderboard WHERE pubkey = :g_id GROUP BY w;", [
        ':g_id' => $id[1]
    ]);
    // If there are no losses, set loss to 0 using ternary operator
    $result2['loss'] = $result2[1]['count'] ?? 0;
    $result2['wins'] = $result2[0]['count'] ?? 0;

    $db->execute("UPDATE games
        SET difficulty = :difficulty
        WHERE g_id = :g_id", [
        ':difficulty' => difficulty($result2['wins'], $result2['loss']),
        ':g_id' => $id[1]
    ]);
} else {
    echo "&success=false";
}
