<?php
session_start();
require_once('../services/GameListRenderService.php');
require_once('../repositories/repositorymanager.php');

$gameRepository = RepositoryManager::get()->getGameRepository();
$gameListRenderService = new GameListRenderService($gameRepository);
$perPage = 12;
$offset = $_GET['o'] ?? 0;
$total = $gameRepository->getTotalPublishedGameCount();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML+RDFa 1.0//EN" "http://www.w3.org/MarkUp/DTD/xhtml-rdfa-1.dtd">
<!-- <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"> -->
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <?php include('../content/head.php') ?>
    <link rel="stylesheet" type="text/css" href="/css/sploder_v2p22.min.css" />
    <link rel="stylesheet" type="text/css" href="/css/treemap.css" />
    <script type="text/javascript">
        var _sf_startpt = (new Date()).getTime()
    </script>
    <?php include(__DIR__ . '/../content/onlinechecker.php'); ?>
</head>
<?php include('../content/addressbar.php'); ?>

<body id="members" class="">

    <?php include('../content/headernavigation.php') ?>
    <div id="page">
        <?php include('../content/subnav.php') ?>


        <div id="content">
            <h3>Today's Top Members</h3>
            <p>Welcome to the Sploder members home page!  This page shows the top members for today.  The members who are getting the most gameplays today are listed here.  This page is updated hourly.  To see the top members of all time, visit our <a href="/members/hall-of-fame/">Hall of Fame</a>. To meet and communicate with our staff, visit the <a href="/staff.php">Sploder Staff</a> page.</p>
            <div class="button" style="float: right; width: 120px; padding-top: 13px;"><a href="/members/all/">All members &raquo;</a></div> <form action="/members/search.php" method="get">
				<label for="search_username" style="font-size: 16px;">Member Search: &nbsp;</label>
				<input type="text" id="search_username" name="u" value="" size="16" maxlength="16" class="biginput" />
				<input type="submit" value="Search" class="postbutton" />
			</form><br /><br /><br />
            <?php
            require('../members/treemap/treemap.php');
            $baseurl = "/";

// squash time if necessary.  1 is unsquashed.
$timesquash = 1;

// array of items and sizes
$tagArray = array(
	"apples" => 12,
	"oranges" => 38,
	"pears" => 10,
	"mangos" => 24,
	"grapes" => 18,
	"bananas" => 56,
	"watermelons" => 80,
	"lemons" => 12,
	"limes" => 12,
	"pineapples" => 15,
	"strawberries" => 20,
	"coconuts" => 43,
	"cherries" => 20,
	"raspberries" => 8,
	"peaches" => 25
	);
	
// array of items and ages
$taggedArray = array(
	"apples" => "4/21/2006",
	"oranges" => "4/21/2006",
	"pears" => "4/22/2006",
	"mangos" => "4/22/2006",
	"grapes" => "4/23/2006",
	"bananas" => "4/23/2006",
	"watermelons" => "4/24/2006",
	"lemons" => "4/24/2006",
	"limes" => "4/25/2006",
	"pineapples" => "4/26/2006",
	"strawberries" => "4/27/2006",
	"coconuts" => "4/27/2006",
	"cherries" => "4/28/2006",
	"raspberries" => "4/28/2006",
	"peaches" => "4/28/2006"
	);

// define timespan
$fromwhen = date("Y-m-d H:i:s",strtotime("4/21/2006"));
$towhen = date("Y-m-d H:i:s",strtotime("4/28/2006"));

// sort the array according to size
arsort($tagArray);
	
// call the function
echo render_treemap($tagArray, 570, 900, 0, 1);

            ?>
        </div>
        <div class="spacer">&nbsp;</div>
    
    <div id="sidebar">
        <br /><br /><br />
        <div class="spacer">&nbsp;</div>
    </div>
    <div class="spacer">&nbsp;</div>
    <?php include('../content/footernavigation.php') ?>
</body>

</html>