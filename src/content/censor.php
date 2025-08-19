<?php

/**
 * Function to censor text by replacing certain words with splode
 * 
 * @param string $text The text to be censored
 * @return string The censored text
 */
function censorText($text): string {
    // Return early if text is empty
    if (empty($text)) {
        return $text;
    }
    
    $censoredWords = getenv('CENSORED_WORDS');
    if ($censoredWords) {
        $censoredWords = explode(',', $censoredWords);
        // Replace each censored word surrounded by word boundaries
        // Case-insensitive matching
        foreach ($censoredWords as $word) {
            $word = trim($word);
            if ($word !== '') {
                // Use word boundaries to match whole words only
                $pattern = '/\b' . preg_quote($word, '/') . '\b/i';
                $text = preg_replace($pattern, 'splode', $text);
            }
        }
    }
    return $text;
}
