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

    public function getTopMembers()
    {
        $qs = "
        WITH latest_view AS (
    SELECT MAX(create_date) AS max_time
    FROM (
        SELECT MAX(create_date) AS create_date FROM public.game_views_members
        UNION ALL
        SELECT MAX(create_date) FROM public.game_views_anonymous
    ) AS combined_views
)
SELECT 
    g.author, 
    COUNT(gv.g_id) + COUNT(ga.g_id) AS total_views, 
    (SELECT max_time FROM latest_view) AS last_view_time
FROM public.games g
LEFT JOIN public.game_views_members gv 
    ON g.g_id = gv.g_id 
    AND gv.create_date >= (SELECT max_time FROM latest_view) - INTERVAL '24 hours'
LEFT JOIN public.game_views_anonymous ga 
    ON g.g_id = ga.g_id 
    AND ga.create_date >= (SELECT max_time FROM latest_view) - INTERVAL '24 hours'
GROUP BY g.author
ORDER BY total_views DESC
LIMIT 90;
";
        return $this->db->query($qs);
    }
}
