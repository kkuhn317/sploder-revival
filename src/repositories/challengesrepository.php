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

    public function addChallenge(int $gameId, bool $mode, int $challenge, int $prize, int $winners)
    {
        $query = "INSERT INTO challenges (g_id, mode, challenge, prize, winners, verified) VALUES (:g_id, :mode, :challenge, :prize, :winners, :verified)";
        $this->db->execute($query, [
            ':g_id' => $gameId,
            ':mode' => $mode,
            ':challenge' => $challenge,
            ':prize' => $prize,
            ':winners' => $winners,
            ':verified' => false,
        ]);
    }
}
