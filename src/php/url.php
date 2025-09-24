<?php
require_once '../content/initialize.php';

$url = $_POST["url"];
require_once(__DIR__ . '/../config/env.php');

$envParsed = parse_url(getenv("DOMAIN_NAME"));
$domainName = $envParsed['host'] ?? '';
$back = str_replace(["&urlerr=1", "&errload=1"], "", $_POST["back"]);

$parsedUrl = parse_url($url);
$host = $parsedUrl['host'] ?? '';
$path = $parsedUrl['path'] ?? '';
$scheme = $parsedUrl['scheme'] ?? '';
$query = $parsedUrl['query'] ?? '';

$normalizedDomainName = str_replace('www.', '', $domainName);
$normalizedHost = str_replace('www.', '', $host);

$isDomainValid = ($normalizedHost === $normalizedDomainName);

$filePath = __DIR__ . '/..' . $path;
$fileExists = file_exists($filePath) || is_dir($filePath);

if (!$isDomainValid) {
    header("Location: " . $back . "&urlerr=1");
} elseif (!$fileExists) {
    header("Location: " . $back . "&errload=1");
} else {
    $safeUrl = $scheme . '://' . $host . $path;
    if (!empty($query)) {
        $safeUrl .= '?' . $query;
    }
    header("Location: " . $safeUrl);
}
?>