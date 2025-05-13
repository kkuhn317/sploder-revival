<?php

require_once(__DIR__ . "/../database/idatabase.php");
require_once(__DIR__ . "/ichallengesrepository.php");

class ChallengesRepository implements IChallengesRepository
{
    private readonly IDatabase $db;

    function __construct(IDatabase $db)
    {
        $this->db = $db;
    }
}
