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

    public function getChallengeInfo(int $gameId): array
    {
        $query = "SELECT mode,prize,challenge FROM challenges WHERE g_id = :id";
        return $this->db->queryFirst($query, [':id' => $gameId]);
    }

    public function verifyIfOwner(int $gameId, int $userId): bool
    {
        $gameRepository = RepositoryManager::get()->getGameRepository();
        $ownerId = $gameRepository->getUserId($gameId);
        return $ownerId == $userId;
    }

    public function verifyIfSIsCorrect(int $gameId, int $userId): bool
    {
        $query = "SELECT user_id FROM games WHERE g_id = :g_id";
        $result = $this->db->queryFirst($query, [':g_id' => $gameId]);
        return $result['user_id'] === $userId;
    }

    public function getChallengeId(int $gameId): int
    {
        $query = "SELECT c_id FROM challenges WHERE g_id = :g_id";
        return $this->db->queryFirst($query, [':g_id' => $gameId])['c_id'];
    }

    public function verifyChallengeId(int $gameId, int $challengeId, int $sessionChallengeId): bool
    {
        $query = "SELECT c_id FROM challenges WHERE g_id = :g_id";
        $result = $this->db->queryFirst($query, [':g_id' => $gameId]);
        return ($result['c_id'] === $challengeId) && ($result['c_id'] === $sessionChallengeId);
    }
}
