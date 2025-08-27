<?php

/**
 * Function to censor text by replacing certain words with splode
 * 
 * @param string $text The text to be censored
 * @return string The censored text
 */
function censorText($text): string|null {
    // Return early if text is empty
    if (empty($text)) {
        return $text;
    }
    
    $censoredWords = getenv('CENSORED_WORDS');
    if ($censoredWords) {
        $censoredWords = explode(',', $censoredWords);
        // Replace each censored word, even if split by punctuation, case-insensitive
        foreach ($censoredWords as $word) {
            $word = trim($word);
            if ($word !== '') {
                // Build a regex that allows any non-alphanumeric char between each letter
                $letters = preg_split('//u', $word, -1, PREG_SPLIT_NO_EMPTY);
                $pattern = '/';
                foreach ($letters as $i => $ch) {
                    $pattern .= preg_quote($ch, '/');
                    if ($i < count($letters) - 1) {
                        $pattern .= '[^a-zA-Z0-9]*'; // allow zero or more non-alphanumeric between letters
                    }
                }
                $pattern .= '(?=[.,!?:;]?)([.,!?:;]?)/iu'; // allow trailing punctuation, capture it
                // Replace the match with 'splode' and preserve trailing punctuation
                $text = preg_replace_callback($pattern, function($matches) {
                    return 'splode' . ($matches[1] ?? '');
                }, $text);
            }
        }
    }
    return $text;
}
