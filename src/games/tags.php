<?php
$perpage = 100;
require_once('../database/connect.php');
$db = getDatabase();
// Select all distinct tags
$qs = "SELECT DISTINCT tag FROM game_tags ORDER BY tag LIMIT $perpage OFFSET :offset";
$tags = $db->query($qs, [':offset' => $_GET['offset'] ?? 0]);
$qs = "SELECT COUNT(DISTINCT tag) as total_unique_tags FROM game_tags";
$total = $db->queryFirstColumn($qs, 0);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML+RDFa 1.0//EN" "http://www.w3.org/MarkUp/DTD/xhtml-rdfa-1.dtd">
<!-- <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"> -->
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <?php include('../content/head.php') ?>
    <link rel="stylesheet" type="text/css" href="/css/sploder_v2p22.min.css" />
    <link rel="stylesheet" type="text/css" href="/css/tags.css" />
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
            <h3>Tags</h3>
            <p>Why should you tag your games? Tags are a way to group and categorize your game according to theme,
                style, storyline, or world-type. Tagging your games helps other people find your games. They also help
                keep everything neat and tidy, and make it more interesting and fun!
            </p>
            <div id="viewpage">
                <div class="tagbox">
                    <p class="tags" style="line-height: 40px;">Tags:
                        <?php
                        require_once('../content/taglister.php');
                        echo displayTags($tags,true);
                        
                        ?>
                    </p>
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