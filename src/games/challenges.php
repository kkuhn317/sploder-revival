<?php
session_start();
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
                if(($userId != $gameUserId) || ($userId != $_SESSION['userid'])) {
                    echo "<div class='alert'>You cannot play this game!</div>";
                } else { ?>
                    <div style="border-radius:10px;" class="challenge_confirm">
                        <p><strong>Game:</strong> Test</p>
                        <p><strong>Game:</strong> Test</p>
                        <p><strong>Game:</strong> Test</p>
                        <input type="button" class="postbutton" value="Play">
                        
                    </div>
                <?php }
            }
            // Display challenges form
            if(isset($_GET['s'])) {
                // Verify if owner
                $gameInfo = explode("_", $_GET['s']);
                $userId = $gameInfo[0];
                $gameId = $gameInfo[1];
                $gameUserId = $gameRepository->getUserId($gameId);
                if(($userId != $gameUserId) || ($userId != $_SESSION['userid'])) {
                    echo "<div class='alert'>You are not the owner of this game!</div>";
                } else {
                    $gameTitle = $gameRepository->getGameTitle($gameId);
                    $gameTitle = htmlspecialchars($gameTitle);
            ?>
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
