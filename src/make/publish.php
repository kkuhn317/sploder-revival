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
    <link rel="stylesheet" href="../css/sploder_v2p12.css" type="text/css" />
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

            <div style="display:none;" id="message" class="prompt"></div>
            <br id="promptBr">
            <p class="description" style="overflow: hidden; border: 1px solid #999; padding: 10px; margin: 0; ">test</p>
            <br><br>
            <div class="buttons" style="padding: 0;">
                <span class="button firstbutton"><a style="cursor:pointer;" onclick="showDescription()">Describe &raquo;</a></span>&nbsp;
            </div>

            <br>

            <script>
                function showDescription() {
                    document.getElementById('description').style.display = 'block';
                }

                function hideDescription() {
                    document.getElementById('description').style.display = 'none';
                }

                function sendDescription() {
                    var description = document.getElementById('descriptionTextarea').value;
                    var xhr = new XMLHttpRequest();
                    xhr.open('POST', 'description.php', true);
                    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                    xhr.send('id=<?= $id ?>&description=' + description);
                    xhr.onreadystatechange = function() {
                        if (xhr.readyState == 4) {
                            if (xhr.status == 200) {
                                document.getElementsByClassName('description')[0].innerHTML = description;
                                hideDescription();
                                setMessageType('prompt');
                                document.getElementById('message').innerHTML = 'Game Description saved.';
                            } else {
                                setMessageType('alert');
                                document.getElementById('message').innerHTML = 'Failed to save description. Please try again later.';
                            }
                            showMessage();
                        }
                    }
                }

                function sendTags() {
                    var tags = document.getElementById('tagsText').value;
                    // Check whether each tag is valid
                    // For a tag to be valid, it must be less than 30 characters long
                    // It also must have only letters and numbers
                    var tagArray = tags.split(' ');
                    for (var i = 0; i < tagArray.length; i++) {
                        // If a tag is empty, remove it from the array
                        if (tagArray[i] == '') {
                            tagArray.splice(i, 1);
                            i--;
                            continue;
                        }
                        if (tagArray[i].length > 30) {
                            setMessageType('alert');
                            document.getElementById('message').innerHTML = 'Tags must be less than 30 characters long.';
                            showMessage();
                            return;
                        }
                        if (!/^[a-zA-Z0-9]*$/.test(tagArray[i])) {
                            setMessageType('alert');
                            document.getElementById('message').innerHTML = 'Tags must contain only letters and numbers. Use spaces to separate tags';
                            showMessage();
                            return;
                        }
                    }
                    var xhr = new XMLHttpRequest();
                    xhr.open('POST', 'tags.php', true);
                    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                    xhr.send('id=<?= $id ?>&tags=' + tags);
                    xhr.onreadystatechange = function() {
                        if (xhr.readyState == 4) {
                            if (xhr.status == 200) {
                                setMessageType('prompt');
                                document.getElementById('message').innerHTML = 'Game Tags saved.';
                            } else {
                                setMessageType('alert');
                                document.getElementById('message').innerHTML = 'Failed to save tags. Please try again later.';
                            }
                            showMessage();
                        }
                    }
                }

                function showMessage() {
                    document.getElementById('promptBr').style.display = 'none';
                    document.getElementById('message').style.display = 'block';
                }

                function setMessageType(type) {
                    document.getElementById('message').className = type;
                }
            </script>

            <div style="display:none;" id="description">
                <hr>
                <p>Please enter a description for your game.</p>
                <textarea id="descriptionTextarea" type="text" name="description" size="50" style="width: 300px; height: 200px;"></textarea><br><br>
                <input onclick="sendDescription()" type="submit" value="Save Description" class="loginbutton postbutton">
                <br><br>
            </div>
            <hr>
            <div id="kickdown" class="tagbox">
                <p class="tags" style="text-align:justify !important;"><strong><span style="all: unset; font-weight: bold; font-family: Verdana, Arial, Helvetica, sans-serif; color:#0055FC;"><big>ONE MORE STEP!</big></span></strong>
                    Please add some tags to describe your game
                    (like <a class="tagcolor0">space</a>,
                    <a class="tagcolor1">adventure</a>,
                    <a class="tagcolor2">rpg</a>,
                    <a class="tagcolor3">monster</a>,
                    <a class="tagcolor0">alien</a>)
                    . Use any words you like:
                </p>

                <input type="hidden" name="id" value="<?= $id ?>">
                <textarea type="text" id="tagsText" name="tags" size="50" style="width: 300px; height: 100px;"></textarea><br><br>
                <input type="submit" onclick="sendTags()" value="Save Tags" class="loginbutton postbutton">

            </div>
            <hr>
            <script type="text/javascript">
                us_config = {
                    container: 'messages',
                    venue: 'game-<?= $id . '-' . $result['author'] ?>',
                    venue_container: 'venue',
                    venue_type: 'game',
                    owner: '<?= $result['author'] ?>',
                    username: '<?php if (isset($_SESSION['username'])) {
                                    echo $_SESSION['username'];
                                } ?>',
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
                    n.href = '../css/venue5.css';
                    document.getElementsByTagName('head')[0].appendChild(n);
                    n = document.createElement('script');
                    n.type = 'text/javascript';
                    n.src = '../comments/venue7.js';
                    document.getElementsByTagName('head')[0].appendChild(n);
                    if (window.addthis) addthis.button('#btn1', addthis_ui_config, addthis_share_config);
                }
            </script>

            <div style="text-align:left;">
            <div id="messages"></div>
            <div id="venue" class="mprofvenue"></div>
            </div>
            



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