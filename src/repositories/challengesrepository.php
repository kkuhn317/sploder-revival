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
        $query = "INSERT INTO challenges (g_id, mode, challenge, prize, winners, verified, date) VALUES (:g_id, :mode, :challenge, :prize, :winners, :verified, NOW())";
        $this->db->execute($query, [
            ':g_id' => $gameId,
            ':mode' => $mode,
            ':challenge' => $challenge,
            ':prize' => $prize,
            ':winners' => $winners,
            ':verified' => false,
        ]);
    }

    public function getChallengeInfo(int $gameId): array|false
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
        $query = "SELECT c.c_id
          FROM challenges c
          LEFT JOIN challenge_winners w ON w.g_id = c.g_id
          WHERE c.g_id = :g_id
            AND c.date > NOW() - INTERVAL '15 days'
          GROUP BY c.c_id, c.winners
          HAVING COUNT(w.winner_id) < c.winners";
        $result = $this->db->queryFirst($query, [':g_id' => $gameId]);
        return ($result['c_id'] === $challengeId) && ($result['c_id'] === $sessionChallengeId);
    }

    public function getAllChallenges(int $offset, int $perPage): array
    {

        $query = "SELECT c.c_id, c.g_id, c.mode, c.challenge, c.prize, c.winners, c.verified, 
                 c.date + INTERVAL '15 days' AS expires_at, 
                 g.user_id, g.title, g.author,
                 COUNT(w.winner_id) AS total_winners
          FROM challenges c
          JOIN games g ON c.g_id = g.g_id
          LEFT JOIN challenge_winners w ON w.g_id = c.g_id
          WHERE c.date > NOW() - INTERVAL '15 days'
          GROUP BY c.c_id, c.g_id, c.mode, c.challenge, c.prize, c.winners, c.verified, c.date, g.user_id, g.title, g.author
          HAVING COUNT(w.winner_id) < c.winners
          ORDER BY c.verified DESC OFFSET :offset LIMIT :perPage";
        return $this->db->query($query, [
            ':offset' => $offset,
            ':perPage' => $perPage,
        ]);
    }

    public function checkIfChallengeCreatorIsOwner(int $challengeId, int $userId): bool
    {
        $query = "SELECT g.user_id FROM challenges c JOIN games g ON c.g_id = g.g_id WHERE c.c_id = :c_id";
        $result = $this->db->queryFirst($query, [':c_id' => $challengeId]);
        return $result['user_id'] === $userId;
    }

    public function verifyChallenge(int $challengeId): bool
    {
        $query = "UPDATE challenges SET verified = true WHERE c_id = :c_id";
        $this->db->execute($query, [':c_id' => $challengeId]);
        return true;
    }

    public function addChallengeWinner(int $challengeId, int $userId): bool
    {
        $query = "INSERT INTO challenge_winners (g_id, user_id) VALUES (:g_id, :user_id)";
        $this->db->execute($query, [
            ':g_id' => $challengeId,
            ':user_id' => $userId,
        ]);
        return true;
    }

    public function hasWonChallenge(int $g_id, int $userId): bool
    {
        $query = "SELECT COUNT(*) FROM challenge_winners WHERE g_id = :g_id AND user_id = :user_id";
        $result = $this->db->queryFirst($query, [
            ':g_id' => $g_id,
            ':user_id' => $userId,
        ]);
        return $result['count'] > 0;
    }

    public function getTotalChallengeCount(): int
    {
        $query = "SELECT COUNT(*) FROM challenges WHERE date > NOW() - INTERVAL '15 days'";
        return $this->db->queryFirst($query)['count'];
    }

    public function formatChallengeMode($mode, $challenge): string
    {
        if ($mode) {
            $minutes = floor($challenge / 60);
            $seconds = $challenge % 60;
            $result = "Win in less than";

            if ($minutes > 0 && $seconds == 0) {
                $unit = $minutes == 1 ? "minute" : "minutes";
                $result .= " {$minutes} {$unit}";
            } elseif ($minutes > 0 && $seconds > 0) {
                $minUnit = $minutes == 1 ? "min" : "mins";
                $secUnit = $seconds == 1 ? "sec" : "secs";
                $result .= " {$minutes} {$minUnit} {$seconds} {$secUnit}";
            } elseif ($minutes == 0 && $seconds > 0) {
                $unit = $seconds == 1 ? "second" : "seconds";
                $result .= " {$seconds} {$unit}";
            }

            return trim($result);
        } else {
            return "Score at least {$challenge} points";
        }
    }
}