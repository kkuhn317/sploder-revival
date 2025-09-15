<?php

$url = $_POST["url"];
require_once(__DIR__ . '/../config/env.php');

$envParsed = parse_url(getenv("DOMAIN_NAME"));
$domainName = $envParsed['host'] ?? '';
$back = str_replace(["&urlerr=1", "&err404=1"], "", $_POST["back"]);

$parsedUrl = parse_url($url);
$host = $parsedUrl['host'] ?? '';
$path = $parsedUrl['path'] ?? '';
$scheme = $parsedUrl['scheme'] ?? '';

$normalizedDomainName = str_replace('www.', '', $domainName);
$normalizedHost = str_replace('www.', '', $host);

$isDomainValid = ($normalizedHost === $normalizedDomainName);

// Check if the file exists and if the URL path is a directory
$fileExists = file_exists(__DIR__ . '/..' . $path);
$isDir = is_dir(__DIR__ . '/..' . $path);

if (!$isDomainValid) {
    header("Location: " . $back . "&urlerr=1");
} elseif (!$fileExists && !$isDir) {
    // If it's not a file or a directory, then it's a 404
    header("Location: " . $back . "&err404=1");
} else {
    if ($isDir) {
        $path = rtrim($path, '/') . '/index.php';
    }

    $safeUrl = $scheme . '://' . $host . $path;
    header("Location: " . $safeUrl);
}
?>