<?php

require_once('../../config/env.php');
if (!isset($_GET['url'])) {
    die('No target URL set.');
}

$url = $_GET['url'];
$domain = getenv('DOMAIN_NAME');

// Parse the URL to separate the path and query parameters
$parsed_url = parse_url($url);
$path = $parsed_url['path'];
$query = isset($parsed_url['query']) ? $parsed_url['query'] : '';

// Whitelist specific paths
$whitelisted_paths = [
    '/php/getproject.php',
    '/php/getprojects.php',
    '/php/saveproject7.php',
    '/php/savegamedata7.php'
];

if (!in_array($path, $whitelisted_paths)) {
    // Check whether URL is in the format of
    // /users/userx/images/projx/thumbnail.png
    // using regex
    if (!preg_match('/^\/users\/[^\/]+\/images\/[^\/]+\/thumbnail\.png$/', $path)) {
        die('URL not whitelisted.');
    }
}

$target_url = $domain . $path;

// Merge the query parameters
$get_data = $_GET;
unset($get_data['url']); // Remove 'url' parameter to avoid duplication
if ($query) {
    parse_str($query, $query_params);
    $get_data = array_merge($get_data, $query_params);
}
$full_url = $target_url . '?' . http_build_query($get_data);

// Initialize cURL session
$ch = curl_init($full_url);

// Forward POST data if available
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $_POST);
}

// Forward cookies
$cookie = $_COOKIE['PHPSESSID'] ?? '';
curl_setopt($ch, CURLOPT_COOKIE, 'PHPSESSID=' . $cookie);

// Set cURL options to return the response
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HEADER, true);

// Execute cURL request
$response = curl_exec($ch);

// Get the response headers and body
$header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
$headers = substr($response, 0, $header_size);
$body = substr($response, $header_size);

// Close cURL session
curl_close($ch);

// Forward the response headers
foreach (explode("\r\n", $headers) as $header) {
    if (stripos($header, 'Transfer-Encoding:') === false && stripos($header, 'Content-Length:') === false) {
        header($header);
    }
}

// Output the response body
echo $body;