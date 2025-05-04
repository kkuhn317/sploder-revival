<?php
session_start();

require_once(__DIR__ . '/../database/connect.php');
require_once(__DIR__ . '/../repositories/graphicsrepository.php');

$db = getDatabase();

$graphicsRepository = new GraphicsRepository($db);

// Set path to the GIF folder
$gifFolder = __DIR__ . '/gif/'; 

// Check if the directory exists
if (!is_dir($gifFolder)) {
    die("Error: Directory not found or is not accessible.");
}

// Get all .gif files in the directory
$gifFiles = array_filter(scandir($gifFolder), function ($file) use ($gifFolder) {
    return is_file($gifFolder . '/' . $file) && strtolower(pathinfo($file, PATHINFO_EXTENSION)) === 'gif';
});

// Pagination variables
$perPage = 18; //36
$offset = isset($_GET['o']) ? intval($_GET['o']) : 0;
$total = count($gifFiles);
$gifFiles = array_slice($gifFiles, $offset, $perPage);

// Fetch total likes and total graphics from the database using GraphicsRepository
$total = [];
try {
    $total = $graphicsRepository->getTotal();
    $totalLikes = $total['likes'] ?: 0; // Default to 0 if no result
    $totalGraphics = $total['graphics'] ?: 0; // Default to 0 if no result
} catch (Exception $e) {
    $totalLikes = 0; // Default to 0 if error
    $totalGraphics = 0; // Default to 0 if error
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML+RDFa 1.0//EN" "http://www.w3.org/MarkUp/DTD/xhtml-rdfa-1.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <?php include('../content/head.php') ?>
    <link rel="stylesheet" type="text/css" href="/css/sploder_v2p22.min.css" />
    <script type="text/javascript">var _sf_startpt = (new Date()).getTime()</script>
    <?php include(__DIR__ . '/../content/onlinechecker.php'); ?>
</head>

<?php include('../content/addressbar.php'); ?>

<body id="everyones" class="featured">
    <?php include('../content/headernavigation.php') ?>
    <div id="page">
        <?php include('../content/subnav.php'); ?>

        <div id="content">
            <h3>Game Graphics</h3>
            <h4>What are these?</h4>
            <p>These are all of the graphics created by Sploder members. You can use these graphics in the <a href="/make/ppg.php">Physics Puzzle Maker</a> or in the <a href="/make/plat.php">Platformer Game Creator</a>. You can also create your own graphics using the online graphics editor.</p>

            <p>There are <?php echo number_format($totalGraphics); ?> graphics so far with <?php echo number_format($totalLikes); ?> total likes.</p>
            

            <form action="" method="post">
                <input type="hidden" name="graphic_id" value="">
                <input type="hidden" name="post_action" value="">

                <div id="viewpage">
                    <div class="set">
                        <?php
                        $counter = 0;
                        foreach ($gifFiles as $file) {
                            $filename = htmlspecialchars($file);
                            $src = "/src/graphics/gif/{$filename}";
                            ?>
                            <div class="game vignette">
                                <div class="photo">
                                    <a href=""><img src="<?= $src ?>" width="80" height="80" alt="gfx" title="<?= $filename ?>" onerror="r(this)"></a>
                                </div>
                                <div class="spacer">&nbsp;</div>
                            </div>
                            <?php
                            $counter++;
                            if ($counter % 3 === 0): ?>
                                <div class="spacer">&nbsp;</div>
                            <?php endif;
                        }
                        ?>
                        <div class="spacer">&nbsp;</div>
                    </div>
                </div>
                
                <div class="pagination">
                    <?php if ($offset > 0): ?>
                    <a href="?o=<?php echo max(0, $offset - $perPage); ?>">Previous</a>
                    <?php endif; ?>
                    <?php echo '&nbsp;'; ?>
                    <?php if ($offset + $perPage < $total): ?>
                    <a href="?o=<?php echo $offset + $perPage; ?>">Next</a>
                    <?php endif; ?>
                </div>
            </form>
        </div>

        <div id="sidebar"><br /><br /><br /><div class="spacer">&nbsp;</div></div>
        <div class="spacer">&nbsp;</div>
    </div>

    <?php include('../content/footernavigation.php') ?>
</body>

</html>
