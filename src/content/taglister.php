<?php

// A functon that can take an array of string and return colored tags

function displayTags($tagList, $hyperlink = true){
    $colors = ["0", "1", "2", "3"]; // There are 4 colors for tags on Sploder
    // Get total number of games for said tag
    require_once('../database/connect.php');
    $db = connectToDatabase();
    $tags = array_map(function($tag) { return $tag[0]; }, $tagList);
    $placeholders = implode(',', array_fill(0, count($tags), '?'));
    $qs = "SELECT tag, COUNT(g_id) as count FROM game_tags WHERE tag IN ($placeholders) GROUP BY tag";
    $stmt = $db->prepare($qs);
    $stmt->execute($tags);
    $counts = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);
    for($i = 0; $i < count($tagList); $i++){
        $tagList[$i][1] = $counts[$tagList[$i][0]] ?? 0;
    }
    $tagString = "";
    if($hyperlink){
        for($i = 0; $i < count($tagList); $i++){
            $tagString .= "<a class=\"tagcolor{$colors[$i % 4]}\" href=\"game-tags.php?t={$tagList[$i][0]}\" title=\"{$tagList[$i][0]} - {$tagList[$i][1]} game" . ($tagList[$i][1] == 1 ? "" : "s") . ".\">{$tagList[$i][0]}</a> ";
        }
    } else {
        for($i = 0; $i < count($tagList); $i++){
            $tagString .= "<span class=\"tagcolor{$colors[$i % 4]}\">{$tagList[$i][0]}</span> ";
        }
    }
    return $tagString;
}