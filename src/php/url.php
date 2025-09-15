<?php

$url = $_POST["url"];
require_once(__DIR__ . '/../config/env.php');
$domainName = getenv("DOMAIN_NAME");
$domainNameNoWww = preg_replace('/^www\./', '', $domainName);
$back = str_replace("&urlerr=1", "", str_replace("&err404=1", "", $_POST["back"]));

// Extract the file path from the URL
$parsedUrl = parse_url($url);
$file = $parsedUrl['path'];
$exists = file_exists(__DIR__."/../".$file);

$host = isset($parsedUrl['host']) ? $parsedUrl['host'] : '';
$hostNoWww = preg_replace('/^www\./', '', $host);

if (!$exists) {
    $err404 = true;
} else {
    $err404 = false;
}

if (($host == $domainName || $hostNoWww === $domainNameNoWww) && ($err404 == false)) {
    header("Location: " . $url);
} elseif ($host !== $domainName && $hostNoWww !== $domainNameNoWww) {
    header("Location: " . $back . "&urlerr=1");
} elseif ($err404) {
    header("Location: " . $back . "&errload=1");
}