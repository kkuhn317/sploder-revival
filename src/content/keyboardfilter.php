<?php
// Make sure the message has only characters available on a standard keyboard
function filterKeyboard($str) {
    // Allow only tab, newline, carriage return, and printable ASCII (space to ~). Remove all Unicode (including emojis).
    return preg_replace("~[^a-zA-Z0-9_ !@#$%^&*();\\\/|<>\"'+.,:?=-]~", '', $str);
}