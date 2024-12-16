<?php
if (isset($_SESSION['username'])) {
    if (!isset($status)) {
        $status = "online";
    }
    ?>
<script>
fetch(__DIR__."/../php/idlecheck.php")
function checkonline() 
{
    fetch(__DIR__."/../php/online.php?status=<?= $status ?>");
}
var checkonline = setInterval(checkonline, 10000);
</script>
<?php } ?>
