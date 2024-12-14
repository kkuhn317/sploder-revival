<?php

require_once(__DIR__ . "/../database/databasemanager.php");
require_once(__DIR__ . "/irepositorymanager.php");
require_once(__DIR__ . "/gamerepository.php");

class RepositoryManager implements IRepositoryManager
{
    private readonly IGameRepository $gameRepository;

    private function __construct(IDatabase $database)
    {
        $this->gameRepository = new GameRepository($database);
    }

    public function getGameRepository(): IGameRepository
    {
        return $this->gameRepository;
    }

    private static IRepositoryManager|null $value = null;
    public static function get(): IRepositoryManager
    {
        if (RepositoryManager::$value == null) {
            RepositoryManager::$value = new RepositoryManager(DatabaseManager::get()->getPostgresDatabase());
        }

        return RepositoryManager::$value;
    }
}
