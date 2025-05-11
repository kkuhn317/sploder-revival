<?php
include('../content/logincheck.php');
require_once('../graphics/verify.php');
require_once('../repositories/repositorymanager.php');
$id = $_GET['id'];
$verified = verifyIfGraphicOwner((int)$id, $_SESSION['userid']);

if ($verified) {
    $tags = RepositoryManager::get()->getGraphicsRepository()->getTags($id);
} else {
    $tags = [];
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
                <li><a href="/dashboard/my-games.php">My Games</a></li>
                <li><a href="profile-edit.php">Profile</a></li>
                <li><a href="/friends/index.php">Friends</a></li>
                <!-- TODO: Groups <li><a href="groups/">Groups</a></li> -->
                <li><a href="/awards/index.php">Awards</a></li>
                <li><a href="/tournaments/index.php" style="display: none;">Tournaments</a></li>
                <li><a href="/dashboard/my-graphics.php" class="active">Graphics</a></li>
                <li style="float: right;"><a href="/accounts/account.php">My Account</a></li>
            </ul>
        </div>
        <div id="content">
            <h3>Tag Graphic</h3>
            <?php
            if (!$verified) {
                echo '<div class="alert">You do not own this graphic</div>';
            } else {
                if (isset($_GET['err'])) {
                    echo '<div class="alert">' . $_GET['err'] . '</div>';
                }

                ?>
            <div id="viewpage">
                <div class="set">
                    <div class="game vignette">
                        <div class="photo">
                            <a><img src="/graphics/gif/<?= $id ?>.gif" width="80" height="80" /></a>
                        </div>


                    </div>
                    <div style="margin:0px;" class="tagbox">
                        <?php if ($_GET['success'] ?? null == 'true') { ?>
                        Tags saved! Thank you!<br><br>
                        <?php }
                        if (!isset($tags[0][0])) {
                            ?>

                        <strong><span
                                style="all: unset; font-weight: bold; font-family: Verdana, Arial, Helvetica, sans-serif; color:#0055FC;"><big><big>ONE
                                        MORE STEP!</big></big></span></strong><big>
                            Please add some tags to describe your graphic
                            (like <a class="tagcolor0">space</a>,
                            <a class="tagcolor1">adventure</a>,
                            <a class="tagcolor2">rpg</a>,
                            <a class="tagcolor3">monster</a>,
                            <a class="tagcolor0">alien</a>).
                            Use any words you like: </big>
                        <?php } else { ?>
                        Tags: <?php include('../content/taglister.php');
                                    echo displayTags($tags, false) ?><br><br>
                        Edit your descriptive tags:
                        <?php } ?><br><br>
                        <form action="../graphics/add-tag.php" method="post">
                            <input type="hidden" name="id" value="<?= $id ?>">
                            <textarea type="text" id="tagsText" name="tags" size="50"
                                style="width: 300px; height: 100px;"><?php if (isset($tags[0][0])) {
                                                                            $tagString = '';
                                    foreach ($tags as $tag) {
                                        $tagString .= $tag[0] . ' ';
                                    }
                                                                            $tagString = substr($tagString, 0, -1);
                                                                            echo $tagString;
                                                                     } ?></textarea><br><br>
                            <input type="submit" onclick="sendTags()" value="Save Tags" class="loginbutton postbutton">
                        </form>
                    </div>
                </div>
            </div>
            <?php } ?>
        </div>
        <div class="spacer">&nbsp;</div>
        <?php include('../content/footernavigation.php') ?>
</body>
