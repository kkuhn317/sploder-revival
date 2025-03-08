<?php

/**
 * Handles database interations with froemds
 */
interface IFriendsRepository
{
    /**
     * Gets the number of unviewed friends
     * 
     * @param $userId the user to check for unviewed friends
     * @return the number of unviewed friends
     */
    public function getNumerOfUnviewedFriends(int $userId): int;

    /**
     * Views all friend requests for a user
     */
    public function markAllFriendsAsViewed(int $userId): void;
}
