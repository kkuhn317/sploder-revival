<?php

$url = $_POST["url"];
require_once(__DIR__ . '/../config/env.php');
$domainName = getenv("DOMAIN_NAME");
$domainNameNoWww = preg_replace('/^www\./', '', $domainName);
$back = str_replace("&urlerr=1", "", str_replace("&err404=1", "", $_POST["back"]));

// Extract the file path from the URL
$parsedUrl = parse_url($url);
$file = isset($parsedUrl['path']) ? $parsedUrl['path'] : '';
$exists = $file ? file_exists(__DIR__."/../".$file) : false;

$host = isset($parsedUrl['host']) ? $parsedUrl['host'] : '';
$hostNoWww = preg_replace('/^www\./', '', $host);

// Accept if host matches domain (with or without www), or if host is empty (relative URL)
$isLocal = (
    ($host && ($host === $domainName || $hostNoWww === $domainNameNoWww)) ||
    (!$host && $exists)
);

if ($isLocal && $exists) {
    header("Location: " . $url);
} elseif ($host && $host !== $domainName && $hostNoWww !== $domainNameNoWww) {
    header("Location: " . $back . "&urlerr=1");
} elseif (!$exists) {
    header("Location: " . $back . "&errload=1");
}