<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include('../content/logincheck.php');
$username = $_SESSION['username'];
include('../database/connect.php');
$db = getDatabase();
$result4 = $db->query("SELECT g_id
    FROM games
    WHERE author=:user
    AND isdeleted=0", [
        ':user' => $username
]);
$total_games = count($result4);
if (isset($_GET['game']) && $_GET['game'] == null) {
    unset($_GET['game']);
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML+RDFa 1.0//EN">
<!-- <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"> -->
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <?php include('../content/head.php'); ?>
    <link href="css/css.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" type="text/css" href="../css/sploder_v2p3.css">
    <link rel="stylesheet" type="text/css" href="../css/sploder_v2p22.min.css" />
    <link rel="stylesheet" type="text/css" href="../slider/nivo-slider.css" />
    <link rel="stylesheet" type="text/css" href="../css/inline_help.css">
    <link rel="stylesheet" type="text/css" href="../css/dashboard.css">
    <link rel="stylesheet" type="text/css" href="./css/notifications.css">
    <style media="screen" type="text/css">
        #swfhttpobj {
            visibility: hidden
        }
    </style>
    <?php include('../content/onlinechecker.php'); ?>
    <script>
        function delproj(id, title) {
            let text;
            if (confirm(("Are you sure you want to delete " + title)) == true) {
                location.href = ("../php/delete.php?id=" + id);
            } else {}
        }
    </script>
</head>
<?php include('../content/addressbar.php'); ?>

<body id="home" class="" onload="doLoad();">
    <?php include('../content/headernavigation.php'); ?>
    <div id="page">
        <div id="subnav">
            <ul class="nav_dashboard">
                <li><a href="/">Home</a></li>
                <li><a href="" class="active">My Games</a></li>
                <li><a href="profile-edit.php">Profile</a></li>
                <li><a href="/friends/index.php">Friends</a></li>
                <!-- TODO: Groups <li><a href="groups/">Groups</a></li> -->
                <li><a href="/awards/index.php">Awards</a></li>
                <li><a href="/tournaments/index.php" style="display: none;">Tournaments</a></li>
                <li><a href="my-graphics.php">Graphics</a></li>
                <li style="float: right;"><a href="/accounts/account.php">My Account</a></li>
            </ul>
        </div>
        <div id="content">
            <h3>My Games</h3>
            <p>You've made <?= $total_games ?> games, with a total of ? views so far.
            <form action="<?= $currentpage ?>" method="GET"><label for="title">Search by title: &nbsp;</label><input
                    style="width:98.5%;height:26px" placeholder="My awesome game" value="<?php if (isset($_GET['game'])) {
                                                                                                echo $_GET['game'];
                                                                                         } ?>" class="urlthing"
                    type="text" id="game" name="game" autocomplete="off" autocorrect="off" autocapitalize="off"
                    spellcheck="false" maxlength="100" /><br><br><br></form>
            <div class="set">
                <?php
                $o = isset($_GET['o']) ? $_GET['o'] : "0";
                $offset = 12;

                $queryString = 'SELECT * FROM games WHERE author=:username AND isdeleted = 0 ORDER BY "g_id" DESC';
                if (isset($_GET['game'])) {
                    $queryString = 'SELECT * FROM games WHERE author=:username AND isdeleted = 0 AND SIMILARITY(title, :game) > 0.3 ORDER BY "g_id" DESC';
                }


                $parameters = [':username' => $username];
                if (isset($_GET['game'])) {
                    $parameters = $parameters + [':game' => $_GET['game']];
                }

                $result = $db->query($queryString, $parameters);
                $total = count($result);

                $queryString = $queryString . ' LIMIT 12 OFFSET ' . $o;
                $result = $db->query($queryString, $parameters);

                $qTotal = "SELECT count(1) FROM games WHERE author=:username AND isdeleted = 0" . (isset($_GET['game']) ? ' AND SIMILARITY(title, :game) > 0.3' : '') . ' LIMIT 12 OFFSET ' . $o;
                $resultTotal = $db->queryFirstColumn($qTotal, 0, $parameters);

                $f = '20';

                if (count($result) == "0" &&  isset($_GET['game'])) {
                    echo 'This game was not found.<div class="spacer">&nbsp;</div>';
                } elseif (count($result) == "0") {
                    echo 'You have not made any games yet.<div class="spacer">&nbsp;</div>';
                }
                foreach ($result as $game) {
                    if ($game['g_id'] == null) {
                        break;
                    }
                    ?>
                    <div class="game">
                        <div class="photo">
                            <a href="../games/play.php?&id=<?= $game['g_id'] ?>">
                                <img src="/users/user<?= $_SESSION['userid'] ?>/images/proj<?= $game['g_id'] ?>/thumbnail.png"
                                    width="80" height="80" />
                            </a>
                        </div>
                        <p class="gamedate"><?= date('m&\m\i\d\d\o\t;d&\m\i\d\d\o\t;y', strtotime($game['date'])) ?></p>
                        <h4><a href="../games/play.php?&id=<?= $game['g_id'] ?>"><?= urldecode($game['title']) ?></a></h4>
                        <input title="Delete" type="button"
                            onclick="delproj(<?= $game['g_id'] ?>,'<?= urldecode($game['title']) ?>')" style="width:37px"
                            value="Delete">&nbsp;
                        <input title="Boost" style="width:27px" class="boost_button" value="Boost">&nbsp;
                        <input title="Challenge" style="width:46px" class="challenge_button" value="Challenge">
                        <div class="spacer">&nbsp;</div>
                    </div>
                    <?php
                }
                ?>




                <div class="spacer">&nbsp;</div>


            </div>
            <?php include('../content/pages.php');
            addPagination($total ?? 0) ?>

            <div class="promo">Lost a game?<br><small><small>If you accidentally deleted a game, we may be able to
                        restore it. You can request to have it restored <a href="trash.php">here</a></small></small>
            </div>
        </div>
        <div id="sidebar">
            <!-- TODO: <h1>GAME BUZZ INCOMPLETE</h1> -->
            <br>
            <br>
            <br>
            <div class="spacer">&nbsp;</div>
        </div>
        <div class="spacer">&nbsp;</div>
        <?php include('../content/footernavigation.php') ?>
</body>
