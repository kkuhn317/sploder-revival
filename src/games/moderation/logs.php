<?php require(__DIR__.'/../../content/disablemobile.php'); ?>
<?php
include('php/verify.php');
require_once("../../database/connect.php");
?>
<?php
$db = getDatabase();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML+RDFa 1.0//EN" "http://www.w3.org/MarkUp/DTD/xhtml-rdfa-1.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <?php include('../../content/head.php'); ?>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
    </style>
    <link rel="stylesheet" type="text/css" href="/css/sploder_v2p22.min.css" />


    <?php include('../../content/onlinechecker.php'); ?>

</head>
<?php include('../../content/addressbar.php'); ?>

<body id="everyones" class="collections">
    <?php include('../../content/headernavigation.php'); ?>

    <div id="page">
        <?php include('content/subnav.php'); ?>

        <div id="content" style="width:940px">
            <?php if (isset($_GET['err'])) : ?>
                <p class="alert"><?= htmlspecialchars($_GET['err']) ?></p>
            <?php endif; ?>
            <?php if (isset($_GET['msg'])) : ?>
                <p class="prompt"><?= htmlspecialchars($_GET['msg']) ?></p>
            <?php endif; ?>
            <h2>List of all actions performed by moderators</h2>
            <ul>
                <?php
                $offset = $_GET['offset'] ?? 0;
                if ($offset < 0) {
                    $offset = 0;
                }
                $logs = $db->query("SELECT *
                    FROM moderation_logs
                    ORDER BY time DESC
                    LIMIT 100 OFFSET :offset", [
                        ':offset' => $offset
                    ]);

                // Display logs as a list
                foreach ($logs as $log) {
                    // Convert timestamp to human readable format
                    $log['time'] = date('Y-m-d H:i:s', strtotime($log['time']));
                    // Depending on level, add a color, more level means higher danger
                    if ($log['level'] == 1) {
                        $log['color'] = 'lime';
                    } elseif ($log['level'] == 2) {
                        $log['color'] = 'orange';
                    } elseif ($log['level'] == 3) {
                        $log['color'] = 'red';
                    } else {
                        $log['color'] = 'gray';
                    }

                    ?>
                    <li><?= $log['time'] ?>: <u><?= $log['moderator'] ?></u> <span
                            style="color: <?= $log['color'] ?>;"><?= $log['action'] ?></span> <?= $log['on'] ?></li>
                    <?php
                }



                ?>

            </ul>
            <div style="float:right">
                <a href="logs.php?offset=<?= $offset - 100 ?>">Previous</a>
                <a href="logs.php?offset=<?= $offset + 100 ?>">Next</a>
            </div>
            <div class="spacer">&nbsp;</div>
        </div>
        <div id="sidebar">


            <br /><br /><br />
            <div class="spacer">&nbsp;</div>
        </div>
        <div class="spacer">&nbsp;</div>
        <?php include('../../content/footernavigation.php'); ?>
</body>

</html>
