<?php

interface IFriendsRepository
{
    /**
     * Get bested friends for a user, ordered by most recent first
     * @param string $username The username to get bested friends for
     * @param int $limit Maximum number of friends to return
     * @return array Array of friend records with user1 and user2 fields
     */
    public function getBestedFriends(string $username, int $limit = 30): array;

    /**
     * Get non-bested (regular) friends for a user, ordered by most recent first
     * @param string $username The username to get friends for
     * @param int $limit Maximum number of friends to return
     * @return array Array of friend records with user1 and user2 fields
     */
    public function getAcceptedFriends(string $username, int $limit): array;

    /**
     * Mark all friends as viewed for a user
     * @param string $userId The user ID to mark friends as viewed for
     */
    public function setAllFriendsAsViewed(string $userId): void;
}
