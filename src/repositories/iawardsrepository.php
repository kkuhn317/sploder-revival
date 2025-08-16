<?php

/**
 * Handles database interactions with awards
 */
interface IAwardsRepository
{
    /**
     * Gets the total number of awards for a user
     * 
     * @param string $username The username to get award count for
     * @return int The total number of awards
     */
    public function getAwardCount(string $username): int;

    /**
     * Gets a paginated list of awards for a user
     * 
     * @param string $username The username to get awards for
     * @param int $offset The number of awards to skip
     * @param int $limit Maximum number of awards to return
     * @return array Array of award records
     */
    public function getAwardsPage(string $username, int $offset, int $limit): array;
}
