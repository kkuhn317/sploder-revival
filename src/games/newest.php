<?php require('content/newest.php');
$perPage = 12;
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
<?php include('../content/addressbar.php'); ?>

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
                    href="?o=<?php echo max(0, floor(($total - 1) / $perPage) * $perPage); ?>">end of
                    the
                    list</a>.
            </p>
            <div id="viewpage">
                <div class="set">

                    <?php
                    if ($gamesCount == 0) {
                        echo '<div class="prompt">No games found!</div>';
                    } else {
                        for ($i = 0; $i < $gamesCount; $i++) {
                            if ($games[$i]['g_id'] == null) {
                                break;
                            };
                            echo '<div class="game">';

                            echo '<div class="photo">';
                            echo '<a href="/games/play.php?&s=' . $games[$i]['user_id'] . '_' . $games[$i]['g_id'] . '"><img src="/users/user' . $games[$i]['user_id'] . '/images/proj' . $games[$i]['g_id'] . '/thumbnail.png" width="80" height="80"/></a>';
                            echo '</div>';
                    ?>
                    <p class="gamedate"><?= date('m&\m\i\d\d\o\t;d&\m\i\d\d\o\t;y', strtotime($games[$i]['date'])) ?>
                    </p>
                    <?php
                            echo '<h4><a href="/games/play.php?&s=' . $games[$i]['user_id'] . '_' . $games[$i]['g_id'] . '">' . urldecode($games[$i]['title']) . '</a></h4>';
                            echo '<h5><a href="/members/?u=' . $games[$i]['author'] . '">' . $games[$i]['author'] . '</a></h5>';
                            echo '<p class="gamevote"><img src="/chrome/rating' . ($games[$i]['avg_rating'] * 10) . '.gif" width="64" height="12" border="0" alt="' . $games[$i]['avg_rating'] . ' stars"/> ' . $games[$i]['total_votes'] . ' vote' . ($games[$i]['total_votes'] == 1 ? '' : 's') . '</p><p class="gameviews">' . $games[$i]['views'] . ' view' . ($games[$i]['views'] == 1 ? '' : 's') . '</p>';
                            echo '<div class="spacer">&nbsp;</div>';
                            echo '</div>';
                        }
                    }
                    ?>
                    <div class="spacer">&nbsp;</div>
                </div>
                <?php require('../content/pages.php');
                addPagination($total ?? 0, $perPage) ?>
                ?>
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