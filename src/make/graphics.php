<?php session_start(); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML+RDFa 1.0//EN" "http://www.w3.org/MarkUp/DTD/xhtml-rdfa-1.dtd">
<!-- <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"> -->
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <link rel="stylesheet" type="text/css" href="../css/sploder_v2p22.min.css" />
    <?php require('../content/swfobject.php'); ?>
    <?php require('../content/head.php'); ?>



    <script type="text/javascript">
        
        var userid = "<?php echo isset($_SESSION['username']) ? $_SESSION['userid'] : 'demo'; ?>";

        var page_start_time = (new Date()).getTime();



        var popUpWin = 0;

        function popUpWindow(URLStr, left, top, width, height)

        {



            if (popUpWin)

            {

                if (!popUpWin.closed) popUpWin.close();

            }

            popUpWin = open(URLStr, '_blank',
                'toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=yes,copyhistory=yes,width=' +
                width + ',height=' + height + ',left=' + left + ', top=' + top + ',screenX=' + left + ',screenY=' +
                top + '');

        }



        function setup_exit() {

            if (userid != "demo") {

                window.onbeforeunload = function() {

                    if ((new Date()).getTime() - page_start_time > 5000)
                        return "If you exit thisl page you will lose any unsaved work.";

                };

            }

        }





        function getPhotos() {

            popUpWindow("php/uploadform.php?PHPSESSID=", Math.floor(screen.width / 2) - 275, Math.floor(screen.height / 2) -
                225, 550, 450);

        }



        function launchHelp() {

            popUpWindow("help_inline.php?PHPSESSID=", Math.floor(screen.width / 2) - 275, Math.floor(screen.height / 2) -
                225, 550, 450);

        }



        function playMovie(movieID, userID, creationDate, wide) {

            if ((wide == null) | (wide < 660)) {

                wide = 660;

            } else if (wide > screen.width) {

                wide = screen.width - 20;

            }

            popUpWindow("/php/player.php?PHPSESSID=&m=" + movieID + "&u=" + userID + "&c=" + creationDate, Math.floor(screen
                .width / 2) - (wide / 2), Math.floor(screen.height / 2) - 200, wide, 400);

        }



        function playPubMovie(pubkey, wide) {

            if ((wide == null) | (wide < 660)) {

                wide = 660;

            } else if (wide > screen.width) {

                wide = screen.width - 20;

            }

            popUpWindow("/publish.php?PHPSESSID=&s=" + pubkey + "#kickdown", Math.floor(screen.width / 2) - (wide / 2), Math
                .floor(screen.height / 2) - 270, wide, 540);

        }



        function updateMovie(value) {

            var InternetExplorer = navigator.appName.indexOf("Microsoft") != -1;

            if (InternetExplorer) {

                document.creator.SetVariable("/browsermanager:callvalue", value);

                document.creator.TPlay("browsermanager");

            } else {

                document.creator.SetVariable("/browsermanager:callvalue", value);

                document.creator.TPlay("browsermanager");

            }

        }



        setup_exit();

        <?php if (!isset($_SESSION['username'])) { ?>
            var flashvars = {
                PHPSESSID: "demo",
                userid: "demo",
                username: "demo",
                creationdate: "20070102003743"
            };
        <?php } else { ?>
            var flashvars = {
                PHPSESSID: "<?php echo $_SESSION['PHPSESSID']; ?>",
                userid: "<?php echo $_SESSION['userid'] ?>",
                username: "<?php echo $_SESSION['username'] ?>",
                creationdate: "<?php echo time() ?>"
            };

        <?php } ?>

        var params = {
            menu: "false",
            quality: "high",
            scale: "noscale",
            salign: "tl",
            bgcolor: "#333333",
        };

        var attributes = {
            v: "1"
        };

        swfobject.embedSWF("../swf/pixeleditor05.swf", "flashcontent", "720", "550", "10.1.53", "/swfobject/expressInstall.swf",
            flashvars, params);
        
    </script>


    <script type="text/javascript">
        if (userid != "demo") {
            window.onbeforeunload = function() {
                return "Are you sure you want to navigate away from this page?";
            }
        }
    </script>

</head>
<?php require('../content/addressbar.php'); ?>

<body id="creator" class="graphicseditor">
    <?php require('../content/headernavigation.php'); ?>


    <div id="page">
        <?php require('../content/subnav.php'); ?>


        <div id="content">
            <h3>Free Graphics Editor, Make Pixel Art for Games</h3>
            <div id="creatorcontainer" style="height: 550px;">
                <div id="flashcontent">
                    <div style="margin: 40px auto; text-align: center; width: 420px;">
                        <img src="../images/graphics-editor.png" width="348" height="172" /><br /><br /><br />
                        <p>Learn how to design and draw your own graphics with this <strong>free graphics
                                editor</strong>. With this, you can build your own animated game sprites, game tiles and
                            textures (like <a href="https://www.gimp.org">gimp</a> or other programs) for use in the
                            game maker.</p>
                        <p>The graphics editor requires the Adobe Flash plugin. <br /><br /><br />
                        <div align="center" style="margin: auto;"><a href="https://get.adobe.com/flashplayer"><img
                                    border="0" alt="Enable Flash" src="../images/enable_flash.gif" /></a></div>
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
        <?php require('../content/footernavigation.php'); ?>


</body>

</html>