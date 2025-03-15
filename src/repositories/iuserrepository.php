<?php

/**
 * Handles database interations with users
 */
interface IUserRepository
{
  /**
   * Search for similar users by userName
   *
   * @param $userName
   * @param $limit
   * @return the array of search values
   */
    function search(string $userName, int $limit = 180);

    /**
     * Get top 90 members which have the most views per day
     * 
     * @return array of top users
     */
    function getTopMembers();

    /**
     * Get 100 members with offset
     * 
     * @param $offset
     * @return array of users
     */
    function getMembers(int $offset);

    /**
     * Get level of user
     * 
     * @param $rating
     * @param $friends
     * @param $games
     * @param $views
     * @return int level of user
     */
    function getLevel(int $rating, int $friends, int $games, int $views);

    /**
     * Get total number of members
     * 
     * @return int total number of members
     */
    function getTotalNumberOfMembers();
}
