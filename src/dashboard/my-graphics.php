<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include('../content/logincheck.php');
$username = $_SESSION['username'];
$userid = $_SESSION['userid'];
include('../database/connect.php');
$db = connectToDatabase();
$qs2 = "SELECT COUNT(id) FROM graphics WHERE userid=:userid";
$statement2 = $db->prepare($qs2);
$statement2->execute(
    [
        ':userid' => $userid
    ]
);
$result4 = $statement2->fetchAll();
$total_games = $result4[0][0];
$currentpage = "my-graphics.php";

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
    function delproj(id) {
        let text;
        if (confirm(("Are you sure you want to delete this graphic?")) == true) {
            location.href = ("../php/delete_graphic.php?id=" + id);
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
                <li><a href="">My Games</a></li>
                <li><a href="profile-edit.php">Profile</a></li>
                <li><a href="/friends/index.php">Friends</a></li>
                <!-- TODO: Groups <li><a href="groups/">Groups</a></li> -->
                <li><a href="/awards/index.php">Awards</a></li>
                <li><a href="/tournaments/index.php" style="display: none;">Tournaments</a></li>
                <li><a href="my-graphics.php" class="active">Graphics</a></li>
                <li style="float: right;"><a href="/accounts/account.php">My Account</a></li>
            </ul>
        </div>
        <div id="content">
            <h3>My Graphics</h3>
            <p>You've made <?= $total_games ?> graphic<?= $total_games == 1 ? '' : 's' ?> so far, with a total of ?
                like<?= $total_games == 1 ? '' : 's' ?> so far.
                <a href="../make/graphics.php">Make
                    some graphics
                </a> now!
            </p>
            <div id="viewpage">
                <div class="set">
                    <?php
                    $o = isset($_GET['o']) ? $_GET['o'] : "0";
                    $offset = 12;

                    $queryString = 'SELECT * FROM graphics WHERE userid=:userid ORDER BY id DESC';

                    $statement = $db->prepare($queryString);
                    $statement->execute([':userid' => $userid]);

                    $result = $statement->fetchAll();
                    $total = count($result);

                    $queryString = $queryString . ' LIMIT 12 OFFSET ' . $o;
                    $statement = $db->prepare($queryString);
                    $statement->execute([':userid' => $userid]);

                    $result = $statement->fetchAll();

                    $f = '20';

                    if ($total_games == "0") {
                        echo 'You have not made any graphics yet.<div class="spacer">&nbsp;</div>';
                    }
                    $counter = 0;
                    foreach ($result as $game) {
                        if ($game['id'] == null) {
                            break;
                        }
                        $counter = $counter + 1;
                    ?><div class="game vignette">
                        <div class="photo">
                            <a><img src="/graphics/gif/<?= $game['id'] ?>.gif" width="80" height="80" /></a>
                            <div style="text-align: center;">
                                <div style="height:5px" class="spacer">&nbsp;</div>
                                0 likes<br>
                                <input title=" Delete" type="button" onclick="delproj(<?= $game['id'] ?>)"
                                    style="width:37px" value="Delete">&nbsp;
                                <a href="tag-graphic.php?id=<?= $game['id'] ?>"><input title=" Tag" type="button"
                                        style="width:25px" value="Tag"></a>
                            </div>
                        </div>


                        <div class="spacer">&nbsp;</div><br>
                        <div class="spacer">&nbsp;</div><br><br>
                    </div>
                    <?php
                        if ($counter % 4 == 0) {
                            echo '<div class="spacer">&nbsp;</div>';
                        }
                    }
                    ?>




                    <div class="spacer">&nbsp;</div>


                </div>
            </div>
            <?php include('../content/pages.php'); ?>

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