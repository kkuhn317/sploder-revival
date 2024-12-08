<?php session_start();
// Get required data...
require_once('../database/connect.php');
$db = getDatabase();
$qs = "SELECT author,title,description,g_id,user_id,g_swf,date,user_id,views FROM games WHERE ispublished = 1 AND isprivate = 0 ORDER BY g_id DESC LIMIT 12";
$games = $db->query($qs);
$perPage = 12;
$qs = "SELECT COUNT(g_id) FROM games WHERE ispublished = 1 AND isprivate = 0";
$total = $db->queryFirstColumn($qs);
$currentpage = 'newest.php';
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
            <h3>Everyone's Games</h3>
            <p>This is a list of the newest games Sploder Revival members have created. Play them, have fun, and make
                sure you
                vote for your favorites! To see
                the first games made on Sploder Revival, go to the <a
                    href="?o=<?php if($total-$perPage<0){echo 0;}else{echo $total-$perPage;} ?>">end of
                    the
                    list</a>.
            </p>
            <div id="viewpage">
                <div class="set">

                    <?php
                    for ($i = 0; $i < count($games); $i++) {
                        if ($games[$i]['g_id'] == null) {
                            break;
                        };
                        echo '<div class="game">';

                        echo '<div class="photo">';
                        echo '<a href="/games/play.php?&pubkey=' . $games[$i]['user_id'] . '_' . $games[$i]['g_id'] . '&g_swf=' . $games[$i]['g_swf'] . '&title=' . $games[$i]['title'] . '&pub=0"><img src="/users/user' . $games[$i]['user_id'] . '/images/proj' . $games[$i]['g_id'] . '/thumbnail.png" width="80" height="80"/></a>';
                        echo '</div>';
                    ?>
                    <p class="gamedate"><?= date('m&\m\i\d\d\o\t;d&\m\i\d\d\o\t;y', strtotime($games[$i]['date'])) ?>
                    </p>
                    <?php
                        echo '<h4><a href="/games/play.php?&pubkey=' . $games[$i]['user_id'] . '_' . $games[$i]['g_id'] . '&g_swf=' . $games[$i]['g_swf'] . '&title=' . $games[$i]['title'] . '&pub=0">' . urldecode($games[$i]['title']) . '</a></h4>';
                        echo '<p class="gamevote"><img src="/chrome/rating0.gif" width="64" height="12" border="0" alt="0 stars"/> 0 votes</p><p class="gameviews">' . $games[$i]['views'] . ' views</p>';
                        echo '<div class="spacer">&nbsp;</div>';
                        echo '</div>';
                    }
                    ?>
                    <div class="spacer">&nbsp;</div>
                </div>
                <?php require('../content/pages.php') ?>
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