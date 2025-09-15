<?php

$url = $_POST["url"];
require_once(__DIR__ . '/../config/env.php');
$domainName = getenv("DOMAIN_NAME");
$back = str_replace(["&urlerr=1", "&err404=1"], "", $_POST["back"]);

// Use parse_url to correctly validate the hostname
$parsedUrl = parse_url($url);
$host = $parsedUrl['host'] ?? '';
$path = $parsedUrl['path'] ?? '';

// Normalize domains for comparison (handle www and non-www)
$normalizedDomainName = str_replace('www.', '', $domainName);
$normalizedHost = str_replace('www.', '', $host);

// Check if the domain is valid
$isDomainValid = ($normalizedHost === $normalizedDomainName);

// Check if the file exists on the server
$fileExists = file_exists(__DIR__ . '/..' . $path);

if (!$isDomainValid) {
    header("Location: " . $back . "&urlerr=1");
} elseif (!$fileExists) {
    header("Location: " . $back . "&err404=1");
} else {
    // Both domain is valid and file exists
    header("Location: " . $url);
}
?>