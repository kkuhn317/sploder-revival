<?php require(__DIR__.'/../content/disablemobile.php'); ?>
<?php session_start(); ?>
<?php
require_once('../repositories/repositorymanager.php');

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML+RDFa 1.0//EN" "http://www.w3.org/MarkUp/DTD/xhtml-rdfa-1.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <?php include('../content/head.php') ?>
    <link rel="stylesheet" type="text/css" href="/css/reviews.css" />
    <link rel="stylesheet" type="text/css" href="/css/allreviews.css" />
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
            <h3>Game Reviews</h3>
            <p>This is the <strong>game reviews hub</strong> where you can find reviews of
        	some of your favorite games.  If you're interested in becoming a reviewer, let us know in the <a href="https://discord.com/invite/<?= getenv('DISCORD_INVITE') ?>" target="_blank">discord server</a>! Now, on to the main attraction...</p>

            <div class="game">
					<h4><a href="reviewlink">[REVIEW TITLE]</a></h4>
					<cite>Review by <a href="reviewerpage">[reviewer username]</a> on Day of the Week, Month Day of the Month(th) Year</cite>
					<div class="smallthumb">
						<a class="thumb" href="gamelink">
						<img src="gamethumburl" width="80" height="80" alt="Click to play gameauthor"/>
						</a>
					</div>
					<p>Review up to 320 chars [316 actual characters, last 4 " ..."]</p>
					<p class="postlink" align="right"><a href="reviewlink">Read and comment &raquo;</a></p>
					
					<div class="spacer">&nbsp;</div>
					<hr/>
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