<?php
function generateSearchResults(array $result)
{
    if (isset($result[0][0])) {
        foreach ($result as $index => $member) {
            // Sploder copy-paste
            ?>
<div class="friend friend_48">
    <a class="name" href="/members/index.php?u=<?= $member['username'] ?>"><img
            src="/php/avatarproxy.php?u=<?= $member['username'] ?>" width="48" height="48"
            alt="<?= $member['username'] ?>" title="<?= $member['username'] ?>, level <?= $member['level'] ?>" /></a>
    <a class="name" href="/members/index.php?u=<?= $member['username'] ?>"><?= $member['username'] ?></a>
</div>
            <?php
            if ($index % 5 == 4) { // At every 5th member, add a spacer
                echo '<div class="spacer">&nbsp;</div>';
            }
        }
    } else {
        echo '<blockquote>Aack! No members found!</blockquote>';
    }
}
