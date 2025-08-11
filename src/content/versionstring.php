<?php
$pageExecutionEndTime = microtime(true);
$pageExecutionTime = $pageExecutionEndTime - $pageExecutionStartTime;
$pageExecutionTime = number_format($pageExecutionTime, 3) . "s";
?>
<div style="position: fixed; right: 0.7vw; bottom: 0.7vw; font-size: 0.8vw; opacity: 50%; z-index: 1000;"><?= $pageExecutionTime ?> | alpha v1.0</div>