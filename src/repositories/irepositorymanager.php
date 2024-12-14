<?php

require_once(__DIR__ . "/igamerepository.php");
require_once(__DIR__ . "/igraphicsrepository.php");
require_once(__DIR__ . "/iuserrepository.php");
require_once(__DIR__ . "/iawardsrepository.php");
require_once(__DIR__ . "/iuserrepository.php");
require_once(__DIR__ . "/icontestrepository.php");

interface IRepositoryManager
{
    public function getAwardsRepository(): IAwardsRepository;
    public function getContestRepository(): IContestRepository;
    public function getGameRepository(): IGameRepository;
    public function getGraphicsRepository(): IGraphicsRepository;
    public function getUserRepository(): IUserRepository;
}
