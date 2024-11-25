<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML+RDFa 1.0//EN" "http://www.w3.org/MarkUp/DTD/xhtml-rdfa-1.dtd">
<!-- <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"> -->
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <?php include('../content/head.php'); ?>
    <link rel="stylesheet" type="text/css" href="/css/sploder_v2p22.min.css" />
    <link rel="stylesheet" type="text/css" href="/common/autocomplete/jquery.autocomplete.css" />
    <link rel="stylesheet" type="text/css" href="/awards/css/awards.css" />
    <link rel="stylesheet" type="text/css" href="/awards/css/awards_editor.css" />
    <script type="text/javascript" src="includes/lib/jquery.autocomplete.js"></script>

    <script type="text/javascript" src="includes/awards.js"></script>
    <!-- Include jQuery UI -->

    <script type="text/javascript">
    var _sf_startpt = (new Date()).getTime()
    </script>



    <!--[if IE 6]>
<link rel="stylesheet" type="text/css"  href="/awards/css/ie6.css" />
<![endif]-->

    <!--[if IE 7]>
<link rel="stylesheet" type="text/css"  href="/awards/css/ie7.css" />
<![endif]-->


</head>

<body id="awardsmanager" class="">
    <?php include('../content/headernavigation.php'); ?>




    <div id="page">
        <?php include('../content/subnav.php'); ?>

        <div id="content">
            <h3>Create an Award</h3>
            <!-- START CUSTOM AWARD HTML -->
            <div class="award_controls">
                <a href="#">Award 1</a>
                <a href="#">Award 2</a>
            </div>

            <input type="text" id="memberRemote" placeholder="Search members">

            <ul id="result"></ul>

            <div id="rpc_messages"></div>

            <!-- Add more HTML elements as needed for your application -->

            <script>
            // Simulate some actions to trigger the provided JavaScript functions
            // You should replace these actions with actual user interactions in your application

            // Simulate clicking an award link
            $(".award_controls a").eq(0).click();

            // Simulate selecting a member (you would typically do this after searching)
            $("#memberRemote").val("John Doe");
            $("#memberRemote").trigger("input");
            </script>

            <!-- END CUSTOM AWARD HTML -->

            <a name="guide">&nbsp;</a><br />
            <h5>Awards Guide:</h5>
            <img src="/awards/chrome/award_guide.gif" title="Awards you can make at each level"
                alt="Awards you can make at each level" />
            <p>As you obtain higher levels on Sploder, you can make more types of awards. Above is a simple guide to the
                types of awards
                you can make at each level. You must be at least level 10 to make an award.</p>
            <p>Award styles are important! Award styles on the right of the list above appear before award styles on the
                right in member profiles,
                which means the fancier styles have a better chance of appearing in the initial listing.</p>
            <p>You can give awards to any member of any level, but the awards must be accepted by the member for them to
                appear on their profile. Keep in mind some members may choose to respectfully decline the award if they
                wish to
                keep their awards list tidy. Don't be offended by this, and don't worry about declining awards yourself!
            </p>
            <div class="spacer">&nbsp;</div>
        </div>
        <div id="sidebar">

            <br /><br /><br />
            <div class="spacer">&nbsp;</div>
        </div>
        <div class="spacer">&nbsp;</div>
        <?php include('../content/footernavigation.php'); ?>


</body>

</html>