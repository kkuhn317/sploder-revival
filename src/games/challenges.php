<?php
session_start();
require_once('../repositories/repositorymanager.php');

$gameRepository = RepositoryManager::get()->getGameRepository();
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
            // Display challenges form
            if(isset($_GET['s'])) {
            ?>
            <script type="text/javascript" src="challenges.js"></script>
            <div style="border-radius:10px" class="challenge_form">
                <br>
                <h4>Make a Challenge for <i>Ski Jump</i></h4>
                <hr>
                <form action="/php/challenges.php" method="post">
                    
                <label>
                    <input type="radio" name="choice" value="option1" checked>Speed Run
                </label>
                <label>
                    <input type="radio" name="choice" value="option2">Get a Score
                </label>
                <br><br>
                <table>
                    <tr>
                        <td><label for="name">Win in less than</label></td>
                        <td><input type="text" id="name" name="name" value=60 pattern="[0-9]+" required></td>
                        <td class="suffix">seconds</td>
                    </tr>
                    <tr>
                        <td><label for="description">Challenge Prize:</label></td>
                        <td><input type="text" id="description" name="description" value=50 pattern="[0-9]+" required></td>
                        <td class="suffix">coins</td>
                    </tr>
                    <tr>
                        <td><label for="points">Max Winners:</label></td>
                        <td><input type="text" id="points" name="points" value=3 pattern="[0-9]+" required></td>
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
            <?php } ?>
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
