<?php session_start(); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML+RDFa 1.0//EN" "http://www.w3.org/MarkUp/DTD/xhtml-rdfa-1.dtd">
<!-- <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"> -->
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <?php require('content/head.php'); ?>

    var attributes = {
    v: "1"
    };

    swfobject.embedSWF("../swf/creator1_b01.swf", "flashcontent", "860", "540", "8", "/swfobject/expressInstall.swf",
    flashvars, params);

    </script>
</head>

<body id="creator" class="shooter">

    <?php include('../content/headernavigation.php'); ?>
    <div id="page">
        <?php include('../content/subnav.php'); ?>

        <div id="content">
            <h3>Make My Own Shooter Game</h3>



            <div id="creatorcontainer" style="width: 860px;">
                <div id="flashcontent">
                    <div style="margin: 40px auto; text-align: center; width: 420px;">
                        <p>Make your own space shooter game with this game maker. Add ships, robots, powerups and
                            fight with your ray gun, mortars, mines and bots.</p>
                        The Flash Game Maker requires the Adobe Flash plugin.<br /><br /><br />
                        <div align="center" style="margin: auto;"><a href="https://get.adobe.com/flashplayer"><img
                                    border="0" alt="Enable Flash" src="enable_flash.gif" /></a></div>
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