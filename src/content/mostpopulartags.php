<div class="tagbox"><p class="tags"><strong>Most Popular Tags:</strong>
<?php
require_once(__DIR__.'/../repositories/repositorymanager.php');
$gameRepository = RepositoryManager::get()->getGameRepository();
require_once(__DIR__.'/../content/taglister.php');
echo displayTags($gameRepository->getGameTags(0,25)->data, true);
echo '<p>Learn more about <a href="/games/tags.php">tags</a>.</p>';
?>
</p></div>