<?php

include_once('../database/connect.php');
require_once('../repositories/repositorymanager.php');
$time = time();
$pagechange = $time - 900;
$userRepository = RepositoryManager::get()->getUserRepository();
$members = $userRepository->getOnlineMembers();
$total = count($members);
?>
var _____WB$wombat$assign$function_____ = function(name) {return (self._wb_wombat && self._wb_wombat.local_init &&
self._wb_wombat.local_init(name)) || self[name]; };
if (!self.__WB_pmw) { self.__WB_pmw = function(obj) { this.__WB_source = obj; return this; } }
{
let window = _____WB$wombat$assign$function_____("window");
let self = _____WB$wombat$assign$function_____("self");
let document = _____WB$wombat$assign$function_____("document");
let location = _____WB$wombat$assign$function_____("location");
let top = _____WB$wombat$assign$function_____("top");
let parent = _____WB$wombat$assign$function_____("parent");
let frames = _____WB$wombat$assign$function_____("frames");
let opener = _____WB$wombat$assign$function_____("opener");



try {

var net_result = ' <div class="users_online"><ul><?php
for ($i = 0; $i < $total; $i++) {
    if ($members[$i]['status'] == "online") {
        if ($members[$i]['lastpagechange'] > $pagechange) {
            $status = "online";
        } else {
            $status = "idle";
        }
    } elseif ($members[$i]['status'] == "creating") {
        $status = "making";
    } elseif ($members[$i]['status'] == "playing") {
        $status = "playing";
    }

    echo '<li><a href="../members/index.php?u=' . $members[$i]['username'] . '"><img src="../php/avatarproxy.php?u=' . $members[$i]['username'] . '" alt="' . $members[$i]['username'] . '" border="0" style="width:24px;height:24px;margin:-6px 8px" />' . $members[$i]['username'] . '</a><img style="margin-left:30px" class="status" src="../images/status_' . $status . '.gif" width="11" height="11"/></li>';
}



?></ul><div class="spacer">&nbsp;</div></div>';

if (document && document.getElementById) {

var c = document.getElementById("whos_online_container");

if (c) {

c.innerHTML = net_result;

}

}

} catch (err) {


}
}
