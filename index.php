<?php

session_Start();
if(isset($_SESSION['username'])){
header('Location: dashboard/index.php');
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML+RDFa 1.0//EN">
<!-- <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"> -->
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include('content/head.php'); ?>
    <script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
    <script type="text/javascript" src="/slider/jquery.nivo.slider.pack.js"></script>
<link rel="stylesheet" type="text/css"  href="/css/sploder_v2p22.min.css" />
	<link rel="stylesheet" type="text/css"  href="/slider/nivo-slider.css" />
	<link rel="stylesheet" type="text/css"  href="/slider/sploder/style_v2p10.css" />
	<?php include('content/onlinecheck.php'); ?>
</head>
<?php include('content/addressbar.php'); ?>

<body id="home" class=""  onload="doLoad();">
<?php include('content/headernavigation.php'); ?>
		<div id="page">
			<?php include('content/subnav.php'); ?>
			<div id="s-wrapper">

    <div class="slider-wrapper theme-dark" id="slideshow_bkgd">

        <div id="slider" class="nivoSlider"> 

            <a href="make/arcade.php"><img src="/images/hp3/hp_retro_arcade_night.gif" data-src="/images/hp3/hp_retro_arcade_night.gif" width="920" height="440" alt="" title="#htmlcaption6" /></a>

            <a href="make/plat.php "><img src="/images/hp3/loading.gif" data-src="/images/hp3/hp_platformer_archers.gif" width="920" height="440" alt="" title="#htmlcaption2" /></a>

            <a href="make/ppg.php"><img src="/images/hp3/loading.gif" data-src="/images/hp3/hp_physics_topple.gif" width="920" height="440" alt="" title="#htmlcaption4" /></a>

            <a href="make/shooter.php"><img src="/images/hp3/loading.gif" data-src="/images/hp3/hp_classic_kaboom.gif" width="920" height="440" alt="" title="#htmlcaption1" /></a>

            <a href="make/algo.php"><img src="/images/hp3/loading.gif" data-src="/images/hp3/hp_3d_tactical_kaboom.gif" width="920" height="440" alt="" title="#htmlcaption3" /></a>

            <a href="make/arcade.php"><img src="/images/hp3/loading.gif" data-src="/images/hp3/hp_retro_arcade_slip.gif" width="920" height="440" alt="" title="#htmlcaption6" /></a>

            <a href="make/plat.php"><img src="/images/hp3/loading.gif" data-src="/images/hp3/hp_platformer_ninjas.gif" width="920" height="440" alt="" title="#htmlcaption2" /></a>

            <a href="make/ppg.php"><img src="/images/hp3/loading.gif" data-src="/images/hp3/hp_physics_splash.gif" width="920" height="440" alt="" title="#htmlcaption4" /></a>

            <a href="make/shooter.php"><img src="/images/hp3/loading.gif" data-src="/images/hp3/hp_classic_episode.gif" width="920" height="440" alt="" title="#htmlcaption1" /></a>

            <a href="make/algo.php"><img src="/images/hp3/loading.gif" data-src="/images/hp3/hp_3d_tactical_heroes.gif" width="920" height="440" alt="" title="#htmlcaption3" /></a>

            <a href="make/plat.php"><img src="/images/hp3/loading.gif" data-src="/images/hp3/hp_platformer_gator.gif" width="920" height="440" alt="" title="#htmlcaption2" /></a>

            <a href="make/shooter.php"><img src="/images/hp3/loading.gif" data-src="/images/hp3/hp_classic_robot_battles.gif" width="920" height="440" alt="" title="#htmlcaption1" /></a>

            <a href="make/algo.php"><img src="/images/hp3/loading.gif" data-src="/images/hp3/hp_3d_ship.gif" width="920" height="440" alt="" title="#htmlcaption3" /></a>

            <a href="make/shooter.php"><img src="/images/hp3/loading.gif" data-src="/images/hp3/hp_classic_art.gif" width="920" height="440" alt="" title="#htmlcaption1" /></a>

        </div>

        

        

        <div id="htmlcaption6" class="nivo-html-caption" style="overflow: visible;">

            <h3><a href="make/arcade.php">Retro Arcade Game Maker</a></h3>Make your own 8-bit side-scrolling arcade games with stories and RPG elements. 


	</div>
        

        <div id="htmlcaption4" class="nivo-html-caption">

            <h3><a href="make/ppg.php">Physics Puzzle Game Maker</a></h3>Build tumbling, toppling physics puzzlers, interactive stories or games with your own <a href="make/graphics.php">graphics</a>.

        </div>

        

        <div id="htmlcaption3" class="nivo-html-caption">

            <h3><a href="make/algo.php">3d Sci-fi Action Game Maker</a></h3>Create levels for this intense pseudo-3d action game with stunning graphics.

        </div>

        

        <div id="htmlcaption2" class="nivo-html-caption">

            <h3><a href="make/plat.php">Platformer Game Maker</a></h3>Make awesome adventure games with hundreds of unique platforming elements.

        </div>

        

        <div id="htmlcaption1" class="nivo-html-caption">

            <h3><a href="make/shooter.php">Classic Space Shooter</a></h3>Make fast-paced space shooting games with intricate geometric level designs.

        </div>

    </div>

</div>



<script type="text/javascript">

   var prev_img = null;

   $(window).load(function() {

        var ss_bkgd = $('#slideshow_bkgd');

        $('#slider').nivoSlider({

          pauseOnHover: true,

          pauseTime: 6000,

          afterChange: function () {

              ss_bkgd.css("background-position", "10px 10px");

              ss_bkgd.css("background-image", "url(" + $('#slider').data('nivo:vars').currentImage.data('src') + ")");

          }

        })

    });

</script>



			<div id="content">
<?php if((isset($_GET['msg']))&&($_GET['msg'])=="out") { ?>
<div class="prompt">You have been logged out of your account.</div>
<?php } ?>
<div class="homebuttons">

    <a href="/free-game-creator.php" class="sprite_button home_button_makegame">Make a game</a>

    <a href="/games/members/" class="sprite_button home_button_members">Members</a>

	<a href="games/newest.php"><img src="/chrome/home_button_newestgames.gif" width="160" height="120" alt="Newest Games"/></a>
</div>



<br style="clear: both;" />



<p>Want to make your own online games for free? <strong>Sploder &trade;</strong> makes it super easy for you to make your own free games online. Make your own <a href="make/arcade.php">arcade games</a>, <a href="make/plat.php">platformer games</a>, <a href="make/shooter.php">spaceship shooters</a>, or <a href="/previews/algorithmcrew/">space adventure games</a>. Advanced game maker? Try the <a href="make/ppg.php">physics game maker</a> for creating original minigames! You can even customize it with your own game art using our <a href="make/graphics.php">free graphics editor!</a></p>



<div class="buttons" style="padding: 0;">

	<span class="button firstbutton"><a href="/members/hall-of-fame/">Hall of Fame &raquo;</a></span>&nbsp;

    <span class="button"><a href="games/groups/">Groups &raquo;</a></span>&nbsp;

	<span class="button"><a href="games/epic/">Epic Games &raquo;</a></span>&nbsp;

	<span class="button"><a href="games/reviews/">Reviews &raquo;</a></span>&nbsp;

	<span class="button"><a href="games/tags/">Tags &raquo;</a></span>

</div>



<br /><br />



<div id="viewpage"><div class="set wideset"><h4 style="margin-bottom: 12px;">Most Popular Games</h4><div class="grid"><div class="game vignette"><div class="photo"><a href="games/members/chloride/play/adventure-zone-1/"><img src="/users/group1330/user1330862_20121120092156/thumbs/proj5070625.png" alt="Adventure Zone 1 - by chloride, 6285views" /></a></div><div class="spacer">&nbsp;</div></div><div class="game vignette"><div class="photo"><a href="games/members/liammead/play/ruins-ii/"><img src="/users/group445/user445717_20100117010807/thumbs/proj5093330.png" alt="Ruins II - by liammead, 4090views" /></a></div><div class="spacer">&nbsp;</div></div><div class="game vignette"><div class="photo"><a href="games/members/chloride/play/atraction/"><img src="/users/group1330/user1330862_20121120092156/thumbs/proj5080251.png" alt="Atraction - by chloride, 2714views" /></a></div><div class="spacer">&nbsp;</div></div><div class="game vignette"><div class="photo"><a href="games/members/vaxen2/play/slender-man-vii/"><img src="/users/group596/user596686_20100720210831/thumbs/proj5030768.png" alt="Slender Man VII - by vaxen2, 2335views" /></a></div><div class="spacer">&nbsp;</div></div><div class="game vignette"><div class="photo"><a href="games/members/daniel567/play/one-day-at-sploder-town/"><img src="/users/group1404/user1404406_20130209160623/thumbs/proj5071176.png" alt="One day at Sploder Town - by daniel567, 2307views" /></a></div><div class="spacer">&nbsp;</div></div><div class="game vignette"><div class="photo"><a href="games/members/vaxen2/play/breach-ii/"><img src="/users/group596/user596686_20100720210831/thumbs/proj5064299.png" alt="Breach II - by vaxen2, 2135views" /></a></div><div class="spacer">&nbsp;</div></div><div class="game vignette"><div class="photo"><a href="games/members/vaxen2/play/the-impossible-game/"><img src="/users/group596/user596686_20100720210831/thumbs/proj5024743.png" alt="The Impossible Game - by vaxen2, 2127views" /></a></div><div class="spacer">&nbsp;</div></div><div class="game vignette"><div class="photo"><a href="games/members/vaxen2/play/sploder-star-champions/"><img src="/users/group596/user596686_20100720210831/thumbs/proj5105075.png" alt="Sploder Star Champions - by vaxen2, 2104views" /></a></div><div class="spacer">&nbsp;</div></div><div class="game vignette"><div class="photo"><a href="games/members/123qwe9000studer/play/slender-man-vs-herobrine-3/"><img src="/users/group1246/user1246932_20120805195133/thumbs/proj5092233.png" alt="slender man vs herobrine 3 - by 123qwe9000studer, 2049views" /></a></div><div class="spacer">&nbsp;</div></div><div class="game vignette"><div class="photo"><a href="games/members/defensified/play/explosive-ordinance/"><img src="/users/group1038/user1038090_20120106182150/thumbs/proj5132881.png" alt="Explosive Ordinance - by defensified, 1918views" /></a></div><div class="spacer">&nbsp;</div></div><div class="game vignette"><div class="photo"><a href="games/members/thebluesun/play/red-ball-super-hard/"><img src="/users/group1318/user1318973_20121108024949/thumbs/proj5079001.png" alt="RED BALL Super Hard - by thebluesun, 1827views" /></a></div><div class="spacer">&nbsp;</div></div><div class="game vignette"><div class="photo"><a href="games/members/vaxen2/play/breach-ii-demo/"><img src="/users/group596/user596686_20100720210831/thumbs/proj5066265.png" alt="Breach II Demo - by vaxen2, 1207views" /></a></div><div class="spacer">&nbsp;</div></div><div class="game vignette"><div class="photo"><a href="games/members/phantomflame/play/through-the-body-of-gumball/"><img src="/users/group1398/user1398452_20130202184607/thumbs/proj5067473.png" alt="through the body of gumball - by phantomflame, 1190views" /></a></div><div class="spacer">&nbsp;</div></div><div class="game vignette"><div class="photo"><a href="games/members/woohoo32/play/tom-and-jerry/"><img src="/users/group1187/user1187320_20120604155207/thumbs/proj5089616.png" alt="Tom And Jerry - by woohoo32, 1095views" /></a></div><div class="spacer">&nbsp;</div></div><div class="game vignette"><div class="photo"><a href="games/members/religious2/play/make-a-pizza-3d/"><img src="/users/group1270/user1270952_20120908151331/thumbs/proj5115433.png" alt="Make a pizza 3D - by religious2, 1009views" /></a></div><div class="spacer">&nbsp;</div></div><div class="game vignette"><div class="photo"><a href="games/members/religious2/play/why-kids-shouldnt-skydive/"><img src="/users/group1270/user1270952_20120908151331/thumbs/proj5096769.png" alt="Why Kids shouldnt skydive - by religious2, 849views" /></a></div><div class="spacer">&nbsp;</div></div><br /><br /><div class="more"><a href="games/">More Games &raquo;</a></div></div><div class="spacer">&nbsp;</div></div></div><div class="bucket trending">

    <ul>

	<li><a href="/games/tags/rpg/">RPG Games</a></li>

	<li><a href="/games/tags/defense/">Defense Games</a></li>

	<li><a href="/games/tags/girls/">Girls Games</a></li>

	<li><a href="/games/tags/crafting/">Crafting Games</a></li>

	<li><a href="/games/tags/puzzle/">Puzzle Games</a></li>

	<li><a href="/games/tags/2player/">2 Player Games</a></li>

	<li><a href="/games/tags/strategy/">Strategy Games</a></li>

	<li><a href="/games/tags/zombie/">Zombie Games</a></li>

	<li><a href="/games/tags/simulator/">Simulator Games</a></li>

	<li><a href="/games/tags/funny/">Funny Games</a></li>

	<li><a href="/games/tags/easy/">Easy Games</a></li>

	<li><a href="/games/tags/impossible/">Impossible Games</a></li>

	<li><a href="/games/tags/crush/">Crush Games</a></li>

	<li><a href="/games/tags/scary/">Scary Games</a></li>

	<li><a href="/games/tags/pets/">Pets Games</a></li>

	<li><a href="/games/tags/kingdom/">Kingdom Games</a></li>

	<li><a href="/games/tags/anime/">Anime Games</a></li>

	<li><a href="/games/tags/bird/">Bird Games</a></li>

	<li><a href="/games/tags/quiz/">Quiz Games</a></li>

	<li><a href="/games/tags/racing/">Racing Games</a></li>

    </ul>

</div><br style="clear: both;" />




<div class="spacer">&nbsp;</div></div>
			<div id="sidebar">
					    
				
				
				
				
				<!--<img src="../chrome/bots_side.jpg" width="175" height="210" border="0" alt="Robots!" />-->

	<div class="spacer">&nbsp;</div>

<br /><div class="bucket membercontest_winner">

		<div class="member_badge">

		    <a class="button" href="/games/members/heroicdude/">

			<img src="https://avatars.sploder.com/a/h/e/heroicdude_96.png" width="72" height="72" />

		    </a>

		</div><p class="winner_name"><a class="button" href="/games/members/heroicdude/">real</a></p><hr /><div class="winnerstat"><div class="stat">2.8k</div><p>Total Plays</p></div><div class="winnerstat"><div class="stat">549</div><p>Total Friends</p></div><h3>Member of the Day!</h3><h4>December 22nd, 2020</h4><div class="spacer">&nbsp;</div></div><div class="newfeatures">



	<h4>Share Your Games with Everyone!</h4>

	<p>You can embed your games on your facebook or myspace profile, your own web site or send a link to your game by email.</p>

	

	<h4>Play Games and Cast your Vote!</h4>

	<p>You can vote on the games you play, and others can vote on yours.  The most popular games are featured here!</p>



</div><div class="bucket">

<?php include('content/powercharts.php') ?>

	<p>Check out great games as they are rated by other members!</p>

    </div>

</div>
				
				
				<br /><br /><br />
				<div class="spacer">&nbsp;</div>
			</div>			
			<div class="spacer">&nbsp;</div>
			<?php include('content/footernavigation.php'); ?>


</body>
</html>