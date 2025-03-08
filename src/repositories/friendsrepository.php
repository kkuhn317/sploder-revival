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

    public function getNumerOfUnviewedFriends(int $userId): int
    {
        $newFriends = $this->db->queryFirstColumn("SELECT count(*) FROM friend_requests WHERE receiver_id=:user AND is_viewed=false", 0, [
            ':user' => $userId
        ]);
        return $newFriends;
    }

    public function markAllFriendsAsViewed(int $userId): void
    {
        $this->db->execute("UPDATE friend_requests SET is_viewed=true WHERE receiver_id=:receiver_id", [
            ':receiver_id' => $_SESSION['userid']
            ]);
    }
}
