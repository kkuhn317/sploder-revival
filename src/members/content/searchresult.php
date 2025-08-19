<?php
function generateSearchResults(array $result)
{
    require(__DIR__ . "/../../services/FriendsListRenderService.php");
    $friendsRepository = RepositoryManager::get()->getFriendsRepository();
    $friendsListRenderService = new FriendsListRenderService($friendsRepository);

    echo $friendsListRenderService->renderPartialViewForMemberList($result);
}