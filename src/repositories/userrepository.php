<?php

require_once(__DIR__ . "/../database/idatabase.php");
require_once(__DIR__ . "/iuserrepository.php");

class UserRepository implements IUserRepository
{
    private readonly IDatabase $db;

    function __construct(IDatabase $db)
    {
        $this->db = $db;
    }

    public function search(string $userName, int $limit = 180)
    {
        $qs = "
SELECT
    username,
    SIMILARITY(username, :u) AS sim,
    level
FROM members
WHERE SIMILARITY(username, :u) > 0.3 
ORDER BY sim 
DESC LIMIT :limit";
        return $this->db->query($qs, [
        ':u' => trim($userName ?? ''),
        ':limit' => $limit,
        ]);
    }
}
