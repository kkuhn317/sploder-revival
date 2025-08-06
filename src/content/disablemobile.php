<?php
// Check if the visitor is on a mobile device
// Credits to whoever I stole this from... I honestly forgot
if (preg_match("/(android|avantgo|blackberry|bolt|boost|cricket|docomo
|fone|hiptop|mini|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i"
, $_SERVER["HTTP_USER_AGENT"])) {
header('Location: /error_pages/mob.php');
}