<?php

require_once(__DIR__ . "/../database/idatabase.php");
require_once(__DIR__ . "/igraphicsrepository.php");

class GraphicsRepository implements IGraphicsRepository
{
    private readonly IDatabase $db;

    function __construct(IDatabase $db)
    {
        $this->db = $db;
    }

    public function replaceTags(int $graphicId, array $tags): void
    {
        $this->db->execute("DELETE FROM graphic_tags WHERE g_id = :id", [
          ':id' => $graphicId
        ]);

        if (!empty($tags)) {
            $values = [];
            $params = [':id' => $graphicId];
            foreach ($tags as $index => $tag) {
                $values[] = "(:id, :tag$index)";
                $params[":tag$index"] = $tag;
            }
            $qs = "INSERT INTO graphic_tags (g_id, tag) VALUES " . implode(", ", $values);
            $this->db->execute($qs, $params);
        }
    }

    public function getUserId(int $graphicId): string
    {
        return $this->db->queryFirstColumn("SELECT userid FROM graphics WHERE id = :id", 0, [
          ':id' => $graphicId
        ]);
    }

    public function getTags($graphicId): array
    {
        return $this->db->query("SELECT tag FROM graphic_tags WHERE g_id = :id", [
          ':id' => $graphicId,
        ]);
    }

    public function trackLike(int $graphicsId, int $loggedInUserId): void
    {
        $userId = $this->db->queryFirstColumn("SELECT userid FROM graphics WHERE id=:id", 0, [
        'id' => $graphicsId
        ]);

        if ($userId !== $loggedInUserId) {
            try {
                $this->db->execute("
INSERT INTO graphic_likes (userid, g_id) VALUES (:userid, :projid)
on conflict do nothing", [
                ':userid' => $loggedInUserId,
                ':projid' => $graphicsId,
                ]);
            } catch (Exception $ex) {
              // TODO: log this error, as the conflict should handle this
            }
        }
    }
	
	public function getTotal()
	{
	    try {
	        $likesQuery = "SELECT COUNT(*) AS count FROM graphic_likes";
	        $graphicsQuery = "SELECT COUNT(*) AS count FROM graphics";

	        if ($this->db instanceof PDO) {
	            $likesStmt = $this->db->query($likesQuery);
	            $graphicsStmt = $this->db->query($graphicsQuery);
	            return [
	                'likes' => (int) $likesStmt->fetchColumn(),
	                'graphics' => (int) $graphicsStmt->fetchColumn()
	            ];
	        } elseif (method_exists($this->db, 'query')) {
	            $likesResult = $this->db->query($likesQuery);
	            $graphicsResult = $this->db->query($graphicsQuery);
	            return [
	                'likes' => (int) ($likesResult[0]['count'] ?? 0),
	                'graphics' => (int) ($graphicsResult[0]['count'] ?? 0)
	            ];
	        }

	        return ['likes' => 0, 'graphics' => 0];
	    } catch (Exception $e) {
	        return ['likes' => 0, 'graphics' => 0];
	    }
	}

    public function getTotalPublicGraphics(): int
    {
        $qs = "SELECT COUNT(id) FROM graphics WHERE isprivate=false AND ispublished=true";
        $total_graphics = $this->db->queryFirstColumn($qs, 0);
        return $total_graphics;
    }

    public function getPublicGraphics(int $offset = 0, int $perPage = 36): array
    {

        $queryString = '
            SELECT g.*, m.username 
            FROM graphics g
            LEFT JOIN members m ON g.userid = m.userid
            WHERE g.isprivate = false AND g.ispublished = true
            ORDER BY g.id DESC
            LIMIT :perPage OFFSET :offset';
        return $this->db->query($queryString, [
				':perPage' => $perPage,
				':offset' => $offset*$perPage
	]);
    }

}
