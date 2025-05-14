<?php
session_start();
require_once('../repositories/repositorymanager.php');
$graphicsRepository = RepositoryManager::get()->getGraphicsRepository();
$perPage = 36;
$result = $graphicsRepository->getPublicGraphics($_GET['o'] ?? 0, $perPage);
$total = $graphicsRepository->getTotalPublicGraphics();
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

<body id="everyones" class="newest" onload="doLoad();">
    <?php include('../content/headernavigation.php'); ?>
    <div id="page">
    <?php include('../content/subnav.php'); ?>
    <div id="content">
    <h3>Game Graphics</h3><h4>What are these?</h4>
	<p>These are all of the graphics created by Sploder members.
	You can use these graphics in the <a href="/make/ppg.php">Physics Puzzle Maker</a> or in 
	the <a href="/make/plat.php">Platformer Game Creator</a>.
	You can also <a href="/make/graphics.php">create your own graphics</a> using the online graphics editor.
	All graphics should also be <a href="/graphics/tags/">tagged</a> to make them easy to find!</p><p>There <?= $total == 1 ? 'is' : 'are' ?> <?= $total ?> graphic<?= $total == 1 ? '' : 's' ?> so far with ? likes.</p>
            <div id="viewpage">
                <div class="set">
                    <?php
                    if ($total == "0") {
                        echo 'No public graphics have been made yet.<div class="spacer">&nbsp;</div>';
                    }

                    foreach ($result as $counter => $graphic) {
                        if ($graphic['id'] == null) {
                            break;
                        }
                        $counter++;
                        ?><div class="game vignette">
                            <div class="photo">
                                <a href="/members/index.php?u=<?= $graphic['username'] ?>"><img src="/graphics/gif/<?= $graphic['id'] ?>.gif" width="80" height="80" /></a>
                            </div>


                            <div class="spacer">&nbsp;</div>
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
            <?php include('../content/pages.php');
            addPagination($total ?? 0, $perPage, $_GET['o'] ?? 0) ?>
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
