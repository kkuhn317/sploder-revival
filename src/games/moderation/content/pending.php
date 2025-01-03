<?php

require_once("../../../database/connect.php");

$db = getDatabase();

// Remove pending deletions older than 14 days
$db->execute("DELETE FROM pending_deletions WHERE timestamp < NOW() - INTERVAL '14 days'");

// Get pending games and display name, thumbnail, username
$games = $db->query("SELECT games.g_id, games.date, MIN(pending_deletions.timestamp) as deletion_date, g_swf, author, title, userid, reason, views 
          FROM pending_deletions
          JOIN games ON games.g_id = pending_deletions.g_id 
          JOIN members ON games.author = members.username 
          WHERE pending_deletions.timestamp = (SELECT MIN(timestamp) FROM pending_deletions pd WHERE pd.g_id = games.g_id)
          GROUP BY games.g_id, g_swf, author, title, userid, reason, views");
