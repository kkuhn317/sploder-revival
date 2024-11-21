<?php
$sql = "SELECT username, reason, banned_by, bandate, autounbandate FROM banned_members";
$statement = $db->prepare($sql);
$statement->execute();
$bans = $statement->fetchAll();