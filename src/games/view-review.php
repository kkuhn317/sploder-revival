<?php require(__DIR__.'/../content/disablemobile.php'); ?>
<?php session_start(); ?>
<?php
require_once('../repositories/repositorymanager.php');
$s = $_GET['s'] ?? '';
if ($s == '') {
    header('Location: /games/reviews.php');
    die();
}
$s = explode("_", $s);
$userId = $s[0];
$gameId = $s[1];
$gameRepository = RepositoryManager::get()->getGameRepository();
$gameInfo = $gameRepository->getGameBasicInfo($gameId);
if ($gameInfo === null) {
    header('Location: /games/reviews.php');
    die();
}
$reviewData = $gameRepository->getReviewData($_GET['userid'], $gameId);
if ($reviewData['ispublished'] == false && $reviewData['userid'] != $_SESSION['userid']) {
    header('Location: /games/reviews.php');
    die();
}
$gameInfo = $gameRepository->getGameBasicInfo($gameId);
$gameTitle = $gameInfo['title'];
$gameAuthor = $gameInfo['author'];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML+RDFa 1.0//EN" "http://www.w3.org/MarkUp/DTD/xhtml-rdfa-1.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <?php include('../content/head.php') ?>
    <link rel="stylesheet" type="text/css" href="/css/reviews.css" />
    <!-- <link rel="stylesheet" type="text/css" href="/css/allreviews.css" /> -->
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
            <h3><?= htmlspecialchars($reviewData['title']) ?></h3>
            <cite>Review by <a href="../members/?u=<?= $reviewData['username'] ?>"><?= htmlspecialchars($reviewData['username']) ?></a> on <?= date('l, F jS Y', strtotime($reviewData['review_date'])) ?></cite><br><br>
            <div id="venue" style="margin-bottom: 20px;">...</div>
            <div class="game">
                    <div class="thumb">
                        <a class="thumb" href="play.php?s=<?= $userId ?>_<?= $gameId ?>">
                        <img src="/users/user<?= $userId ?>/images/proj<?= $gameId ?>/thumbnail.png" width="200" height="200"/>
                        </a>
                        <p><a href="play.php?s=<?= $userId ?>_<?= $gameId ?>"><?= htmlspecialchars($gameTitle) ?></a> is a game created by <a href="../members/?u=<?= $gameAuthor ?>"><?= htmlspecialchars($gameAuthor) ?></a></p>
                    </div>
                    <?php
                    function formatReview($reviewText) {
                        // Split by double line breaks for paragraphs
                        $paragraphs = preg_split("/\r?\n\r?\n/", $reviewText);
                        $output = '';
                        $paraCount = count($paragraphs);
                        foreach ($paragraphs as $i => $para) {
                            // Split by single line for line-level formatting
                            $lines = preg_split("/\r?\n/", $para);
                            foreach ($lines as $line) {
                                $trimmed = trim($line);

                                if (preg_match('/^(.*?)([\*]+[^\/]*)\/([^\s]*)$/', $trimmed, $matches)) {
                                    $label = trim($matches[1]);
                                    $ticks1 = $matches[2];
                                    $ticks2 = $matches[3];
                                    $output .= '<div class="rating">';
                                    if ($label !== '') {
                                        $output .= '<span class="label">' . htmlspecialchars($label) . '</span> ';
                                    }
                                    // Replace * with <span class="tick">_</span>, keep other chars as-is
                                    $output .= preg_replace_callback('/./u', function($m) {
                                        return $m[0] === '*' ? '<span class="tick">_</span>' : htmlspecialchars($m[0]);
                                    }, $ticks1);
                                    $output .= '/';
                                    $output .= preg_replace_callback('/./u', function($m) {
                                        return $m[0] === '*' ? '<span class="tick">_</span>' : htmlspecialchars($m[0]);
                                    }, $ticks2);
                                    $output .= '<div class="spacer">&nbsp;</div></div>';
                                    continue;
                                }
                                // Large Heading
                                if (preg_match('/\*\*(.+)\*\*/', $trimmed, $matches)) {
                                    $output .= '<h6>' . htmlspecialchars($matches[1]) . '</h6>';
                                    continue;
                                }
                                // Bold Heading
                                if (preg_match('/\*(.+)\*/', $trimmed, $matches)) {
                                    $output .= '<p><strong>' . htmlspecialchars($matches[1]) . '</strong></p>';
                                    continue;
                                }
                                // Italic Heading
                                if (preg_match('/~(.+)~/', $trimmed, $matches)) {
                                    $output .= '<p><em>' . htmlspecialchars($matches[1]) . '</em></p>';
                                    continue;
                                }
                                // Default: wrap in <p>
                                $output .= '<p>' . htmlspecialchars($trimmed) . '</p>';
                            }
                            // Only add <br> if not the last paragraph
                            if ($i < $paraCount - 1) {
                                $output .= '<br>';
                            }
                        }
                        return $output;
                    }
                    echo formatReview($reviewData['review']);
                    ?>
                
            
                <div class="spacer">&nbsp;</div>
            </div>
       
        <div class="pagination">
            <span class="button"><a href="reviews.php">&laquo; All Reviews</a></span>
            <?php
            if ($reviewData['userid'] == $_SESSION['userid']) {
                $userRepository = RepositoryManager::get()->getUserRepository();
                $perms = $userRepository->getUserPerms($_SESSION['username']);
                if ($perms !== null && $perms !== '' && str_contains($perms, 'R')) {
            ?>
                <span class="button"><a href="make-review.php?s=<?= $userId ?>_<?= $gameId ?>">Edit Review</a></span>
                <span class="button"><a href="../php/delete-review.php?s=<?= $userId ?>_<?= $gameId ?>">Delete Review</a></span>
            <?php }} ?>
            <span class="button"><a href="../members/?u=<?= $reviewData['username'] ?>">Profile: <?= htmlspecialchars($reviewData['username']) ?></a></span>
            <span class="button"><a href="../members/?u=<?= $gameAuthor ?>">Profile: <?= htmlspecialchars($gameAuthor) ?></a></span>
            <div class="spacer">&nbsp;</div>
        </div>
        <script type="text/javascript">
            us_config = {
                container: 'messages',
                venue: 'review-<?= $reviewData['review_id'] ?>',
                venue_container: 'venue',
                venue_type: 'review',
                owner: '<?= $reviewData['username'] ?>',
                username: '<?php if (isset($_SESSION['username'])) {
                        echo $_SESSION['username'];
                           }?>',
                ip_address: '',
                timestamp: '<?= time() ?>',
                auth: '',
                use_avatar: true,
                venue_anchor_link: true,
                show_messages: true,
            }

            window.onload = function() {
                var n;
                n = document.createElement('link');
                n.rel = 'stylesheet';
                n.type = 'text/css';
                n.href = '/css/venue5.css';
                document.getElementsByTagName('head')[0].appendChild(n);
                n = document.createElement('script');
                n.type = 'text/javascript';
                n.src = '/comments/venue7.js';
                document.getElementsByTagName('head')[0].appendChild(n);
                if (window.addthis) addthis.button('#btn1', addthis_ui_config, addthis_share_config);
            }
            </script>


            <a id="messages_top"></a>
            <div id="messages"></div>
        </div>
        
        <div id="sidebar">

            <br /><br /><br />
            <div class="spacer">&nbsp;</div>
        </div>
        <div class="spacer">&nbsp;</div>
        <?php include('../content/footernavigation.php') ?>
</body>

</html>