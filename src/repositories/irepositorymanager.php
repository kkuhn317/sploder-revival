<?php

require_once(__DIR__ . "/igamerepository.php");

interface IRepositoryManager
{
    public function getGameRepository(): IGameRepository;
}
