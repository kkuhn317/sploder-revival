<?php

/**
 * Handles database interations with friends
 */
interface IFriendsRepository
{
    /**
     * Gets the number of unviewed friends
     * 
     * @param $userId the user to check for friends
     * @param $isViewed whether the friend request has been viewed
     * @return the number of friends
     */
    public function getFriendRequestCount(int $userId, bool $isViewed): int;

    /**
     * Views all friend requests for a user
     */
    public function setAllFriendsAsViewed(int $userId): void;

    /**
     * Gets the bested friends for a user, ordered by most recent first
     * 
     * @param string $username The username to get bested friends for
     * @param int $limit Maximum number of friends to return
     * @return array Array of friend records containing user1 and user2 fields
     */
    public function getBestedFriends(string $username, int $limit = 30): array;

    /**
     * Gets the regular (non-bested) friends for a user, ordered by most recent first
     * 
     * @param string $username The username to get friends for
     * @param int $limit Maximum number of friends to return
     * @return array Array of friend records containing user1 and user2 fields
     */
    public function getAcceptedFriends(string $username, int $limit): array;
}
