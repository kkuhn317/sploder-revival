<div id="footer">

    <div class="spacer">&nbsp;</div>
</div>
<div class="spacer">&nbsp;</div>
</div>
</div>
<div id="bottomnav">
    <ul>
        <li><a href="/">Home</a></li>
        <li><a href="/legal/termsofservice.php">Terms of Service</a></li>
        <li><a href="/credits.php">Credits</a></li>
        <li><a href="/legal/privacypolicy.php">Privacy Policy</a></li>
        <li><a href="https://discord.com/invite/<?= getenv('DISCORD_INVITE') ?>/" target="_blank">Discord</a></li>
    </ul><br><br><i>All assets are the property of neurofuzzy
        <?php
        $public = getenv('NETWORKED');
        if($public == 'true') {
            $repository = getenv('REPOSITORY');
            echo " | <a href='$repository' target='_blank'>Source code</a>";
        }
        ?>
    </i>
    <div style="position: fixed; right: 0.7vw; bottom: 0.7vw; font-size: 0.8vw; opacity: 50%; z-index: 1000;">alpha v1.0</div>
</div>