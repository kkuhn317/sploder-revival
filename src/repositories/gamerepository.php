<?php

require_once(__DIR__ . "/../database/idatabase.php");
require_once(__DIR__ . "/igamerepository.php");

class GameRepository implements IGameRepository
{
    private readonly IDatabase $db;

    function __construct(IDatabase $db)
    {
        $this->db = $db;
    }

    function trackView(int $game_id, string $ip_address, int|null $user_id): void
    {
        $query = "
INSERT INTO game_views_anonymous(g_id, ipaddress) values(:g_id, :ipaddress)
on conflict do nothing;";

        $this->db->execute($query, [
            ':g_id' => $game_id,
            ':ipaddress' => $ip_address,
        ]);

        if ($user_id !== null) {
            $query = "
INSERT INTO game_views_members(g_id, userid) values(:g_id, :userid)
on conflict do nothing;";

            $this->db->execute($query, [
                ':g_id' => $game_id,
                ':userid' => $user_id,
            ]);

            $query = "
UPDATE games
  SET views=(select count(*) from game_views_members gvm where gvm.g_id = :g_id)
where g_id = :g_id
";
            $this->db->execute($query, [
                ':g_id' => $game_id,
            ]);
        }
    }

    public function getGameData(int $gameId): GameData
    {
        $gameInfo = $this->db->queryFirst("SELECT author, difficulty FROM games WHERE g_id = :g_id", [
            ':g_id' => $gameId,
        ]);

        $avgScore = $this->db->queryFirstColumn("SELECT AVG(score) as avg FROM votes WHERE g_id = :g_id", 0, [
            ':g_id' => $gameId,
        ]);

        if ($avgScore === null) {
            $avgScore = 0;
        } else {
            $avgScore = round($avgScore);
        }

        return new GameData($gameInfo['author'], round($gameInfo['difficulty']), $avgScore);
    }

    public function getGameTags(int $perPage, int $offset): GameTags
    {
        $tags = $this->db->query("SELECT DISTINCT tag FROM game_tags ORDER BY tag LIMIT :perpage OFFSET :offset", [
            ':perpage' => $perPage,
            ':offset' => $offset,
        ]);

      // Delete the below line and just get the length of $tags?
        $total = $this->db->queryFirstColumn("SELECT COUNT(DISTINCT tag) as total_unique_tags FROM game_tags");

        return new GameTags($tags, $total);
    }

    public function getUserId(int $gameId): string
    {
        return $this->db->queryFirstColumn("SELECT user_Id FROM games WHERE g_id = :g_id", 0, [
            ':g_id' => $gameId,
        ]);
    }

    public function getRandomGames(): array
    {
        $query = "SELECT g_id, title, author, user_id FROM games WHERE ispublished = 1 AND isprivate = 0 ORDER BY RANDOM() LIMIT 6";
        return $this->db->query($query);
    }

    public function getContestWinners(int $contestId): array
    {
        if ($contestId < 0) {
            return [];
        }

        $query = "SELECT games.g_id, games.title, games.author, games.user_id
		FROM (
			SELECT contest_id, g_id
			FROM contest_winner
			ORDER BY contest_id
			LIMIT 6 OFFSET (:id * 6)
		) AS recent_contests
		JOIN games ON recent_contests.g_id = games.g_id;";
        return $this->db->query($query, ['id' => $contestId]);
    }
}
