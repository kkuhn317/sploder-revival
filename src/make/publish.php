<?php
// Get required data...
session_start();
$s_array = explode("_", $_GET['s']);
$id = end($s_array);
require_once('../database/connect.php');
$db = getDatabase();
$qs = "SELECT author,title FROM games WHERE g_id = :id";
$result = $db->queryFirst($qs, [':id' => $id]);
if ($_SESSION['username'] != $result['author']) {
    header('Location: /?s=' . $_GET['s']);
}
print_r($result);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <title>Sploder</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link rel="stylesheet" href="../css/sploder_v2.css" type="text/css" />
    <link rel="stylesheet" href="../css/publishpage2.css" type="text/css" />
    <?php //require('../content/swfobject.php'); 
    ?>
    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/swfobject/2.2/swfobject.js"></script>
    <script type="text/javascript" language="Javascript">
    </script>
</head>

<body bgcolor="#FFFFFF">
    <div id="show" style="width: 590px;"><a name="kickdown" style="height:1px; overflow:hidden;"></a>
        <div class="showcontent">
            <h4><?= $result['title'] ?></h4>
            <div class="gameobject" style="width: 508px; height: 381px;">
                <div id="flashcontent">
                    <br /><br /><br /><br /><br /><br />
                    <p style="text-align: center;">Loading game...</p>

                    <br /><br /><br /><br /><br /><br />
                </div>
            </div>
            <br>
            
        </div>
        <div class="showbottom">
            <div class="showbottomright">&nbsp;</div>
        </div>
    </div><br /><br>
    <script type="text/javascript">
        if (so) so.write("flashcontent");
    </script>
</body>

</html>