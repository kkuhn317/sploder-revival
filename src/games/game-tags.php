<?php
$perpage = 12;
$t = $_GET['t']; // Tag by user input
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML+RDFa 1.0//EN" "http://www.w3.org/MarkUp/DTD/xhtml-rdfa-1.dtd">
<!-- <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"> -->
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <?php include('../content/head.php') ?>
    <link rel="stylesheet" type="text/css" href="/css/sploder_v2p22.min.css" />
    <script type="text/javascript">
    var _sf_startpt = (new Date()).getTime()
    </script>
    <?php include(__DIR__ . '/../content/onlinechecker.php'); ?>
</head>

<body id="everyones" class="featured">

    <?php include('../content/headernavigation.php') ?>
    <div id="page">
        <?php include('../content/subnav.php') ?>


        <div id="content">
            <h3><?= ucfirst($t); ?> Games</h3>

            <p>Tags are keywords or phrases that describe your game. They help people find your games, and make browsing
                them more fun! <a href="tags.php">Browse all tags now.</a>
            <h4>Games with tag <span class="tagcolor1"><?= $t ?></span>:</h4>
            </p>
            <div id="viewpage">
                <div class="set">

                    <?php //require('../content/pages.php') ?>
                </div>
            </div>
            <div class="spacer">&nbsp;
            </div>
        </div>
        <div id="sidebar">
            <br /><br /><br />
            <div class="spacer">&nbsp;</div>
        </div>
        <div class="spacer">&nbsp;</div>
        <?php include('../content/footernavigation.php') ?>
</body>

</html>