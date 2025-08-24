<?php require(__DIR__.'/../content/disablemobile.php'); ?>
<?php session_start(); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML+RDFa 1.0//EN" "http://www.w3.org/MarkUp/DTD/xhtml-rdfa-1.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <?php include('../content/head.php') ?>
    <link rel="stylesheet" type="text/css" href="/css/sploder_v2p22.min.css" />
    <script type="text/javascript">
    var _sf_startpt = (new Date()).getTime()
    </script>
    <?php include(__DIR__ . '/../content/onlinechecker.php'); ?>
    <script type="text/javascript">window.rpcinfo = "Viewing Featured Games";</script>
</head>
<?php include('../content/addressbar.php'); ?>

<body id="everyones" class="featured">

    <?php include('../content/headernavigation.php') ?>
    <div id="page">
        <?php include('../content/subnav.php') ?>


        <div id="content">
            <h3>Featured Games</h3>
            <p>These games were chosen as some of the best<!--, some of which will become <a href="/games/picks/">weekly
                    editor's picks</a>-->! To see the newest games, see <a href="/games/newest.php">everyone's games</a>.<!-- Also, check
                out <a href="/games/epic/">the EPIC game library</a> and the <a href="/games/hall-of-game/">Hall of
                    GAME</a>.

                Want your game at the top of this list? Try bidding on <a href="/games/boosts/">Game Boosts!</a></p>-->
            <div id="viewpage">
                <div class="set">

                    <div class="spacer">&nbsp;</div>
                </div>
                <div class="pagination">
                    <div class="pagination"><span class="page_button page_next" title="/games/featured/12/">next
                            &raquo;</span><span class="page_button page_button_inactive">&laquo;</span></div>
                    <div class="spacer">&nbsp;</div>
                </div>
            </div>
            
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