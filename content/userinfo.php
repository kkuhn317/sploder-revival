<?php
function display_user_info($username){
    include_once('../database/connect.php');
    if(!isset($db)){
        $db = connectToDatabase();
    }
    $sql = "SELECT * FROM user_info WHERE username = :username";
    $statement = $db->prepare($sql);
    $statement->execute([':username' => $username]);
    $row = $statement->fetch();
    $showAbout = true;
    //check if all columns are empty or if row does not exist
    if(empty($row['description']) && empty($row['hobbies']) && empty($row['sports']) && empty($row['games']) && empty($row['movies']) && empty($row['bands']) && empty($row['respect'])){
        $showAbout = false;
    }
    ?>
    <script type="text/javascript">
        function setClass (id, c) { var e = document.getElementById(id); if (e) e.className = c; }
    </script>
    <?php if($showAbout){ ?>
    <div class="mprofgroup mprofsection">
    <h4><a href="#" onclick="setClass('mprof_about', 'shown'); return false;">About <?= $username ?></a></h4>
    <div class="mprofcontent hidden" id="mprof_about">
    <?php
    if($row['description'] != ''){
    echo '<p class="intro">
    <img class="p_avatar" src="/php/avatarproxy.php?u="' . $username . ' width="48" height="48" alt="member speaking"/>
    ' . nl2br(htmlspecialchars($row['description'])) . '</p>';
    }
    $fields = [
        'hobbies' => 'Hobbies',
        'sports' => 'Favorite Sports',
        'games' => 'Favorite Games',
        'movies' => 'Favorite Movies',
        'bands' => 'Favorite Bands',
        'respect' => 'Whom I Respect'
    ];

    foreach ($fields as $column => $label) {
        if ($row[$column] != '') {
            echo '<div class="subsection">';
            echo "<h5>$label</h5>";
            echo "<p>".htmlspecialchars($row[$column])."</p>";
            echo '</div>';
        }
    }
    ?>
    <div class="spacer">&nbsp;</div>
				</div>
			</div>
    <?php } ?>
    
    <?php

    // Get required data for votes, comments, vote average, tributes, group memberships, and group ownerships
    // TODO: Group Memberships, Group Ownerships, Comment view page
    $sql = "SELECT COUNT(*) as votes FROM votes WHERE username = :username";
    $statement = $db->prepare($sql);
    $statement->execute([':username' => $username]);
    $votes_cast = $statement->fetch()['votes'];

    $sql = "SELECT COUNT(*) as comments FROM comments WHERE creator_name = :username";
    $statement = $db->prepare($sql);
    $statement->execute([':username' => $username]);
    $comments_made = $statement->fetch()['comments'];

    $sql = "SELECT AVG(score) as vote_avg FROM votes WHERE username = :username";
    $statement = $db->prepare($sql);
    $statement->execute([':username' => $username]);
    // Round the average vote to the nearest integer and make it a percentage out of 100
    // Scores are store in the database as integers from 1 to 5
    $vote_avg = round($statement->fetch()['vote_avg']);
    // Convert the average vote to a percentage out of 96 (the width of the bar [WHY GEOFF, WHY!!!])
    $max_score = 5;
    $vote_avg_percentage = ($vote_avg / $max_score) * 96;


    // Tributes made algorithm: Any game that starts with "Tribute to" and has a VALID
    // username of a user that has registered on the web site. It must check whether the
    // username is actually valid or not. If not, do not add to the count.
    
    // Fetch all games that start with "Tribute to"
    $stmt = $db->prepare("SELECT g_id, title FROM games WHERE title LIKE 'Tribute to %'");
    $stmt->execute();
    $games = $stmt->fetchAll();

    $validTributesCount = 0;

    foreach ($games as $game) {
        // Extract the username from the game title
        $titleParts = explode(' ', $game['title']);
        $username = end($titleParts);

        // Check if the username is valid
        $userStmt = $db->prepare("SELECT COUNT(*) FROM members WHERE username = :username");
        $userStmt->execute([':username' => $username]);
        $isValidUser = $userStmt->fetchColumn() > 0;

        // If the username is valid, count this tribute
        if ($isValidUser) {
            $validTributesCount++;
        }
    }
    // All hail GitHub Copilot!! Someone please switch this to a more optimized method by probably caching or something


    ?>
            <div class="mprofgroup mprofsection">
    <h4><a href="#" onclick="setClass('mprof_activity', 'shown'); return false;" title="Things this member has accomplished">Actions</a></h4>
    <div class="mprofcontent hidden" id="mprof_activity">
        <dl class="mprofdata">
            <dt>Votes cast:</dt>
            <dd><?= $votes_cast ?></dd>
            <dt>Comments made:</dt>
            <dd><?= $comments_made ?><a href="/messages/?creator=<?=$username?>"> view &raquo;</a></dd>
            <dt>Vote average:</dt>
            <dd style="position: relative; width: 96px; height: 24px; background-color: #666;" title="Average vote this member has cast on others' games">
                <div style="background: #ffec00; width: <?= $vote_avg_percentage ?>px; height: 24px;">&nbsp;</div>
                <div style="width: 96px; height: 24px; position: absolute; top: 0; left: 0; z-index: 2; background-image: url('/chrome/starmask.png');">&nbsp;
                </div>
            </dd>
            <dt>Tributes made:</dt>
            <dd><?= $validTributesCount ?></dd>
            <dt>Group Memberships:</dt>
            <dd>??</dd>
            <dt>Group Ownerships:</dt>
            <dd>??</dd>
        </dl>
        <div class="spacer">&nbsp;</div>
    </div>
</div>
    <?php
}
?>