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
    //check if all columns are empty or if row does not exist
    if(empty($row['description']) && empty($row['hobbies']) && empty($row['sports']) && empty($row['games']) && empty($row['movies']) && empty($row['bands']) && empty($row['respect'])){
        return;
    }
    ?>
    <script type="text/javascript">
        function setClass (id, c) { var e = document.getElementById(id); if (e) e.className = c; }
    </script>
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
            <div class="mprofgroup mprofsection">
    <h4><a href="#" onclick="setClass('mprof_activity', 'shown'); return false;" title="Things this member has accomplished">Actions</a></h4>
    <div class="mprofcontent hidden" id="mprof_activity">
        <dl class="mprofdata">
            <dt>Votes cast:</dt>
            <dd>??</dd>
            <dt>Comments made:</dt>
            <dd>?? <a href="/messages/?creator=<?=$username?>">view &raquo;</a></dd>
            <dt>Vote average:</dt>
            <dd style="position: relative; width: 96px; height: 24px; background-color: #666;" title="Average vote this member has cast on others' games">
                <div style="background: #ffec00; width: 1px; height: 24px;">&nbsp;</div>
                <div style="width: 96px; height: 24px; position: absolute; top: 0; left: 0; z-index: 2; background-image: url('/chrome/starmask.png');">&nbsp;
                </div>
            </dd>
            <dt>Tributes made:</dt>
            <dd>??</dd>
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