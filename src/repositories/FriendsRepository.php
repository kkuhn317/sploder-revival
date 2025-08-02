<?php

class FriendsRepository implements IFriendsRepository
{
    private $db;

    public function __construct()
    {
        $this->db = getDatabase();
    }

    public function getBestedFriends(string $username, int $limit = 30): array
    {
        return $this->db->query("SELECT user1,user2
            FROM friends
            WHERE (bested=true)
            AND (user1=:sender_id)
            ORDER BY id DESC
            LIMIT :limit", [
                ':sender_id' => $username,
                ':limit' => $limit
            ]);
    }

    public function getAcceptedFriends(string $username, int $limit): array
    {
        return $this->db->query("SELECT user1, user2
            FROM friends
            WHERE (bested = false)
            AND (user1=:sender_id)
            ORDER BY id DESC 
            LIMIT :limit", [
                ':sender_id' => $username,
                ':limit' => $limit
            ]);
    }

    public function setAllFriendsAsViewed(string $userId): void
    {
        // Implementation from existing repository
    }
}
