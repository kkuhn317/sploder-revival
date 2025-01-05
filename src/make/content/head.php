<link rel="stylesheet" type="text/css" href="/css/sploder_v2p22.min.css" />
<?php require('../content/swfobject.php'); ?>
<?php include('../content/head.php'); ?>




<script type="text/javascript">
var userid = "demo";

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

    popUpWindow("help_inline.php?&inLauncher=1", Math.floor(screen.width / 2) - 275, Math.floor(screen.height / 2) -
        225, 550, 450);

}



function playMovie(movieID, userID, creationDate, wide) {

    if ((wide == null) | (wide < 660)) {

        wide = 660;

    } else if (wide > screen.width) {

        wide = screen.width - 20;

    }

    popUpWindow("/php/player.php?m=" + movieID + "&u=" + userID + "&c=" + creationDate, Math.floor(screen
        .width / 2) - (wide / 2), Math.floor(screen.height / 2) - 200, wide, 400);

}



function playPubMovie(pubkey, wide) {

    if ((wide == null) | (wide < 660)) {

        wide = 660;

    } else if (wide > screen.width) {

        wide = screen.width - 20;

    }

    popUpWindow("publish.php?s=" + pubkey + "#kickdown", Math.floor(screen.width / 2) - (wide / 2), Math
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

if (userid != "demo") {
    window.onbeforeunload = function() {
        return "Are you sure you want to navigate away from this page?";
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
    base: ""
};