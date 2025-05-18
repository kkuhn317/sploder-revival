<?php
session_start();
if(!isset($_SESSION['userid']) && isset($_GET['accept'])) {
    header('Location: /games/play.php?s=' . $_GET['accept'] . '&challenge=1');
    exit();
}
require_once('../repositories/repositorymanager.php');

$gameRepository = RepositoryManager::get()->getGameRepository();
$challengesRepository = RepositoryManager::get()->getChallengesRepository();
$perPage = 12;
$offset = $_GET['o'] ?? 0;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML+RDFa 1.0//EN" "http://www.w3.org/MarkUp/DTD/xhtml-rdfa-1.dtd">
<!-- <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"> -->
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <?php include('../content/head.php') ?>
    <link rel="stylesheet" type="text/css" href="/css/sploder_v2p22.min.css" />
    <link rel="stylesheet" type="text/css"  href="/css/challenges2.css" />
    <script type="text/javascript">
        var _sf_startpt = (new Date()).getTime()
    </script>
    <?php include(__DIR__ . '/../content/onlinechecker.php'); ?>
</head>
<?php include('../content/addressbar.php'); ?>

<body id="everyones" class="challenges">

    <?php include('../content/headernavigation.php') ?>
    <div id="page">
        <?php include('../content/subnav.php') ?>


        <div id="content">
            <?php
            if(isset($_GET['accept'])) {
                // Verify if owner
                $gameInfo = explode("_", $_GET['accept']);
                $userId = $gameInfo[0];
                $gameId = $gameInfo[1];
                $gameUserId = $gameRepository->getUserId($gameId);
                if(!$challengesRepository->verifyIfSIsCorrect($gameId, $userId)) {
                    echo "<div class='alert'>You cannot play this game!</div>";
                } else {
                    $gameTitle = $gameRepository->getGameTitle($gameId);
                    $gameTitle = htmlspecialchars($gameTitle);
                    $gameAuthor = $gameRepository->getGameAuthor($gameId);
                    $gameAuthor = htmlspecialchars($gameAuthor);
                    $challengeInfo = $challengesRepository->getChallengeInfo($gameId);
                    $mode = $challengeInfo['mode'] == true ? "time" : "Score at least " . $challengeInfo['challenge'] . " points";
                    $prize = $challengeInfo['prize'];
                    ?>
                    <div style="border-radius:10px; overflow: auto; height:auto;" class="challenge_confirm">
                        <big>
                        <p><strong>Game:</strong> <i><?= $gameTitle ?></i> by <?= $gameAuthor ?></p>
                        <p><strong>Challenge:</strong> <?= $mode ?></p>
                        <p><strong>Reward:</strong> <?= $prize ?> pts</p>
                        </big>
                        <form action="/php/challenges.php" method="post">
                            <input type="hidden" name="accept" value="1">
                            <input type="hidden" name="s" value="<?= $_GET['accept'] ?>">
                            <input style="float:right; margin-right:10px;" type="submit" class="postbutton" value="Accept Challenge and Play Now"><br><br><br>
                        </form>
                        
                    </div><br><br>
                <?php }
            }
            // Display challenges form
            if(isset($_GET['s'])) {
                // Verify if owner
                $gameInfo = explode("_", $_GET['s']);
                $gameId = $gameInfo[1];
                if(!$challengesRepository->verifyIfOwner($gameId, $_SESSION['userid'])) {
                    echo "<div class='alert'>You are not the owner of this game!</div>";
                } else {
                    $gameTitle = $gameRepository->getGameTitle($gameId);
                    $gameTitle = htmlspecialchars($gameTitle);
                    $userRepository = RepositoryManager::get()->getUserRepository();
            ?>
            <script>const boostPoints = <?= $userRepository->getBoostPoints($_SESSION['userid']); ?>;</script>
            <script type="text/javascript" src="challenges.js"></script>
            <div style="border-radius:10px" class="challenge_form">
                <br>
                <h4>Make a Challenge for <i><?= $gameTitle ?></i></h4>
                <hr>
                <form action="/php/challenges.php" method="post">
                    
                <label>
                    <input type="radio" name="choice" value="0" checked>Speed Run
                </label>
                <label>
                    <input type="radio" name="choice" value="1">Get a Score
                </label>
                <br><br>
                <table>
                    <tr>
                        <td><label for="name">Win in less than</label></td>
                        <td><input type="text" id="name" name="challenge" value=60 pattern="[0-9]+" required></td>
                        <td class="suffix">seconds</td>
                    </tr>
                    <tr>
                        <td><label for="description">Challenge Prize:</label></td>
                        <td><input type="text" id="prize" name="prize" value=50 pattern="[0-9]+" required></td>
                        <td class="suffix">coins</td>
                    </tr>
                    <tr>
                        <td><label for="points">Max Winners:</label></td>
                        <td><input type="text" id="winners" name="winners" value=3 pattern="[0-9]+" required></td>
                        <td class="suffix">winners</td>
                    </tr>
                </table>
                <hr>
                <table>
                    <tr>
                        <td><label></label></td>
                        <td colspan="3" style="text-align: right; padding-top: 5px;">Cost: <mark style="color:lime; background: none;">150</mark> coins</td>
                    </tr>
                </table>
                <br>
                <table>
                    <tr>
                        <td><label></label></td>
                        <td>
                            <a href="/dashboard/my-games.php"><input type="button" class="postbutton" value="Cancel"></input></a>
                            <input type="hidden" name="g_id" value="<?= $gameId ?>">
                            <input value="Create" type="submit" class="postbutton"></input>
                        </td>
                    </tr>
                </table>
                <br>  
                </form>
                
                <p style="text-align: justify;">After your challenge is created, you can <i>verify</i> it by accepting and winning the challenge.</p>
                <br>
            </div>
            <?php }} ?>
            <div class="challenges_intro">
                <h3>Game Challenges</h3>

                <p>Make your games even more fun by adding a challenge! Set a goal for everyone to beat, and the first ones to beat it will get a boost points prize! 

                You use your own boost points to make the challenge, and they are awarded to the first winners!</p>
            </div>
            <div class="set">
                <?php
                $challenges = $challengesRepository->getAllChallenges($offset, $perPage);
                print_r($challenges);
                foreach($challenges as $index => $challenge) {
                    $gameId = $challenge['g_id'];
                    $userId = $challenge['user_id'];
                    $gameTitle = htmlspecialchars($challenge['title']);
                    $gameAuthor = htmlspecialchars($challenge['author']);
                    if ($challenge['mode'] == true) {
                        $minutes = floor($challenge['challenge'] / 60);
                        $seconds = $challenge['challenge'] % 60;
                        $mode = "Win in less than";
                        if ($minutes > 0) {
                            $mode .= " {$minutes} mins";
                        }
                        if ($seconds > 0) {
                            if ($minutes > 0) $mode .= " ";
                            $mode .= "{$seconds} secs";
                        }
                        $mode = trim($mode);
                    } else {
                        $mode = "Score at least " . $challenge['challenge'] . " points";
                    }
                    $prize = $challenge['prize'];
                    $winners = $challenge['winners'];
                    $totalWinners = $challenge['total_winners'];
                    $expiresAt = new DateTime($challenge['expires_at']);
                    $now = new DateTime();
                    $interval = $now->diff($expiresAt);

                    if ($interval->days > 0) {
                        $timeLeft = $interval->days . " days";
                    } elseif ($interval->h > 0) {
                        $timeLeft = $interval->h . " hours";
                    } elseif ($interval->i > 0) {
                        $timeLeft = $interval->i . " minutes";
                    } else {
                        $timeLeft = $interval->s . " seconds";
                    }
                    ?>
                    <div class="game challenge_game chal_ver">
                        <p class="goal"><?= $mode ?></p>
                        <div class="gameinfo gametype_5">
                            <a href="/games/challenges.php?accept=<?= $userId ?>_<?= $gameId ?>" title="<?= $timeLeft ?> left in this challenge"><img src="/users/user<?= $userId ?>/images/proj<?= $gameId ?>/image.png" alt="<?= $gameTitle ?>" onerror="r(this)" /></a>
                            <div class="game_titles">
                                <h4><a href="/games/challenges.php?accept=<?= $userId ?>_<?= $gameId ?>"><?= $gameTitle ?></a></h4>
                                <h5><a href="/games/members/<?= $gameAuthor ?>/"><?= $gameAuthor ?></a></h5>
                            </div>
                        </div>
                        <p class="winners"><?= ($totalWinners) ?>/<?= ($winners) ?> winners</p>
                        <p class="prize">Win and get <span><?= $prize ?></span></p>
                        <?php if($challenge['verified']) { ?>
                            <img class="verified" src="http://cdn.sploder.com/chrome/challenge_verified.png" width="24" height="24" alt="Challenge verified" title="This challenge was verified as possible by <?= $gameAuthor ?>" />
                        <?php } ?>
                        <div class="spacer">&nbsp;</div>

                    </div>
                    <?php
                    if($index % 2 == 0) {
                        echo "<div class='spacer'>&nbsp;</div>";
                    }
                } ?>
                
            </div>
            <p class="description">To make a game challenge, go to your My Games page and then click the
	    <em>Challenge</em> button next to your game.</p>
        </div>
        
        <div class="spacer">&nbsp;</div>
    
    <div id="sidebar">
        <br /><br /><br />
        <div class="spacer">&nbsp;</div>
    </div>
    <div class="spacer">&nbsp;</div>
    <?php include('../content/footernavigation.php') ?>
</body>

</html>
