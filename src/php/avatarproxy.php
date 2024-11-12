<?php

$username = $_GET['u'];
if($raw = file_get_contents('../avatar/a/'.$username.'.png')){
    echo $raw;
} else {
    echo file_get_contents('../avatar/a/fb/noob.png');
}

?>