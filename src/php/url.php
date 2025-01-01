<?php

$url = $_POST["url"];
require_once(__DIR__ . '/../config/env.php');
$domainName = getenv("DOMAIN_NAME");
$domain = substr($url, 0, strlen($domainName));
$back = str_replace("&urlerr=1", "", str_replace("&err404=1", "", $_POST["back"]));
$file = $url;
$file_headers = @get_headers($file);
if (!$file_headers || $file_headers[0] !== 'HTTP/1.1 200 OK') {
    $err404 = true;
} else {
    $err404 = false;
}
echo $file_headers;
if (($domain == $domainName) && ($err404 == false)) {
    header("Location: " . $url);
} elseif ($domain !== $domainName) {
    header("Location: " . $back . "&urlerr=1");
} elseif ($err404 == true) {
    header("Location: " . $back . "&errload=1");
}