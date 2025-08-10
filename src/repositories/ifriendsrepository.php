<?php

/**
 * Handles database interactions with friends
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
     * Checks if two users are already friends
     * 
     * @param $sender the sender of the friend request
     * @param $receiver the receiver of the friend request
     * @return boolean true if they are friends, false otherwise
     */
    public function alreadyFriends(string $sender, string $receiver): bool;

    /**
     * Gets the total number of friends for a user
     * 
     * @param $username the username to check
     * @return int the total number of friends
     */
    public function getTotalFriends(string $username): int;
}
