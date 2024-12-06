<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include('../database/connect.php');
$db = connectToDatabase();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML+RDFa 1.0//EN">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <?php include('../content/head.php'); ?>

    <link rel="stylesheet" type="text/css"  href="../css/sploder_v2p22.min.css"/>
    <link rel="stylesheet" type="text/css"  href="../css/members.css"/>
    <style media="screen" type="text/css">
        #swfhttpobj {
            visibility: hidden
        }
    </style>
    <?php include('../content/ruffle.php'); ?>


    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/swfobject/2.2/swfobject.js"></script>

    <script type="text/javascript">var _sf_startpt=(new Date()).getTime()</script>


    <link href="../css/sploder_v2p22.min.css" rel="stylesheet" type="text/css" />


        <?php include('../content/onlinecheck.php'); ?>
        </head>
<?php include('../content/addressbar.php'); ?>
<body id="everyones" class="contest" >
<?php include('../content/headernavigation.php'); ?>
    <div id="page">
        <?php include('../content/subnav.php') ?>
        <div id="content"><h3>Game Contest</h3><div class="gameobject" style="width: 570px; height: 406px;">

                <div id="spotlight">

                    <br /><br/><br /><br /><br/><br /><center>Loading contest data...<br /><br />This requires the Adobe Flash Player.<br /><br /><a href="https://get.adobe.com/flashplayer"><img border="0" alt="Enable Flash" src="enable_flash.gif"/></a></center><br /><br/><br />



                </div>

            </div>



            <script type="text/javascript">

                swfobject.embedSWF('../swf/spotlight.swf', 'spotlight', '570', '406', '8', '/swfobject/expressInstall.swf', null, null);

            </script>

            <br style="clear: both;" /><br/>

        <?php
            // Contest status, 0 = results, 1 = nominations, 2 = voting
            $day = date("w");
        if ($day == 1 || $day == 2) {
            $status = 1;
        } elseif ($day == 3 || $day == 4 || $day == 5) {
            $status = 2;
        } else {
            $status = 0;
        }
        if ($status == 2) {
            $qs = "SELECT games.g_id, games.title, games.author, games.user_id
                FROM (
                    SELECT id
                    FROM contest_votes
                    LIMIT 32
                ) AS recent_contests
                JOIN games ON recent_contests.id = games.g_id LIMIT 32;";
            $statement = $db->prepare($qs);
            $statement->execute();
            $result = $statement->fetchAll();
        } else {
            $qs = "SELECT games.g_id, games.title, games.author, games.user_id
                FROM (
                    SELECT contest_id, g_id
                    FROM contest_winner
                    ORDER BY contest_id DESC
                    LIMIT 24
                ) AS recent_contests
                JOIN games ON recent_contests.g_id = games.g_id ORDER BY contest_id DESC LIMIT 24;";
            $statement = $db->prepare($qs);
            $statement->execute();
            $result = $statement->fetchAll();
        }
                // Display everything
        if (count($result) > 0) {
            echo '<div id="viewpage">';
            echo '<div class="set wideset">';
            if ($status == 2) {
                    echo '<h4 style="margin-bottom: 12px;">This Week\'s Contest Nominees</h4>';
            } else {
                echo '<h4 style="margin-bottom: 12px;">Past Contest Winners</h4>';
            }
                    echo '<div class="grid">';

            foreach ($result as $row) {
                echo '<div class="game vignette">';
                echo '<div class="photo">';
                echo '<a href="/games/play.php?id=' . $row['g_id'] . '"><img src="https://sploder.xyz/users/user' . $row['user_id'] . '/images/proj' . $row['g_id'] . '/thumbnail.png" alt="' . $row['title'] . ' by ' . $row['author'] . '" title="' . $row['title'] . ' by ' . $row['author'] . '" onerror="r(this)" /></a>';
                echo '</div>';
                echo '<div class="spacer">&nbsp;</div>';
                        echo '</div>';
            }
                    echo '<div class="spacer">&nbsp;</div>';
                    echo '</div></div></div>';
        }

        ?>
        <!--<h4 style="margin-bottom: 12px;">This Week's Contest Nominees</h4>
        <div class="grid">
            <div class="game vignette">
                <div class="photo">
                    <a href="games/members/hidekigamer/play/quiz/"><img src="https://web.archive.org/web/20190306200957im_/http://cdn.sploder.com/users/group3005/user3005768_20180115074549/thumbs/proj8212698.png" alt="quiz by hidekigamer" title="quiz by hidekigamer" onerror="r(this)" /></a>
                </div>
                <div class="spacer">&nbsp;</div>
            </div>
            <div class="game vignette">
                <div class="photo">
                    <a href="games/members/supersploder987/play/sploder-jungle/"><img src="https://web.archive.org/web/20190306200957im_/http://cdn.sploder.com/users/group2279/user2279751_20150525082123/thumbs/proj6930135.png" alt="sploder jungle by supersploder987" title="sploder jungle by supersploder987" onerror="r(this)" /></a>
                </div>
                <div class="spacer">&nbsp;</div>
            </div>
            <div class="spacer">&nbsp;</div>
        </div>-->
   

            <br style="clear: both;" /><br />
            
            <?php include('../content/trendinggames.php'); ?>

            <div class="spacer">&nbsp;</div></div>
        <div id="sidebar">


           <?php include('../content/hotgames.php'); ?>


           <?php include('../content/friendliest.php'); ?>


            <br /><br /><br />
            <div class="spacer">&nbsp;</div>
        </div>
        <div class="spacer">&nbsp;</div>
        <?php include('../content/footernavigation.php') ?>
</body>
</html>
