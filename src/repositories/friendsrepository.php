<?php

require_once(__DIR__ . "/../database/idatabase.php");
require_once(__DIR__ . "/ifriendsrepository.php");

class FriendsRepository implements IFriendsRepository
{
    private readonly IDatabase $db;

    function __construct(IDatabase $db)
    {
        $this->db = $db;
    }

    public function getFriendRequestCount(int $userId, bool $isViewed): int
    {
        $isViewedStr = $isViewed ? 'true' : 'false'; // Convert boolean to string
        $newFriends = $this->db->queryFirstColumn("SELECT count(*) FROM friend_requests WHERE receiver_id=:user AND is_viewed=:is_viewed", 0, [
            ':user' => $userId,
            ':is_viewed' => $isViewedStr
        ]);
        return $newFriends;
    }

    public function setAllFriendsAsViewed(int $userId): void
    {
        $this->db->execute("UPDATE friend_requests SET is_viewed=true WHERE receiver_id=:receiver_id", [
            ':receiver_id' => $userId
        ]);
    }

    public function alreadyFriends(string $sender, string $receiver): bool
    {
        $query = "SELECT id FROM friends WHERE user1 = :sender AND user2 = :receiver";
        $result = $this->db->query($query, [
            ':sender' => $sender,
            ':receiver' => $receiver
        ]);
        
        return !empty($result);
    }

    public function getTotalFriends(string $username): int
    {
        $query = "SELECT COUNT(*) FROM friends WHERE user1 = :username";
        $result = $this->db->queryFirstColumn($query, 0, [':username' => $username]);
        
        return (int)$result;
    }
}