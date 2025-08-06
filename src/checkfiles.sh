#!/bin/bash

# Script to find all .php files containing the keyword "onlinecheck"
echo "Searching for PHP files containing 'onlinecheck'..."
echo "================================================"

# Find all .php files and search for the keyword "onlinecheck"
files=$(find . -type f -name "*.php" -exec grep -l "onlinecheck" {} \;)

echo "Found files:"
echo "$files"
echo "================================================"

# Open each file in browser
for file in $files; do
    # Remove the ./ prefix and convert to web path
    webpath="${file#./}"
    url="http://127.0.0.1:8010/$webpath"
    echo "Opening: $url"
    
    # Open in default browser (works on most Linux systems)
    if command -v xdg-open &> /dev/null; then
        xdg-open "$url" &
    elif command -v firefox &> /dev/null; then
        firefox "$url" &
    elif command -v chromium &> /dev/null; then
        chromium "$url" &
    elif command -v google-chrome &> /dev/null; then
        google-chrome "$url" &
    else
        echo "No suitable browser found. Please open manually: $url"
    fi
    
    # Small delay to prevent overwhelming the browser
    sleep 0.1
done

echo "================================================"
echo "All files opened in browser."