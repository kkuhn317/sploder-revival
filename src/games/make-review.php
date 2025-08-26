<?php require(__DIR__.'/../content/disablemobile.php'); ?>
<?php session_start(); ?>
<?php
require_once('../repositories/repositorymanager.php');
$s = $_GET['s'] ?? '';
if ($s == '') {
    header('Location: /games/reviews.php');
    die();
}
$s = explode("_", $s);
$userId = $s[0];
$gameId = $s[1];
$gameRepository = RepositoryManager::get()->getGameRepository();
$gameInfo = $gameRepository->getGameBasicInfo($gameId);
if ($gameInfo === null) {
    header('Location: /games/reviews.php');
    die();
}
print_r($gameInfo);
$gameAuthor = $gameInfo['author'];
$gameTitle = $gameInfo['title'];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML+RDFa 1.0//EN" "http://www.w3.org/MarkUp/DTD/xhtml-rdfa-1.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <?php include('../content/head.php') ?>
    <link rel="stylesheet" type="text/css" href="/css/reviews.css" />
    <!-- <link rel="stylesheet" type="text/css" href="/css/allreviews.css" /> -->
    <link rel="stylesheet" type="text/css" href="/css/sploder_v2p22.min.css" />
    <script type="text/javascript">
    var _sf_startpt = (new Date()).getTime()
    </script>
    <?php include(__DIR__ . '/../content/onlinechecker.php'); ?>
    <script type="text/javascript">window.rpcinfo = "Viewing Reviews";</script>
</head>
<?php include('../content/addressbar.php'); ?>

<body id="everyones" class="reviews">

    <?php include('../content/headernavigation.php') ?>
    <div id="page">
        <?php include('../content/subnav.php') ?>


        <div id="content">
            <h3>Game Review Editor</h3>
            <p>This is the <strong>game reviews hub</strong> where you can find reviews of
            some of your favorite games.  If you're interested in becoming a reviewer, let us know in the <a href="https://discord.com/invite/<?= getenv('DISCORD_INVITE') ?>" target="_blank">discord server</a>! Now, on to the main attraction...</p>

            <div class="game">
                    <div class="smallthumb">
                        <a class="thumb" href="play.php?s=<?= $userId ?>_<?= $gameId ?>">
                        <img src="/users/user<?= $userId ?>/images/proj<?= $gameId ?>/thumbnail.png" width="80" height="80"/>
                        </a>
                    </div><br>
                    <p>You are now writing a review for the game <a href="play.php?s=<?= $userId ?>_<?= $gameId ?>"><?= $gameTitle ?></a> by <a href="../members/?u=<?= $gameAuthor ?>"><?= $gameAuthor ?></a>. You can click "Save" at any time to save your work.
                    It won't be published until you also check the "Publish Now" checkbox. You can also unpublish by unchecking the box and saving. Be sure to provide
                    a fair, constructive review!</p>				
                    <div class="spacer">&nbsp;</div>
                    <hr style="margin-top:-1px;"/>
                </div>
            
            <div class="spacer">&nbsp;</div>
        <!-- Review Form UI -->
        <form id="reviewForm" method="post" action="">
            <label for="reviewTitle"><span style="font-weight:bold;">Review Title:</span></label><br />
            <input type="text" id="reviewTitle" name="reviewTitle" value="" required maxlength="100" style="width: 75%;"/><br /><br />
            <label for="reviewBody"><span style="font-weight:bold;">Review Body:</span> <span style="color:#aaa">(HTML is removed. ENTER twice for new paragraph.)</span></label><br />
            <textarea id="reviewBody" name="reviewBody" rows="17" required maxlength="5000" style="width: 100%; resize: none;"></textarea><br /><br />
            <input type="button" class="postbutton" value="Submit Review"></input>
            &nbsp;
            <input type="checkbox" id="publishNow" name="publishNow"> Publish Now</input>
            <!-- Code syntax -->
            <div class="codeSyntax" style="position: relative; float: right; margin; font-size: 13px; width: 200px; padding: 12px 12px 10px 12px; border: 3px solid #aaa;">
            <span style="position: absolute; top: -13px; left: 12px; background: #000000; padding: 0 6px; margin-top: 3px;">
                Code Syntax
            </span>
            <div>
                Rating: ******<br />
                <span>**Large Heading**</span><br />
                <span>*Bold Heading*</span><br />
                <span>~Italic Heading~</span>
            </div>
            </div>
                    
        </form>
        <div class="spacer">&nbsp;</div>
        </div>
        <div id="sidebar">

            <br /><br /><br />
            <div class="spacer">&nbsp;</div>
        </div>
        <div class="spacer">&nbsp;</div>
        <?php include('../content/footernavigation.php') ?>
</body>

</html>