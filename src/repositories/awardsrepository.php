<?php

require_once(__DIR__ . "/../database/idatabase.php");
require_once(__DIR__ . "/iawardsrepository.php");

class AwardsRepository implements IAwardsRepository
{
    private readonly IDatabase $db;

    function __construct(IDatabase $db)
    {
        $this->db = $db;
    }
}
