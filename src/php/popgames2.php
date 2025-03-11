<?php

require_once('../repositories/repositorymanager.php');
require_once('../services/GameFeedService.php');

$gameRepository = RepositoryManager::get()->getGameRepository();

$gameFeed = new GameFeedService($gameRepository);

echo $gameFeed->generateFeedForWeirdPopularGames();

