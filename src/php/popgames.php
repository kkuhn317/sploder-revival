<?php

require('../repositories/repositorymanager.php');
require('../services/GameFeedService.php');

$gameRepository = RepositoryManager::get()->getGameRepository();

$gameFeed = new GameFeedService($gameRepository);

echo $gameFeed->generateFeedForPopularGames();
