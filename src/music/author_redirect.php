<?php
// Function to load author URLs from index.m3u file
function loadAuthorsFromM3U() {
    $authors = [];
    $m3uFile = __DIR__ . '/modules/index.m3u';

    if (!file_exists($m3uFile)) {
        return $authors;
    }

    $handle = fopen($m3uFile, 'r');
    if ($handle) {
        $count = 0;
        while (!feof($handle) && $count < 6) {
            $line = trim(fgets($handle));
            if ($line === '') continue;
            
            if (strpos($line, 'http') !== false && strpos($line, '\\') !== false) {
                $parts = explode('\\', $line, 2);
                if (count($parts) === 2) {
                    $author = trim($parts[0]);
                    $url = trim($parts[1]);
                    $authors[strtolower($author)] = $url;
                }
            }

            $count++;
        }
        fclose($handle);
    }

    return $authors;
}

// Load authors from the centralized m3u file
$authors = loadAuthorsFromM3U();

// Get the author parameter from the URL
$author = $_GET['author'] ?? null;

$author = strtolower(trim($author));

// Check if the author exists in our array
if (!array_key_exists($author, $authors)) {
    header('Location: /');
    exit();
}

header('Location: ' . $authors[$author]);