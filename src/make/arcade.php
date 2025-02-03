<?php session_start(); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML+RDFa 1.0//EN" "http://www.w3.org/MarkUp/DTD/xhtml-rdfa-1.dtd">
<!-- <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"> -->
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <?php require('content/head.php'); ?>

    function preventDefault(e) {
    e = e || window.event;
    if (e.preventDefault)
    e.preventDefault();
    e.returnValue = false;
    }

    function wheel(e) {
    preventDefault(e);
    }

    function disable_scroll() {
    if (window.addEventListener) {
    window.addEventListener("DOMMouseScroll", wheel, false);
    }
    window.onmousewheel = document.onmousewheel = wheel;
    }

    function enable_scroll() {
    if (window.removeEventListener) {
    window.removeEventListener("DOMMouseScroll", wheel, false);
    }
    window.onmousewheel = document.onmousewheel = null;
    }

    var current_pubkey = "";

    function tryPubMovie (pubkey, size) {
    current_pubkey = pubkey;
    playPubMovie(pubkey, size);
    setClass("launchprompt","shown");
    }

    function relaunchPubMovie () {
    playPubMovie(current_pubkey, 480);
    setClass("launchprompt","hidden");
    }

    var attributes = {
    v: "1"
    };

    // Make the flashcontent think that there is always a mouse cursor inside the window
    // This is done by blocking events of the mouse outside the flashwindow to be sent to the flashwindow

    function blockMouseEvents(e) {
    var flashcontent = document.getElementById("flashcontent");
    if (!flashcontent) return;

    var flashcontentRect = flashcontent.getBoundingClientRect();
    var mouseX = e.clientX;
    var mouseY = e.clientY;

    // Block only if the mouse is outside flashcontent
    if (mouseX < flashcontentRect.left || mouseX> flashcontentRect.right ||
        mouseY < flashcontentRect.top || mouseY> flashcontentRect.bottom) {

        // Check if the event target is not a form control, link, or interactive element
        var interactiveTags = ['A', 'BUTTON', 'INPUT', 'SELECT', 'TEXTAREA'];
        if (!interactiveTags.includes(e.target.tagName)) {
            e.preventDefault();
            }
        }
    }

    document.addEventListener("mousedown", blockMouseEvents, true);
    document.addEventListener("mouseup", blockMouseEvents, true);

    swfobject.embedSWF("/swf/creator7preloader2.swf", "flashcontent", "860", "626", "10.2.152",
    "/swfobject/expressInstall.swf", flashvars, params);

</script>

</head>
<?php include('../content/addressbar.php'); ?>

<body id="creator" class="arcade">
    <?php include('../content/headernavigation.php'); ?>


    <div id="page">
        <?php include('../content/subnav.php'); ?>

        <div id="content">
            <h3>Retro Arcade Game Maker</h3>
            <div style="border: 1px solid #999; color: #ccc; padding: 6px; margin: 0 19px 10px 19px; font-size: 11px; background: #660066"
                align="center" id="launchprompt" class="hidden">Playing published game. If you are blocking pop-ups,
                click <a href="#" onclick="relaunchPubMovie();">play game now</a>.</div>
            <div id="creatorcontainer" style="width: 860px; height: 626px;">
                <div id="flashcontent">
                    <div style="margin: 40px auto; text-align: center; width: 600px;">

                        <a href="https://itunes.apple.com/us/app/sploder-arcade-creator/id897669842?ls=1&mt=8">
                            <img src="../chrome/app_badge_apple.gif" width="203" height="60" /> &nbsp;
                        </a>
                        <a href="https://play.google.com/store/apps/details?id=com.sploder.arcadecreator">
                            <img src="../chrome/app_badge_googleplay.gif" width="170" height="60" /> &nbsp;
                        </a>
                        <a href="https://www.amazon.com/gp/product/B00LWFWFVY/ref=mas_pm_sploder_arcade_creator">
                            <img src="../chrome/app_badge_amazon.gif" width="171" height="60" />
                        </a>

                        <br /><br />

                        <img src="../images/retro-arcade-game-maker2.gif" width="420" height="260" /><br /><br />


                        <br />

                        <p style="width: 420px; margin: auto;">Make your own 8-bit retro arcade game with this game
                            maker. Build fun platformers, RPG stories, boss-battles, and unique games with this game
                            maker.</p>
                        <p style="width: 420px; margin: auto;">The Flash Arcade Game Maker requires the Adobe Flash
                            plugin.<br /><br /><br />
                        <div align="center" style="margin: auto;"><a href="https://get.adobe.com/flashplayer"><img
                                    border="0" alt="Enable Flash" src="enable_flash.gif" /></a></div>
                        </p>
                    </div>
                </div>
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